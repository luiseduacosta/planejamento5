<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h3><?= __('Salas') ?></h3>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Nova Sala'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('sala') ?></th>
                    <th><?= $this->Paginator->sort('localizacao') ?></th>
                    <th><?= $this->Paginator->sort('lotacao') ?></th>
                    <th><?= $this->Paginator->sort('recursos') ?></th>
                    <th><?= $this->Paginator->sort('observacoes') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?= $this->Number->format($sala->id) ?></td>
                    <td><?= h($sala->sala) ?></td>
                    <td><?= h($sala->localizacao) ?></td>
                    <td><?= $this->Number->format($sala->lotacao) ?></td>
                    <td><?= h($sala->recursos) ?></td>
                    <td><?= h($sala->observacoes) ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $sala->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $sala->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $sala->id], [
                            'confirm' => __('Tem certeza que deseja excluir {0}?', $sala->sala),
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
