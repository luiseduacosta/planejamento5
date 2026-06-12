<div class="salas form content">
    <?= $this->Form->create($sala) ?>
    <fieldset>
        <legend><?= __('Editar Sala') ?></legend>
        <?php
            echo $this->Form->control('sala', ['class' => 'form-control', 'label' => 'Sala']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
