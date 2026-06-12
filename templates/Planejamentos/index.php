<div class="planejamentos index content">
    <div class="row">
        <div class="col"><h3><?= __('Planejamentos') ?></h3></div>
        <div class="col-auto mb-3"><?= $this->Html->link(__('Novo Planejamento'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th><?= $this->Paginator->sort('id') ?></th><th><?= $this->Paginator->sort('disciplina_id') ?></th><th><?= $this->Paginator->sort('docente_id') ?></th><th><?= $this->Paginator->sort('configuraplanejamento_id') ?></th><th><?= $this->Paginator->sort('dia_id') ?></th><th><?= $this->Paginator->sort('horario_id') ?></th><th><?= $this->Paginator->sort('sala_id') ?></th><th class="actions"><?= __('Ações') ?></th></tr></thead>
            <tbody>
                <?php foreach ($planejamentos as $planejamento): ?>
                <tr>
                    <td><?= $this->Number->format($planejamento->id) ?></td>
                    <td><?= $planejamento->hasValue('disciplina') ? h($planejamento->disciplina->nome) : '-' ?></td>
                    <td><?= $planejamento->hasValue('docente') ? h($planejamento->docente->nome) : '-' ?></td>
                    <td><?= $planejamento->hasValue('configuraplanejamento') ? h($planejamento->configuraplanejamento->semestre) : '-' ?></td>
                    <td><?= $planejamento->hasValue('dia') ? h($planejamento->dia->dia) : '-' ?></td>
                    <td><?= $planejamento->hasValue('horario') ? h($planejamento->horario->horario) : '-' ?></td>
                    <td><?= $planejamento->hasValue('sala') ? h($planejamento->sala->sala) : '-' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $planejamento->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $planejamento->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $planejamento->id], ['confirm' => __('Tem certeza?'), 'class' => 'btn btn-sm btn-danger']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator"><?= $this->Paginator->first('<< '.__('primeiro')) ?><?= $this->Paginator->prev('< '.__('anterior')) ?><?= $this->Paginator->numbers() ?><?= $this->Paginator->next(__('próximo').' >') ?><?= $this->Paginator->last(__('último').' >>') ?>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}')) ?></p>
    </div>
</div>
