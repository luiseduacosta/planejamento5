<div class="usuarioplanejamentos view content">
    <h3><?= h($user->email) ?></h3>
    <table class="table table-striped">
        <tr><th><?= __('ID') ?></th><td><?= $this->Number->format($user->id) ?></td></tr>
        <tr><th><?= __('Email') ?></th><td><?= h($user->email) ?></td></tr>
        <tr><th><?= __('Nome') ?></th><td><?= h($user->nome ?? '-') ?></td></tr>
        <tr><th><?= __('Perfil') ?></th><td><?= h($user->role) ?></td></tr>
        <tr><th><?= __('Criado') ?></th><td><?= h($user->created) ?></td></tr>
        <tr><th><?= __('Modificado') ?></th><td><?= h($user->modified) ?></td></tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $user->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
