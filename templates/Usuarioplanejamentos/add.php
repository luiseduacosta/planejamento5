<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Adicionar Usuário') ?></legend>
        <?php
            echo $this->Form->control('email', ['type' => 'email']);
            echo $this->Form->control('nome');
            echo $this->Form->control('password', ['label' => 'Senha', 'value' => '']);
            echo $this->Form->control('role', [
                'label' => 'Perfil',
                'options' => [
                    'admin' => 'Administrador',
                    'editor' => 'Editor',
                    'viewer' => 'Visualizador'
                ]
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
