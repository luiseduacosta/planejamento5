<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateProfessores extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('professores');

        $table
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('cpf', 'string', [
                'default' => null,
                'limit' => 14,
                'null' => true,
            ])
            ->addColumn('siape', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => true,
            ])
            ->addColumn('cress', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('regiao', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('codigo_telefone', 'string', [
                'default' => '21',
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => true,
            ])
            ->addColumn('codigo_celular', 'string', [
                'default' => '21',
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('celular', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('curriculolattes', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('atualizacaolattes', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('dataingresso', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('tipocargo', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('departamento', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => true,
            ])
            ->addColumn('dataegresso', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('motivoegresso', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('observacoes', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('estagiarios_count', 'integer', [
                'default' => 0,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => 'ativo',
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->create();
    }
}
