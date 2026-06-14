<?php
declare(strict_types=1);
?>
<div class="container">
    <h3><?= h($ementa->titulo ?? __('Ementa')) ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($ementa->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Configuração') ?></th>
            <td><?= $ementa->hasValue('configuraplanejamento') ? h($ementa->configuraplanejamento->nome) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Disciplina') ?></th>
            <td><?= $ementa->hasValue('disciplina') ? h($ementa->disciplina->disciplina) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Optativa') ?></th>
            <td><?= $ementa->hasValue('optativa') ? h($ementa->optativa->nome) : '-' ?></td>
        </tr>
        <tr></tr>
        <tr>
            <th><?= __('Docente') ?></th>
            <td><?= $ementa->hasValue('docente') ? h($ementa->docente->nome) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Título') ?></th>
            <td><?= h($ementa->titulo) ?></td>
        </tr>
        <tr>
            <th><?= __('Ementa') ?></th>
            <td><?= $ementa->ementa ? nl2br(h($ementa->ementa)) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= $ementa->observacoes ? nl2br(h($ementa->observacoes)) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Criado') ?></th>
            <td><?= h($ementa->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modificado') ?></th>
            <td><?= h($ementa->modified) ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $ementa->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
