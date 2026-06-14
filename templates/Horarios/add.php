<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($horario) ?>
    <fieldset>
        <legend><?= __('Adicionar Horário') ?></legend>
        <?php
            echo $this->Form->control('horario', ['label' => 'Horário']);
            echo $this->Form->control('ordem');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
