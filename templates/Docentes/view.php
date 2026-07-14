<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="col-auto mb-3">
        <?= $this->Html->link(__('Docentes'), ['controller' => 'Docentes', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
    </div>
    <h3><?= h($docente->nome) ?></h3>
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
            <table class="table table-striped">
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($docente->id) ?></td>
                </tr>
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

    <div class="row mt-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= __('Disponibilidade por Semestre') ?></h4>
                <?= $this->Html->link(
                    __('Adicionar'),
                    ['controller' => 'DocenteDisponibilidades', 'action' => 'add', '?' => ['docente_id' => $docente->id]],
                    ['class' => 'btn btn-sm btn-primary']
                ) ?>
            </div>
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

    <div class="row mt-3">
        <div class="col">
            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $docente->id], ['class' => 'btn btn-warning']) ?>
            <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
