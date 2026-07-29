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
    <div class="row mb-3">
        <div class="col-auto">
            <h3><?= __('Docentes') ?></h3>
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
        <div class="col-auto">
            <?= $this->Html->link(__('Novo Docente'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
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
                    ['action' => 'index', '?' => ['status' => 'ativo']],
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
                    <th><?= $this->Paginator->sort('tipocargo', __('Tipo de Cargo')) ?></th>
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
                <?php foreach ($docentes as $docente): ?>
                <tr>
                    <td><?= $this->Number->format($docente->id) ?></td>
                    <td><?= h($docente->nome) ?></td>
                    <td><?= h($docente->siape) ?></td>
                    <td><?= h($docente->departamento) ?></td>
                    <td><?= h($docente->tipocargo ?? '-') ?></td>
                    <td><?= h($statusLabels[$docente->status] ?? $docente->status) ?></td>
                    <td>
                        <?php if ($configuracaoAtual !== null): ?>
                            <?php
                                $disp = $disponibilidades[$docente->id] ?? null;
                                $isDisponivel = $disp ? (bool)$disp->disponivel : true;
                                $motivoAtual = $disp ? (string)$disp->motivo : '';
                            ?>
                            <?= $this->Form->create(null, [
                                'url' => ['controller' => 'DocenteDisponibilidades', 'action' => 'salvarRapido'],
                                'class' => 'disp-form d-flex flex-column gap-1',
                            ]) ?>
                                <?= $this->Form->hidden('docente_id', ['value' => $docente->id]) ?>
                                <?= $this->Form->hidden('configuraplanejamento_id', ['value' => $configuracaoAtual->id]) ?>
                                <div class="form-check form-switch mb-0">
                                    <?= $this->Form->checkbox('disponivel', [
                                        'checked' => $isDisponivel,
                                        'class' => 'form-check-input disp-switch',
                                        'role' => 'switch',
                                        'id' => 'disp-' . $docente->id,
                                    ]) ?>
                                    <label class="form-check-label disp-label" for="disp-<?= $docente->id ?>">
                                        <?= $isDisponivel ? __('Sim') : __('Não') ?>
                                    </label>
                                    <?php if ($disp === null): ?>
                                        <small class="text-muted d-block"><?= __('(não informada)') ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="disp-motivo" style="<?= $isDisponivel ? 'display:none;' : '' ?>">
                                    <?= $this->Form->text('motivo', [
                                        'value' => $motivoAtual,
                                        'placeholder' => __('Motivo'),
                                        'maxlength' => 100,
                                        'class' => 'form-control form-control-sm',
                                    ]) ?>
                                    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-sm btn-primary mt-1 disp-save']) ?>
                                </div>
                            <?= $this->Form->end() ?>
                        <?php else: ?>
                            <span class="text-muted"><?= __('Selecione um semestre') ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($docente->email) ?></td>
                    <td class="text-nowrap">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $docente->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $docente->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $docente->id], [
                            'confirm' => __('Tem certeza que deseja excluir {0}?', $docente->nome),
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.disp-form').forEach(function (form) {
        var sw = form.querySelector('.disp-switch');
        var motivo = form.querySelector('.disp-motivo');
        var label = form.querySelector('.disp-label');
        if (!sw) { return; }
        sw.addEventListener('change', function () {
            if (sw.checked) {
                // Disponível (Sim): esconde o motivo e salva imediatamente.
                if (motivo) { motivo.style.display = 'none'; }
                if (label) { label.textContent = <?= json_encode(__('Sim')) ?>; }
                form.submit();
            } else {
                // Indisponível (Não): revela o campo de motivo para preenchimento.
                if (motivo) { motivo.style.display = ''; }
                if (label) { label.textContent = <?= json_encode(__('Não')) ?>; }
                var input = motivo ? motivo.querySelector('input[name="motivo"]') : null;
                if (input) { input.focus(); }
            }
        });
    });
});
</script>
