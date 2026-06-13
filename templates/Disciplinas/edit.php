<?php
$departamentos = ['Fundamentos' => 'Fundamentos', 'Metodos' => 'Métodos', 'Políticas' => 'Políticas', 'Interdepartamental' => 'Interdepartamental', 'Outros' => 'Outros'];
?>
<div class="disciplinas form content">
    <?= $this->Form->create($disciplina) ?>
    <fieldset>
        <legend><?= __('Editar Disciplina') ?></legend>
        <?php
            echo $this->Form->control('codigo', ['class' => 'form-control', 'label' => 'Código']);
            echo $this->Form->control('disciplina', ['class' => 'form-control', 'label' => 'Disciplina']);
            echo $this->Form->control('creditos', ['class' => 'form-control', 'label' => 'Créditos']);
            echo $this->Form->control('carga_horaria', ['class' => 'form-control', 'label' => 'Carga Horária']);
            echo $this->Form->control('periodo_diurno', ['class' => 'form-control', 'label' => 'Período Diurno']);
            echo $this->Form->control('periodo_noturno', ['class' => 'form-control', 'label' => 'Período Noturno']);
            echo $this->Form->control('requisitos', ['class' => 'form-control', 'label' => 'Requisitos']);
            echo $this->Form->control('optativa', ['class' => 'form-control', 'label' => 'Optativa']);
            echo $this->Form->control('departamento', ['class' => 'form-control', 'label' => 'Departamento', 'options' => $departamentos]);
            echo $this->Form->control('observacoes', ['class' => 'form-control', 'label' => 'Observacoes', 'type' => 'textarea', 'rows' => 4]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
