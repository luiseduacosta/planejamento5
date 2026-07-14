<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($dia) ?>
    <fieldset>
        <legend><?= __('Editar Dia') ?></legend>
        <?php
            echo $this->Form->control('dia', ['label' => 'Dia']);
            echo $this->Form->control('ordem');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
