<div class="optativas index content">
    <div class="row">
        <div class="col"><h3><?= __('Optativas') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Nova Optativa'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('codigo') ?></th>
                    <th><?= $this->Paginator->sort('disciplina') ?></th>
                    <th><?= $this->Paginator->sort('carga_horaria') ?></th>
                    <th><?= $this->Paginator->sort('departamento') ?></th>
            <tbody>
                <?php foreach ($optativas as $optativa): ?>
                <tr>
                    <td><?= $this->Number->format($optativa->id) ?></td>
                    <td><?= h($optativa->codigo) ?></td>
                    <td><?= h($optativa->disciplina) ?></td>
                    <td><?= $optativa->carga_horaria ? $optativa->carga_horaria . 'h' : '-' ?></td>
                    <td><?= $optativa->departamento ? $optativa->departamento : '-' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $optativa->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $optativa->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $optativa->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <?= $this->Paginator->first('<< '.__('primeiro')) ?>
        <?= $this->Paginator->prev('< '.__('anterior')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('próximo').' >') ?>
        <?= $this->Paginator->last(__('último').' >>') ?>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </div>
</div>
