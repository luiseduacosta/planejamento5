<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= h($disciplina->disciplina) ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($disciplina->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Código') ?></th>
            <td><?= h($disciplina->codigo) ?></td>
        </tr>
        <tr>
            <th><?= __('Disciplina') ?></th>
            <td><?= h($disciplina->disciplina) ?></td>
        </tr>
        <tr>
            <th><?= __('Créditos') ?></th>
            <td><?= h($disciplina->creditos) ?></td>
        </tr>
        <tr>
            <th><?= __('Carga Horária') ?></th>
            <td><?= $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '-' ?></td>
        </tr>    
        <tr>
            <th><?= __('Periodo Diurno') ?></th>
            <td><?= h($disciplina->periodo_diurno) ?></td>
        </tr>
        <tr>
            <th><?= __('Periodo Noturno') ?></th>
            <td><?= h($disciplina->periodo_noturno) ?></td>
        </tr>
        <tr>
            <th><?= __('Requisitos') ?></th>
            <td><?= h($disciplina->requisitos) ?></td>
        </tr>
        <tr>
            <th><?= __('Optativa') ?></th>
            <td><?= h($disciplina->optativa) ?></td>
        </tr>
        <tr>
            <th><?= __('Departamento') ?></th>
            <td><?= h($disciplina->departamento) ?></td>
        </tr>
        <tr>
            <th><?= __('Currículo') ?></th>
            <td><?= h($disciplina->curriculo ?? '-') ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= $disciplina->observacoes ? nl2br(h($disciplina->observacoes)) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Criado') ?></th>
            <td><?= h($disciplina->created) ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $disciplina->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
