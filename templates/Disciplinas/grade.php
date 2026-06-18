<?php
declare(strict_types=1);

/**
 * @var array<int, array<int, array<int, array<int, \App\Model\Entity\Planejamento>>>> $gradeDiurno
 * @var array<int, array<int, array<int, array<int, \App\Model\Entity\Planejamento>>>> $gradeNoturno
 * @var array<\App\Model\Entity\Dia> $dias
 * @var array<\App\Model\Entity\Horario> $horariosDiurno
 * @var array<\App\Model\Entity\Horario> $horariosNoturno
 * @var array<string, string> $semestresList
 * @var string|null $selectedSemestre
 * @var \App\Model\Entity\Configuraplanejamento|null $configuracaoAtual
 */

$diaLabels = [];
foreach ($dias as $dia) {
    $diaLabels[$dia->id] = ucfirst(str_replace('-feira', '', $dia->dia));
}

$renderTable = function (int $periodo, array $gradeData, array $horarios, array $dias, array $diaLabels, string $turno, ?\App\Model\Entity\Configuraplanejamento $configuracaoAtual): string {
    ob_start();
    $turnoLabel = $turno === 'diurno' ? __('Diurno') : __('Noturno');
?>
<div class="card mb-3">
    <div class="card-header py-2">
        <strong><?= h($turnoLabel) ?> - <?= __('Período') ?> <?= $periodo ?></strong>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 18%;"><?= __('Dias') ?></th>
                    <?php foreach ($dias as $dia): ?>
                        <th class="text-center" style="width: 16.4%;"><?= h($diaLabels[$dia->id] ?? $dia->dia) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarios as $horario): ?>
                    <tr>
                        <td class="text-center align-middle fw-semibold"><?= h($horario->horario) ?></td>
                        <?php foreach ($dias as $dia): ?>
                            <td class="align-middle p-1">
                                <?php
                                $cell = $gradeData[$horario->id][$dia->id] ?? [];
                                if (empty($cell)):
                                    $addQuery = ['dia_id' => $dia->id, 'horario_id' => $horario->id];
                                    if ($configuracaoAtual !== null) {
                                        $addQuery['configuraplanejamento_id'] = $configuracaoAtual->id;
                                    }
                                    ?>
                                    <div class="text-center">
                                        <?= $this->Html->link('+', ['controller' => 'Planejamentos', 'action' => 'add', '?' => $addQuery], [
                                            'class' => 'btn btn-sm btn-outline-success',
                                            'title' => __('Adicionar planejamento'),
                                        ]) ?>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($cell as $planejamento): ?>
                                        <div class="mb-1 small border rounded p-1 bg-white">
                                            <div class="fw-semibold"><?= h($planejamento->disciplina->disciplina ?? '-') ?></div>
                                            <div class="text-muted"><?= h($planejamento->docente->nome ?? '-') ?></div>
                                            <div class="text-muted"><?= h($planejamento->sala->sala ?? '-') ?></div>
                                            <div class="text-center mt-1">
                                                <?= $this->Html->link(__('Ver'), ['controller' => 'Planejamentos', 'action' => 'view', $planejamento->id], [
                                                    'class' => 'btn btn-sm btn-info',
                                                    'title' => __('Ver planejamento'),
                                                ]) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
    return ob_get_clean();
};
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= __('Grade de Disciplinas') ?></h3>
        <div>
            <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
        </div>
    </div>

    <?php if (!empty($semestresList)): ?>
        <div class="row mb-4">
            <div class="col-auto">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center gap-2']) ?>
                    <label class="form-label mb-0"><?= __('Filtrar por Semestre:') ?></label>
                    <?= $this->Form->control('semestre', [
                        'options' => ['' => __('Todos os Semestres')] + $semestresList,
                        'default' => $selectedSemestre,
                        'label' => false,
                        'onchange' => 'this.form.submit()',
                        'class' => 'form-select form-select-sm',
                    ]) ?>
                <?= $this->Form->end() ?>
            </div>
            <?php if ($selectedSemestre): ?>
                <div class="col-auto">
                    <?= $this->Html->link(
                        __('Limpar Filtro'),
                        ['action' => 'grade'],
                        ['class' => 'btn btn-outline-secondary btn-sm']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h4 class="mt-4 mb-3"><?= __('Turno Diurno') ?></h4>
    <?php for ($periodo = 1; $periodo <= 8; $periodo++): ?>
        <?= $renderTable($periodo, $gradeDiurno[$periodo] ?? [], $horariosDiurno, $dias, $diaLabels, 'diurno', $configuracaoAtual) ?>
    <?php endfor; ?>

    <h4 class="mt-4 mb-3"><?= __('Turno Noturno') ?></h4>
    <?php for ($periodo = 1; $periodo <= 10; $periodo++): ?>
        <?= $renderTable($periodo, $gradeNoturno[$periodo] ?? [], $horariosNoturno, $dias, $diaLabels, 'noturno', $configuracaoAtual) ?>
    <?php endfor; ?>
</div>
