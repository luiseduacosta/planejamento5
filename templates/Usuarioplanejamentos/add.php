<div class="usuarioplanejamentos form content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Adicionar Usuário') ?></legend>
        <?php
            echo $this->Form->control('email', ['class' => 'form-control', 'label' => 'Email', 'type' => 'email']);
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('password', ['class' => 'form-control', 'label' => 'Senha', 'value' => '']);
            echo $this->Form->control('role', [
                'class' => 'form-select', 
                'label' => 'Perfil',
                'options' => [
                    'admin' => 'Administrador',
                    'editor' => 'Editor',
                    'viewer' => 'Visualizador'
                ]
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
