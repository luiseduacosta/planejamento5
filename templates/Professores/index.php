<?php
declare(strict_types=1);
?>
<div class="container">
    <?php
        $statusLabels = [
            'ativo' => __('Ativo'),
            'active' => __('Ativo'),
            'activo' => __('Ativo'),
            'aposentado' => __('Aposentado'),
            'retired' => __('Aposentado'),
            'inativo' => __('Inativo'),
            'inactive' => __('Inativo'),
            'inactivo' => __('Inativo'),
        ];
    ?>
    <div class="row">
        <div class="col">
            <h3><?= __('Professores') ?></h3>
            <?php if ($statusFilter || $departamentoFilter || $configuraplanejamentoFilter): ?>
                <small class="text-muted">
                    <?= __('Filtros ativos:') ?>
                    <?php if ($statusFilter): ?>
                        <span class="badge bg-primary"><?= __('Status') ?>: <?= h($statusFilterLabel) ?></span>
                    <?php endif; ?>
                    <?php if ($departamentoFilter): ?>
                        <span class="badge bg-primary"><?= __('Departamento') ?>: <?= h($departamentoFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($configuraplanejamentoFilter): ?>
                        <span class="badge bg-primary"><?= __('Disponibilidade') ?>: <?= h($configuracaoFilterLabel) ?></span>
                    <?php endif; ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Novo Professor'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end']) ?>
            
            <!-- Status Filter -->
            <div class="col-auto">
                <?= $this->Form->control('status', [
                    'label' => __('Status'),
                    'options' => [
                        '' => __('Todos')] + $statusList,
                    'default' => $statusFilter,
                    'empty' => false
                ]) ?>
            </div>
            
            <!-- Departamento Filter -->
            <div class="col-auto">
                <?= $this->Form->control('departamento', [
                    'label' => __('Departamento'),
                    'options' => ['' => __('Todos')] + $departamentosList,
                    'default' => $departamentoFilter,
                    'empty' => false
                ]) ?>
            </div>

            <!-- Disponibilidade Filter -->
            <div class="col-auto">
                <?= $this->Form->control('configuraplanejamento_id', [
                    'label' => __('Disponibilidade'),
                    'options' => ['' => __('Todas')] + $configuracoesList,
                    'default' => $configuraplanejamentoFilter,
                    'empty' => false
                ]) ?>
            </div>
            
            <!-- Filter Button -->
            <div class="col-auto">
                <?= $this->Form->button(__('Filtrar')) ?>
            </div>
            
            <!-- Clear Filters Button -->
            <?php if ($statusFilter || $departamentoFilter || $configuraplanejamentoFilter): ?>
            <div class="col-auto">
                <?= $this->Html->link(
                    __('Limpar Filtros'),
                    ['action' => 'index'],
                    ['class' => 'btn btn-outline-secondary']
                ) ?>
            </div>
            <?php endif; ?>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id', __('ID')) ?></th>
                    <th><?= $this->Paginator->sort('nome', __('Nome')) ?></th>
                    <th><?= $this->Paginator->sort('siape', __('SIAPE')) ?></th>
                    <th><?= $this->Paginator->sort('departamento', __('Departamento')) ?></th>
                    <th><?= $this->Paginator->sort('status', __('Status')) ?></th>
                    <th>
                        <?= __('Disponibilidade') ?>
                        <?php if ($configuracaoAtual !== null): ?>
                            <small class="text-muted">(<?= h($configuracaoAtual->semestre . ' - ' . ($configuracaoAtual->versao ?? '1')) ?>)</small>
                        <?php endif; ?>
                    </th>
                    <th><?= $this->Paginator->sort('email', __('Email')) ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($professores as $professor): ?>
                <tr>
                    <td><?= $this->Number->format($professor->id) ?></td>
                    <td><?= h($professor->nome) ?></td>
                    <td><?= h($professor->siape) ?></td>
                    <td><?= h($professor->departamento) ?></td>
                    <td><?= h($statusLabels[$professor->status] ?? $professor->status) ?></td>
                    <td>
                        <?php if (isset($disponibilidades[$professor->id])): ?>
                            <?php if ($disponibilidades[$professor->id]->disponivel): ?>
                                <span class="badge bg-success"><?= __('Sim') ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?= __('Não') ?></span>
                            <?php endif; ?>
                            <?php if ($disponibilidades[$professor->id]->motivo): ?>
                                <small class="text-muted d-block" title="<?= h($disponibilidades[$professor->id]->motivo) ?>"><?= h($disponibilidades[$professor->id]->motivo) ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted"><?= __('Não informada') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($professor->email) ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $professor->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $professor->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $professor->id], [
                            'confirm' => __('Tem certeza que deseja excluir {0}?', $professor->nome),
                            'class' => 'btn btn-sm btn-danger'
                        ]) ?>
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
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}} total')) ?></p>
    </nav>
</div>
