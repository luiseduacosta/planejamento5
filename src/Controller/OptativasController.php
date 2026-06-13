<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class OptativasController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $this->set('optativas', $this->paginate($this->Optativas->find()));
    }

    public function view($id = null): void
    {
        $this->Authorization->skipAuthorization();
        $this->set('optativa', $this->Optativas->get($id));
    }

    public function add(): \Cake\Http\Response|null
    {
        $optativa = $this->Optativas->newEmptyEntity();
        $this->Authorization->authorize($optativa, 'add');
        if ($this->request->is('post')) {
            $optativa = $this->Optativas->patchEntity($optativa, $this->request->getData());
            if ($this->Optativas->save($optativa)) {
                $this->Flash->success(__('A optativa foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar. Tente novamente.'));
        }
        $this->set(compact('optativa'));
        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $optativa = $this->Optativas->get($id);
        $this->Authorization->authorize($optativa, 'edit');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $optativa = $this->Optativas->patchEntity($optativa, $this->request->getData());
            if ($this->Optativas->save($optativa)) {
                $this->Flash->success(__('Atualizada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        $this->set(compact('optativa'));
        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $optativa = $this->Optativas->get($id);
        $this->Authorization->authorize($optativa, 'delete');
        if ($this->Optativas->delete($optativa)) {
            $this->Flash->success(__('Excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
