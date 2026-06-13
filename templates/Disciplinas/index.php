<div class="disciplinas index content">
    <div class="row">
        <div class="col"><h3><?= __('Disciplinas') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Nova Disciplina'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('codigo') ?></th>
                    <th><?= $this->Paginator->sort('disciplina') ?></th>
                    <th><?= $this->Paginator->sort('carga_horaria') ?></th>
                    <th><?= $this->Paginator->sort('departamento') ?></th>
                    <th class="actions"><?= __('Ações') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disciplinas as $disciplina): ?>
                <tr>
                    <td><?= $disciplina->id ?></td>
                    <td><?= h($disciplina->codigo) ?></td>
                    <td><?= $this->Html->link($disciplina->disciplina, ['action' => 'view', $disciplina->id]) ?></td>
                    <td><?= $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '-' ?></td>
                    <td><?= h($disciplina->departamento) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $disciplina->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $disciplina->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
        <?= $this->Paginator->prev('< ' . __('anterior')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('próximo') . ' >') ?>
        <?= $this->Paginator->last(__('último') . ' >>') ?>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </div>
</div>
