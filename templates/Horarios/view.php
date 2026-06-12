<div class="horarios view content">
    <h3><?= h($horario->horario) ?></h3>
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($horario->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Horário') ?></th>
                    <td><?= h($horario->horario) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ordem') ?></th>
                    <td><?= $this->Number->format($horario->ordem) ?></td>
                </tr>
                <tr>
                    <th><?= __('Criado') ?></th>
                    <td><?= h($horario->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificado') ?></th>
                    <td><?= h($horario->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $horario->id], ['class' => 'btn btn-warning']) ?>
            <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
