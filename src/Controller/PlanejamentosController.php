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
        
        // Get selected semestre from query params
        $selectedSemestre = $this->request->getQuery('semestre');
        
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
            'Docentes.nome', 
            'Configuraplanejamentos.semestre', 
            'Dias.dia', 
            'Horarios.horario', 
            'Salas.sala'
            ],
        ];
        
        $planejamentos = $this->paginate($query, $config);
        $this->set(compact('planejamentos', 'semestresList', 'selectedSemestre'));
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
            $planejamento->configuraplanejamento_id = (int)$selectedConfiguracaoId;
            $selectedConfiguracaoId = (int)$selectedConfiguracaoId;
        } else {
            $selectedConfiguracaoId = null;
        }
        
        $this->_setRelatedData($selectedConfiguracaoId, null);
        
        if ($this->request->is('post')) {
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $this->request->getData());
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
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $this->request->getData());
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
        $salas = $this->Planejamentos->Salas->find('list', limit: 200)->all();
        $dias = $this->Planejamentos->Dias->find('list', limit: 200)->all();
        $horarios = $this->Planejamentos->Horarios->find('list', limit: 200)->all();

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
