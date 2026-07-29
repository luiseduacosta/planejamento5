<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsuarioplanejamentosController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Allow logout to run even if the session has already expired
        $this->Authentication->addUnauthenticatedActions(['logout']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $users = $this->paginate($this->Usuarioplanejamentos);
        $this->set(compact('users'));
    }

    public function view($id = null): void
    {
        $user = $this->Usuarioplanejamentos->get($id);
        $this->Authorization->authorize($user, 'view');
        $this->set(compact('user'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $user = $this->Usuarioplanejamentos->newEmptyEntity();
        $this->Authorization->authorize($user, 'add');
        
        if ($this->request->is('post')) {
            $user = $this->Usuarioplanejamentos->patchEntity($user, $this->request->getData());
            if ($this->Usuarioplanejamentos->save($user)) {
                $this->Flash->success(__('Usuário criado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível criar o usuário.'));
        }
        $this->set(compact('user'));

        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $user = $this->Usuarioplanejamentos->get($id);
        $this->Authorization->authorize($user, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (empty($data['password'])) {
                unset($data['password']);
            }
            $user = $this->Usuarioplanejamentos->patchEntity($user, $data);
            if ($this->Usuarioplanejamentos->save($user)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        $this->set(compact('user'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Usuarioplanejamentos->get($id);
        $this->Authorization->authorize($user, 'delete');
        if ($this->Usuarioplanejamentos->delete($user)) {
            $this->Flash->success(__('Usuário excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function logout(): \Cake\Http\Response|null
    {
        $this->Authorization->skipAuthorization();
        $this->Authentication->logout();
        $this->Flash->success(__('Até mais! Você foi deslogado.'));

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
