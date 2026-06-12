<div class="configuraplanejamentos index content">
    <div class="row">
        <div class="col"><h3><?= __('Configurações de Planejamento') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Nova Configuração'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th><?= $this->Paginator->sort('id') ?></th><th><?= $this->Paginator->sort('nome') ?></th><th><?= $this->Paginator->sort('semestre') ?></th><th><?= $this->Paginator->sort('ativo') ?></th><th><?= $this->Paginator->sort('usuarioplanejamento_id') ?></th><th class="actions"><?= __('Ações') ?></th></tr></thead>
            <tbody>
                <?php foreach ($configuracoes as $configuracao): ?>
                <tr>
                    <td><?= $this->Number->format($configuracao->id) ?></td>
                    <td><?= h($configuracao->nome) ?></td>
                    <td><?= h($configuracao->semestre) ?></td>
                    <td><?= $configuracao->ativo ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>' ?></td>
                    <td><?= $configuracao->hasValue('usuarioplanejamento') ? h($configuracao->usuarioplanejamento->username) : '-' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $configuracao->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $configuracao->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Clonar'), ['action' => 'clone', $configuracao->id], ['confirm' => __('Deseja clonar esta configuração?'), 'class' => 'btn btn-sm btn-primary']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $configuracao->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator"><?= $this->Paginator->first('<< '.__('primeiro')) ?><?= $this->Paginator->prev('< '.__('anterior')) ?><?= $this->Paginator->numbers() ?><?= $this->Paginator->next(__('próximo').' >') ?><?= $this->Paginator->last(__('último').' >>') ?>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </div>
</div>
