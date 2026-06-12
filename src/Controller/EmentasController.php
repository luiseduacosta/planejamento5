<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class EmentasController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $ementas = $this->paginate($this->Ementas->find()->contain(['Disciplinas']));
        $this->set(compact('ementas'));
    }

    public function view($id = null): void
    {
        $ementa = $this->Ementas->get($id, contain: ['Disciplinas']);
        $this->Authorization->skipAuthorization();
        $this->set(compact('ementa'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $ementa = $this->Ementas->newEmptyEntity();
        $this->Authorization->authorize($ementa, 'add');
        
        // Load disciplines for dropdown
        $disciplinas = $this->Ementas->Disciplinas->find('list', limit: 200)->all();
        $this->set(compact('disciplinas'));
        
        if ($this->request->is('post')) {
            $ementa = $this->Ementas->patchEntity($ementa, $this->request->getData());
            if ($this->Ementas->save($ementa)) {
                $this->Flash->success(__('A ementa foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar. Tente novamente.'));
        }
        $this->set(compact('ementa'));
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $ementa = $this->Ementas->get($id, contain: []);
        $this->Authorization->authorize($ementa, 'edit');
        
        $disciplinas = $this->Ementas->Disciplinas->find('list', limit: 200)->all();
        $this->set(compact('disciplinas'));
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ementa = $this->Ementas->patchEntity($ementa, $this->request->getData());
            if ($this->Ementas->save($ementa)) {
                $this->Flash->success(__('Atualizada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        $this->set(compact('ementa'));
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $ementa = $this->Ementas->get($id);
        $this->Authorization->authorize($ementa, 'delete');
        if ($this->Ementas->delete($ementa)) {
            $this->Flash->success(__('Excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
