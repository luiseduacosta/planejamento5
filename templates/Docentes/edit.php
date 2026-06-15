<?php
declare(strict_types=1);
$departamentos = ['Fundamentos' => 'Fundamentos', 'Métodos e técnicas' => 'Métodos e técnicas', 'Política Social' => 'Política Social', 'Outros' => 'Outros'];
?>
<div class="container">
    <?= $this->Form->create($docente) ?>
    <fieldset>
        <legend><?= __('Editar Docente') ?></legend>
        <?php
            echo $this->Form->control('nome');
            echo $this->Form->control('cpf');
            echo $this->Form->control('siape');
            echo $this->Form->control('cress');
            echo $this->Form->control('regiao', ['label' => 'Região']);
            echo $this->Form->control('telefone');
            echo $this->Form->control('celular');
            echo $this->Form->control('departamento', ['options' => $departamentos]);
            echo $this->Form->control('email');
            echo $this->Form->control('dataingresso', ['label' => 'Data de Ingresso', 'type' => 'date']);
            echo $this->Form->control('tipocargo', ['label' => 'Tipo de Cargo', 'options' => ['' => '- Selecionar -', 'efetivo' => 'Efetivo', 'substituto' => 'Substituto', 'outro' => 'Outro']]);
            echo $this->Form->control('dataegresso', ['label' => 'Data de Egresso', 'type' => 'date']);
            echo $this->Form->control('motivoegresso', ['label' => 'Motivo de Egresso']);
            echo $this->Form->control('status', [
                'label' => 'Situação',
                'type' => 'select',
                'empty' => '-- Selecione --',
                'options' => [
                    'ativo' => 'Ativo',
                    'aposentado' => 'Aposentado',
                    'inativo' => 'Inativo',
                ],
            ]);
            echo $this->Form->control('observacoes', [
                'label' => 'Observações',
                'type' => 'textarea',
                'rows' => 4,
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar')) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
