<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($ementa) ?>
    <fieldset><legend><?= __('Adicionar Ementa') ?></legend>
        <?php
            echo $this->Form->control('configuraplanejamento_id', ['options' => $configuraplanejamentos, 'empty' => '-- Selecione a Disciplina --', 'label' => 'Disciplina']);
            echo $this->Form->control('disciplina_id', ['options' => $disciplinas, 'empty' => '-- Selecione a Disciplina --', 'label' => 'Disciplina']);
            echo $this->Form->control('optativa_id', ['options' => $optativas, 'empty' => '-- Selecione a Disciplina --', 'label' => 'Disciplina']);
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione a Disciplina --', 'label' => 'Disciplina']);
            echo $this->Form->control('titulo', ['label' => 'Título', 'type' => 'text']);
            echo $this->Form->control('ementa', ['label' => 'Conteúdo Programático', 'type' => 'textarea', 'rows' => 6]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
