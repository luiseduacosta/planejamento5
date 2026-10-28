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
            // The app requires one active semester: the first configuration
            // ever created becomes the active one.
            if ($this->getActiveConfiguraplanejamentoId() === null) {
                $configuracao->ativo = true;
            }
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
            // Forget the session choice when the semester in use was removed;
            // the table fallback re-activates the last remaining one.
            $this->setActiveConfiguraplanejamentoId(null);
            $this->Flash->success(__('Excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Marks a configuration as the active semester (persisted in DB and in
     * session) so every controller uses it as default until the user picks
     * another semester. The id may come from the URL ("Ativar" button) or
     * from the POST body (navbar dropdown switcher).
     */
    public function setativo($id = null): \Cake\Http\Response
    {
        $this->request->allowMethod(['post']);

        $id = $id ?? $this->request->getData('id');
        if ($id === null || $id === '') {
            $this->Flash->error(__('Semestre não informado.'));

            return $this->redirect($this->request->referer() ?: ['action' => 'index']);
        }

        $configuracao = $this->Configuraplanejamentos->find()
            ->where(['id' => (int)$id])
            ->first();
        if ($configuracao === null) {
            $this->Flash->error(__('Semestre não encontrado.'));

            return $this->redirect($this->request->referer() ?: ['action' => 'index']);
        }

        $this->Authorization->authorize($configuracao, 'edit');

        $configuracao->ativo = true;
        if ($this->Configuraplanejamentos->save($configuracao)) {
            $this->setActiveConfiguraplanejamentoId((int)$configuracao->id);
            $this->Flash->success(__('O semestre "{0}" agora está ativo.', $configuracao->semestre));
        } else {
            $this->Flash->error(__('Não foi possível ativar o semestre.'));
        }

        return $this->redirect($this->request->referer() ?: ['action' => 'index']);
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
