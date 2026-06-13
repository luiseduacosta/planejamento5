<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Disciplinas Controller
 */
class DisciplinasController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $disciplinas = $this->paginate($this->Disciplinas->find());
        $this->set(compact('disciplinas'));
    }

    public function view($id = null): void
    {
        $disciplina = $this->Disciplinas->get($id, contain: ['Planejamentos']);
        $this->Authorization->skipAuthorization();
        $this->set(compact('disciplina'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $disciplina = $this->Disciplinas->newEmptyEntity();
        $this->Authorization->authorize($disciplina, 'add');
        
        if ($this->request->is('post')) {
            $disciplina = $this->Disciplinas->patchEntity($disciplina, $this->request->getData());
            if ($this->Disciplinas->save($disciplina)) {
                $this->Flash->success(__('A disciplina foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a disciplina. Tente novamente.'));
        }
        $this->set(compact('disciplina'));
        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $disciplina = $this->Disciplinas->get($id, contain: []);
        $this->Authorization->authorize($disciplina, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $disciplina = $this->Disciplinas->patchEntity($disciplina, $this->request->getData());
            if ($this->Disciplinas->save($disciplina)) {
                $this->Flash->success(__('A disciplina foi atualizada com sucesso.'));
                return $this->redirect(['action' => 'view', $disciplina->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar a disciplina. Tente novamente.'));
            debug($disciplina);
            exit;
        }
        $this->set(compact('disciplina'));
        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $disciplina = $this->Disciplinas->get($id);
        $this->Authorization->authorize($disciplina, 'delete');
        
        if ($this->Disciplinas->delete($disciplina)) {
            $this->Flash->success(__('A disciplina foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a disciplina. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
