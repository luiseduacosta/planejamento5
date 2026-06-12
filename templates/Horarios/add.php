<div class="horarios form content">
    <?= $this->Form->create($horario) ?>
    <fieldset>
        <legend><?= __('Adicionar Horário') ?></legend>
        <?php
            echo $this->Form->control('horario', ['class' => 'form-control', 'label' => 'Horário']);
            echo $this->Form->control('ordem', ['class' => 'form-control', 'label' => 'Ordem']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
