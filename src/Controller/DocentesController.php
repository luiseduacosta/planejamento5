<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Docentes Controller
 *
 * @property \App\Model\Table\DocentesTable $Docentes
 */
class DocentesController extends AppController
{
    private const STATUS_LABELS = [
        'ativo' => 'Ativo',
        'aposentado' => 'Aposentado',
        'inativo' => 'Inativo',
    ];

    private const STATUS_ALIASES = [
        'ativo' => ['ativo', 'active', 'activo'],
        'aposentado' => ['aposentado', 'retired'],
        'inativo' => ['inativo', 'inactive', 'inactivo'],
    ];

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        // Valor sentinela para "todos os status" no multi-select.
        $statusAllSentinel = 'all';

        // Get filter parameters from query string.
        // Status é persistido na sessão e agora é SEMPRE um array (multi-select).
        // A entrada "all" (ou array vazio) significa "sem filtro".
        // Quando a chave não está presente nem na query nem na sessão, o
        // padrão é exibir apenas docentes ativos.
        $statusSessionKey = 'Docentes.statusFilter';
        $session = $this->request->getSession();

        // Normaliza qualquer entrada (string única ou array) para array de strings.
        $normalizeStatusList = function ($raw) use ($statusAllSentinel): array {
            if ($raw === null) { return []; }
            if (is_array($raw)) {
                $res = [];
                foreach ($raw as $v) {
                    if ($v === null || $v === '') { continue; }
                    $res[] = (string)$v;
                }
                return $res;
            }
            if ($raw === '') { return []; }
            return [(string)$raw];
        };

        $statusQueryRaw = $this->request->getQuery('status');
        $statusQuery = $normalizeStatusList($statusQueryRaw);
        if ($statusQueryRaw !== null) {
            $session->write($statusSessionKey, $statusQuery);
            $statusFilter = $statusQuery;
        } else {
            $stored = $session->read($statusSessionKey);
            if ($stored === null) {
                // Padrão da sessão nunca usada: exibe apenas ativos.
                $statusFilter = ['ativo'];
            } else {
                $statusFilter = $normalizeStatusList($stored);
            }
        }

        $departamentoFilter = $this->request->getQuery('departamento');
        $configuraplanejamentoFilter = $this->request->getQuery('configuraplanejamento_id');

        // Determina se o filtro de status deve ser aplicado (isto é, se NÃO
        // está em modo "todos"). "Todos" = array vazio ou presença do sentinela.
        $statusIsAll = count($statusFilter) === 0 || in_array($statusAllSentinel, $statusFilter, true);

        // Get unique departamentos for dropdown
        $departamentos = $this->Docentes->find()
            ->select(['departamento'])
            ->distinct(['departamento'])
            ->where(['departamento IS NOT' => null])
            ->orderBy(['departamento' => 'ASC'])
            ->toArray();

        $departamentosList = [];
        foreach ($departamentos as $departamento) {
            $departamentosList[$departamento->departamento] = $departamento->departamento;
        }

        // Opções fixas do filtro de status (multi-select). Sempre: ativo,
        // inativo, aposentado, e a opção "Todos" (sentinela 'all').
        $statusList = [
            $statusAllSentinel => __('Todos'),
            'ativo' => self::STATUS_LABELS['ativo'],
            'inativo' => self::STATUS_LABELS['inativo'],
            'aposentado' => self::STATUS_LABELS['aposentado'],
        ];

        // Get planning configurations (dropdown que seleciona QUAL semestre é
        // exibido na coluna Disponibilidade — NÃO filtra as linhas da tabela).
        $configuracoes = $this->Docentes->DocenteDisponibilidades->Configuraplanejamentos
            ->find()
            ->select(['id', 'semestre', 'versao'])
            ->distinct(['id'])
            ->orderBy(['semestre' => 'DESC', 'versao' => 'DESC'])
            ->toArray();

        $configuracoesList = [];
        foreach ($configuracoes as $configuracao) {
            $label = $configuracao->semestre . ' - ' . ($configuracao->versao ?? '1');
            $configuracoesList[$configuracao->id] = $label;
        }

        // Build query
        $query = $this->Docentes->find();

        // Apply status filter (apenas se NÃO estiver em "Todos").
        if (!$statusIsAll) {
            $statusAliasesExpanded = [];
            foreach ($statusFilter as $s) {
                $canonical = $this->canonicalStatus((string)$s);
                $aliases = self::STATUS_ALIASES[$canonical] ?? [$canonical];
                foreach ($aliases as $a) { $statusAliasesExpanded[$a] = true; }
            }
            if ($statusAliasesExpanded !== []) {
                $query->where(['Docentes.status IN' => array_keys($statusAliasesExpanded)]);
            }
        }

        // Apply departamento filter
        if ($departamentoFilter) {
            $query->where(['Docentes.departamento' => $departamentoFilter]);
        }

        // NOTA: NÃO aplicamos mais nenhum matching() / filtro por
        // disponibilidade baseado em $configuraplanejamentoFilter. O parâmetro
        // serve apenas para decidir qual semestre é exibido na coluna
        // "Disponibilidade" e no seu botão toggle. Todas as linhas de
        // docentes que passam pelos filtros de status/departamento são
        // exibidas, conforme requisito #1.

        $config = [
            'order' => ['nome' => 'ASC'],
            'sortableFields' => [
                'id',
                'nome',
                'cpf',
                'siape',
                'departamento',
                'tipocargo',
                'periodo_diurno',
                'periodo_noturno',
                'status',
                'email',
            ],
        ];

        $docentes = $this->paginate($query, $config);

        // Monta os rótulos dos status ativos para exibição no badge.
        $statusFilterLabels = [];
        if (!$statusIsAll) {
            foreach ($statusFilter as $s) {
                $canonical = $this->canonicalStatus((string)$s);
                $statusFilterLabels[$canonical] = self::STATUS_LABELS[$canonical] ?? $canonical;
            }
        }
        $configuracaoFilterLabel = $configuraplanejamentoFilter ? ($configuracoesList[(int)$configuraplanejamentoFilter] ?? null) : null;

        // Determine which planning configuration to show in the availability column
        $activeConfiguraplanejamentoId = $this->getActiveConfiguraplanejamentoId();
        $configuracaoAtiva = null;
        if ($activeConfiguraplanejamentoId !== null) {
            $configuracaoAtiva = $this->Docentes->DocenteDisponibilidades->Configuraplanejamentos
                ->find()
                ->where(['id' => $activeConfiguraplanejamentoId])
                ->first();
        }

        $configuracaoAtual = null;
        if ($configuraplanejamentoFilter) {
            $configuracaoAtual = $this->Docentes->DocenteDisponibilidades->Configuraplanejamentos
                ->find()
                ->where(['id' => (int)$configuraplanejamentoFilter])
                ->first();
        } else {
            $configuracaoAtual = $configuracaoAtiva;
        }

        $disponibilidades = [];
        if ($configuracaoAtual !== null) {
            $disponibilidadesRows = $this->Docentes->DocenteDisponibilidades
                ->find()
                ->where(['configuraplanejamento_id' => $configuracaoAtual->id])
                ->all();

            foreach ($disponibilidadesRows as $disponibilidade) {
                $disponibilidades[$disponibilidade->docente_id] = $disponibilidade;
            }
        }

        $this->set(compact(
            'docentes',
            'departamentosList',
            'statusList',
            'statusFilter',
            'statusFilterLabels',
            'statusIsAll',
            'statusAllSentinel',
            'departamentoFilter',
            'configuracoesList',
            'configuraplanejamentoFilter',
            'configuracaoFilterLabel',
            'disponibilidades',
            'configuracaoAtiva',
            'configuracaoAtual'
        ));
    }

    public function view($id = null): void
    {
        $docente = $this->Docentes->get($id, contain: [
            'Planejamentos',
            'DocenteDisponibilidades' => ['Configuraplanejamentos'],
        ]);
        $this->Authorization->skipAuthorization();

        // Active configuration from the session (semester currently in use),
        // plus this docente's availability record for it (if any). Used by the
        // quick Sim/Não toggle in the "Disponibilidade por Semestre" section.
        $activeConfiguraplanejamentoId = $this->getActiveConfiguraplanejamentoId();
        $configuracaoAtiva = null;
        $disponibilidadeAtiva = null;
        if ($activeConfiguraplanejamentoId !== null) {
            $configuracaoAtiva = $this->Docentes->DocenteDisponibilidades->Configuraplanejamentos
                ->find()
                ->where(['id' => $activeConfiguraplanejamentoId])
                ->first();
            foreach ($docente->docente_disponibilidades as $disp) {
                if ((int)$disp->configuraplanejamento_id === (int)$activeConfiguraplanejamentoId) {
                    $disponibilidadeAtiva = $disp;
                    break;
                }
            }
        }

        $this->set(compact('docente', 'configuracaoAtiva', 'disponibilidadeAtiva'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $docente = $this->Docentes->newEmptyEntity();
        $docente->status = 'ativo';
        $this->Authorization->authorize($docente, 'add');
        
        if ($this->request->is('post')) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('O docente foi salvo com sucesso.'));
                return $this->redirect(['action' => 'view', $docente->id]);
            }
            $this->Flash->error(__('Não foi possível salvar o docente. Tente novamente.'));
        }
        $this->set(compact('docente'));

        return null;
    }

    private function canonicalStatus(string $status): string
    {
        foreach (self::STATUS_ALIASES as $canonicalStatus => $aliases) {
            if (\in_array($status, $aliases, true)) {
                return $canonicalStatus;
            }
        }

        return $status;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $docente = $this->Docentes->get($id, contain: []);
        $docente->status = $this->canonicalStatus((string)$docente->status);
        $this->Authorization->authorize($docente, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $docente = $this->Docentes->patchEntity($docente, $this->request->getData());
            if ($this->Docentes->save($docente)) {
                $this->Flash->success(__('O docente foi atualizado com sucesso.'));
                return $this->redirect(['action' => 'view', $docente->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar o docente. Tente novamente.'));
        }
        $this->set(compact('docente'));

        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $docente = $this->Docentes->get($id);
        $this->Authorization->authorize($docente, 'delete');
        
        if ($this->Docentes->delete($docente)) {
            $this->Flash->success(__('O docente foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir o docente. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
