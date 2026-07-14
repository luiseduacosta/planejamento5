<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class ConfiguraplanejamentosController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $query = $this->Configuraplanejamentos->find()
            ->orderBy(['semestre' => 'DESC']);
        $configuracoes = $this->paginate($query);
        $this->set(compact('configuracoes'));
    }

    public function view($id = null): void
    {
        $configuracao = $this->Configuraplanejamentos->get($id, contain: ['Usuarioplanejamentos', 'Planejamentos']);
        $this->Authorization->skipAuthorization();
        $this->set(compact('configuracao'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $configuracao = $this->Configuraplanejamentos->newEmptyEntity();
        $this->Authorization->authorize($configuracao, 'add');
        
        // Get current user ID
        $user = $this->request->getAttribute('identity');
        if ($user) {
            $configuracao->usuarioplanejamento_id = $user->id;
        }
        
        if ($this->request->is('post')) {
            $configuracao = $this->Configuraplanejamentos->patchEntity($configuracao, $this->request->getData());
            if ($this->Configuraplanejamentos->save($configuracao)) {
                $this->Flash->success(__('A configuração foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar. Tente novamente.'));
        }
        $this->set(compact('configuracao'));
        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $configuracao = $this->Configuraplanejamentos->get($id, contain: []);
        $this->Authorization->authorize($configuracao, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configuracao = $this->Configuraplanejamentos->patchEntity($configuracao, $this->request->getData());
            if ($this->Configuraplanejamentos->save($configuracao)) {
                $this->Flash->success(__('Atualizada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        $this->set(compact('configuracao'));
        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $configuracao = $this->Configuraplanejamentos->get($id);
        $this->Authorization->authorize($configuracao, 'delete');
        
        if ($this->Configuraplanejamentos->delete($configuracao)) {
            $this->Flash->success(__('Excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function clone($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post']);
        $original = $this->Configuraplanejamentos->get($id, contain: ['Planejamentos']);
        $this->Authorization->authorize($original, 'clone');
        
        // Clone configuration
        $clone = $this->Configuraplanejamentos->newEmptyEntity();
        $clone->usuarioplanejamento_id = $original->usuarioplanejamento_id;
        $clone->nome = $original->nome . ' (Cópia)';
        $clone->semestre = $original->semestre;
        $clone->versao = $original->versao ?? 1;
        $clone->ativo = false;
        
        if ($this->Configuraplanejamentos->save($clone)) {
            $this->Flash->success(__('Configuração clonada com sucesso!'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->Flash->error(__('Não foi possível clonar.'));
        return $this->redirect(['action' => 'index']);
    }
}
