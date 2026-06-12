<div class="disciplinas form content">
    <?= $this->Form->create($disciplina) ?>
    <fieldset>
        <legend><?= __('Adicionar Disciplina') ?></legend>
        <?php
            echo $this->Form->control('codigo', ['class' => 'form-control', 'label' => 'Código']);
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('carga_horaria', ['class' => 'form-control', 'label' => 'Carga Horária']);
            echo $this->Form->control('ementa', ['class' => 'form-control', 'label' => 'Ementa', 'type' => 'textarea', 'rows' => 4]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
