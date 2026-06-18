<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Disciplinas Controller
 */
class DisciplinasController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index', 'view', 'grade']);
    }

    public function index(): void
    {
        $this->Authorization->skipAuthorization();

        // Get filter parameters from query string
        $curriculoFilter = $this->request->getQuery('curriculo');
        $periodoDiurnoFilter = $this->request->getQuery('periodo_diurno');
        $periodoNoturnoFilter = $this->request->getQuery('periodo_noturno');

        // Get unique curriculos for dropdown
        $curriculos = $this->Disciplinas->find()
            ->select(['curriculo'])
            ->distinct(['curriculo'])
            ->where(['curriculo IS NOT' => null])
            ->orderBy(['curriculo' => 'ASC'])
            ->toArray();

        $curriculosList = [];
        foreach ($curriculos as $curriculo) {
            $curriculosList[$curriculo->curriculo] = $curriculo->curriculo;
        }

        // Fixed period options
        $periodosDiurnoList = array_combine(range(1, 8), range(1, 8));
        $periodosNoturnoList = array_combine(range(1, 10), range(1, 10));

        // Build query
        $query = $this->Disciplinas->find();

        // Apply filters (combinable)
        if ($curriculoFilter) {
            $query->where(['Disciplinas.curriculo' => $curriculoFilter]);
        }
        if ($periodoDiurnoFilter) {
            $query->where(['Disciplinas.periodo_diurno' => (int)$periodoDiurnoFilter]);
        }
        if ($periodoNoturnoFilter) {
            $query->where(['Disciplinas.periodo_noturno' => (int)$periodoNoturnoFilter]);
        }

        $disciplinas = $this->paginate($query);

        $this->set(compact(
            'disciplinas',
            'curriculosList',
            'periodosDiurnoList',
            'periodosNoturnoList',
            'curriculoFilter',
            'periodoDiurnoFilter',
            'periodoNoturnoFilter'
        ));
    }

    public function grade(): void
    {
        $this->Authorization->skipAuthorization();

        $selectedSemestre = $this->request->getQuery('semestre');

        $semestres = $this->fetchTable('Planejamentos')
            ->Configuraplanejamentos
            ->find()
            ->select(['semestre'])
            ->distinct(['semestre'])
            ->orderBy(['semestre' => 'DESC'])
            ->toArray();

        $semestresList = [];
        foreach ($semestres as $semestre) {
            $semestresList[$semestre->semestre] = $semestre->semestre;
        }

        $query = $this->fetchTable('Planejamentos')
            ->find()
            ->contain([
                'Disciplinas',
                'Docentes',
                'Configuraplanejamentos',
                'Salas',
                'Dias',
                'Horarios',
            ]);

        if ($selectedSemestre) {
            $query->matching('Configuraplanejamentos', function ($q) use ($selectedSemestre) {
                return $q->where(['Configuraplanejamentos.semestre' => $selectedSemestre]);
            });
        }

        $planejamentos = $query->all();

        $gradeDiurno = [];
        $gradeNoturno = [];
        foreach ($planejamentos as $planejamento) {
            if (!$planejamento->hasValue('disciplina') ||
                !$planejamento->hasValue('dia') ||
                !$planejamento->hasValue('horario')
            ) {
                continue;
            }

            $disciplina = $planejamento->disciplina;
            $diaId = $planejamento->dia_id;
            $horarioId = $planejamento->horario_id;

            if (in_array($horarioId, [1, 2, 3, 4], true) && $disciplina->periodo_diurno !== null) {
                $periodo = (int)$disciplina->periodo_diurno;
                $gradeDiurno[$periodo][$horarioId][$diaId][] = $planejamento;
            } elseif (in_array($horarioId, [5, 6], true) && $disciplina->periodo_noturno !== null) {
                $periodo = (int)$disciplina->periodo_noturno;
                $gradeNoturno[$periodo][$horarioId][$diaId][] = $planejamento;
            }
        }

        $planejamentosTable = $this->fetchTable('Planejamentos');
        $dias = $planejamentosTable->Dias->find()->orderBy(['ordem' => 'ASC'])->toArray();
        $horariosDiurno = $planejamentosTable->Horarios->find()
            ->where(['id IN' => [1, 2, 3, 4]])
            ->orderBy(['ordem' => 'ASC'])
            ->toArray();
        $horariosNoturno = $planejamentosTable->Horarios->find()
            ->where(['id IN' => [5, 6]])
            ->orderBy(['ordem' => 'ASC'])
            ->toArray();

        // Determine the planning configuration to use for "add" links
        $configuracaoAtual = null;
        if ($selectedSemestre) {
            $configuracaoAtual = $planejamentosTable->Configuraplanejamentos
                ->find()
                ->where(['semestre' => $selectedSemestre])
                ->orderBy(['ativo' => 'DESC', 'semestre' => 'DESC', 'versao' => 'DESC'])
                ->first();
        } else {
            $configuracaoAtual = $planejamentosTable->Configuraplanejamentos
                ->find()
                ->where(['ativo' => true])
                ->orderBy(['semestre' => 'DESC'])
                ->first();
        }

        $this->set(compact(
            'gradeDiurno',
            'gradeNoturno',
            'dias',
            'horariosDiurno',
            'horariosNoturno',
            'semestresList',
            'selectedSemestre',
            'configuracaoAtual'
        ));
    }

    public function view($id = null): void
    {
        $disciplina = $this->Disciplinas->get($id, contain: ['Planejamentos']);
        $this->Authorization->skipAuthorization();
        $this->set(compact('disciplina'));
    }

    public function add(): \Cake\Http\Response|null
    {
        $disciplina = $this->Disciplinas->newEmptyEntity();
        $this->Authorization->authorize($disciplina, 'add');
        
        if ($this->request->is('post')) {
            $disciplina = $this->Disciplinas->patchEntity($disciplina, $this->request->getData());
            if ($this->Disciplinas->save($disciplina)) {
                $this->Flash->success(__('A disciplina foi salva com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a disciplina. Tente novamente.'));
        }
        $this->set(compact('disciplina'));
        return null;
    }

    public function edit($id = null): \Cake\Http\Response|null
    {
        $disciplina = $this->Disciplinas->get($id, contain: []);
        $this->Authorization->authorize($disciplina, 'edit');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $disciplina = $this->Disciplinas->patchEntity($disciplina, $this->request->getData());
            if ($this->Disciplinas->save($disciplina)) {
                $this->Flash->success(__('A disciplina foi atualizada com sucesso.'));
                return $this->redirect(['action' => 'view', $disciplina->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar a disciplina. Tente novamente.'));
            debug($disciplina);
            exit;
        }
        $this->set(compact('disciplina'));
        return null;
    }

    public function delete($id = null): \Cake\Http\Response|null
    {
        $this->request->allowMethod(['post', 'delete']);
        $disciplina = $this->Disciplinas->get($id);
        $this->Authorization->authorize($disciplina, 'delete');
        
        if ($this->Disciplinas->delete($disciplina)) {
            $this->Flash->success(__('A disciplina foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir a disciplina. Tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
