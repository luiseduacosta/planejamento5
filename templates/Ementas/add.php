<div class="ementas form content">
    <?= $this->Form->create($ementa) ?>
    <fieldset><legend><?= __('Adicionar Ementa') ?></legend>
        <?php
            echo $this->Form->control('configuraplanejamento_id', ['options' => $configuraplanejamentos, 'empty' => '-- Selecione a Disciplina --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('disciplina_id', ['options' => $disciplinas, 'empty' => '-- Selecione a Disciplina --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('optativa_id', ['options' => $optativas, 'empty' => '-- Selecione a Disciplina --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione a Disciplina --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('titulo', ['class' => 'form-control', 'label' => 'Titulo', 'type' => 'text']);
            echo $this->Form->control('ementa', ['class' => 'form-control', 'label' => 'Conteúdo Programático', 'type' => 'textarea', 'rows' => 6]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
