<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class PlanejamentosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('planejamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Disciplinas', [
            'foreignKey' => 'disciplina_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
        ]);
        $this->belongsTo('Configuraplanejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Salas', [
            'foreignKey' => 'sala_id',
        ]);
        $this->belongsTo('Dias', [
            'foreignKey' => 'dia_id',
        ]);
        $this->belongsTo('Horarios', [
            'foreignKey' => 'horario_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('disciplina_id')->notEmptyString('disciplina_id')
            ->integer('docente_id')->allowEmptyString('docente_id')
            ->integer('configuraplanejamento_id')->notEmptyString('configuraplanejamento_id')
            ->integer('periodo')->allowEmptyString('periodo')
                        ->range('periodo', [1, 10], __('O período deve estar entre 1 e 10.'))
            ->scalar('turno')->allowEmptyString('turno')
            ->integer('sala_id')->allowEmptyString('sala_id')
            ->integer('dia_id')->allowEmptyString('dia_id')
            ->integer('horario_id')->allowEmptyString('horario_id')
            ->scalar('observacoes')->allowEmptyString('observacoes');
        return $validator;
    }

    /**
     * Copia todos os planejamentos de uma configuração (origem) para outra
     * (destino) e garante que todos os docentes usados na origem tenham
     * registro em docente_disponibilidades no destino com disponivel = 1
     * (o usuário ajusta depois apenas as exceções).
     *
     * Retorna ['planejamentos' => copiados, 'disponibilidades' => criadas].
     *
     * @return array{planejamentos: int, disponibilidades: int}
     */
    public function clonarPlanejamentos(int $origemId, int $destinoId): array
    {
        $origens = $this->find()
            ->where(['configuraplanejamento_id' => $origemId])
            ->all();

        $novos = [];
        foreach ($origens as $origem) {
            $novo = $this->newEmptyEntity();
            $novo->configuraplanejamento_id = $destinoId;
            $novo->disciplina_id = $origem->disciplina_id;
            $novo->docente_id = $origem->docente_id;
            $novo->periodo = $origem->periodo;
            $novo->turno = $origem->turno;
            $novo->sala_id = $origem->sala_id;
            $novo->dia_id = $origem->dia_id;
            $novo->horario_id = $origem->horario_id;
            $novo->ementa_id = $origem->ementa_id;
            $novo->optativa_id = $origem->optativa_id;
            $novo->observacoes = $origem->observacoes;
            $novos[] = $novo;
        }

        if ($novos === []) {
            return ['planejamentos' => 0, 'disponibilidades' => 0];
        }

        // Cópia de dados já persistidos: dispensa validação e é atômica.
        $this->saveManyOrFail($novos, ['validate' => false]);

        $disponibilidades = $this->seedDisponibilidades($origens, $destinoId);

        return ['planejamentos' => count($novos), 'disponibilidades' => $disponibilidades];
    }

    /**
     * Cria em docente_disponibilidades (configuração de destino, disponivel = 1)
     * um registro para cada docente distinto dos planejamentos de origem que
     * ainda não o possua. Respeita o índice único (docente_id, configuraplanejamento_id).
     * Retorna a quantidade de registros criados.
     *
     * @param iterable<\App\Model\Entity\Planejamento> $origens
     */
    private function seedDisponibilidades(iterable $origens, int $destinoId): int
    {
        $docenteIds = [];
        foreach ($origens as $origem) {
            if ($origem->docente_id !== null) {
                $docenteIds[$origem->docente_id] = $origem->docente_id;
            }
        }
        if ($docenteIds === []) {
            return 0;
        }

        $dispTable = TableRegistry::getTableLocator()->get('DocenteDisponibilidades');
        $existentes = $dispTable->find()
            ->select(['docente_id'])
            ->where([
                'configuraplanejamento_id' => $destinoId,
                'docente_id IN' => array_values($docenteIds),
            ])
            ->all();
        foreach ($existentes as $existente) {
            unset($docenteIds[$existente->docente_id]);
        }

        $novas = [];
        foreach ($docenteIds as $docenteId) {
            $nova = $dispTable->newEmptyEntity();
            $nova->docente_id = $docenteId;
            $nova->configuraplanejamento_id = $destinoId;
            $nova->disponivel = true;
            $novas[] = $nova;
        }
        if ($novas === []) {
            return 0;
        }
        $dispTable->saveManyOrFail($novas, ['validate' => false]);

        return count($novas);
    }

    /**
     * Exclui todos os planejamentos vinculados a uma configuração.
     * Retorna a quantidade de registros excluídos.
     */
    public function excluirPorConfiguracao(int $configuraplanejamentoId): int
    {
        return $this->deleteAll(['configuraplanejamento_id' => $configuraplanejamentoId]);
    }

    /**
     * Detecta problemas no planejamento de uma configuração (semestre):
     *  - docente alocado em mais de uma turma no mesmo dia/horário;
     *  - sala usada por mais de uma turma no mesmo dia/horário;
     *  - docente com alocação apesar de marcado como indisponível no semestre.
     *
     * Cada item retornado possui: tipo, mensagem e itens (turmas envolvidas).
     *
     * @return array<int, array{tipo: string, mensagem: string, itens: array<int, string>}>
     */
    public function detectarConflitos(int $configuraplanejamentoId): array
    {
        $rows = $this->find()
            ->contain(['Disciplinas', 'Docentes', 'Salas', 'Dias', 'Horarios'])
            ->where(['Planejamentos.configuraplanejamento_id' => $configuraplanejamentoId])
            ->all();

        $porDocente = [];
        $porSala = [];
        foreach ($rows as $r) {
            if ($r->docente_id && $r->dia_id && $r->horario_id) {
                $porDocente[$r->docente_id . '-' . $r->dia_id . '-' . $r->horario_id][] = $r;
            }
            if ($r->sala_id && $r->dia_id && $r->horario_id) {
                $porSala[$r->sala_id . '-' . $r->dia_id . '-' . $r->horario_id][] = $r;
            }
        }

        $rotulo = fn($entidade, $campo, $id) => $entidade->{$campo} ?? ('#' . $id);
        $turmas = fn(array $grupo): array => array_map(
            fn($x) => $x->disciplina->disciplina ?? ('#' . $x->disciplina_id),
            $grupo
        );

        $conflitos = [];
        foreach ($porDocente as $grupo) {
            if (count($grupo) > 1) {
                $r = $grupo[0];
                $conflitos[] = [
                    'tipo' => 'docente',
                    'mensagem' => sprintf(
                        'Docente "%s" alocado em %d turmas no mesmo dia/horário (%s, %s).',
                        $rotulo($r->docente, 'nome', $r->docente_id),
                        count($grupo),
                        $rotulo($r->dia, 'dia', $r->dia_id),
                        $rotulo($r->horario, 'horario', $r->horario_id)
                    ),
                    'itens' => $turmas($grupo),
                ];
            }
        }
        foreach ($porSala as $grupo) {
            if (count($grupo) > 1) {
                $r = $grupo[0];
                $conflitos[] = [
                    'tipo' => 'sala',
                    'mensagem' => sprintf(
                        'Sala "%s" usada por %d turmas no mesmo dia/horário (%s, %s).',
                        $rotulo($r->sala, 'sala', $r->sala_id),
                        count($grupo),
                        $rotulo($r->dia, 'dia', $r->dia_id),
                        $rotulo($r->horario, 'horario', $r->horario_id)
                    ),
                    'itens' => $turmas($grupo),
                ];
            }
        }

        // Otimização: docente com alocação mas marcado como indisponível.
        $indisponiveis = TableRegistry::getTableLocator()->get('DocenteDisponibilidades')->find()
            ->where([
                'configuraplanejamento_id' => $configuraplanejamentoId,
                'disponivel' => false,
            ])
            ->all();
        $motivoPorDocente = [];
        foreach ($indisponiveis as $d) {
            $motivoPorDocente[$d->docente_id] = $d->motivo;
        }
        $avisados = [];
        foreach ($rows as $r) {
            if ($r->docente_id && array_key_exists($r->docente_id, $motivoPorDocente) && !isset($avisados[$r->docente_id])) {
                $avisados[$r->docente_id] = true;
                $motivo = $motivoPorDocente[$r->docente_id];
                $conflitos[] = [
                    'tipo' => 'disponibilidade',
                    'mensagem' => sprintf(
                        'Docente "%s" está marcado como INDISPONÍVEL neste semestre%s, mas possui alocação.',
                        $rotulo($r->docente, 'nome', $r->docente_id),
                        $motivo ? ' (motivo: ' . $motivo . ')' : ''
                    ),
                    'itens' => [],
                ];
            }
        }

        return $conflitos;
    }
}
