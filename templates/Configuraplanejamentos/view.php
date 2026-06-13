<div class="configuraplanejamentos view content">
    <h3><?= h($configuracao->nome) ?></h3>
    <table class="table table-striped">
        <tr><th><?= __('ID') ?></th><td><?= $this->Number->format($configuracao->id) ?></td></tr>
        <tr><th><?= __('Nome') ?></th><td><?= h($configuracao->nome) ?></td></tr>
        <tr><th><?= __('Semestre') ?></th><td><?= h($configuracao->semestre) ?></td></tr>
        <tr><th><?= __('Versão') ?></th><td><?= h($configuracao->versao ?? '-') ?></td></tr>
        <tr><th><?= __('Ativo') ?></th><td><?= $configuracao->ativo ? 'Sim' : 'Não' ?></td></tr>
        <tr><th><?= __('Criado') ?></th><td><?= h($configuracao->created) ?></td></tr>
        <tr><th><?= __('Modificado') ?></th><td><?= h($configuracao->modified) ?></td></tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $configuracao->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Form->postLink(__('Clonar'), ['action' => 'clone', $configuracao->id], ['confirm' => __('Deseja clonar?'), 'class' => 'btn btn-primary']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
