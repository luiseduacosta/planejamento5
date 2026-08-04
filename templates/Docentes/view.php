<?php
declare(strict_types=1);
?>
<div class="card">
    <div class="card-header">
        <h3><?= h($docente->nome) ?></h3>
    </div>
    <div class="card-body">
        
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
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($docente->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('CPF') ?></th>
                    <td><?= h($docente->cpf) ?></td>
                </tr>
                <tr>
                    <th><?= __('SIAPE') ?></th>
                    <td><?= h($docente->siape) ?></td>
                </tr>
                <tr>
                    <th><?= __('CRESS') ?></th>
                    <td><?= h($docente->cress) ?></td>
                </tr>
                <tr>
                    <th><?= __('Região') ?></th>
                    <td><?= h($docente->regiao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($docente->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular') ?></th>
                    <td><?= h($docente->celular) ?></td>
                </tr>
                <tr>
                    <th><?= __('Departamento') ?></th>
                    <td><?= h($docente->departamento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo de Cargo') ?></th>
                    <td><?= h($docente->tipocargo ?? '-') ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($docente->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data de Ingresso') ?></th>
                    <td><?= h($docente->dataingresso?->format('d/m/Y')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data de Egresso') ?></th>
                    <td><?= h($docente->dataegresso?->format('d/m/Y')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Motivo de Egresso') ?></th>
                    <td><?= h($docente->motivoegresso) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($statusLabels[$docente->status] ?? $docente->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Observações') ?></th>
                    <td><?= $docente->observacoes ? nl2br(h($docente->observacoes)) : '-' ?></td>
                </tr>
                <tr>
                    <th><?= __('Criado') ?></th>
                    <td><?= h($docente->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificado') ?></th>
                    <td><?= h($docente->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4><?= __('Disponibilidade por Semestre') ?></h4>
        </div>
        <div class="card-body">
            <?php if ($configuracaoAtiva !== null): ?>
                <?php
                    $temRegistroAtivo = ($disponibilidadeAtiva !== null);
                    if ($temRegistroAtivo) {
                        $isDisponivel = (isset($disponibilidadeAtiva->disponivel) && filter_var($disponibilidadeAtiva->disponivel, FILTER_VALIDATE_BOOLEAN));
                        $motivoAtual = isset($disponibilidadeAtiva->motivo) ? (string)$disponibilidadeAtiva->motivo : '';
                    } else {
                        $isDisponivel = false;
                        $motivoAtual = '';
                    }

                    if ($temRegistroAtivo && $isDisponivel) {
                        $badgeClass = 'bg-success text-white';
                        $badgeIcon = 'bi-check-circle-fill';
                        $badgeLabel = __('Disponível');
                    } elseif ($temRegistroAtivo) {
                        $badgeClass = 'bg-danger text-white';
                        $badgeIcon = 'bi-x-circle-fill';
                        $badgeLabel = __('Indisponível');
                    } else {
                        $badgeClass = 'bg-warning text-dark border border-warning-subtle';
                        $badgeIcon = 'bi-question-circle-fill';
                        $badgeLabel = __('Não definido');
                    }
                ?>
                <div class="alert alert-light border d-flex flex-wrap align-items-center gap-3 mb-3">
                    <strong><?= __('Semestre ativo:') ?> <?= h($configuracaoAtiva->semestre) ?></strong>
                    <span class="badge <?= $badgeClass ?> px-2 py-1 rounded-pill">
                        <i class="bi <?= $badgeIcon ?> me-1"></i><?= h($badgeLabel) ?>
                    </span>
                    <?php if (!$temRegistroAtivo): ?>
                        <small class="text-warning-emphasis">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <?= __('Nenhuma definição para o semestre ativo — tratado como indisponível.') ?>
                        </small>
                    <?php endif; ?>
                    <?= $this->Form->create(null, [
                        'url' => ['controller' => 'DocenteDisponibilidades', 'action' => 'salvarRapido'],
                        'class' => 'disp-form d-flex align-items-center gap-2 mb-0',
                    ]) ?>
                        <?= $this->Form->hidden('docente_id', ['value' => $docente->id]) ?>
                        <?= $this->Form->hidden('configuraplanejamento_id', ['value' => $configuracaoAtiva->id]) ?>
                        <div class="form-check form-switch mb-0">
                            <?= $this->Form->checkbox('disponivel', [
                                'checked' => $isDisponivel,
                                'class' => 'form-check-input disp-switch',
                                'role' => 'switch',
                                'id' => 'disp-ativa-' . $docente->id,
                            ]) ?>
                            <label class="form-check-label disp-label" for="disp-ativa-<?= $docente->id ?>">
                                <?= $isDisponivel ? __('Sim') : __('Não') ?>
                            </label>
                        </div>
                        <div class="disp-motivo" style="<?= $isDisponivel ? 'display:none;' : '' ?>">
                            <?= $this->Form->text('motivo', [
                                'value' => $motivoAtual,
                                'placeholder' => $temRegistroAtivo ? __('Motivo') : __('Opcional: motivo da indisponibilidade'),
                                'maxlength' => 100,
                                'class' => 'form-control form-control-sm',
                            ]) ?>
                            <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-sm btn-primary disp-save']) ?>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            <?php else: ?>
                <p class="text-muted"><?= __('Nenhum semestre ativo selecionado na sessão.') ?></p>
            <?php endif; ?>
            <div class="table-responsive mt-2">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= __('Semestre') ?></th>
                            <th><?= __('Disponível') ?></th>
                            <th><?= __('Motivo') ?></th>
                            <th><?= __('Observações') ?></th>
                            <th class="text-nowrap"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($docente->docente_disponibilidades)): ?>
                            <?php foreach ($docente->docente_disponibilidades as $disp): ?>
                                <tr>
                                    <td><?= $disp->hasValue('configuraplanejamento') ? h($disp->configuraplanejamento->semestre) : '-' ?></td>
                                    <td><?= $disp->disponivel ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?></td>
                                    <td><?= h($disp->motivo) ?></td>
                                    <td><?= $disp->observacoes !== null ? nl2br(h($disp->observacoes)) : '-' ?></td>
                                    <td class="text-nowrap">
                                        <?= $this->Html->link(__('Editar'), ['controller' => 'DocenteDisponibilidades', 'action' => 'edit', $disp->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                        <?= $this->Form->postLink(__('Excluir'), ['controller' => 'DocenteDisponibilidades', 'action' => 'delete', $disp->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5"><?= __('Nenhum registro de disponibilidade.') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?= $this->Html->link(__('Editar'), ['action' => 'edit', $docente->id], ['class' => 'btn btn-warning']) ?>
    <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
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