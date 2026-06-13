<?php
declare(strict_types=1);
?>
<div class="docentes index content">
    <div class="row">
        <div class="col">
            <h3><?= __('Docentes') ?></h3>
            <?php if ($statusFilter || $departamentoFilter): ?>
                <small class="text-muted">
                    <?= __('Filtros ativos:') ?>
                    <?php if ($statusFilter): ?>
                        <span class="badge bg-primary"><?= __('Status') ?>: <?= h($statusFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($departamentoFilter): ?>
                        <span class="badge bg-primary"><?= __('Departamento') ?>: <?= h($departamentoFilter) ?></span>
                    <?php endif; ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Novo Docente'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
            
            <!-- Status Filter -->
            <div class="col-auto">
                <?= $this->Form->control('status', [
                    'label' => __('Status'),
                    'class' => 'form-select',
                    'options' => [
                        '' => __('Todos')] + $statusList,
                    'default' => $statusFilter,
                    'empty' => false
                ]) ?>
            </div>
            
            <!-- Departamento Filter -->
            <div class="col-auto">
                <?= $this->Form->control('departamento', [
                    'label' => __('Departamento'),
                    'class' => 'form-select',
                    'options' => ['' => __('Todos')] + $departamentosList,
                    'default' => $departamentoFilter,
                    'empty' => false
                ]) ?>
            </div>
            
            <!-- Filter Button -->
            <div class="col-auto">
                <?= $this->Form->button(__('Filtrar'), ['class' => 'btn btn-primary']) ?>
            </div>
            
            <!-- Clear Filters Button -->
            <?php if ($statusFilter || $departamentoFilter): ?>
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
                    <th><?= $this->Paginator->sort('id', __('ID')) ?></th>
                    <th><?= $this->Paginator->sort('nome', __('Nome')) ?></th>
                    <th><?= $this->Paginator->sort('cpf', __('CPF')) ?></th>
                    <th><?= $this->Paginator->sort('siape', __('SIAPE')) ?></th>
                    <th><?= $this->Paginator->sort('departamento', __('Departamento')) ?></th>
                    <th><?= $this->Paginator->sort('status', __('Status')) ?></th>
                    <th><?= $this->Paginator->sort('email', __('Email')) ?></th>
                    <th class="actions"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente): ?>
                <tr>
                    <td><?= $this->Number->format($docente->id) ?></td>
                    <td><?= h($docente->nome) ?></td>
                    <td><?= h($docente->cpf) ?></td>
                    <td><?= h($docente->siape) ?></td>
                    <td><?= h($docente->departamento) ?></td>
                    <td><?= h($docente->status) ?></td>
                    <td><?= h($docente->email) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $docente->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $docente->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $docente->id], [
                            'confirm' => __('Tem certeza que deseja excluir {0}?', $docente->nome),
                            'class' => 'btn btn-sm btn-danger'
                        ]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('próximo') . ' >') ?>
            <?= $this->Paginator->last(__('último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}} total')) ?></p>
    </div>
</div>
