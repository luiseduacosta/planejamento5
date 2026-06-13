<div class="docenteDisponibilidades form content">
    <?= $this->Form->create($docenteDisponibilidade) ?>
    <fieldset>
        <legend><?= __('Editar Disponibilidade') ?></legend>
        <?php
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Docente']);
            echo $this->Form->control('configuraplanejamento_id', ['options' => $configuracoes, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Semestre']);
            echo $this->Form->control('disponivel', ['class' => 'form-check-input', 'label' => 'Disponível', 'type' => 'checkbox']);
            echo $this->Form->control('motivo', ['class' => 'form-control', 'label' => 'Motivo']);
            echo $this->Form->control('observacoes', ['class' => 'form-control', 'label' => 'Observações', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>

