<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateConfiguraplanejamentos extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('configuraplanejamentos');

        $table
            ->addColumn('usuarioplanejamento_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('semestre', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('versao', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('ativo', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['usuarioplanejamento_id'])
            ->addIndex(['semestre'])
            ->addIndex(['ativo'])
            ->create();
    }
}
