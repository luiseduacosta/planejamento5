<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Horarios Controller
 *
 * @property \App\Model\Table\HorariosTable $Horarios
 */
class HorariosController extends AppController
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
        $query = $this->Horarios->find();
        $horarios = $this->paginate($query);

        $this->set(compact('horarios'));
    }

    /**
     * View method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null): void
    {
        $horario = $this->Horarios->get($id, contain: []);
        $this->Authorization->skipAuthorization();
        $this->set(compact('horario'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): \Cake\Http\Response|null
    {
        $horario = $this->Horarios->newEmptyEntity();
        $this->Authorization->authorize($horario, 'add');
        
        if ($this->request->is('post')) {
            $horario = $this->Horarios->patchEntity($horario, $this->request->getData());
            if ($this->Horarios->save($horario)) {
                $this->Flash->success(__('O horário foi salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar o horário. Tente novamente.'));
        }
        $this->set(compact('horario'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null): \Cake\Http\Response|null
    {
        $horario = $this->Horarios->get($id, contain: []);
        $this->Authorization->authorize($horario, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $horario = $this->Horarios->patchEntity($horario, $this->request->getData());
            if ($this->Horarios->save($horario)) {
                $this->Flash->success(__('O horário foi atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar o horário. Tente novamente.'));
        }
        $this->set(compact('horario'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Horario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $horario = $this->Horarios->get($id);
        $this->Authorization->authorize($horario, 'delete');
        
        if ($this->Horarios->delete($horario)) {
            $this->Flash->success(__('O horário foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir o horário. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
