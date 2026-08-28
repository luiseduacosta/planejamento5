<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePlanejamentos extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('planejamentos');

        $table
            ->addColumn('disciplina_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('docente_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('configuraplanejamento_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('sala_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('dia_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('horario_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('periodo', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('turno', 'string', [
                'default' => null,
                'limit' => 20,
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
            ->addIndex(['disciplina_id'])
            ->addIndex(['docente_id'])
            ->addIndex(['configuraplanejamento_id'])
            ->addIndex(['sala_id'])
            ->addIndex(['dia_id'])
            ->addIndex(['horario_id'])
            ->create();
    }
}
