<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Professores Controller
 *
 * @property \App\Model\Table\ProfessoresTable $Professores
 */
class ProfessoresController extends AppController
{
    private const STATUS_LABELS = [
        'ativo' => 'Ativo',
        'aposentado' => 'Aposentado',
        'inativo' => 'Inativo',
    ];

    private const STATUS_ALIASES = [
        'ativo' => ['ativo', 'active', 'activo'],
        'aposentado' => ['aposentado', 'retired'],
        'inativo' => ['inativo', 'inactive', 'inactivo'],
    ];

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        // Get filter parameters from query string
        $statusFilter = $this->request->getQuery('status');
        $departamentoFilter = $this->request->getQuery('departamento');
        $configuraplanejamentoFilter = $this->request->getQuery('configuraplanejamento_id');

        // Get unique departamentos for dropdown
        $departamentos = $this->Professores->find()
            ->select(['departamento'])
            ->distinct(['departamento'])
            ->where(['departamento IS NOT' => null])
            ->orderBy(['departamento' => 'ASC'])
            ->toArray();

        $departamentosList = [];
        foreach ($departamentos as $departamento) {
            $departamentosList[$departamento->departamento] = $departamento->departamento;
        }

        // Get unique status for dropdown
        $status = $this->Professores->find()
            ->select(['status'])
            ->distinct(['status'])
            ->where(['status IS NOT' => null])
            ->orderBy(['status' => 'ASC'])
            ->toArray();
        $statusList = [];
        foreach ($status as $statusItem) {
            $canonicalStatus = $this->canonicalStatus((string) $statusItem->status);
            $statusList[$canonicalStatus] = self::STATUS_LABELS[$canonicalStatus] ?? $canonicalStatus;
        }
        asort($statusList);

        // Get planning configurations that have availability records for dropdown
        $configuracoes = $this->Professores->DocenteDisponibilidades->Configuraplanejamentos
            ->find()
            ->select(['id', 'semestre', 'versao'])
            ->distinct(['id'])
            ->orderBy(['semestre' => 'DESC', 'versao' => 'DESC'])
            ->toArray();

        $configuracoesList = [];
        foreach ($configuracoes as $configuracao) {
            $label = $configuracao->semestre . ' - ' . ($configuracao->versao ?? '1');
            $configuracoesList[$configuracao->id] = $label;
        }

        // Build query
        $query = $this->Professores->find();

        // Apply status filter (normalize aliases to canonical status first)
        if ($statusFilter) {
            $canonical = $this->canonicalStatus($statusFilter);
            $aliases = self::STATUS_ALIASES[$canonical] ?? [$canonical];
            $query->where(['Professores.status IN' => $aliases]);
        }

        // Apply departamento filter
        if ($departamentoFilter) {
            $query->where(['Professores.departamento' => $departamentoFilter]);
        }

        // Apply availability filter for a planning configuration
        if ($configuraplanejamentoFilter) {
            $query->matching('DocenteDisponibilidades', function ($q) use ($configuraplanejamentoFilter) {
                return $q->where([
                    'DocenteDisponibilidades.configuraplanejamento_id' => (int) $configuraplanejamentoFilter,
                    'DocenteDisponibilidades.disponivel' => true,
                ]);
            });
        }

        $config = [
            'order' => ['nome' => 'ASC'],
            'sortableFields' => [
                'id',
                'nome',
                'cpf',
                'siape',
                'departamento',
                'status',
                'email',
            ],
        ];

        $professores = $this->paginate($query, $config);

        $statusFilterLabel = $statusFilter ? (self::STATUS_LABELS[$this->canonicalStatus($statusFilter)] ?? $statusFilter) : null;
        $configuracaoFilterLabel = $configuraplanejamentoFilter ? ($configuracoesList[(int) $configuraplanejamentoFilter] ?? null) : null;

        // Determine which planning configuration to show in the availability
        // column: the persistent active semester (session > DB flag > last).
        $configuracaoAtiva = $this->getActiveConfiguraplanejamento();

        $configuracaoAtual = null;
        if ($configuraplanejamentoFilter) {
            $configuracaoAtual = $this->Professores->DocenteDisponibilidades->Configuraplanejamentos
                ->find()
                ->where(['id' => (int) $configuraplanejamentoFilter])
                ->first();
        } else {
            $configuracaoAtual = $configuracaoAtiva;
        }

        $disponibilidades = [];
        if ($configuracaoAtual !== null) {
            $disponibilidadesRows = $this->Professores->DocenteDisponibilidades
                ->find()
                ->where(['configuraplanejamento_id' => $configuracaoAtual->id])
                ->all();

            foreach ($disponibilidadesRows as $disponibilidade) {
                $disponibilidades[$disponibilidade->docente_id] = $disponibilidade;
            }
        }

        $this->set(compact(
            'professores',
            'departamentosList',
            'statusList',
            'statusFilter',
            'statusFilterLabel',
            'departamentoFilter',
            'configuracoesList',
            'configuraplanejamentoFilter',
            'configuracaoFilterLabel',
            'disponibilidades',
            'configuracaoAtiva',
            'configuracaoAtual'
        ));
    }

    public function view($id = null): void
    {
        $professor = $this->Professores->get($id, contain: [
            'Planejamentos' => [
                'Disciplinas',
                'Configuraplanejamentos',
                'Dias',
                'Horarios',
                'Salas',
            ],
            'DocenteDisponibilidades' => ['Configuraplanejamentos'],
        ]);

        // Related planning records, ordered by semester and then by day/time.
        $planejamentos = collection($professor->planejamentos ?? [])
            ->sortBy(function ($planejamento) {
                return sprintf(
                    '%s|%02d|%02d|%06d',
                    $planejamento->configuraplanejamento->semestre ?? '',
                    (int)($planejamento->dia_id ?? 0),
                    (int)($planejamento->horario_id ?? 0),
                    (int)$planejamento->id
                );
            }, SORT_ASC, SORT_STRING)
            ->toList();

        $this->Authorization->skipAuthorization();
        $this->set(compact('professor', 'planejamentos'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $professor = $this->Professores->newEmptyEntity();
        $professor->status = 'ativo';
        $this->Authorization->authorize($professor, 'add');

        if ($this->request->is('post')) {
            $professor = $this->Professores->patchEntity($professor, $this->request->getData());
            if ($this->Professores->save($professor)) {
                $this->Flash->success(__('O professor foi salvo com sucesso.'));
                return $this->redirect(['action' => 'view', $professor->id]);
            }
            $this->Flash->error(__('Não foi possível salvar o professor. Tente novamente.'));
        }
        $this->set(compact('professor'));

        return null;
    }

    private function canonicalStatus(string $status): string
    {
        foreach (self::STATUS_ALIASES as $canonicalStatus => $aliases) {
            if (\in_array($status, $aliases, true)) {
                return $canonicalStatus;
            }
        }

        return $status;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $professor = $this->Professores->get($id, contain: []);
        $professor->status = $this->canonicalStatus((string) $professor->status);
        $this->Authorization->authorize($professor, 'edit');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $professor = $this->Professores->patchEntity($professor, $this->request->getData());
            if ($this->Professores->save($professor)) {
                $this->Flash->success(__('O professor foi atualizado com sucesso.'));
                return $this->redirect(['action' => 'view', $professor->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar o professor. Tente novamente.'));
        }
        $this->set(compact('professor'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $professor = $this->Professores->get($id);
        $this->Authorization->authorize($professor, 'delete');

        if ($this->Professores->delete($professor)) {
            $this->Flash->success(__('O professor foi excluído com sucesso.'));
        } else {
            // Surface the real reason (e.g. the "hasPlanejamentos" rule) instead of a
            // generic message, so the admin knows deletion is blocked, not transient.
            $message = $this->firstErrorMessage($professor->getErrors())
                ?? __('Não foi possível excluir o professor. Tente novamente.');
            $this->Flash->error($message);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Extracts the first human-readable message from a (possibly nested) entity
     * errors array, e.g. ['id' => ['hasPlanejamentos' => 'message']].
     */
    private function firstErrorMessage(array $errors): ?string
    {
        foreach ($errors as $fieldErrors) {
            if (\is_string($fieldErrors) && $fieldErrors !== '') {
                return $fieldErrors;
            }
            if (\is_array($fieldErrors)) {
                foreach ($fieldErrors as $message) {
                    if (\is_string($message) && $message !== '') {
                        return $message;
                    }
                }
            }
        }

        return null;
    }
}
