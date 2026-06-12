<div class="docentes form content">
    <?= $this->Form->create($docente) ?>
    <fieldset>
        <legend><?= __('Adicionar Docente') ?></legend>
        <?php
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('titulo', ['class' => 'form-control', 'label' => 'Título']);
            echo $this->Form->control('departamento', ['class' => 'form-control', 'label' => 'Departamento']);
            echo $this->Form->control('email', ['class' => 'form-control', 'label' => 'Email']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
