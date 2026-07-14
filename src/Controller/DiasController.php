<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Dias Controller
 *
 * @property \App\Model\Table\DiasTable $Dias
 */
class DiasController extends AppController
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
        $query = $this->Dias->find();
        $dias = $this->paginate($query);

        $this->set(compact('dias'));
    }

    /**
     * View method
     *
     * @param string|null $id Dia id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null): void
    {
        $dia = $this->Dias->get($id, contain: []);
        $this->Authorization->skipAuthorization();
        $this->set(compact('dia'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): \Cake\Http\Response|null
    {
        $dia = $this->Dias->newEmptyEntity();
        $this->Authorization->authorize($dia, 'add');
        
        if ($this->request->is('post')) {
            $dia = $this->Dias->patchEntity($dia, $this->request->getData());
            if ($this->Dias->save($dia)) {
                $this->Flash->success(__('O dia foi salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar o dia. Tente novamente.'));
        }
        $this->set(compact('dia'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dia id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null): \Cake\Http\Response|null
    {
        $dia = $this->Dias->get($id, contain: []);
        $this->Authorization->authorize($dia, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dia = $this->Dias->patchEntity($dia, $this->request->getData());
            if ($this->Dias->save($dia)) {
                $this->Flash->success(__('O dia foi atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar o dia. Tente novamente.'));
        }
        $this->set(compact('dia'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dia id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $dia = $this->Dias->get($id);
        $this->Authorization->authorize($dia, 'delete');
        
        if ($this->Dias->delete($dia)) {
            $this->Flash->success(__('O dia foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir o dia. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
