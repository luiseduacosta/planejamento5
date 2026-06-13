<div class="salas form content">
    <?= $this->Form->create($sala) ?>
    <fieldset>
        <legend><?= __('Adicionar Sala') ?></legend>
        <?php
            echo $this->Form->control('sala', ['class' => 'form-control', 'label' => 'Sala']);
            echo $this->Form->control('localizacao', ['class' => 'form-control', 'label' => 'Localização']);
            echo $this->Form->control('lotacao', ['class' => 'form-control', 'label' => 'Lotacao', 'placeholder' => 'Capacidade']);
            echo $this->Form->control('recursos', ['class' => 'form-control', 'label' => 'Recursos', 'placeholder' => 'Equipamentos']);
            echo $this->Form->control('observacoes', ['class' => 'form-control', 'label' => 'Observacoes', 'placeholder' => 'Observacoes']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
