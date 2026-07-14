<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= __('Optativa') ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($optativa->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Código') ?></th>
            <td><?= h($optativa->codigo) ?></td>
        </tr>
        <tr>
            <th><?= __('Disciplina') ?></th>
            <td><?= h($optativa->disciplina) ?></td>
        </tr>
        <tr>
            <th><?= __('Créditos') ?></th>
            <td><?= h($optativa->creditos) ?></td>
        </tr>
        <tr>
            <th><?= __('Carga Horária') ?></th>
            <td><?= h($optativa->carga_horaria) ?></td>
        </tr>
        <tr>
            <th><?= __('Período Diurno') ?></th>
            <td><?= h($optativa->periodo_diurno) ?></td>
        </tr>
        <tr>
            <th><?= __('Período Noturno') ?></th>
            <td><?= h($optativa->periodo_noturno) ?></td>
        </tr>
        <tr>
            <th><?= __('Requisitos') ?></th><td><?= h($optativa->requisitos) ?></td>
        </tr>
        <tr>
            <th><?= __('Optativa') ?></th>
            <td><?= $optativa->optativa ? 'Sim' : 'Não' ?></td>
        </tr>
        <tr>
            <th><?= __('Departamento') ?></th>
            <td><?= h($optativa->departamento) ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= $optativa->observacoes ? nl2br(h($optativa->observacoes)) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Criado') ?></th><td><?= h($optativa->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modificado') ?></th><td><?= h($optativa->modified) ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $optativa->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
