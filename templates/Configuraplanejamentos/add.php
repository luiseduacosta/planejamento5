<div class="configuraplanejamentos form content">
    <?= $this->Form->create($configuracao) ?>
    <fieldset><legend><?= __('Adicionar Configuração') ?></legend>
        <?php
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('semestre', ['class' => 'form-control', 'label' => 'Semestre', 'placeholder' => 'Ex: 2024.1']);
            echo $this->Form->control('versao', ['class' => 'form-control', 'label' => 'Versão', 'type' => 'number', 'min' => 1]);
            echo $this->Form->control('ativo', ['class' => 'form-check-input', 'label' => ['text' => 'Ativo', 'class' => 'form-check-label']]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
