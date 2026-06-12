<div class="configuraplanejamentos form content">
    <?= $this->Form->create($configuracao) ?>
    <fieldset><legend><?= __('Editar Configuração') ?></legend>
        <?php
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('semestre', ['class' => 'form-control', 'label' => 'Semestre', 'placeholder' => 'Ex: 2024.1']);
            echo $this->Form->control('ativo', ['class' => 'form-check-input', 'label' => ['text' => 'Ativo', 'class' => 'form-check-label']]);
            echo $this->Form->control('descricao', ['class' => 'form-control', 'label' => 'Descrição', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
