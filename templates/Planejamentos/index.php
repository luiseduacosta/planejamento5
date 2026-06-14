<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col"><h3><?= __('Planejamentos') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Novo Planejamento'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    
    <!-- Semestre Filter -->
    <?php if (!empty($semestresList)): ?>
    <div class="row mb-3">
        <div class="col-auto">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center gap-2']) ?>
            <label class="form-label mb-0"><?= __('Filtrar por Semestre:') ?></label>
            <?= $this->Form->control('semestre', [
                'options' => ['' => __('Todos os Semestres')] + $semestresList,
                'default' => $selectedSemestre,
                'label' => false,
                'onchange' => 'this.form.submit()'
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
        <?php if ($selectedSemestre): ?>
        <div class="col-auto">
            <?= $this->Html->link(
                '<i class="bi bi-x-circle"></i> ' . __('Limpar Filtro'),
                ['action' => 'index'],
                ['class' => 'btn btn-outline-secondary', 'escape' => false]
            ) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('Planejamentos.id', 'ID') ?></th>
                    <th><?= $this->Paginator->sort('Disciplinas.disciplina', 'Disciplina') ?></th>
                    <th><?= $this->Paginator->sort('Docentes.nome', 'Docente') ?></th>
                    <th><?= $this->Paginator->sort('Configuraplanejamentos.semestre', 'Semestre') ?></th>
                    <th><?= $this->Paginator->sort('Dias.dia', 'Dia') ?></th>
                    <th><?= $this->Paginator->sort('Horarios.horario', 'Horário') ?></th>
                    <th><?= $this->Paginator->sort('Salas.sala', 'Sala') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($planejamentos as $planejamento): ?>
                <tr>
                    <td><?= $this->Number->format($planejamento->id) ?></td>
                    <td><?= $planejamento->hasValue('disciplina') ? h($planejamento->disciplina->disciplina) : '-' ?></td>
                    <td><?= $planejamento->hasValue('docente') ? h($planejamento->docente->nome) : '-' ?></td>
                    <td><?= $planejamento->hasValue('configuraplanejamento') ? h($planejamento->configuraplanejamento->semestre) : '-' ?></td>
                    <td><?= $planejamento->hasValue('dia') ? h($planejamento->dia->dia) : '-' ?></td>
                    <td><?= $planejamento->hasValue('horario') ? h($planejamento->horario->horario) : '-' ?></td>
                    <td><?= $planejamento->hasValue('sala') ? h($planejamento->sala->sala) : '-' ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $planejamento->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $planejamento->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $planejamento->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
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
