<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col"><h3><?= __('Ementas') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Nova Ementa'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('titulo') ?></th>
                    <th><?= $this->Paginator->sort('disciplina_id') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ementas as $ementa): ?>
                <tr>
                    <td><?= $this->Number->format($ementa->id) ?></td>
                    <td><?= h($ementa->titulo ?? '-') ?></td>
                    <td><?= $ementa->hasValue('disciplina') ? h($ementa->disciplina->disciplina) : '-' ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $ementa->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $ementa->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $ementa->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
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
