<div class="docenteDisponibilidades view content">
    <h3><?= __('Disponibilidade') ?></h3>
    <table class="table table-striped">
        <tr>
            <th><?= __('ID') ?></th>
            <td><?= $this->Number->format($docenteDisponibilidade->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Docente') ?></th>
            <td><?= $docenteDisponibilidade->hasValue('docente') ? h($docenteDisponibilidade->docente->nome) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Semestre') ?></th>
            <td><?= $docenteDisponibilidade->hasValue('configuraplanejamento') ? h($docenteDisponibilidade->configuraplanejamento->semestre) : '-' ?></td>
        </tr>
        <tr>
            <th><?= __('Disponível') ?></th>
            <td><?= $docenteDisponibilidade->disponivel ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?></td>
        </tr>
        <tr>
            <th><?= __('Motivo') ?></th>
            <td><?= h($docenteDisponibilidade->motivo) ?></td>
        </tr>
        <tr>
            <th><?= __('Observações') ?></th>
            <td><?= nl2br(h($docenteDisponibilidade->observacoes)) ?></td>
        </tr>
        <tr>
            <th><?= __('Criado') ?></th>
            <td><?= h($docenteDisponibilidade->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modificado') ?></th>
            <td><?= h($docenteDisponibilidade->modified) ?></td>
        </tr>
    </table>
    <div class="mt-3">
        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $docenteDisponibilidade->id], ['class' => 'btn btn-warning']) ?>
        <?= $this->Html->link(__('Voltar'), ['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

