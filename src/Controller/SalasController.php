<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Salas Controller
 *
 * @property \App\Model\Table\SalasTable $Salas
 */
class SalasController extends AppController
{
    /**
     * Before filter - allow public access to index and view
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        
        // Allow public access to index and view
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $query = $this->Salas->find();
        $salas = $this->paginate($query);

        $this->set(compact('salas'));
    }

    /**
     * View method
     *
     * @param string|null $id Sala id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null): void
    {
        $sala = $this->Salas->get($id, contain: []);
        $this->Authorization->skipAuthorization();
        $this->set(compact('sala'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): \Cake\Http\Response|null
    {
        $sala = $this->Salas->newEmptyEntity();
        $this->Authorization->authorize($sala, 'add');
        
        if ($this->request->is('post')) {
            $sala = $this->Salas->patchEntity($sala, $this->request->getData());
            if ($this->Salas->save($sala)) {
                $this->Flash->success(__('A sala foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a sala. Tente novamente.'));
        }
        $this->set(compact('sala'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sala id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null): \Cake\Http\Response|null
    {
        $sala = $this->Salas->get($id, contain: []);
        $this->Authorization->authorize($sala, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sala = $this->Salas->patchEntity($sala, $this->request->getData());
            if ($this->Salas->save($sala)) {
                $this->Flash->success(__('A sala foi atualizada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar a sala. Tente novamente.'));
        }
        $this->set(compact('sala'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sala id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $sala = $this->Salas->get($id);
        $this->Authorization->authorize($sala, 'delete');
        
        if ($this->Salas->delete($sala)) {
            $this->Flash->success(__('A sala foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a sala. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
