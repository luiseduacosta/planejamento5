<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDisciplinas extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/guides/writing-migrations/migration-methods.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('disciplinas');

        $table
            ->addColumn('codigo', 'string', [
                'default' => null,
                'limit' => 6,
                'null' => true,
            ])
            ->addColumn('disciplina', 'string', [
                'default' => null,
                'limit' => 60,
                'null' => true,
            ])
            ->addColumn('creditos', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('carga_horaria', 'string', [
                'default' => null,
                'limit' => 3,
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
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('optativa', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('departamento', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('observacoes', 'string', [
                'default' => null,
                'limit' => 256,
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
