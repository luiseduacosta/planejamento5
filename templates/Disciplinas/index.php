<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h3><?= __('Disciplinas') ?></h3>
            <?php if ($curriculoFilter || $periodoDiurnoFilter || $periodoNoturnoFilter): ?>
                <small class="text-muted">
                    <?= __('Filtros ativos:') ?>
                    <?php if ($curriculoFilter): ?>
                        <span class="badge bg-primary"><?= __('Currículo') ?>: <?= h($curriculoFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($periodoDiurnoFilter): ?>
                        <span class="badge bg-primary"><?= __('Período Diurno') ?>: <?= h($periodoDiurnoFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($periodoNoturnoFilter): ?>
                        <span class="badge bg-primary"><?= __('Período Noturno') ?>: <?= h($periodoNoturnoFilter) ?></span>
                    <?php endif; ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Grade'), ['action' => 'grade'], ['class' => 'btn btn-info me-2']) ?>
            <?= $this->Html->link(__('Nova Disciplina'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>

                <!-- Curriculo Filter -->
                <div class="col-auto">
                    <?= $this->Form->control('curriculo', [
                        'label' => __('Currículo'),
                        'options' => ['' => __('Todos')] + $curriculosList,
                        'default' => $curriculoFilter,
                        'empty' => false,
                    ]) ?>
                </div>

                <!-- Periodo Diurno Filter -->
                <div class="col-auto">
                    <?= $this->Form->control('periodo_diurno', [
                        'label' => __('Período Diurno'),
                        'options' => ['' => __('Todos')] + $periodosDiurnoList,
                        'default' => $periodoDiurnoFilter,
                        'empty' => false,
                    ]) ?>
                </div>

                <!-- Periodo Noturno Filter -->
                <div class="col-auto">
                    <?= $this->Form->control('periodo_noturno', [
                        'label' => __('Período Noturno'),
                        'options' => ['' => __('Todos')] + $periodosNoturnoList,
                        'default' => $periodoNoturnoFilter,
                        'empty' => false,
                    ]) ?>
                </div>

                <!-- Filter Button -->
                <div class="col-auto">
                    <?= $this->Form->button(__('Filtrar')) ?>
                </div>

                <!-- Clear Filters Button -->
                <?php if ($curriculoFilter || $periodoDiurnoFilter || $periodoNoturnoFilter): ?>
                    <div class="col-auto">
                        <?= $this->Html->link(
                            __('Limpar Filtros'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary']
                        ) ?>
                    </div>
                <?php endif; ?>

            <?= $this->Form->end() ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('codigo') ?></th>
                    <th><?= $this->Paginator->sort('disciplina') ?></th>
                    <th><?= $this->Paginator->sort('carga_horaria') ?></th>
                    <th><?= $this->Paginator->sort('departamento') ?></th>
                    <th><?= $this->Paginator->sort('curriculo', __('Currículo')) ?></th>
                    <th><?= $this->Paginator->sort('periodo_diurno') ?></th>
                    <th><?= $this->Paginator->sort('periodo_noturno') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disciplinas as $disciplina): ?>
                <tr>
                    <td><?= $disciplina->id ?></td>
                    <td><?= h($disciplina->codigo) ?></td>
                    <td><?= $this->Html->link($disciplina->disciplina, ['action' => 'view', $disciplina->id]) ?></td>
                    <td><?= $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '-' ?></td>
                    <td><?= h($disciplina->departamento) ?></td>
                    <td><?= h($disciplina->curriculo ?? '-') ?></td>
                    <td><?= h($disciplina->periodo_diurno) ?></td>
                    <td><?= h($disciplina->periodo_noturno) ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $disciplina->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $disciplina->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginator -->
    <nav aria-label="Paginação">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('próximo') . ' >') ?>
            <?= $this->Paginator->last(__('último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </nav>
</div>
