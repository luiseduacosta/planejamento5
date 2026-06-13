<div class="salas view content">
    <h3><?= h($sala->sala) ?></h3>
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <tr>
                    <th><?= __('ID') ?></th>
                    <td><?= $this->Number->format($sala->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sala') ?></th>
                    <td><?= h($sala->sala) ?></td>
                </tr>
                <tr>
                    <th><?= __('Localizacao') ?></th>
                    <td><?= h($sala->localizacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lotacao') ?></th>
                    <td><?= $this->Number->format($sala->lotacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Recursos') ?></th>
                    <td><?= h($sala->recursos) ?></td>
                </tr>
                <tr>
                    <th><?= __('Observacoes') ?></th>
                    <td><?= h($sala->observacoes) ?></td>
                </tr>
                <tr>
                    <th><?= __('Criado') ?></th>
                    <td><?= h($sala->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modificado') ?></th>
                    <td><?= h($sala->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <?= $this->Html->link(__('Editar'), ['action' => 'edit', $sala->id], ['class' => 'btn btn-warning']) ?>
            <?= $this->Html->link(__('Voltar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
