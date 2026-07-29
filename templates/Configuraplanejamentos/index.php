<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col"><h3><?= __('Configurações de Planejamento') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Nova Configuração'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th><?= $this->Paginator->sort('id') ?></th><th><?= $this->Paginator->sort('nome') ?></th><th><?= $this->Paginator->sort('semestre') ?></th><th><?= $this->Paginator->sort('versao') ?></th><th><?= $this->Paginator->sort('ativo') ?></th><th class="text-nowrap"><?= __('Ações') ?></th></tr></thead>
            <tbody>
                <?php foreach ($configuracoes as $configuracao): ?>
                <?php $isAtivaSessao = isset($activeConfiguraplanejamentoId) && (int)$configuracao->id === (int)$activeConfiguraplanejamentoId; ?>
                <tr<?= $isAtivaSessao ? ' class="table-success"' : '' ?>>
                    <td><?= $this->Number->format($configuracao->id) ?></td>
                    <td><?= h($configuracao->nome) ?></td>
                    <td><?= h($configuracao->semestre) ?></td>
                    <td><?= h($configuracao->versao ?? '-') ?></td>
                    <td>
                        <?= $configuracao->ativo ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>' ?>
                        <?= $isAtivaSessao ? ' <span class="badge bg-primary">Em uso</span>' : '' ?>
                    </td>
                    <td class="text-nowrap">
                        <?php if (!$isAtivaSessao): ?>
                        <?= $this->Form->postLink(__('Ativar'), ['action' => 'setativo', $configuracao->id], ['confirm' => __('Definir esta configuração como ativa?'), 'class' => 'btn btn-sm btn-success']) ?>
                        <?php endif; ?>
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $configuracao->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $configuracao->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Html->link(__('Clonar Planej.'), ['controller' => 'Planejamentos', 'action' => 'clonar', '?' => ['destino' => $configuracao->id]], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?= $this->Form->postLink(__('Excluir Planej.'), ['controller' => 'Planejamentos', 'action' => 'excluirTodos', $configuracao->id], ['confirm' => __('Excluir TODOS os planejamentos desta configuração? Esta ação não pode ser desfeita.'), 'class' => 'btn btn-sm btn-outline-danger']) ?>
                        <?= $this->Form->postLink(__('Duplicar'), ['action' => 'clone', $configuracao->id], ['confirm' => __('Deseja duplicar esta configuração?'), 'class' => 'btn btn-sm btn-primary']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $configuracao->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginator -->
    <nav aria-label="Paginação">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('próximo') . ' >') ?>
            <?= $this->Paginator->last(__('último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </nav>
</div>
