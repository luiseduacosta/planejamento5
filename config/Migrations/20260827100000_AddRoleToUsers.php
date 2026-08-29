<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddRoleToUsers extends BaseMigration
{
    /**
     * Databases provisioned manually (without the CreateUsers migration) may
     * be missing the `role` and `nome` columns that the authorization policies
     * and the Usuarioplanejamentos model rely on, so only missing columns are
     * added here.
     */
    public function change(): void
    {
        $table = $this->table('users');
        $pending = false;

        if (!$table->hasColumn('role')) {
            $table->addColumn('role', 'string', [
                'default' => 'user',
                'limit' => 20,
                'null' => false,
            ]);
            $pending = true;
        }

        if (!$table->hasColumn('nome')) {
            $table->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true,
            ]);
            $pending = true;
        }

        if ($pending) {
            $table->update();
        }
    }
}
