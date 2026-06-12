<div class="optativas view content">
    <h3><?= h($optativa->nome) ?></h3>
    <table class="table table-striped">
        <tr><th><?= __('ID') ?></th><td><?= $this->Number->format($optativa->id) ?></td></tr>
        <tr><th><?= __('Código') ?></th><td><?= h($optativa->codigo) ?></td></tr>
        <tr><th><?= __('Nome') ?></th><td><?= h($optativa->nome) ?></td></tr>
        <tr><th><?= __('Carga Horária') ?></th><td><?= $optativa->carga_horaria ? $optativa->carga_horaria . 'h' : '-' ?></td></tr>
        <tr><th><?= __('Ementa') ?></th><td><?= nl2br(h($optativa->ementa)) ?></td></tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $optativa->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
