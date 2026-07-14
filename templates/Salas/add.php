<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($sala) ?>
    <fieldset>
        <legend><?= __('Adicionar Sala') ?></legend>
        <?php
            echo $this->Form->control('sala', ['label' => 'Sala']);
            echo $this->Form->control('localizacao', ['label' => 'Localização']);
            echo $this->Form->control('lotacao', ['label' => 'Lotação', 'placeholder' => 'Capacidade']);
            echo $this->Form->control('recursos', ['label' => 'Recursos', 'placeholder' => 'Equipamentos']);
            echo $this->Form->control('observacoes', ['label' => 'Observações', 'placeholder' => 'Observações']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
