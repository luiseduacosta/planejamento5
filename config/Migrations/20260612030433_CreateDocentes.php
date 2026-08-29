<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDocentes extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('docentes');

        $table
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('cpf', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('siape', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('cress', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('regiao', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('celular', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 100,
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
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('dataegresso', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('motivoegresso', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ])
            ->addColumn('observacoes', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => 'ativo',
                'limit' => 20,
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
            ->addIndex(['status'])
            ->addIndex(['departamento'])
            ->create();
    }
}
