<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Docentes Controller
 *
 * @property \App\Model\Table\DocentesTable $Docentes
 */
class DocentesController extends AppController
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
        
        // Get unique departamentos for dropdown
        $departamentos = $this->Docentes->find()
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
        $status = $this->Docentes->find()
            ->select(['status'])
            ->distinct(['status'])
            ->where(['status IS NOT' => null])
            ->orderBy(['status' => 'ASC'])
            ->toArray();
        $statusList = [];
        foreach ($status as $statusItem) {
            $canonicalStatus = $this->canonicalStatus((string)$statusItem->status);
            $statusList[$canonicalStatus] = self::STATUS_LABELS[$canonicalStatus] ?? $canonicalStatus;
        }
        asort($statusList);

        // Build query
        $query = $this->Docentes->find();
        
        // Apply status filter
        if ($statusFilter) {
            $query->where(['Docentes.status IN' => self::STATUS_ALIASES[$statusFilter] ?? [$statusFilter]]);
        }
        
        // Apply departamento filter
        if ($departamentoFilter) {
            $query->where(['Docentes.departamento' => $departamentoFilter]);
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

        $docentes = $this->paginate($query, $config);

        $statusFilterLabel = $statusFilter ? (self::STATUS_LABELS[$this->canonicalStatus($statusFilter)] ?? $statusFilter) : null;

        $this->set(compact(
            'docentes',
            'departamentosList',
            'statusList',
            'statusFilter',
            'statusFilterLabel',
            'departamentoFilter'
        ));
    }

    public function view($id = null): void
    {
        $docente = $this->Docentes->get($id, contain: [
            'Planejamentos',
            'DocenteDisponibilidades' => ['Configuraplanejamentos'],
        ]);
        $this->Authorization->skipAuthorization();
        $this->set(compact('docente'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $docente = $this->Docentes->newEmptyEntity();
        $this->Authorization->authorize($docente, 'add');
        
        if ($this->request->is('post')) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('O docente foi salvo com sucesso.'));
                return $this->redirect(['action' => 'view', $docente->id]);
            }
            $this->Flash->error(__('Não foi possível salvar o docente. Tente novamente.'));
        }
        $this->set(compact('docente'));

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
        $docente = $this->Docentes->get($id, contain: []);
        $docente->status = $this->canonicalStatus((string)$docente->status);
        $this->Authorization->authorize($docente, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('O docente foi atualizado com sucesso.'));
                return $this->redirect(['action' => 'view', $docente->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar o docente. Tente novamente.'));
        }
        $this->set(compact('docente'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $docente = $this->Docentes->get($id);
        $this->Authorization->authorize($docente, 'delete');
        
        if ($this->Docentes->delete($docente)) {
            $this->Flash->success(__('O docente foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir o docente. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
