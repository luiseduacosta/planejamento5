<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= __('Turma de OTP') ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($turmaotp->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Turma') ?></th>
            <td><?= h($turmaotp->turmaotp) ?></td>
        </tr>
        <tr>
            <th><?= __('Semestre') ?></th>
            <td><?= $turmaotp->hasValue('configuraplanejamento') ? h($turmaotp->configuraplanejamento->semestre) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Turno') ?></th>
            <td><?= $turmaotp->turno !== null ? h(ucfirst($turmaotp->turno)) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Período') ?></th>
            <td><?= $turmaotp->periodo !== null ? $this->Number->format($turmaotp->periodo) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Docente') ?></th>
            <td><?= $turmaotp->hasValue('docente') ? h($turmaotp->docente->nome) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Dia') ?></th>
            <td><?= $turmaotp->hasValue('dia') ? h($turmaotp->dia->dia) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Horário') ?></th>
            <td><?= $turmaotp->hasValue('horario') ? h($turmaotp->horario->horario) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Sala') ?></th>
            <td><?= $turmaotp->hasValue('sala') ? h($turmaotp->sala->sala) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= $turmaotp->observacoes !== null ? nl2br(h($turmaotp->observacoes)) : '-' ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $turmaotp->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
