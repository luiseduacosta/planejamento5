<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\Database\Schema\TableSchema;
use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public string $table = 'users';

    public array $fields = [
        'id' => ['type' => 'integer', 'autoIncrement' => true],
        'username' => ['type' => 'string', 'length' => 50, 'null' => false],
        'email' => ['type' => 'string', 'length' => 100, 'null' => false],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false],
        'nome' => ['type' => 'string', 'length' => 200, 'null' => true],
        'role' => ['type' => 'string', 'length' => 20, 'null' => false],
        'created' => ['type' => 'datetime', 'null' => false],
        'modified' => ['type' => 'datetime', 'null' => false],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ];

    public array $records = [
        [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'nome' => 'Admin',
            'role' => 'admin',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
    ];

    protected function _schemaFromReflection(): void
    {
        $schema = new TableSchema($this->table);
        foreach ($this->fields as $key => $attrs) {
            if ($key === '_constraints') {
                foreach ($attrs as $name => $constraint) {
                    $schema->addConstraint($name, $constraint);
                }
                continue;
            }
            if ($key === '_indexes') {
                foreach ($attrs as $name => $index) {
                    $schema->addIndex($name, $index);
                }
                continue;
            }
            if ($key === '_options') {
                if (method_exists($schema, 'setOptions')) {
                    $schema->setOptions($attrs);
                }
                continue;
            }
            $schema->addColumn((string)$key, $attrs);
        }
        $this->_schema = $schema;
    }
}
