<div class="dias form content">
    <?= $this->Form->create($dia) ?>
    <fieldset>
        <legend><?= __('Adicionar Dia') ?></legend>
        <?php
            echo $this->Form->control('dia', ['class' => 'form-control', 'label' => 'Dia']);
            echo $this->Form->control('ordem', ['class' => 'form-control', 'label' => 'Ordem']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
