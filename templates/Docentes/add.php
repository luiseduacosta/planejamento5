<?php
declare(strict_types=1);
$departamentos = ['Fundamentos' => 'Fundamentos', 'Métodos e técnicas' => 'Métodos e técnicas', 'Política Social' => 'Política Social', 'Outros' => 'Outros'];
?>
<div class="docentes form content">
    <?= $this->Form->create($docente) ?>
    <fieldset>
        <legend><?= __('Adicionar Docente') ?></legend>
        <?php
            echo $this->Form->control('nome', ['class' => 'form-control', 'label' => 'Nome']);
            echo $this->Form->control('cpf', ['class' => 'form-control', 'label' => 'CPF']);
            echo $this->Form->control('siape', ['class' => 'form-control', 'label' => 'SIAPE']);
            echo $this->Form->control('cress', ['class' => 'form-control', 'label' => 'CRESS']);
            echo $this->Form->control('regiao', ['class' => 'form-control', 'label' => 'Região']);
            echo $this->Form->control('telefone', ['class' => 'form-control', 'label' => 'Telefone']);
            echo $this->Form->control('celular', ['class' => 'form-control', 'label' => 'Celular']);
            echo $this->Form->control('departamento', ['class' => 'form-control', 'label' => 'Departamento', 'options' => $departamentos]);
            echo $this->Form->control('email', ['class' => 'form-control', 'label' => 'Email']);
            echo $this->Form->control('dataingresso', ['class' => 'form-control', 'label' => 'Data de Ingresso', 'type' => 'date']);
            echo $this->Form->control('dataegresso', ['class' => 'form-control', 'label' => 'Data de Egresso', 'type' => 'date']);
            echo $this->Form->control('motivoegresso', ['class' => 'form-control', 'label' => 'Motivo de Egresso']);
            echo $this->Form->control('status', [
                'class' => 'form-select',
                'label' => 'Situação',
                'type' => 'select',
                'empty' => '-- Selecione --',
                'options' => [
                    'activo' => 'Ativo',
                    'aposentado' => 'Aposentado',
                    'inactivo' => 'Inativo',
                ],
            ]);
            echo $this->Form->control('observacoes', [
                'class' => 'form-control',
                'label' => 'Observações',
                'type' => 'textarea',
                'rows' => 4,
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
