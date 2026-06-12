<div class="ementas form content">
    <?= $this->Form->create($ementa) ?>
    <fieldset><legend><?= __('Adicionar Ementa') ?></legend>
        <?php
            echo $this->Form->control('disciplina_id', ['options' => $disciplinas, 'empty' => '-- Selecione a Disciplina --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('conteudo_programatico', ['class' => 'form-control', 'label' => 'Conteúdo Programático', 'type' => 'textarea', 'rows' => 6]);
            echo $this->Form->control('objetivos', ['class' => 'form-control', 'label' => 'Objetivos', 'type' => 'textarea', 'rows' => 4]);
            echo $this->Form->control('bibliografia_basica', ['class' => 'form-control', 'label' => 'Bibliografia Básica', 'type' => 'textarea', 'rows' => 4]);
            echo $this->Form->control('bibliografia_complementar', ['class' => 'form-control', 'label' => 'Bibliografia Complementar', 'type' => 'textarea', 'rows' => 4]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
