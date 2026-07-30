<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class DocenteDisponibilidadesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        $docenteId = $this->request->getQuery('docente_id');

        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $query = $docenteDisponibilidades->find()
            ->contain(['Docentes', 'Configuraplanejamentos'])
            ->orderBy(['Configuraplanejamentos.semestre' => 'DESC', 'DocenteDisponibilidades.id' => 'DESC']);

        if ($docenteId) {
            $query->where(['DocenteDisponibilidades.docente_id' => $docenteId]);
        }

        $disponibilidades = $this->paginate($query);
        $this->set(compact('disponibilidades', 'docenteId'));
    }

    public function view($id = null): void
    {
        $this->Authorization->skipAuthorization();
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id, contain: ['Docentes', 'Configuraplanejamentos']);
        $this->set(compact('docenteDisponibilidade'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->newEmptyEntity();
        $this->Authorization->authorize($docenteDisponibilidade, 'add');

        $prefillDocenteId = $this->request->getQuery('docente_id');
        if ($prefillDocenteId) {
            $docenteDisponibilidade->docente_id = (int)$prefillDocenteId;
        }

        $prefillConfiguracaoId = $this->request->getQuery('configuraplanejamento_id');
        if ($prefillConfiguracaoId) {
            $docenteDisponibilidade->configuraplanejamento_id = (int)$prefillConfiguracaoId;
        }

        if ($this->request->is('post')) {
            $docenteDisponibilidade = $docenteDisponibilidades->patchEntity($docenteDisponibilidade, $this->request->getData());
            if ($docenteDisponibilidades->save($docenteDisponibilidade)) {
                $this->Flash->success(__('A disponibilidade foi salva com sucesso.'));

                return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]]);
            }
            $this->Flash->error(__('Não foi possível salvar a disponibilidade. Tente novamente.'));
        }

        $docentes = $this->fetchTable('Docentes')->find('list', limit: 200)->all();
        $configuracoes = $this->fetchTable('Configuraplanejamentos')->find('list', limit: 200)->all();
        $this->set(compact('docenteDisponibilidade', 'docentes', 'configuracoes'));

        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id, contain: []);
        $this->Authorization->authorize($docenteDisponibilidade, 'edit');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $docenteDisponibilidade = $docenteDisponibilidades->patchEntity($docenteDisponibilidade, $this->request->getData());
            if ($docenteDisponibilidades->save($docenteDisponibilidade)) {
                $this->Flash->success(__('A disponibilidade foi atualizada com sucesso.'));

                return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]]);
            }
            $this->Flash->error(__('Não foi possível atualizar a disponibilidade. Tente novamente.'));
        }

        $docentes = $this->fetchTable('Docentes')->find('list', limit: 200)->all();
        $configuracoes = $this->fetchTable('Configuraplanejamentos')->find('list', limit: 200)->all();
        $this->set(compact('docenteDisponibilidade', 'docentes', 'configuracoes'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $docenteDisponibilidades = $this->fetchTable('DocenteDisponibilidades');
        $docenteDisponibilidade = $docenteDisponibilidades->get($id);
        $this->Authorization->authorize($docenteDisponibilidade, 'delete');

        $docenteId = $docenteDisponibilidade->docente_id;

        if ($docenteDisponibilidades->delete($docenteDisponibilidade)) {
            $this->Flash->success(__('A disponibilidade foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a disponibilidade. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['docente_id' => $docenteId]]);
    }

    /**
     * Upsert rápido da disponibilidade de um docente numa configuração.
     * Usado pelo botão de alternar (Sim/Não) no index de Docentes.
     * Respeita o índice único (docente_id, configuraplanejamento_id).
     *
     * Para submissões AJAX (header X-Requested-With: XMLHttpRequest ou campo
     * _ajax=1) retorna uma resposta JSON com o resultado e omite o redirect e
     * as mensagens Flash (essas serão exibidas inline no cliente). Para
     * submissões normais via formulário, mantém o comportamento legado de
     * redirect + Flash (100% retrocompatível).
     */
    public function salvarRapido(): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post']);
        $table = $this->fetchTable('DocenteDisponibilidades');

        $docenteId = (int)$this->request->getData('docente_id');
        $configId = (int)$this->request->getData('configuraplanejamento_id');
        $disponivel = (bool)$this->request->getData('disponivel');
        $motivo = trim((string)$this->request->getData('motivo'));

        $fallback = $this->referer(['controller' => 'Docentes', 'action' => 'index']);

        $isAjax = $this->request->is('ajax')
            || $this->request->getData('_ajax') !== null
            || mb_strtolower((string)$this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';

        if ($docenteId <= 0 || $configId <= 0) {
            if ($isAjax) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'ok' => false,
                        'message' => __('Dados inválidos para atualizar a disponibilidade.'),
                    ], JSON_UNESCAPED_UNICODE));
            }
            $this->Flash->error(__('Dados inválidos para atualizar a disponibilidade.'));

            return $this->redirect($fallback);
        }

        $disponibilidade = $table->find()
            ->where([
                'docente_id' => $docenteId,
                'configuraplanejamento_id' => $configId,
            ])
            ->first();
        if ($disponibilidade === null) {
            $disponibilidade = $table->newEmptyEntity();
            $disponibilidade->docente_id = $docenteId;
            $disponibilidade->configuraplanejamento_id = $configId;
        }

        $this->Authorization->authorize($disponibilidade, $disponibilidade->isNew() ? 'add' : 'edit');

        $disponibilidade->disponivel = $disponivel;
        // O motivo só faz sentido quando o docente está indisponível.
        $disponibilidade->motivo = $disponivel ? null : ($motivo !== '' ? $motivo : null);

        $saved = $table->save($disponibilidade);
        if ($saved) {
            $message = __('Disponibilidade atualizada.');
            if ($isAjax) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'ok' => true,
                        'message' => $message,
                        'disponivel' => (bool)$disponibilidade->disponivel,
                        'motivo' => $disponibilidade->motivo,
                    ], JSON_UNESCAPED_UNICODE));
            }
            $this->Flash->success($message);
        } else {
            $message = __('Não foi possível atualizar a disponibilidade.');
            if ($isAjax) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'ok' => false,
                        'message' => $message,
                    ], JSON_UNESCAPED_UNICODE));
            }
            $this->Flash->error($message);
        }

        return $this->redirect($fallback);
    }
}
