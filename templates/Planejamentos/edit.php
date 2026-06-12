<div class="planejamentos form content">
    <?= $this->Form->create($planejamento) ?>
    <fieldset><legend><?= __('Editar Planejamento') ?></legend>
        <?php
            echo $this->Form->control('disciplina_id', ['options' => $disciplinas, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Disciplina']);
            echo $this->Form->control('docente_id', ['options' => $docentes, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Docente']);
            echo $this->Form->control('configuraplanejamento_id', ['options' => $configuracoes, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Semestre']);
            echo $this->Form->control('dia_id', ['options' => $dias, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Dia']);
            echo $this->Form->control('horario_id', ['options' => $horarios, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Horário']);
            echo $this->Form->control('sala_id', ['options' => $salas, 'empty' => '-- Selecione --', 'class' => 'form-select', 'label' => 'Sala']);
            echo $this->Form->control('carga_horaria', ['class' => 'form-control', 'label' => 'Carga Horária']);
            echo $this->Form->control('alunos', ['class' => 'form-control', 'label' => 'Número de Alunos']);
            echo $this->Form->control('observacoes', ['class' => 'form-control', 'label' => 'Observações', 'type' => 'textarea', 'rows' => 3]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
