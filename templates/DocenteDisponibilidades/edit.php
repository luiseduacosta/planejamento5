<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($docenteDisponibilidade) ?>
    <fieldset>
        <legend><?= __('Editar Disponibilidade') ?></legend>
        <?php
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione --', 'label' => 'Docente']);
            echo $this->Form->control('configuraplanejamento_id', ['options' => $configuracoes, 'empty' => '-- Selecione --', 'label' => 'Semestre']);
            echo $this->Form->control('disponivel', ['label' => 'Disponível', 'type' => 'checkbox']);
            echo $this->Form->control('motivo');
            echo $this->Form->control('observacoes', ['label' => 'Observações', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index', '?' => ['docente_id' => $docenteDisponibilidade->docente_id]], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
