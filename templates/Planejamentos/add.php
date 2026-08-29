<?php
declare(strict_types=1);
?>
<div class="container">
    <?= $this->Form->create($planejamento) ?>
    <fieldset><legend><?= __('Adicionar Planejamento') ?></legend>
        <?php
            echo $this->Form->control('disciplina_id', ['options' => $disciplinas, 'empty' => '-- Selecione --', 'label' => 'Disciplina']);
            echo $this->Form->control('configuraplanejamento_id', [
                'options' => $configuracoes,
                'empty' => '-- Selecione --',
                'label' => 'Semestre',
                'default' => $selectedConfiguracaoId ?? null,
                'onchange' => "window.location = '" . $this->Url->build(['action' => 'add']) . "?configuraplanejamento_id=' + this.value",
            ]);
            if (!empty($professoresFilteredByDisponibilidade)) {
                echo '<div class="form-text">Professores filtrados por disponibilidade no semestre selecionado (somente status Ativo).</div>';
            } else {
                echo '<div class="form-text">Selecione um semestre para filtrar professores por disponibilidade (somente status Ativo).</div>';
            }
            echo $this->Form->control('docente_id', ['options' => $professores, 'empty' => '-- Selecione --', 'label' => 'Professor']);
            echo $this->Form->control('dia_id', ['options' => $dias, 'empty' => '-- Selecione --', 'label' => 'Dia']);
            echo $this->Form->control('horario_id', ['options' => $horarios, 'empty' => '-- Selecione --', 'label' => 'Horário']);
            echo $this->Form->control('sala_id', ['options' => $salas, 'empty' => '-- Selecione --', 'label' => 'Sala']);
            echo $this->Form->control('observacoes', ['label' => 'Observações', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
