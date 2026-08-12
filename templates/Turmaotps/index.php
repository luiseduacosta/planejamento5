<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col"><h3><?= __('Turmas de OTP') ?></h3></div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Nova Turma'), ['action' => 'add', '?' => $selectedConfiguraplanejamentoId !== null ? ['configuraplanejamento_id' => $selectedConfiguraplanejamentoId] : []], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <!-- Filtro: configuração (semestre) congelada na sessão até nova escolha -->
    <?php if (!$configuracoes->isEmpty()): ?>
    <div class="row mb-3">
        <div class="col-auto">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-flex align-items-center gap-2']) ?>
            <label class="form-label mb-0"><?= __('Filtrar por Configuração:') ?></label>
            <?= $this->Form->control('configuraplanejamento_id', [
                'options' => ['todos' => __('Todas as Configurações')] + $configuracoes->toArray(),
                'default' => $selectedConfiguraplanejamentoId,
                'label' => false,
                'onchange' => 'this.form.submit()',
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
        <?php if ($selectedConfiguraplanejamentoId !== null): ?>
        <div class="col-auto">
            <?= $this->Html->link(
                '<i class="bi bi-x-circle"></i> ' . __('Limpar Filtro'),
                ['action' => 'index', '?' => ['configuraplanejamento_id' => 'todos']],
                ['class' => 'btn btn-outline-secondary', 'escape' => false]
            ) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('Turmaotps.id', 'ID') ?></th>
                    <th><?= $this->Paginator->sort('Turmaotps.turmaotp', 'Turma') ?></th>
                    <th><?= $this->Paginator->sort('Configuraplanejamentos.semestre', 'Semestre') ?></th>
                    <th><?= $this->Paginator->sort('Turmaotps.turno', 'Turno') ?></th>
                    <th><?= $this->Paginator->sort('Turmaotps.periodo', 'Período') ?></th>
                    <th><?= $this->Paginator->sort('Docentes.nome', 'Docente') ?></th>
                    <th><?= $this->Paginator->sort('Dias.dia', 'Dia') ?></th>
                    <th><?= $this->Paginator->sort('Horarios.horario', 'Horário') ?></th>
                    <th><?= $this->Paginator->sort('Salas.sala', 'Sala') ?></th>
                    <th class="text-nowrap"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turmaotps as $turmaotp): ?>
                    <tr>
                        <td><?= $this->Number->format($turmaotp->id) ?></td>
                        <td><?= h($turmaotp->turmaotp) ?></td>
                        <td><?= $turmaotp->hasValue('configuraplanejamento') ? h($turmaotp->configuraplanejamento->semestre) : '-' ?></td>
                        <td><?= $turmaotp->turno !== null ? h(ucfirst($turmaotp->turno)) : '-' ?></td>
                        <td><?= $turmaotp->periodo !== null ? $this->Number->format($turmaotp->periodo) : '-' ?></td>
                        <td><?= $turmaotp->hasValue('docente') ? $this->Html->link(h($turmaotp->docente->nome), ['controller' => 'Docentes', 'action' => 'view', $turmaotp->docente->id]) : '-' ?></td>
                        <td><?= $turmaotp->hasValue('dia') ? h($turmaotp->dia->dia) : '-' ?></td>
                        <td><?= $turmaotp->hasValue('horario') ? h($turmaotp->horario->horario) : '-' ?></td>
                        <td><?= $turmaotp->hasValue('sala') ? h($turmaotp->sala->sala) : '-' ?></td>
                        <td class="text-nowrap">
                            <?= $this->Html->link(__('Ver'), ['action' => 'view', $turmaotp->id], ['class' => 'btn btn-sm btn-info']) ?>
                            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $turmaotp->id], ['class' => 'btn btn-sm btn-warning']) ?>
                            <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $turmaotp->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
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
