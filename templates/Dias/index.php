<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h3><?= __('Dias') ?></h3>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Novo Dia'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('dia') ?></th>
                    <th><?= $this->Paginator->sort('ordem') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dias as $dia): ?>
                <tr>
                    <td><?= $this->Number->format($dia->id) ?></td>
                    <td><?= h($dia->dia) ?></td>
                    <td><?= $this->Number->format($dia->ordem) ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $dia->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $dia->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $dia->id], [
                            'confirm' => __('Tem certeza que deseja excluir {0}?', $dia->dia),
                            'class' => 'btn btn-sm btn-danger'
                        ]) ?>
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
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}} total')) ?></p>
    </nav>
</div>
