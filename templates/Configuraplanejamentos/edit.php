<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($configuracao) ?>
    <fieldset>
        <legend><?= __('Editar Configuração') ?></legend>
        <?php
            echo $this->Form->control('nome');
            echo $this->Form->control('semestre', ['placeholder' => 'Ex: 2024.1']);
            echo $this->Form->control('versao', ['label' => 'Versão', 'type' => 'number', 'min' => 1]);
            echo $this->Form->control('ativo', ['label' => ['text' => 'Ativo']]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
