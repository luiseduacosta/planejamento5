<?php
declare(strict_types=1);
$departamentos = ['Fundamentos' => 'Fundamentos', 'Métodos e técnicas' => 'Métodos e técnicas', 'Política Social' => 'Política Social', 'Outros' => 'Outros'];
?>
<div class="container">
    <?= $this->Form->create($professor) ?>
    <fieldset>
        <legend><?= __('Adicionar Professor') ?></legend>
        <?php
            echo $this->Form->control('nome', ['label' => 'Nome']);
            echo $this->Form->control('cpf', ['label' => 'CPF']);
            echo $this->Form->control('siape', ['label' => 'Siape']);
            echo $this->Form->control('cress', ['label' => 'CRESS']);
            echo $this->Form->control('regiao', ['label' => 'Região']);
            echo $this->Form->control('telefone', ['label' => 'Telefone']);
            echo $this->Form->control('celular', ['label' => 'Celular']);
            echo $this->Form->control('departamento', ['label' => 'Departamento', 'options' => $departamentos]);
            echo $this->Form->control('email', ['label' => 'E-mail']);
            echo $this->Form->control('curriculolattes', ['label' => 'Currículo Lattes']);
            echo $this->Form->control('atualizacaolattes', ['label' => 'Atualização do Lattes', 'type' => 'date', 'empty' => true]);
            echo $this->Form->control('dataingresso', ['label' => 'Data de Ingresso', 'type' => 'date']);
            echo $this->Form->control('tipocargo', ['label' => 'Tipo de Cargo', 'options' => [
                'efetivo' => 'Efetivo',
                'substituto' => 'Substituto',
                'temporario' => 'Temporário',
                'visitante' => 'Visitante',
            ], 'empty' => '-- Selecione --']);
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
    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    <?= $this->Form->end() ?>
</div>
