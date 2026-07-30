<?php
declare(strict_types=1);
?>
<div class="container-fluid px-4 py-3">
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

    <!-- Page Title & Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h3 fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i><?= __('Docentes') ?></h2>
            <p class="text-muted small mb-0"><?= __('Gestão de docentes, situação institucional e disponibilidade por semestre') ?></p>
        </div>
        <div>
            <?= $this->Html->link(
                '<i class="bi bi-plus-circle me-1"></i>' . __('Novo Docente'),
                ['action' => 'add'],
                ['class' => 'btn btn-primary shadow-sm', 'escape' => false]
            ) ?>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-vcard fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold"><?= __('Total de Docentes') ?></div>
                        <div class="fs-4 fw-bold text-dark"><?= number_format($statsDocentes['total'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-circle bg-success-subtle text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold"><?= __('Ativos Institucional') ?></div>
                        <div class="fs-4 fw-bold text-success"><?= number_format($statsDocentes['ativos'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold"><?= __('Indisponíveis no Semestre') ?></div>
                        <div class="fs-4 fw-bold text-danger"><?= number_format($statsDocentes['indisponiveisSemestre'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-circle bg-secondary-subtle text-secondary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-person-x fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-semibold"><?= __('Aposentados / Inativos') ?></div>
                        <div class="fs-4 fw-bold text-secondary"><?= number_format($statsDocentes['inativos'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end', 'id' => 'docentes-filter-form']) ?>
            
            <!-- Status Filter -->
            <div class="col-12 col-md-4 col-lg-3">
                <?php
                    $statusSelected = is_array($statusFilter) ? $statusFilter : [];
                    if (!empty($statusIsAll)) {
                        $statusSelected = [$statusAllSentinel];
                    }
                ?>
                <label for="status" class="form-label small fw-semibold mb-1"><?= __('Status Permanente (Multi-seleção)') ?></label>
                <select
                    name="status[]"
                    id="status"
                    multiple="multiple"
                    size="4"
                    class="form-select form-select-sm docentes-status-multi"
                    data-all-sentinel="<?= h($statusAllSentinel) ?>"
                    aria-label="<?= h(__('Filtrar docentes por status')) ?>"
                >
                    <?php foreach ($statusList as $valor => $rotulo): ?>
                        <?php $selecionado = in_array((string)$valor, $statusSelected, true); ?>
                        <option
                            value="<?= h($valor) ?>"
                            <?= $selecionado ? 'selected="selected"' : '' ?>
                            data-role="<?= $valor === $statusAllSentinel ? 'all' : 'single' ?>"
                        ><?= h($rotulo) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text small" style="font-size: 0.75rem;">
                    <?= __('Segure Ctrl/Cmd para múltiplos. "Todos" exibe tudo.') ?>
                </div>
            </div>

            <!-- Departamento Filter -->
            <div class="col-12 col-md-4 col-lg-3">
                <?= $this->Form->control('departamento', [
                    'label' => ['text' => __('Departamento'), 'class' => 'form-label small fw-semibold mb-1'],
                    'options' => ['' => __('Todos os Departamentos')] + $departamentosList,
                    'default' => $departamentoFilter,
                    'empty' => false,
                    'class' => 'form-select form-select-sm'
                ]) ?>
            </div>

            <!-- Semestre na Coluna Disponibilidade -->
            <div class="col-12 col-md-4 col-lg-3">
                <?= $this->Form->control('configuraplanejamento_id', [
                    'label' => ['text' => __('Semestre na Coluna Disponibilidade'), 'class' => 'form-label small fw-semibold mb-1'],
                    'options' => ['' => __('(Usar Semestre Ativo da Sessão)')] + $configuracoesList,
                    'default' => $configuraplanejamentoFilter,
                    'empty' => false,
                    'class' => 'form-select form-select-sm'
                ]) ?>
                <div class="form-text small" style="font-size: 0.75rem;">
                    <?= __('Define o semestre da coluna de disponibilidade.') ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-lg-3 d-flex gap-2">
                <?= $this->Form->button('<i class="bi bi-funnel me-1"></i>' . __('Filtrar'), [
                    'class' => 'btn btn-sm btn-primary flex-grow-1',
                    'escape' => false,
                ]) ?>
                <?php
                    $temStatusFiltro = !($statusIsAll ?? false);
                    $temDepartamento = !empty($departamentoFilter);
                    $temSemestreDisp = !empty($configuraplanejamentoFilter);
                ?>
                <?php if ($temStatusFiltro || $temDepartamento || $temSemestreDisp): ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-x-circle me-1"></i>' . __('Limpar'),
                        ['action' => 'index', '?' => ['status' => [$statusAllSentinel ?? 'all'], 'departamento' => '', 'configuraplanejamento_id' => '']],
                        ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false]
                    ) ?>
                <?php endif; ?>
            </div>

            <?= $this->Form->end() ?>

            <?php if ($temStatusFiltro || $temDepartamento || $temSemestreDisp): ?>
                <div class="mt-2 pt-2 border-top d-flex flex-wrap gap-1 align-items-center">
                    <span class="small text-muted me-1"><?= __('Filtros ativos:') ?></span>
                    <?php if ($temStatusFiltro): ?>
                        <?php foreach (($statusFilterLabels ?? []) as $_canonical => $_label): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-tag-fill me-1"></i>Status: <?= h($_label) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($temDepartamento): ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-building me-1"></i>Dept: <?= h($departamentoFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($temSemestreDisp): ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-calendar-event me-1"></i>Semestre: <?= h($configuracaoFilterLabel ?? '') ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Docentes Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;"><?= $this->Paginator->sort('id', __('ID')) ?></th>
                        <th><?= $this->Paginator->sort('nome', __('Docente / Dados')) ?></th>
                        <th><?= $this->Paginator->sort('departamento', __('Departamento / Cargo')) ?></th>
                        <th style="width: 140px;"><?= $this->Paginator->sort('status', __('Status Permanente')) ?></th>
                        <th style="min-width: 220px;">
                            <?= __('Disponibilidade no Semestre') ?>
                            <?php if ($configuracaoAtual !== null): ?>
                                <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal">(<?= h($configuracaoAtual->semestre . ' - v' . ($configuracaoAtual->versao ?? '1')) ?>)</span>
                            <?php endif; ?>
                        </th>
                        <th class="text-end" style="width: 140px;"><?= __('Ações') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($docentes) && count($docentes) > 0): ?>
                        <?php foreach ($docentes as $docente): ?>
                            <?php
                                $canonicalStatus = strtolower(trim((string)$docente->status));
                                $isDocenteAtivo = in_array($canonicalStatus, ['ativo', 'active', 'activo'], true);
                                $isDocenteAposentado = in_array($canonicalStatus, ['aposentado', 'retired'], true);
                                $isDocenteInativo = in_array($canonicalStatus, ['inativo', 'inactive', 'inactivo'], true);
                            ?>
                        <tr id="docente-row-<?= $docente->id ?>">
                            <td class="text-muted small"><?= $this->Number->format($docente->id) ?></td>
                            <td>
                                <div class="fw-bold text-dark mb-0"><?= h($docente->nome) ?></div>
                                <div class="small text-muted d-flex flex-wrap gap-2 align-items-center">
                                    <?php if ($docente->siape): ?>
                                        <span><i class="bi bi-card-text me-1"></i>SIAPE: <?= h($docente->siape) ?></span>
                                    <?php endif; ?>
                                    <?php if ($docente->email): ?>
                                        <span><i class="bi bi-envelope me-1"></i><?= h($docente->email) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?= h($docente->departamento ?? '-') ?></div>
                                <?php if ($docente->tipocargo): ?>
                                    <span class="badge bg-light text-dark border text-capitalize small"><?= h($docente->tipocargo) ?></span>
                                <?php endif; ?>
                            </td>
                            <!-- Status Permanente (Institutional) -->
                            <td>
                                <?php if ($isDocenteAtivo): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i><?= __('Ativo') ?>
                                    </span>
                                <?php elseif ($isDocenteAposentado): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-mortarboard-fill me-1"></i><?= __('Aposentado') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-dash-circle-fill me-1"></i><?= h($statusLabels[$docente->status] ?? $docente->status) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <!-- Disponibilidade no Semestre (Transitory Status) -->
                            <td class="disp-cell">
                                <?php if (!$isDocenteAtivo): ?>
                                    <span class="badge bg-light text-muted border border-secondary-subtle px-2 py-1" data-bs-toggle="tooltip" title="<?= h(__('Docente não está ativo no cadastro permanente. Disponibilidade não aplicável.')) ?>">
                                        <i class="bi bi-slash-circle me-1"></i><?= __('N/A (Inativo)') ?>
                                    </span>
                                <?php elseif ($configuracaoAtual !== null): ?>
                                    <?php
                                        $disp = $disponibilidades[$docente->id] ?? null;
                                        $isDisponivel = $disp ? (bool)$disp->disponivel : true;
                                        $motivoAtual = $disp ? (string)$disp->motivo : '';
                                    ?>
                                    <div class="disp-wrapper" data-docente-id="<?= $docente->id ?>" data-docente-nome="<?= h($docente->nome) ?>">
                                        <?= $this->Form->create(null, [
                                            'url' => ['controller' => 'DocenteDisponibilidades', 'action' => 'salvarRapido'],
                                            'class' => 'disp-form d-flex flex-column align-items-start gap-1 mb-0',
                                        ]) ?>
                                            <?= $this->Form->hidden('docente_id', ['value' => $docente->id]) ?>
                                            <?= $this->Form->hidden('configuraplanejamento_id', ['value' => $configuracaoAtual->id]) ?>
                                            <?= $this->Form->hidden('disponivel', ['value' => $isDisponivel ? '1' : '0', 'class' => 'disp-val-hidden']) ?>
                                            <?= $this->Form->hidden('motivo', ['value' => $motivoAtual, 'class' => 'disp-motivo-hidden']) ?>
                                            <?= $this->Form->hidden('_ajax', ['value' => '1']) ?>

                                            <div class="d-flex align-items-center gap-2">
                                                <div class="form-check form-switch mb-0 fs-6">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input disp-switch cursor-pointer"
                                                        role="switch"
                                                        id="disp-switch-<?= $docente->id ?>"
                                                        <?= $isDisponivel ? 'checked="checked"' : '' ?>
                                                        aria-label="<?= h(__('Alternar disponibilidade do docente')) ?>"
                                                    >
                                                </div>

                                                <!-- Status Badge Indicator -->
                                                <span class="disp-badge badge <?= $isDisponivel ? 'bg-success text-white' : 'bg-danger text-white' ?> px-2 py-1 rounded-pill shadow-xs transition-all">
                                                    <span class="disp-badge-icon me-1"><i class="bi <?= $isDisponivel ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?>"></i></span>
                                                    <span class="disp-badge-text"><?= $isDisponivel ? __('Disponível') : __('Indisponível') ?></span>
                                                </span>

                                                <?php if ($disp === null): ?>
                                                    <span class="badge bg-light text-secondary border border-secondary-subtle small ms-1" data-bs-toggle="tooltip" title="<?= h(__('Sem registro explícito; assume padrão disponível')) ?>"><?= __('padrão') ?></span>
                                                <?php endif; ?>
                                                
                                                <span class="disp-spinner spinner-border spinner-border-sm text-primary d-none ms-1" role="status" aria-hidden="true"></span>
                                            </div>

                                            <!-- Motivo text (for Indisponível status) -->
                                            <div class="disp-motivo-display small mt-1 <?= $isDisponivel ? 'd-none' : '' ?>">
                                                <?php if (!empty($motivoAtual)): ?>
                                                    <span class="text-danger fw-medium"><i class="bi bi-info-circle me-1"></i><?= h($motivoAtual) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic"><?= __('Sem motivo registrado') ?></span>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-link p-0 ms-1 text-decoration-none btn-edit-motivo text-secondary small" title="<?= h(__('Editar motivo da indisponibilidade')) ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </div>
                                        <?= $this->Form->end() ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border px-2 py-1">
                                        <i class="bi bi-info-circle me-1"></i><?= __('Selecione um semestre') ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <!-- Actions -->
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?= $this->Html->link('<i class="bi bi-eye"></i>', ['action' => 'view', $docente->id], [
                                        'class' => 'btn btn-outline-info',
                                        'escape' => false,
                                        'data-bs-toggle' => 'tooltip',
                                        'title' => __('Ver Detalhes')
                                    ]) ?>
                                    <?= $this->Html->link('<i class="bi bi-pencil"></i>', ['action' => 'edit', $docente->id], [
                                        'class' => 'btn btn-outline-warning',
                                        'escape' => false,
                                        'data-bs-toggle' => 'tooltip',
                                        'title' => __('Editar Docente')
                                    ]) ?>
                                    <?= $this->Form->postLink('<i class="bi bi-trash"></i>', ['action' => 'delete', $docente->id], [
                                        'confirm' => __('Tem certeza que deseja excluir {0}?', $docente->nome),
                                        'class' => 'btn btn-outline-danger',
                                        'escape' => false,
                                        'data-bs-toggle' => 'tooltip',
                                        'title' => __('Excluir Docente')
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                <?= __('Nenhum docente encontrado com os filtros selecionados.') ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="card-footer bg-white border-top-0 d-flex flex-wrap align-items-center justify-content-between py-3 px-3">
            <div class="small text-muted mb-2 mb-md-0">
                <?= $this->Paginator->counter(__('Mostrando {{current}} registro(s) de {{count}} total (Página {{page}} de {{pages}})')) ?>
            </div>
            <nav aria-label="<?= h(__('Paginação')) ?>">
                <ul class="pagination pagination-sm mb-0">
                    <?= $this->Paginator->first('<i class="bi bi-chevron-double-left"></i>', ['escape' => false]) ?>
                    <?= $this->Paginator->prev('<i class="bi bi-chevron-left"></i>', ['escape' => false]) ?>
                    <?= $this->Paginator->numbers(['modulus' => 4]) ?>
                    <?= $this->Paginator->next('<i class="bi bi-chevron-right"></i>', ['escape' => false]) ?>
                    <?= $this->Paginator->last('<i class="bi bi-chevron-double-right"></i>', ['escape' => false]) ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal para Digitar/Editar o Motivo da Indisponibilidade -->
<div class="modal fade" id="modalMotivoIndisponibilidade" tabindex="-1" aria-labelledby="modalMotivoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title h6" id="modalMotivoLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= __('Informar Motivo da Indisponibilidade') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3" id="modalDocenteNomeText">
                    <?= __('Informe o motivo pelo qual o docente estará indisponível neste semestre (ex.: afastamento para doutorado, licença médica, projetos especiais):') ?>
                </p>
                <div class="mb-3">
                    <label for="modalInputMotivo" class="form-label small fw-semibold"><?= __('Motivo (opcional):') ?></label>
                    <input type="text" class="form-control" id="modalInputMotivo" maxlength="100" placeholder="<?= h(__('Ex.: Licença Capacitação, Afastamento Médico...')) ?>">
                </div>
            </div>
            <div class="modal-footer bg-light p-2 px-3">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnModalCancel"><?= __('Cancelar') ?></button>
                <button type="button" class="btn btn-sm btn-danger" id="btnModalConfirmSave">
                    <i class="bi bi-check-lg me-1"></i><?= __('Salvar Indisponibilidade') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 180ms ease-in-out; }
    .disp-cell { vertical-align: middle; }
    
    /* Global Toast Container */
    .disp-toast-wrap {
        position: fixed;
        right: 1.25rem;
        bottom: 1.25rem;
        z-index: 1090;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        pointer-events: none;
    }
    .disp-toast {
        pointer-events: auto;
        min-width: 260px;
        max-width: 380px;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 200ms ease, transform 200ms ease;
    }
    .disp-toast.show { opacity: 1; transform: translateY(0); }
</style>

<script>
(function () {
    'use strict';

    var activeWrapper = null;

    // Show floating toast message
    function showToast(kind, message) {
        var wrap = document.querySelector('.disp-toast-wrap');
        if (!wrap) {
            wrap = document.createElement('div');
            wrap.className = 'disp-toast-wrap';
            document.body.appendChild(wrap);
        }
        var div = document.createElement('div');
        var bg = kind === 'success' ? 'bg-success' : (kind === 'error' ? 'bg-danger' : 'bg-primary');
        var icon = kind === 'success' ? '<i class="bi bi-check-circle-fill me-2"></i>' : '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
        div.className = 'disp-toast alert ' + bg + ' text-white shadow rounded-3 border-0 py-2 px-3 mb-0';
        div.setAttribute('role', 'status');
        div.innerHTML = icon + '<span>' + message + '</span>';
        wrap.appendChild(div);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () { div.classList.add('show'); });
        });
        setTimeout(function () {
            div.classList.remove('show');
            setTimeout(function () { if (div.parentNode) { div.parentNode.removeChild(div); } }, 250);
        }, 2500);
    }

    // Submit AJAX form
    function submitAvailabilityAjax(wrapper, isDisponivel, motivoText) {
        var form = wrapper.querySelector('.disp-form');
        if (!form) return;

        var switchEl = wrapper.querySelector('.disp-switch');
        var badgeEl = wrapper.querySelector('.disp-badge');
        var badgeIcon = wrapper.querySelector('.disp-badge-icon');
        var badgeText = wrapper.querySelector('.disp-badge-text');
        var spinner = wrapper.querySelector('.disp-spinner');
        var motivoDisplay = wrapper.querySelector('.disp-motivo-display');
        var valHidden = form.querySelector('.disp-val-hidden');
        var motivoHidden = form.querySelector('.disp-motivo-hidden');

        if (valHidden) valHidden.value = isDisponivel ? '1' : '0';
        if (motivoHidden) motivoHidden.value = isDisponivel ? '' : (motivoText || '');

        if (spinner) spinner.classList.remove('d-none');
        if (switchEl) switchEl.disabled = true;

        var formData = new FormData(form);
        var url = form.getAttribute('action') || window.location.href;

        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        }).then(function (resp) {
            return resp.json().then(function (json) {
                return { ok: resp.ok && json && json.ok === true, json: json };
            });
        }).then(function (res) {
            if (spinner) spinner.classList.add('d-none');
            if (switchEl) switchEl.disabled = false;

            if (res.ok) {
                var dispNow = res.json.disponivel !== undefined ? Boolean(res.json.disponivel) : isDisponivel;
                var motivoNow = res.json.motivo || '';

                if (switchEl) switchEl.checked = dispNow;
                if (badgeEl) {
                    badgeEl.className = 'disp-badge badge ' + (dispNow ? 'bg-success text-white' : 'bg-danger text-white') + ' px-2 py-1 rounded-pill shadow-xs transition-all';
                }
                if (badgeIcon) {
                    badgeIcon.innerHTML = '<i class="bi ' + (dispNow ? 'bi-check-circle-fill' : 'bi-x-circle-fill') + '"></i>';
                }
                if (badgeText) {
                    badgeText.textContent = dispNow ? <?= json_encode(__('Disponível')) ?> : <?= json_encode(__('Indisponível')) ?>;
                }

                if (motivoDisplay) {
                    if (!dispNow) {
                        motivoDisplay.classList.remove('d-none');
                        var txtSpan = motivoDisplay.querySelector('span');
                        if (txtSpan) {
                            if (motivoNow.trim() !== '') {
                                txtSpan.className = 'text-danger fw-medium';
                                txtSpan.innerHTML = '<i class="bi bi-info-circle me-1"></i>' + escapeHtml(motivoNow);
                            } else {
                                txtSpan.className = 'text-muted fst-italic';
                                txtSpan.textContent = <?= json_encode(__('Sem motivo registrado')) ?>;
                            }
                        }
                    } else {
                        motivoDisplay.classList.add('d-none');
                    }
                }

                showToast('success', res.json.message || <?= json_encode(__('Disponibilidade atualizada.')) ?>);
            } else {
                if (switchEl) switchEl.checked = !isDisponivel;
                showToast('error', res.json.message || <?= json_encode(__('Erro ao atualizar disponibilidade.')) ?>);
            }
        }).catch(function () {
            if (spinner) spinner.classList.add('d-none');
            if (switchEl) {
                switchEl.disabled = false;
                switchEl.checked = !isDisponivel;
            }
            showToast('error', <?= json_encode(__('Erro de conexão ao salvar.')) ?>);
        });
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function hookStatusMultiSelect() {
        var sel = document.querySelector('select.docentes-status-multi');
        if (!sel) return;
        var sentinel = sel.getAttribute('data-all-sentinel') || 'all';
        var lastUserSelectedAll = Array.from(sel.selectedOptions).some(function (o) { return o.value === sentinel; });

        sel.addEventListener('change', function () {
            var allOpt = sel.querySelector('option[value="' + sentinel + '"]');
            var allIsNow = allOpt ? allOpt.selected : false;

            if (allIsNow && !lastUserSelectedAll) {
                Array.from(sel.options).forEach(function (op) {
                    if (op.value !== sentinel) op.selected = false;
                });
            } else {
                var hasSpecific = Array.from(sel.options).some(function (op) {
                    return op.value !== sentinel && op.selected;
                });
                if (hasSpecific && allOpt) allOpt.selected = false;
                var anySelected = Array.from(sel.options).some(function (op) { return op.selected; });
                if (!anySelected && allOpt) allOpt.selected = true;
            }
            lastUserSelectedAll = allOpt ? allOpt.selected : false;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        hookStatusMultiSelect();

        if (window.bootstrap && typeof window.bootstrap.Tooltip === 'function') {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el, { boundary: 'clippingParents' });
            });
        }

        var modalEl = document.getElementById('modalMotivoIndisponibilidade');
        var bsModal = modalEl && window.bootstrap ? new bootstrap.Modal(modalEl) : null;
        var inputMotivo = document.getElementById('modalInputMotivo');
        var btnConfirm = document.getElementById('btnModalConfirmSave');
        var btnCancel = document.getElementById('btnModalCancel');
        var docenteNomeText = document.getElementById('modalDocenteNomeText');

        document.querySelectorAll('.disp-wrapper').forEach(function (wrapper) {
            var switchEl = wrapper.querySelector('.disp-switch');
            var btnEdit = wrapper.querySelector('.btn-edit-motivo');

            if (switchEl) {
                switchEl.addEventListener('change', function () {
                    var isDisponivel = switchEl.checked;
                    if (isDisponivel) {
                        submitAvailabilityAjax(wrapper, true, '');
                    } else {
                        activeWrapper = wrapper;
                        var dNome = wrapper.getAttribute('data-docente-nome') || '';
                        var motivoHidden = wrapper.querySelector('.disp-motivo-hidden');
                        if (inputMotivo) inputMotivo.value = motivoHidden ? motivoHidden.value : '';
                        if (docenteNomeText) {
                            docenteNomeText.innerHTML = <?= json_encode(__('Informe o motivo pelo qual <strong>{0}</strong> estará indisponível neste semestre:')) ?>.replace('{0}', escapeHtml(dNome));
                        }
                        if (bsModal) {
                            bsModal.show();
                            setTimeout(function () { if (inputMotivo) inputMotivo.focus(); }, 300);
                        } else {
                            var m = prompt(<?= json_encode(__('Motivo da indisponibilidade:')) ?>, inputMotivo ? inputMotivo.value : '');
                            if (m !== null) {
                                submitAvailabilityAjax(wrapper, false, m);
                            } else {
                                switchEl.checked = true;
                            }
                        }
                    }
                });
            }

            if (btnEdit) {
                btnEdit.addEventListener('click', function (e) {
                    e.preventDefault();
                    activeWrapper = wrapper;
                    var dNome = wrapper.getAttribute('data-docente-nome') || '';
                    var motivoHidden = wrapper.querySelector('.disp-motivo-hidden');
                    if (inputMotivo) inputMotivo.value = motivoHidden ? motivoHidden.value : '';
                    if (docenteNomeText) {
                        docenteNomeText.innerHTML = <?= json_encode(__('Editar o motivo da indisponibilidade de <strong>{0}</strong>:')) ?>.replace('{0}', escapeHtml(dNome));
                    }
                    if (bsModal) {
                        bsModal.show();
                        setTimeout(function () { if (inputMotivo) inputMotivo.focus(); }, 300);
                    }
                });
            }
        });

        if (btnConfirm) {
            btnConfirm.addEventListener('click', function () {
                if (!activeWrapper) return;
                var motivoVal = inputMotivo ? inputMotivo.value : '';
                if (bsModal) bsModal.hide();
                submitAvailabilityAjax(activeWrapper, false, motivoVal);
            });
        }

        if (inputMotivo) {
            inputMotivo.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (btnConfirm) btnConfirm.click();
                }
            });
        }

        if (btnCancel) {
            btnCancel.addEventListener('click', function () {
                if (bsModal) bsModal.hide();
                if (activeWrapper) {
                    var switchEl = activeWrapper.querySelector('.disp-switch');
                    var valHidden = activeWrapper.querySelector('.disp-val-hidden');
                    if (switchEl && valHidden && valHidden.value === '1') {
                        switchEl.checked = true;
                    }
                }
            });
        }

        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                if (activeWrapper) {
                    var switchEl = activeWrapper.querySelector('.disp-switch');
                    var valHidden = activeWrapper.querySelector('.disp-val-hidden');
                    if (switchEl && valHidden && valHidden.value === '1' && !switchEl.checked) {
                        switchEl.checked = true;
                    }
                }
            });
        }
    });
})();
</script>
