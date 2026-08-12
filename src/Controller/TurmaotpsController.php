<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class TurmaotpsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        // A configuração (semestre) exibida fica congelada na sessão até o
        // usuário escolher outra na URL. Uma escolha explícita vira a nova
        // configuração ativa; sem parâmetro, usa a ativa da sessão. O valor
        // 'todos' remove o filtro (mostra todas as configurações) e é usado
        // em vez de string vazia para que os links de paginação preservem a
        // escolha.
        $configuraplanejamentoId = $this->request->getQuery('configuraplanejamento_id');
        if ($configuraplanejamentoId !== null && $configuraplanejamentoId !== '' && $configuraplanejamentoId !== 'todos') {
            $chosenConfig = $this->Turmaotps->Configuraplanejamentos->find()
                ->where(['id' => (int)$configuraplanejamentoId])
                ->first();
            if ($chosenConfig !== null) {
                $this->setActiveConfiguraplanejamentoId($chosenConfig->id);
            }
        } elseif ($configuraplanejamentoId === null) {
            $activeId = $this->getActiveConfiguraplanejamentoId();
            if ($activeId !== null) {
                $configuraplanejamentoId = (string)$activeId;
            }
        }

        $selectedConfiguraplanejamentoId = null;
        if ($configuraplanejamentoId !== null && $configuraplanejamentoId !== '' && $configuraplanejamentoId !== 'todos') {
            $selectedConfiguraplanejamentoId = (int)$configuraplanejamentoId;
        }

        $query = $this->Turmaotps->find()
            ->contain(['Configuraplanejamentos', 'Docentes', 'Dias', 'Horarios', 'Salas'])
            ->orderBy(['Configuraplanejamentos.semestre' => 'DESC', 'Turmaotps.id' => 'DESC']);

        if ($selectedConfiguraplanejamentoId !== null) {
            $query->where(['Turmaotps.configuraplanejamento_id' => $selectedConfiguraplanejamentoId]);
        }

        $turmaotps = $this->paginate($query);

        $configuracoes = $this->Turmaotps->Configuraplanejamentos->find('list', limit: 200)->all();
        $this->set(compact('turmaotps', 'configuracoes', 'selectedConfiguraplanejamentoId'));
    }

    public function view($id = null): void
    {
        $this->Authorization->skipAuthorization();
        $turmaotp = $this->Turmaotps->get($id, contain: ['Configuraplanejamentos', 'Docentes', 'Dias', 'Horarios', 'Salas']);
        $this->set(compact('turmaotp'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $turmaotp = $this->Turmaotps->newEmptyEntity();
        $this->Authorization->authorize($turmaotp, 'add');

        // Pré-preenche com a configuração escolhida na URL ou com a ativa da
        // sessão (a escolha explícita já congela a nova configuração).
        $selectedConfiguracaoId = $this->_configuraplanejamentoIdFromQuery()
            ?? $this->getActiveConfiguraplanejamentoId();
        if ($selectedConfiguracaoId !== null) {
            $turmaotp->configuraplanejamento_id = $selectedConfiguracaoId;
        }

        if ($this->request->is('post')) {
            $turmaotp = $this->Turmaotps->patchEntity($turmaotp, $this->request->getData());
            $selectedConfiguracaoId = $turmaotp->configuraplanejamento_id ?: null;
            if ($this->Turmaotps->save($turmaotp)) {
                // Congela a configuração salva até o usuário trocá-la.
                if ($turmaotp->configuraplanejamento_id !== null) {
                    $this->setActiveConfiguraplanejamentoId((int)$turmaotp->configuraplanejamento_id);
                }
                $this->Flash->success(__('A turma de optativa foi salva com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a turma de optativa. Tente novamente.'));
        }

        $this->_setRelatedData();
        $this->set(compact('turmaotp', 'selectedConfiguracaoId'));

        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $turmaotp = $this->Turmaotps->get($id);
        $this->Authorization->authorize($turmaotp, 'edit');

        // Escolha explícita na URL prevalece (e congela); senão, mantém a
        // configuração do próprio registro.
        $selectedConfiguracaoId = $this->_configuraplanejamentoIdFromQuery()
            ?? $turmaotp->configuraplanejamento_id;
        if ($selectedConfiguracaoId !== null) {
            $turmaotp->configuraplanejamento_id = $selectedConfiguracaoId;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $turmaotp = $this->Turmaotps->patchEntity($turmaotp, $this->request->getData());
            $selectedConfiguracaoId = $turmaotp->configuraplanejamento_id ?: null;
            if ($this->Turmaotps->save($turmaotp)) {
                if ($turmaotp->configuraplanejamento_id !== null) {
                    $this->setActiveConfiguraplanejamentoId((int)$turmaotp->configuraplanejamento_id);
                }
                $this->Flash->success(__('A turma de optativa foi atualizada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível atualizar a turma de optativa. Tente novamente.'));
        }

        $this->_setRelatedData();
        $this->set(compact('turmaotp', 'selectedConfiguracaoId'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $turmaotp = $this->Turmaotps->get($id);
        $this->Authorization->authorize($turmaotp, 'delete');

        if ($this->Turmaotps->delete($turmaotp)) {
            $this->Flash->success(__('A turma de optativa foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a turma de optativa. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Se o usuário escolheu uma configuração na URL, valida e congela a
     * escolha na sessão (vira a configuração ativa). Retorna null quando não
     * há escolha ou ela é inválida.
     */
    protected function _configuraplanejamentoIdFromQuery(): ?int
    {
        $configuraplanejamentoId = $this->request->getQuery('configuraplanejamento_id');
        if ($configuraplanejamentoId === null || $configuraplanejamentoId === '') {
            return null;
        }

        $configuraplanejamentoId = (int)$configuraplanejamentoId;
        if (!$this->Turmaotps->Configuraplanejamentos->exists(['id' => $configuraplanejamentoId])) {
            return null;
        }

        $this->setActiveConfiguraplanejamentoId($configuraplanejamentoId);

        return $configuraplanejamentoId;
    }

    /**
     * Carrega as listas usadas nos formulários de adição e edição.
     */
    protected function _setRelatedData(): void
    {
        $configuracoes = $this->Turmaotps->Configuraplanejamentos->find('list', limit: 200)->all();
        $docentes = $this->Turmaotps->Docentes
            ->find('list')
            ->where(['Docentes.status IN' => ['ativo', 'active', 'activo']])
            ->orderBy(['Docentes.nome' => 'ASC'])
            ->all();
        $dias = $this->Turmaotps->Dias->find('list')->all();
        $horarios = $this->Turmaotps->Horarios->find('list')->all();
        $salas = $this->Turmaotps->Salas->find('list')->all();

        $this->set(compact('configuracoes', 'docentes', 'dias', 'horarios', 'salas'));
    }
}
