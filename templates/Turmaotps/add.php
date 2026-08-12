<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($turmaotp) ?>
    <fieldset>
        <legend><?= __('Adicionar Turma de OTP') ?></legend>
        <?php
            echo $this->Form->control('configuraplanejamento_id', [
                'options' => $configuracoes,
                'empty' => '-- Selecione --',
                'label' => 'Semestre',
                'default' => $selectedConfiguracaoId ?? null,
                'onchange' => "window.location = '" . $this->Url->build(['action' => 'add']) . "?configuraplanejamento_id=' + this.value",
            ]);
            echo $this->Form->control('turmaotp', ['label' => 'Turma', 'placeholder' => 'Digite o nome da turma: Ex: I - Diurno - Professor(a)']);
            echo $this->Form->control('turno', [
                'options' => ['diurno' => __('Diurno'), 'noturno' => __('Noturno'), 'vespertino' => __('Vespertino')],
                'empty' => '-- Selecione --',
                'label' => 'Turno',
            ]);
            echo $this->Form->control('periodo', [
                'options' => array_combine(range(1, 10), range(1, 10)),
                'empty' => '-- Selecione --',
                'label' => 'Período',
            ]);
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione --', 'label' => 'Docente']);
            echo $this->Form->control('dia_id', ['options' => $dias, 'empty' => '-- Selecione --', 'label' => 'Dia']);
            echo $this->Form->control('horario_id', ['options' => $horarios, 'empty' => '-- Selecione --', 'label' => 'Horário']);
            echo $this->Form->control('sala_id', ['options' => $salas, 'empty' => '-- Selecione --', 'label' => 'Sala']);
            echo $this->Form->control('observacoes', ['label' => 'Observações', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
