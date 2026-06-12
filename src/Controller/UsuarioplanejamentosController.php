<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Auth\DefaultPasswordHasher;

class UsuarioplanejamentosController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Only allow index for viewing list, rest requires auth
        $this->Authentication->addUnauthenticatedActions(['index']);
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
            $data = $this->request->getData();
            // Hash password
            if (!empty($data['password'])) {
                $data['password'] = (new DefaultPasswordHasher())->hash($data['password']);
            }
            $user = $this->Usuarioplanejamentos->patchEntity($user, $data);
            if ($this->Usuarioplanejamentos->save($user)) {
                $this->Flash->success(__('Usuário criado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível criar o usuário.'));
        }
        $this->set(compact('user'));
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $user = $this->Usuarioplanejamentos->get($id);
        $this->Authorization->authorize($user, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            // Only hash password if it was changed
            if (!empty($data['password'])) {
                $data['password'] = (new DefaultPasswordHasher())->hash($data['password']);
            } else {
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
}
