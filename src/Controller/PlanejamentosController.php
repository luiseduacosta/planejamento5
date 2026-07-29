<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class PlanejamentosController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        
        // Get selected semestre from query params. An explicit choice becomes
        // the active configuration for the whole session; when the parameter is
        // absent (not just empty), default to the active configuration's
        // semester so the user keeps working within the configuration in use.
        $selectedSemestre = $this->request->getQuery('semestre');
        if ($selectedSemestre !== null && $selectedSemestre !== '') {
            $chosenConfig = $this->Planejamentos->Configuraplanejamentos->find()
                ->where(['semestre' => $selectedSemestre])
                ->orderBy(['ativo' => 'DESC', 'versao' => 'DESC'])
                ->first();
            if ($chosenConfig !== null) {
                $this->setActiveConfiguraplanejamentoId($chosenConfig->id);
            }
        } elseif ($selectedSemestre === null) {
            $activeId = $this->getActiveConfiguraplanejamentoId();
            if ($activeId !== null) {
                $activeConfig = $this->Planejamentos->Configuraplanejamentos->find()
                    ->select(['semestre'])
                    ->where(['id' => $activeId])
                    ->first();
                if ($activeConfig !== null) {
                    $selectedSemestre = $activeConfig->semestre;
                }
            }
        }
        
        // Extract unique semestres from Configuraplanejamentos
        $semestres = $this->Planejamentos->Configuraplanejamentos->find()
            ->select(['semestre'])
            ->distinct(['semestre'])
            ->orderBy(['semestre' => 'DESC'])
            ->toArray();
        
        $semestresList = [];
        foreach ($semestres as $semestre) {
            $semestresList[$semestre->semestre] = $semestre->semestre;
        }
        
        // Build query
        $query = $this->Planejamentos->find()
            ->contain([
                'Disciplinas',
                'Docentes',
                'Configuraplanejamentos',
                'Salas',
                'Dias',
                'Horarios',
            ]);
        
        // Filter by selected semestre if provided
        if ($selectedSemestre) {
            $query->matching('Configuraplanejamentos', function ($q) use ($selectedSemestre) {
                return $q->where(['Configuraplanejamentos.semestre' => $selectedSemestre]);
            });
        }

        $config = [
            'sortableFields' => ['Planejamentos.id', 
            'Disciplinas.disciplina', 
            'Planejamentos.periodo', 
            'Docentes.nome', 
            'Configuraplanejamentos.semestre', 
            'Dias.dia', 
            'Horarios.horario', 
            'Salas.sala'
            ],
        ];
        
        $planejamentos = $this->paginate($query, $config);

        // Conflict/optimization warnings for the configuration currently in use
        // (the active configuration = the schedule the user is working on).
        $activeConfigId = $this->getActiveConfiguraplanejamentoId();
        $conflitos = [];
        $conflitoSemestre = null;
        if ($activeConfigId !== null) {
            $conflitos = $this->Planejamentos->detectarConflitos($activeConfigId);
            $cfg = $this->Planejamentos->Configuraplanejamentos->find()
                ->select(['semestre'])
                ->where(['id' => $activeConfigId])
                ->first();
            $conflitoSemestre = $cfg?->semestre;
        }

        $this->set(compact('planejamentos', 'semestresList', 'selectedSemestre', 'conflitos', 'conflitoSemestre'));
    }
 
    public function view($id = null): void
    {
        $planejamento = $this->Planejamentos->get($id, contain: [
            'Disciplinas',
            'Docentes',
            'Configuraplanejamentos',
            'Salas',
            'Dias',
            'Horarios',
        ]);
        $this->Authorization->skipAuthorization();
        $this->set(compact('planejamento'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $planejamento = $this->Planejamentos->newEmptyEntity();
        $this->Authorization->authorize($planejamento, 'add');

        $selectedConfiguracaoId = $this->request->getQuery('configuraplanejamento_id');
        if ($selectedConfiguracaoId !== null && $selectedConfiguracaoId !== '') {
            $selectedConfiguracaoId = (int)$selectedConfiguracaoId;
        } else {
            // Nothing chosen in the form: use the active session configuration.
            $selectedConfiguracaoId = $this->getActiveConfiguraplanejamentoId();
        }
        if ($selectedConfiguracaoId !== null) {
            $planejamento->configuraplanejamento_id = $selectedConfiguracaoId;
        }
        
        $this->_setRelatedData($selectedConfiguracaoId, null);
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Set turno based on horario_id
            if (in_array($data['horario_id'], [1, 2, 3, 4])) {
                $data['turno'] = 'diurno';
            } else {
                $data['turno'] = 'noturno';
            }
            // Set periodo based on disciplina_id
            if ($data['disciplina_id']) {
                $disciplina = $this->fetchTable('Disciplinas')->get($data['disciplina_id']);
                $periodo = $disciplina->periodo_diurno ? $disciplina->periodo_diurno : $disciplina->periodo_noturno;
                $data['periodo'] = (int)$periodo;
            } else {
                $this->Flash->error(__('Por favor, selecione uma disciplina.'));
                return $this->redirect(['action' => 'index']);
            }
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $data);
            $selectedConfiguracaoId = $planejamento->configuraplanejamento_id ?: null;
            $this->_setRelatedData($selectedConfiguracaoId, null);
            if ($this->Planejamentos->save($planejamento)) {
                $this->Flash->success(__('O planejamento foi salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar. Tente novamente.'));
        }
        $this->set(compact('planejamento', 'selectedConfiguracaoId'));

        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $planejamento = $this->Planejamentos->get($id, contain: []);
        $this->Authorization->authorize($planejamento, 'edit');

        $selectedConfiguracaoId = $this->request->getQuery('configuraplanejamento_id');
        if ($selectedConfiguracaoId !== null && $selectedConfiguracaoId !== '') {
            $selectedConfiguracaoId = (int)$selectedConfiguracaoId;
            $planejamento->configuraplanejamento_id = $selectedConfiguracaoId;
        } else {
            $selectedConfiguracaoId = $planejamento->configuraplanejamento_id ?: null;
        }
        
        $this->_setRelatedData($selectedConfiguracaoId, $planejamento->docente_id ?: null);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            // Set turno based on horario_id
            if (in_array($data['horario_id'], [1, 2, 3, 4])) {
                $data['turno'] = 'diurno';
            } else {
                $data['turno'] = 'noturno';
            }
            // Set periodo based on disciplina_id
            if ($data['disciplina_id']) {
                $disciplina = $this->fetchTable('Disciplinas')->get($data['disciplina_id']);
                $periodo = $disciplina->periodo_diurno ? $disciplina->periodo_diurno : $disciplina->periodo_noturno;
                $data['periodo'] = (int)$periodo;
            } else {
                $this->Flash->error(__('Por favor, selecione uma disciplina.'));
                return $this->redirect(['action' => 'index']);
            }
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $data);
            $selectedConfiguracaoId = $planejamento->configuraplanejamento_id ?: null;
            $this->_setRelatedData($selectedConfiguracaoId, $planejamento->docente_id ?: null);
            if ($this->Planejamentos->save($planejamento)) {
                $this->Flash->success(__('Planejamento atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        $this->set(compact('planejamento', 'selectedConfiguracaoId'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $planejamento = $this->Planejamentos->get($id);
        $this->Authorization->authorize($planejamento, 'delete');
        
        if ($this->Planejamentos->delete($planejamento)) {
            $this->Flash->success(__('Planejamento excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Clona (copia) todos os planejamentos de uma configuração de origem para
     * uma de destino. O destino precisa estar vazio; caso contrário a cópia é
     * bloqueada e o usuário deve excluir os planejamentos do destino primeiro.
     */
    public function clonar(): \Cake\Http\Response|null
    {
        $this->Authorization->authorize($this->Planejamentos->newEmptyEntity(), 'clone');

        $configuracoes = $this->Planejamentos->Configuraplanejamentos
            ->find('list', valueField: 'nome')
            ->orderBy(['semestre' => 'DESC'])
            ->toArray();

        // Seleção inicial: via querystring (botão em cada configuração) ou POST.
        $origemId = $this->request->getQuery('origem');
        $destinoId = $this->request->getQuery('destino');
        if ($this->request->is('post')) {
            $origemId = $this->request->getData('origem');
            $destinoId = $this->request->getData('destino');
        }
        $origemId = ($origemId !== null && $origemId !== '') ? (int)$origemId : null;
        $destinoId = ($destinoId !== null && $destinoId !== '') ? (int)$destinoId : null;

        // Origem padrão: a última configuração (maior id) com planejamentos na
        // tabela, diferente do destino, para facilitar copiar do semestre anterior.
        if ($origemId === null) {
            $ultima = $this->Planejamentos->find()
                ->select(['configuraplanejamento_id'])
                ->where($destinoId !== null ? ['configuraplanejamento_id !=' => $destinoId] : [])
                ->orderBy(['configuraplanejamento_id' => 'DESC'])
                ->first();
            if ($ultima !== null) {
                $origemId = $ultima->configuraplanejamento_id;
            }
        }

        if ($this->request->is('post')) {
            if ($origemId === null || $destinoId === null) {
                $this->Flash->error(__('Selecione a configuração de origem e a de destino.'));
            } elseif ($origemId === $destinoId) {
                $this->Flash->error(__('A origem e o destino devem ser diferentes.'));
            } else {
                $origemCount = $this->Planejamentos->find()
                    ->where(['configuraplanejamento_id' => $origemId])->count();
                $destinoCount = $this->Planejamentos->find()
                    ->where(['configuraplanejamento_id' => $destinoId])->count();
                if ($origemCount === 0) {
                    $this->Flash->error(__('A configuração de origem não possui planejamentos para copiar.'));
                } elseif ($destinoCount > 0) {
                    $this->Flash->error(__('O destino já possui {0} planejamento(s). Exclua-os primeiro para poder clonar.', $destinoCount));
                } else {
                    try {
                        $copiados = $this->Planejamentos->clonarPlanejamentos($origemId, $destinoId);
                        $this->setActiveConfiguraplanejamentoId($destinoId);
                        $this->Flash->success(__('{0} planejamento(s) copiado(s) com sucesso para o destino.', $copiados));
                        return $this->redirect(['action' => 'index']);
                    } catch (\Exception $e) {
                        $this->Flash->error(__('Não foi possível clonar os planejamentos.'));
                    }
                }
            }
        }

        $this->set(compact('configuracoes', 'origemId', 'destinoId'));

        return null;
    }

    /**
     * Exclui TODOS os planejamentos de uma configuração (semestre).
     */
    public function excluirTodos($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->Authorization->authorize($this->Planejamentos->newEmptyEntity(), 'delete');

        $configuraplanejamentoId = $id !== null
            ? (int)$id
            : (int)$this->request->getData('configuraplanejamento_id');
        if ($configuraplanejamentoId <= 0) {
            $this->Flash->error(__('Configuração inválida.'));
            return $this->redirect(['action' => 'index']);
        }

        $excluidos = $this->Planejamentos->excluirPorConfiguracao($configuraplanejamentoId);
        if ($excluidos > 0) {
            $this->Flash->success(__('{0} planejamento(s) excluído(s) da configuração selecionada.', $excluidos));
        } else {
            $this->Flash->warning(__('Não havia planejamentos para excluir nessa configuração.'));
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    public function listar(): void
    {
        $this->Authorization->skipAuthorization();
        
        // Group by configuraplanejamento
        $query = $this->Planejamentos->find()
            ->contain([
                'Disciplinas',
                'Docentes',
                'Configuraplanejamentos',
                'Salas',
                'Dias',
                'Horarios',
            ])
            ->order(['Configuraplanejamentos.semestre' => 'DESC']);
        
        $planejamentos = $this->paginate($query);
        $this->set(compact('planejamentos'));
    }

    protected function _setRelatedData(?int $configuraplanejamentoId = null, ?int $currentDocenteId = null): void
    {
        $disciplinas = $this->Planejamentos->Disciplinas->find('list', limit: 200)->all();
        $configuracoes = $this->Planejamentos->Configuraplanejamentos->find('list', limit: 200)->all();
        $salas = $this->Planejamentos->Salas->find('list')->all();
        $dias = $this->Planejamentos->Dias->find('list')->all();
        $horarios = $this->Planejamentos->Horarios->find('list')->all();

        $docentesQuery = $this->Planejamentos->Docentes
            ->find('list', limit: 200)
            ->where(['Docentes.status IN' => ['ativo', 'active', 'activo']])
            ->orderBy(['Docentes.nome' => 'ASC']);

        $docentesFilteredByDisponibilidade = false;
        if ($configuraplanejamentoId !== null) {
            $docentesFilteredByDisponibilidade = true;
            $docentesQuery->matching('DocenteDisponibilidades', function ($q) use ($configuraplanejamentoId) {
                return $q->where([
                    'DocenteDisponibilidades.configuraplanejamento_id' => $configuraplanejamentoId,
                    'DocenteDisponibilidades.disponivel' => true,
                ]);
            });
        }

        $docentes = $docentesQuery->toArray();
        if ($currentDocenteId !== null && !isset($docentes[$currentDocenteId])) {
            $currentDocente = $this->Planejamentos->Docentes->find()
                ->select(['id', 'nome'])
                ->where(['Docentes.id' => $currentDocenteId])
                ->first();
            if ($currentDocente) {
                $docentes[$currentDocente->id] = $currentDocente->nome;
            }
        }
        
        $this->set(compact(
            'disciplinas',
            'docentes',
            'configuracoes',
            'salas',
            'dias',
            'horarios',
            'docentesFilteredByDisponibilidade',
            'configuraplanejamentoId'
        ));
    }
}
