<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class DocenteDisponibilidadesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        $docenteId = $this->request->getQuery('docente_id');

        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $query = $docenteDisponibilidades->find()
            ->contain(['Docentes', 'Configuraplanejamentos'])
            ->orderBy(['Configuraplanejamentos.semestre' => 'DESC', 'DocenteDisponibilidades.id' => 'DESC']);

        if ($docenteId) {
            $query->where(['DocenteDisponibilidades.docente_id' => $docenteId]);
        }

        $disponibilidades = $this->paginate($query);
        $this->set(compact('disponibilidades', 'docenteId'));
    }

    public function view($id = null): void
    {
        $this->Authorization->skipAuthorization();
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id, contain: ['Docentes', 'Configuraplanejamentos']);
        $this->set(compact('docenteDisponibilidade'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->newEmptyEntity();
        $this->Authorization->authorize($docenteDisponibilidade, 'add');

        $prefillDocenteId = $this->request->getQuery('docente_id');
        if ($prefillDocenteId) {
            $docenteDisponibilidade->docente_id = (int)$prefillDocenteId;
        }

        $prefillConfiguracaoId = $this->request->getQuery('configuraplanejamento_id');
        if ($prefillConfiguracaoId) {
            $docenteDisponibilidade->configuraplanejamento_id = (int)$prefillConfiguracaoId;
        }

        if ($this->request->is('post')) {
            $docenteDisponibilidade = $docenteDisponibilidades->patchEntity($docenteDisponibilidade, $this->request->getData());
            if ($docenteDisponibilidades->save($docenteDisponibilidade)) {
                $this->Flash->success(__('A disponibilidade foi salva com sucesso.'));

                return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]]);
            }
            $this->Flash->error(__('Não foi possível salvar a disponibilidade. Tente novamente.'));
        }

        $docentes = $this->fetchTable('Docentes')->find('list', limit: 200)->all();
        $configuracoes = $this->fetchTable('Configuraplanejamentos')->find('list', limit: 200)->all();
        $this->set(compact('docenteDisponibilidade', 'docentes', 'configuracoes'));

        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id, contain: []);
        $this->Authorization->authorize($docenteDisponibilidade, 'edit');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $docenteDisponibilidade = $docenteDisponibilidades->patchEntity($docenteDisponibilidade, $this->request->getData());
            if ($docenteDisponibilidades->save($docenteDisponibilidade)) {
                $this->Flash->success(__('A disponibilidade foi atualizada com sucesso.'));

                return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]]);
            }
            $this->Flash->error(__('Não foi possível atualizar a disponibilidade. Tente novamente.'));
        }

        $docentes = $this->fetchTable('Docentes')->find('list', limit: 200)->all();
        $configuracoes = $this->fetchTable('Configuraplanejamentos')->find('list', limit: 200)->all();
        $this->set(compact('docenteDisponibilidade', 'docentes', 'configuracoes'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id);
        $this->Authorization->authorize($docenteDisponibilidade, 'delete');

        $docenteId = $docenteDisponibilidade->docente_id;

        if ($docenteDisponibilidades->delete($docenteDisponibilidade)) {
            $this->Flash->success(__('A disponibilidade foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a disponibilidade. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteId]]);
    }
}
