<?php
declare(strict_types=1);
$departamentos = ['Fundamentos' => 'Fundamentos', 'Metodos' => 'Métodos', 'Políticas' => 'Políticas', 'Interdepartamental' => 'Interdepartamental', 'Outros' => 'Outros'];
?>
<div class="container">
    <?= $this->Form->create($disciplina) ?>
    <fieldset>
        <legend><?= __('Editar Disciplina') ?></legend>
        <?php
            echo $this->Form->control('codigo', ['label' => 'Código']);
            echo $this->Form->control('disciplina');
            echo $this->Form->control('creditos', ['label' => 'Créditos']);
            echo $this->Form->control('carga_horaria', ['label' => 'Carga Horária']);
            echo $this->Form->control('periodo_diurno', ['label' => 'Período Diurno']);
            echo $this->Form->control('periodo_noturno', ['label' => 'Período Noturno']);
            echo $this->Form->control('requisitos', ['label' => 'Requisitos']);
            echo $this->Form->control('optativa');
            echo $this->Form->control('departamento', ['options' => $departamentos]);
            echo $this->Form->control('observacoes', ['label' => 'Observações', 'type' => 'textarea', 'rows' => 4]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
