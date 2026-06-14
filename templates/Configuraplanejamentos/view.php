<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= h($configuracao->nome) ?></h3>
    <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= __('ID') ?></th>
                    <th><?= __('Nome') ?></th>
                    <th><?= __('Semestre') ?></th>
                    <th><?= __('Versão') ?></th>
                    <th><?= __('Ativo') ?></th>
                    <th><?= __('Criado') ?></th>
                    <th><?= __('Modificado') ?></th>
                </tr>
                <tr>
                    <td><?= $this->Number->format($configuracao->id) ?></td>
                    <td><?= h($configuracao->nome) ?></td>
                    <td><?= h($configuracao->semestre) ?></td>
                    <td><?= h($configuracao->versao ?? '-') ?></td>
                    <td><?= $configuracao->ativo ? 'Sim' : 'Não' ?></td>
                    <td><?= h($configuracao->created) ?></td>
                    <td><?= h($configuracao->modified) ?></td>
                </tr>
            </thead>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $configuracao->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Form->postLink(__('Clonar'), ['action' => 'clone', $configuracao->id], ['confirm' => __('Deseja clonar?'), 'class' => 'btn btn-primary']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
