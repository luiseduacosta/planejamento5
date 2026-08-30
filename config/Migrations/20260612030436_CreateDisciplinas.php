<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDisciplinas extends BaseMigration
{
    /**
     * The `disciplinas` table may already exist in databases provisioned from
     * a legacy dump, so this migration is a no-op in that case.
     */
    public function change(): void
    {
        if ($this->hasTable('disciplinas')) {
            return;
        }

        $table = $this->table('disciplinas');

        $table
            ->addColumn('codigo', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('disciplina', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('creditos', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('carga_horaria', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('periodo_diurno', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('periodo_noturno', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('requisitos', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('optativa', 'boolean', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('departamento', 'string', [
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
            ->addIndex(['codigo'])
            ->addIndex(['departamento'])
            ->create();
    }
}
