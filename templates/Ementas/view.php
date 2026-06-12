<div class="ementas view content">
    <h3><?= __('Ementa') ?></h3>
    <table class="table table-striped">
        <tr><th><?= __('ID') ?></th><td><?= $this->Number->format($ementa->id) ?></td></tr>
        <tr><th><?= __('Disciplina') ?></th><td><?= $ementa->hasValue('disciplina') ? h($ementa->disciplina->nome) : '-' ?></td></tr>
        <tr><th><?= __('Conteúdo Programático') ?></th><td><?= nl2br(h($ementa->conteudo_programatico)) ?></td></tr>
        <tr><th><?= __('Objetivos') ?></th><td><?= nl2br(h($ementa->objetivos)) ?></td></tr>
        <tr><th><?= __('Bibliografia Básica') ?></th><td><?= nl2br(h($ementa->bibliografia_basica)) ?></td></tr>
        <tr><th><?= __('Bibliografia Complementar') ?></th><td><?= nl2br(h($ementa->bibliografia_complementar)) ?></td></tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $ementa->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
