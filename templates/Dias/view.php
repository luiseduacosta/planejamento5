<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= h($dia->dia) ?></h3>
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($dia->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dia') ?></th>
                    <td><?= h($dia->dia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ordem') ?></th>
                    <td><?= $this->Number->format($dia->ordem) ?></td>
                </tr>
                <tr>
                    <th><?= __('Criado') ?></th>
                    <td><?= h($dia->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificado') ?></th>
                    <td><?= h($dia->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $dia->id], ['class' => 'btn btn-warning']) ?>
            <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
