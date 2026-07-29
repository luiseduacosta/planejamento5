<?php
declare(strict_types=1);
?>
<div class="container">
    <div class="row">
        <div class="col"><h3><?= __('Clonar Planejamentos entre Semestres') ?></h3></div>
    </div>
    <p class="text-muted">
        <?= __('Copia todos os planejamentos da configuração de origem para a de destino. O destino precisa estar vazio; se já tiver planejamentos, exclua-os antes de clonar.') ?>
    </p>

    <?= $this->Form->create(null, ['url' => ['action' => 'clonar']]) ?>
    <fieldset>
        <?= $this->Form->control('origem', [
            'type' => 'select',
            'options' => $configuracoes,
            'empty' => '-- Selecione a origem --',
            'default' => $origemId,
            'label' => __('Origem (copiar DE)'),
        ]) ?>
        <?= $this->Form->control('destino', [
            'type' => 'select',
            'options' => $configuracoes,
            'empty' => '-- Selecione o destino --',
            'default' => $destinoId,
            'label' => __('Destino (copiar PARA)'),
        ]) ?>
    </fieldset>
    <div class="mt-3">
        <?= $this->Form->button(__('Clonar Planejamentos'), ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
