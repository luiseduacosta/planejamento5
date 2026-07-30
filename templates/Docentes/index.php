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
            <?php
                // Condições usadas para exibir os badges de filtro ativo.
                // Observação: $statusFilter é sempre um array agora.
                $temStatusFiltro = !($statusIsAll ?? false);
                $temDepartamento = !empty($departamentoFilter);
                $temSemestreDisp = !empty($configuraplanejamentoFilter);
            ?>
            <?php if ($temStatusFiltro || $temDepartamento || $temSemestreDisp): ?>
                <small class="text-muted d-inline-flex flex-wrap gap-1 align-items-center">
                    <?= __('Filtros ativos:') ?>
                    <?php if ($temStatusFiltro): ?>
                        <?php foreach (($statusFilterLabels ?? []) as $_canonical => $_label): ?>
                            <span class="badge bg-primary"><?= __('Status') ?>: <?= h($_label) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($temDepartamento): ?>
                        <span class="badge bg-primary"><?= __('Departamento') ?>: <?= h($departamentoFilter) ?></span>
                    <?php endif; ?>
                    <?php if ($temSemestreDisp): ?>
                        <span class="badge bg-primary">
                            <?= __('Semestre na coluna Disponibilidade') ?>: <?= h($configuracaoFilterLabel ?? '') ?>
                        </span>
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
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 align-items-end', 'id' => 'docentes-filter-form']) ?>

            <!-- Status Filter (multi-select) -->
            <div class="col-auto">
                <?php
                    // Garante que o valor selecionado seja sempre um array para
                    // o helper renderizar <option selected> corretamente.
                    $statusSelected = is_array($statusFilter) ? $statusFilter : [];
                    // Opcão sentinela 'all' não tem representação real como dado,
                    // então para fins de exibição, se statusIsAll, preenchemos o
                    // valor selecionado apenas com 'all'. Assim a opção "Todos"
                    // aparece marcada no multi-select quando a tabela exibe tudo.
                    if (!empty($statusIsAll)) {
                        $statusSelected = [$statusAllSentinel];
                    }
                ?>
                <div class="mb-3 mb-0">
                    <?= $this->Form->label('status', __('Status (multi-seleção)'), ['class' => 'form-label']) ?>
                    <select
                        name="status[]"
                        id="status"
                        multiple="multiple"
                        size="4"
                        class="form-select docentes-status-multi"
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
                    <div class="form-text">
                        <?= __('Segure Ctrl/Cmd para selecionar múltiplos. A opção “Todos” exibe todos os registros.') ?>
                    </div>
                </div>
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

            <!-- Escolha do semestre para a coluna "Disponibilidade" (não filtra linhas) -->
            <div class="col-auto">
                <?= $this->Form->control('configuraplanejamento_id', [
                    'label' => __('Semestre na Coluna Disponibilidade'),
                    'options' => ['' => __('(Ativo da sessão)')] + $configuracoesList,
                    'default' => $configuraplanejamentoFilter,
                    'empty' => false,
                ]) ?>
                <div class="form-text mb-2">
                    <?= __('Define qual semestre os botões toggle abaixo editam. Não filtra as linhas da tabela.') ?>
                </div>
            </div>

            <!-- Filter Button -->
            <div class="col-auto">
                <?= $this->Form->button('<i class="bi bi-funnel me-1"></i>' . __('Aplicar Filtros'), [
                    'class' => 'btn btn-primary',
                    'escape' => false,
                ]) ?>
            </div>

            <!-- Clear Filters Button -->
            <?php if ($temStatusFiltro || $temDepartamento || $temSemestreDisp): ?>
            <div class="col-auto">
                <?= $this->Html->link(
                    '<i class="bi bi-x-circle me-1"></i>' . __('Limpar Filtros'),
                    ['action' => 'index', '?' => ['status' => [$statusAllSentinel ?? 'all'], 'departamento' => '', 'configuraplanejamento_id' => '']],
                    ['class' => 'btn btn-outline-secondary', 'escape' => false]
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
                    <td class="align-middle">
                        <?php if ($configuracaoAtual !== null): ?>
                            <?php
                                $disp = $disponibilidades[$docente->id] ?? null;
                                $isDisponivel = $disp ? (bool)$disp->disponivel : true;
                                $motivoAtual = $disp ? (string)$disp->motivo : '';
                                $semestreLabelRaw = $configuracaoAtual->semestre . ' - ' . ($configuracaoAtual->versao ?? '1');
                                // Strings para tooltips: NÃO pré-escape aqui, pois h()
                                // será aplicado no contexto de atributo title="" no HTML.
                                $tooltipAvailRaw = __('Disponível no semestre {0} — clique para marcar como indisponível', $semestreLabelRaw);
                                $tooltipUnavailRaw = __('Indisponível no semestre {0} — clique para marcar como disponível', $semestreLabelRaw);
                                $semestreBadge = $disp === null
                                    ? '<span class="badge bg-light text-secondary border border-secondary-subtle ms-1" title="' . h(__('Nenhuma definição explícita; usa o padrão do sistema')) . '">' . h(__('padrão')) . '</span>'
                                    : '';
                            ?>
                            <?= $this->Form->create(null, [
                                'url' => ['controller' => 'DocenteDisponibilidades', 'action' => 'salvarRapido'],
                                'class' => 'disp-form d-flex flex-column gap-1 align-items-start',
                            ]) ?>
                                <?= $this->Form->hidden('docente_id', ['value' => $docente->id]) ?>
                                <?= $this->Form->hidden('configuraplanejamento_id', ['value' => $configuracaoAtual->id]) ?>
                                <?= $this->Form->hidden('_ajax', ['value' => '1']) ?>

                                <div class="d-flex align-items-center gap-2 disp-toggle-wrap">
                                    <?php
                                        // Badge visual com ícone e cor para comunicar o estado de forma imediata.
                                        if ($isDisponivel) {
                                            $badgeClasses = 'badge bg-success text-white d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill shadow-sm transition-all';
                                            $badgeIcon = '<i class="bi bi-check-circle-fill"></i>';
                                            $badgeText = __('Disponível');
                                            $switchTooltip = $tooltipAvailRaw;
                                        } else {
                                            $badgeClasses = 'badge bg-danger text-white d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill shadow-sm transition-all';
                                            $badgeIcon = '<i class="bi bi-x-circle-fill"></i>';
                                            $badgeText = __('Indisponível');
                                            $switchTooltip = $tooltipUnavailRaw;
                                        }
                                    ?>
                                    <span
                                        class="<?= $badgeClasses ?>"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="<?= h($switchTooltip) ?>"
                                        aria-label="<?= h($badgeText) ?>"
                                    >
                                        <span class="disp-badge-icon"><?= $badgeIcon ?></span>
                                        <span class="disp-badge-text"><?= h($badgeText) ?></span>
                                    </span>
                                    <?= $semestreBadge ?>

                                    <div class="form-check form-switch mb-0 ms-auto">
                                        <?= $this->Form->checkbox('disponivel', [
                                            'checked' => $isDisponivel,
                                            'class' => 'form-check-input disp-switch',
                                            'role' => 'switch',
                                            'id' => 'disp-' . $docente->id,
                                            'data-bs-toggle' => 'tooltip',
                                            'data-bs-placement' => 'top',
                                            'title' => h($switchTooltip),
                                            'aria-label' => h($badgeText),
                                        ]) ?>
                                        <label class="form-check-label disp-label visually-hidden" for="disp-<?= $docente->id ?>">
                                            <?= h($badgeText) ?>
                                        </label>
                                    </div>

                                    <span class="disp-status-icon d-none text-muted small" aria-live="polite"></span>
                                </div>

                                <div
                                    class="disp-motivo w-100"
                                    style="<?= $isDisponivel ? 'display:none;' : '' ?>"
                                    aria-expanded="<?= $isDisponivel ? 'false' : 'true' ?>"
                                >
                                    <?= $this->Form->text('motivo', [
                                        'value' => $motivoAtual,
                                        'placeholder' => __('Ex.: afastamento para doutorado, licença médica...'),
                                        'maxlength' => 100,
                                        'class' => 'form-control form-control-sm',
                                        'data-bs-toggle' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => h(__('Informe o motivo da indisponibilidade (opcional)')),
                                        'aria-label' => h(__('Motivo da indisponibilidade')),
                                    ]) ?>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <?= $this->Form->button('<i class="bi bi-save me-1"></i>' . __('Salvar'), [
                                            'class' => 'btn btn-sm btn-outline-danger disp-save',
                                            'escape' => false,
                                            'data-bs-toggle' => 'tooltip',
                                            'data-bs-placement' => 'top',
                                            'title' => h(__('Salvar a indisponibilidade e o motivo')),
                                        ]) ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary disp-cancel"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="<?= h(__('Cancelar e voltar para Disponível')) ?>"
                                        >
                                            <i class="bi bi-x me-1"></i><?= __('Cancelar') ?>
                                        </button>
                                    </div>
                                </div>
                            <?= $this->Form->end() ?>
                        <?php else: ?>
                            <span
                                class="badge bg-light text-secondary border border-secondary-subtle px-2 py-1"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="<?= h(__('Escolha uma configuração de semestre para editar a disponibilidade')) ?>"
                            >
                                <i class="bi bi-info-circle me-1"></i><?= __('Selecione um semestre') ?>
                            </span>
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

<style>
    /* Animações e efeitos visuais para o toggle de disponibilidade */
    .disp-form .transition-all { transition: background-color 180ms ease, transform 180ms ease, box-shadow 180ms ease, color 180ms ease, opacity 180ms ease; }
    .disp-form .disp-toggle-wrap {
        border-radius: 999px;
        padding: 0.25rem 0.5rem 0.25rem 0.25rem;
        transition: background-color 180ms ease, box-shadow 180ms ease;
    }
    .disp-form.is-saving .disp-toggle-wrap {
        background-color: rgba(13, 110, 253, 0.08);
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }
    .disp-form.is-saving .disp-switch,
    .disp-form.is-saving .disp-save,
    .disp-form.is-saving .disp-cancel { cursor: wait; pointer-events: none; opacity: 0.7; }
    .disp-form.is-flash-success .disp-toggle-wrap {
        animation: dispFlashSuccess 900ms ease-out;
    }
    .disp-form.is-flash-error .disp-toggle-wrap {
        animation: dispFlashError 900ms ease-out;
    }
    @keyframes dispFlashSuccess {
        0%   { background-color: transparent; box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.0); }
        20%  { background-color: rgba(25, 135, 84, 0.18); box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.35); }
        100% { background-color: transparent; box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.0); }
    }
    @keyframes dispFlashError {
        0%   { background-color: transparent; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.0); }
        20%  { background-color: rgba(220, 53, 69, 0.18); box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.35); }
        100% { background-color: transparent; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.0); }
    }
    .disp-toast-wrap {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1080;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        pointer-events: none;
    }
    .disp-toast {
        pointer-events: auto;
        min-width: 240px;
        max-width: 360px;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 180ms ease, transform 180ms ease;
    }
    .disp-toast.show { opacity: 1; transform: translateY(0); }
    @media (max-width: 576px) {
        .disp-badge-text { display: none; }
        .disp-form .disp-motivo { width: 100%; max-width: 240px; }
    }
</style>

<script>
(function () {
    'use strict';

    var STR_SAVE_LABEL  = <?= json_encode(__('Disponível')) ?>;
    var STR_SAVE_ICON   = '<i class="bi bi-check-circle-fill"></i>';
    var STR_UNSAVE_LABEL = <?= json_encode(__('Indisponível')) ?>;
    var STR_UNSAVE_ICON  = '<i class="bi bi-x-circle-fill"></i>';
    var STR_TOOLTIP_AVAIL_FMT = <?= json_encode(__('Disponível no semestre {0} — clique para marcar como indisponível')) ?>;
    var STR_TOOLTIP_UNAVAIL_FMT = <?= json_encode(__('Indisponível no semestre {0} — clique para marcar como disponível')) ?>;

    function getSemestreLabel(form) {
        // O label do semestre vem do cabeçalho da coluna.
        var hdr = document.querySelector('th small.text-muted');
        if (hdr && hdr.textContent) {
            var m = hdr.textContent.match(/\(([^)]+)\)/);
            return m ? m[1].trim() : '';
        }
        return '';
    }
    function fmt(str, a) { return (str || '').replace('{0}', String(a == null ? '' : a)); }

    // Status inline: mostra um ícone + texto breve ao lado do toggle por alguns segundos.
    function setStatusInline(form, kind, message) {
        var el = form.querySelector('.disp-status-icon');
        if (!el) { return; }
        var icon = '';
        var cls = 'text-muted';
        if (kind === 'saving')  { icon = '<i class="bi bi-arrow-repeat spin me-1"></i>'; cls = 'text-primary'; }
        if (kind === 'success') { icon = '<i class="bi bi-check2-circle me-1"></i>';   cls = 'text-success'; }
        if (kind === 'error')   { icon = '<i class="bi bi-exclamation-triangle me-1"></i>'; cls = 'text-danger'; }
        el.className = 'disp-status-icon small px-1 ' + cls;
        el.innerHTML = icon + (message || '');
        el.classList.remove('d-none');
        if (kind !== 'saving') {
            setTimeout(function () { el.classList.add('d-none'); el.innerHTML = ''; }, 2200);
        }
    }

    // Toast global de confirmação (um por vez, pilha).
    function ensureToastWrap() {
        var w = document.querySelector('.disp-toast-wrap');
        if (w) { return w; }
        w = document.createElement('div');
        w.className = 'disp-toast-wrap';
        document.body.appendChild(w);
        return w;
    }
    function showToast(kind, message) {
        var wrap = ensureToastWrap();
        var div = document.createElement('div');
        var bg = kind === 'success' ? 'bg-success' : (kind === 'error' ? 'bg-danger' : 'bg-primary');
        var icon = kind === 'success'
            ? '<i class="bi bi-check-circle-fill me-2"></i>'
            : (kind === 'error' ? '<i class="bi bi-x-circle-fill me-2"></i>' : '<i class="bi bi-info-circle-fill me-2"></i>');
        div.className = 'disp-toast alert ' + bg + ' text-white shadow-sm border-0 rounded-3';
        div.setAttribute('role', 'status');
        div.innerHTML = icon + message;
        wrap.appendChild(div);
        // Trigger transition no próximo frame.
        requestAnimationFrame(function () { requestAnimationFrame(function () { div.classList.add('show'); }); });
        setTimeout(function () {
            div.classList.remove('show');
            setTimeout(function () { if (div.parentNode) { div.parentNode.removeChild(div); } }, 250);
        }, 2400);
    }

    function updateToggleVisuals(form, isDisponivel, keepSemestre) {
        var sw = form.querySelector('.disp-switch');
        var badge = form.querySelector('.disp-form > .disp-toggle-wrap > span.badge');
        // Fallback: caso o primeiro span não seja o badge (ordem flex diferente).
        if (!badge) { badge = form.querySelector('.disp-toggle-wrap span.badge.bg-success, .disp-toggle-wrap span.badge.bg-danger'); }
        var badgeIcon = badge ? badge.querySelector('.disp-badge-icon') : null;
        var badgeText = badge ? badge.querySelector('.disp-badge-text') : null;
        var motivo = form.querySelector('.disp-motivo');

        var sem = keepSemestre ? '' : getSemestreLabel(form);
        var tooltip = isDisponivel ? fmt(STR_TOOLTIP_AVAIL_FMT, sem) : fmt(STR_TOOLTIP_UNAVAIL_FMT, sem);

        if (sw) {
            // Não forçar o .checked aqui se o usuário acabou de mudar; o DOM
            // já está correto, este método é para sincronizar o restante.
            // Apenas atualiza tooltip.
            var s = window.bootstrap ? bootstrap.Tooltip.getInstance(sw) : null;
            sw.setAttribute('title', tooltip);
            if (s) { s.dispose(); }
        }
        if (badge) {
            badge.classList.remove('bg-success', 'bg-danger');
            badge.classList.add(isDisponivel ? 'bg-success' : 'bg-danger');
            if (badgeIcon) { badgeIcon.innerHTML = isDisponivel ? STR_SAVE_ICON : STR_UNSAVE_ICON; }
            if (badgeText) { badgeText.textContent = isDisponivel ? STR_SAVE_LABEL : STR_UNSAVE_LABEL; }
            badge.setAttribute('title', tooltip);
            var b = window.bootstrap ? bootstrap.Tooltip.getInstance(badge) : null;
            if (b) { b.dispose(); }
            badge.setAttribute('aria-label', isDisponivel ? STR_SAVE_LABEL : STR_UNSAVE_LABEL);
        }
        if (motivo) {
            motivo.style.display = isDisponivel ? 'none' : '';
            motivo.setAttribute('aria-expanded', isDisponivel ? 'false' : 'true');
        }
        // Re-inicializa tooltips do Bootstrap se disponível.
        if (window.bootstrap && typeof window.bootstrap.Tooltip === 'function') {
            [sw, badge].forEach(function (el) {
                if (el) { new bootstrap.Tooltip(el, { boundary: 'clippingParents' }); }
            });
        }
    }

    // Submissão AJAX com fallback.
    function submitFormAjax(form, then) {
        if (!form || typeof window.fetch !== 'function' || typeof window.FormData !== 'function') {
            // Fallback: submissão normal via POST (sem AJAX).
            then && then(false);
            form.submit();
            return false;
        }
        try {
            var data = new FormData(form);
            // Garante que a flag _ajax esteja presente (ainda que o formulário
            // tenha sido enviado sem ela, por exemplo por um submit manual).
            if (!data.has('_ajax')) { data.append('_ajax', '1'); }
            var url = form.getAttribute('action') || window.location.href;
            form.classList.add('is-saving');
            setStatusInline(form, 'saving', <?= json_encode(__('Salvando...')) ?>);
            window.fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: data,
            }).then(function (resp) {
                return resp.text().then(function (t) {
                    var json = null;
                    try { json = JSON.parse(t); } catch (_) {}
                    return { ok: resp.ok && json && json.ok === true, status: resp.status, json: json };
                });
            }).then(function (res) {
                form.classList.remove('is-saving');
                if (res.ok) {
                    // Êxito: animar + toast.
                    form.classList.remove('is-flash-success');
                    // Force reflow para reiniciar a animação.
                    void form.offsetWidth;
                    form.classList.add('is-flash-success');
                    setTimeout(function () { form.classList.remove('is-flash-success'); }, 1000);
                    setStatusInline(form, 'success', res.json && res.json.message ? res.json.message : <?= json_encode(__('Salvo')) ?>);
                    showToast('success', res.json && res.json.message ? res.json.message : <?= json_encode(__('Disponibilidade atualizada com sucesso.')) ?>);
                    then && then(true, res.json || {});
                } else {
                    form.classList.remove('is-flash-error');
                    void form.offsetWidth;
                    form.classList.add('is-flash-error');
                    setTimeout(function () { form.classList.remove('is-flash-error'); }, 1000);
                    var msg = res.json && res.json.message ? res.json.message : <?= json_encode(__('Não foi possível atualizar a disponibilidade.')) ?>;
                    setStatusInline(form, 'error', msg);
                    showToast('error', msg);
                    then && then(false, res.json || {});
                }
            }).catch(function () {
                form.classList.remove('is-saving');
                // Erro de rede: fallback para submissão normal (garante quebra).
                then && then(false);
                form.submit();
            });
            return true;
        } catch (_) {
            then && then(false);
            form.submit();
            return false;
        }
    }

    function hookOneForm(form) {
        var sw = form.querySelector('.disp-switch');
        var motivo = form.querySelector('.disp-motivo');
        var btnSave = form.querySelector('.disp-save');
        var btnCancel = form.querySelector('.disp-cancel');
        var wasDisponivelOnLoad = sw ? sw.checked : true;

        // Lembrete do estado anterior (antes do toggle) para poder reverter
        // caso o envio falhe e a página não recarregue.
        form._lastState = { disponivel: wasDisponivelOnLoad };

        // 1) Intercepta o submit padrão para dar preferência ao AJAX.
        form.addEventListener('submit', function (evt) {
            // Se já estivermos salvando, bloqueie disparos duplicados.
            if (form.classList.contains('is-saving')) { evt.preventDefault(); return; }
            evt.preventDefault();
            submitFormAjax(form, function (ok, data) {
                if (ok) {
                    var novo = typeof data.disponivel === 'boolean' ? data.disponivel : sw.checked;
                    form._lastState.disponivel = novo;
                    updateToggleVisuals(form, novo);
                    if (novo && motivo) {
                        // Garante que motivo fique vazio e fechado após salvar.
                        var input = motivo.querySelector('input[name="motivo"]');
                        if (input) { input.value = ''; }
                    }
                }
            });
        });

        // 2) Troca no switch (form-check). Semântica:
        //    - Ligado → Disponível: salva imediatamente, fecha o motivo.
        //    - Desligado → Indisponível: abre o painel do motivo para o usuário
        //      confirmar/salvar (opcionalmente preenchendo um motivo).
        if (sw) {
            sw.addEventListener('change', function () {
                if (form.classList.contains('is-saving')) {
                    sw.checked = !sw.checked;
                    return;
                }
                var agoraDisponivel = sw.checked;
                if (agoraDisponivel) {
                    // Atualiza UI no cliente, depois envia via AJAX (evento submit).
                    updateToggleVisuals(form, true);
                    // Previne submit duplicado via change + via submit().
                    form.requestSubmit ? form.requestSubmit() : form.submit();
                } else {
                    // Abre o motivo sem salvar ainda. Deixa o usuário digitar e
                    // clicar no botão "Salvar". Se desistir, botão "Cancelar"
                    // volta para Disponível (e salva essa reversão).
                    updateToggleVisuals(form, false);
                    var input = motivo ? motivo.querySelector('input[name="motivo"]') : null;
                    if (input) { setTimeout(function () { input.focus(); }, 20); }
                }
            });
        }

        // 3) Botão "Cancelar" dentro do motivo: volta para Disponível e salva.
        if (btnCancel) {
            btnCancel.addEventListener('click', function () {
                if (form.classList.contains('is-saving')) { return; }
                if (sw) { sw.checked = true; }
                updateToggleVisuals(form, true);
                form.requestSubmit ? form.requestSubmit() : form.submit();
            });
        }

        // 4) O botão Salvar (dentro do motivo) já dispara form.submit() naturalmente,
        //    então o handler de submit() acima já cuida do fluxo AJAX.
        if (btnSave) {
            btnSave.addEventListener('click', function () {
                if (sw) { sw.checked = false; } // Garante que o valor chegue como indisponível.
            });
        }
    }

    // CSS para girar o ícone "spin".
    var spinStyle = document.createElement('style');
    spinStyle.textContent = '.spin { display: inline-block; animation: dispSpin 900ms linear infinite; } @keyframes dispSpin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }';
    document.head.appendChild(spinStyle);

    // Comportamento intuitivo do multi-select de status:
    //   - Selecionar "Todos" desmarca os status específicos.
    //   - Selecionar um status específico desmarca "Todos".
    // O backend já reconhece a presença do sentinela 'all' como "exibir tudo".
    function hookStatusMultiSelect() {
        var sel = document.querySelector('select.docentes-status-multi');
        if (!sel) { return; }
        var sentinel = sel.getAttribute('data-all-sentinel') || 'all';
        var lastUserSelectedAll = Array.from(sel.selectedOptions).some(function (o) { return o.value === sentinel; });

        var updateFromAllChoice = function () {
            // Se "Todos" estiver marcado, desmarque os específicos.
            var allOpt = sel.querySelector('option[value="' + sentinel + '"]');
            if (allOpt && allOpt.selected) {
                Array.from(sel.options).forEach(function (op) {
                    if (op.value !== sentinel) { op.selected = false; }
                });
            }
        };
        var updateFromSpecificChoice = function () {
            var allOpt = sel.querySelector('option[value="' + sentinel + '"]');
            var hasSpecific = Array.from(sel.options).some(function (op) {
                return op.value !== sentinel && op.selected;
            });
            if (hasSpecific && allOpt) { allOpt.selected = false; }
            // Se nada estiver selecionado, marque "Todos" como padrão.
            var anySelected = Array.from(sel.options).some(function (op) { return op.selected; });
            if (!anySelected && allOpt) { allOpt.selected = true; }
        };

        // Estado inicial: se algum específico + all estiverem marcados juntos,
        // deixe apenas "Todos" (pois foi o último que o usuário explicitou querer,
        // mas na dúvida use o sentinel já que é o mais conservador).
        updateFromAllChoice();

        sel.addEventListener('change', function () {
            // Detecta se a opção "Todos" ficou marcada nesta interação.
            var allOpt = sel.querySelector('option[value="' + sentinel + '"]');
            var allIsNow = allOpt ? allOpt.selected : false;
            if (allIsNow && !lastUserSelectedAll) {
                updateFromAllChoice();
            } else {
                updateFromSpecificChoice();
            }
            lastUserSelectedAll = allOpt ? allOpt.selected : false;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa tooltips do Bootstrap.
        if (window.bootstrap && typeof window.bootstrap.Tooltip === 'function') {
            bootstrap.Tooltip.getOrCreateInstance = bootstrap.Tooltip.getOrCreateInstance || function (el, opts) {
                var i = bootstrap.Tooltip.getInstance(el);
                if (i) return i;
                return new bootstrap.Tooltip(el, opts || {});
            };
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                bootstrap.Tooltip.getOrCreateInstance(el, { boundary: 'clippingParents' });
            });
        }

        hookStatusMultiSelect();
        document.querySelectorAll('.disp-form').forEach(hookOneForm);
    });
})();
</script>
