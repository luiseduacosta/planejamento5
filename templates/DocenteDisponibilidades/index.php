<div class="docenteDisponibilidades index content">
    <div class="row">
        <div class="col"><h3><?= __('Disponibilidades de Docentes') ?></h3></div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Nova Disponibilidade'), ['action' => 'add', '?' => $docenteId ? ['docente_id' => $docenteId] : []], ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-auto mb-3">
            <?= $this->Html->link(__('Docentes'), ['controller' => 'Docentes', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('docente_id', __('Docente')) ?></th>
                    <th><?= __('Semestre') ?></th>
                    <th><?= $this->Paginator->sort('disponivel', __('Disponível')) ?></th>
                    <th><?= $this->Paginator->sort('motivo', __('Motivo')) ?></th>
                    <th><?= __('Observações') ?></th>
                    <th class="actions"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disponibilidades as $disp): ?>
                    <tr>
                        <td>
                            <?= $disp->hasValue('docente')
                                ? $this->Html->link(h($disp->docente->nome), ['controller' => 'Docentes', 'action' => 'view', $disp->docente->id])
                                : '-' ?>
                        </td>
                        <td><?= $disp->hasValue('configuraplanejamento') ? h($disp->configuraplanejamento->semestre) : '-' ?></td>
                        <td><?= $disp->disponivel ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?></td>
                        <td><?= h($disp->motivo) ?></td>
                        <td><?= nl2br(h($disp->observacoes)) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('Ver'), ['action' => 'view', $disp->id], ['class' => 'btn btn-sm btn-info']) ?>
                            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $disp->id], ['class' => 'btn btn-sm btn-warning']) ?>
                            <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $disp->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('próximo') . ' >') ?>
            <?= $this->Paginator->last(__('último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}} total')) ?></p>
    </div>
</div>

