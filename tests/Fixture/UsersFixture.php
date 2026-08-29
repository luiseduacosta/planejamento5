<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public array $fields = [
        'id' => ['type' => 'integer', 'autoIncrement' => true],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false],
        'nome' => ['type' => 'string', 'length' => 255, 'null' => true],
        'role' => ['type' => 'string', 'length' => 50, 'null' => true],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ];

    public array $records = [
        [
            'id' => 1,
            'email' => 'admin@example.com',
            'password' => 'password',
            'nome' => 'Admin',
            'role' => 'admin',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
    ];
}
