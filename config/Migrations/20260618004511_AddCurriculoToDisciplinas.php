<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddCurriculoToDisciplinas extends BaseMigration
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
        $table->addColumn('curriculo', 'string', [
            'default' => null,
            'limit' => 4,
            'null' => true,
        ]);
        $table->update();
    }
}
