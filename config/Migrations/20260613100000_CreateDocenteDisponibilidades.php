<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDocenteDisponibilidades extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('docente_disponibilidades');

        $table
            ->addColumn('docente_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('configuraplanejamento_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('disponivel', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addColumn('motivo', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('observacoes', 'text', [
                'default' => null,
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
            ->addIndex(['docente_id'])
            ->addIndex(['configuraplanejamento_id'])
            ->addIndex(['docente_id', 'configuraplanejamento_id'], ['unique' => true])
            ->create();
    }
}
