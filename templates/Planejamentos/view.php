<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= __('Planejamento') ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($planejamento->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Disciplina') ?></th>
            <td><?= $planejamento->hasValue('disciplina') ? h($planejamento->disciplina->disciplina) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Docente') ?></th>
            <td><?= $planejamento->hasValue('docente') ? h($planejamento->docente->nome) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Semestre') ?></th>
            <td><?= $planejamento->hasValue('configuraplanejamento') ? h($planejamento->configuraplanejamento->semestre) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Dia') ?></th>
            <td><?= $planejamento->hasValue('dia') ? h($planejamento->dia->dia) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Horário') ?></th>
            <td><?= $planejamento->hasValue('horario') ? h($planejamento->horario->horario) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Sala') ?></th>
            <td><?= $planejamento->hasValue('sala') ? h($planejamento->sala->sala) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= $planejamento->observacoes !== null ? nl2br(h($planejamento->observacoes)) : '-' ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $planejamento->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
