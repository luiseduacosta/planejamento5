<div class="disciplinas view content">
    <h3><?= h($disciplina->nome) ?></h3>
    <table class="table table-striped">
        <tr><th><?= __('ID') ?></th><td><?= $this->Number->format($disciplina->id) ?></td></tr>
        <tr><th><?= __('Código') ?></th><td><?= h($disciplina->codigo) ?></td></tr>
        <tr><th><?= __('Nome') ?></th><td><?= h($disciplina->nome) ?></td></tr>
        <tr><th><?= __('Carga Horária') ?></th><td><?= $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '-' ?></td></tr>
        <tr><th><?= __('Ementa') ?></th><td><?= nl2br(h($disciplina->ementa)) ?></td></tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $disciplina->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
