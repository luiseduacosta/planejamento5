<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ProfessoresFixture extends TestFixture
{
    public string $table = 'professores';

    public array $fields = [
        'id' => ['type' => 'integer', 'autoIncrement' => true],
        'nome' => ['type' => 'string', 'length' => 200, 'null' => false],
        'cpf' => ['type' => 'string', 'length' => 14, 'null' => true],
        'siape' => ['type' => 'string', 'length' => 8, 'null' => true],
        'cress' => ['type' => 'string', 'length' => 10, 'null' => true],
        'regiao' => ['type' => 'string', 'length' => 2, 'null' => true],
        'codigo_telefone' => ['type' => 'string', 'length' => 2, 'null' => true],
        'telefone' => ['type' => 'string', 'length' => 15, 'null' => true],
        'codigo_celular' => ['type' => 'string', 'length' => 2, 'null' => true],
        'celular' => ['type' => 'string', 'length' => 15, 'null' => true],
        'email' => ['type' => 'string', 'length' => 255, 'null' => true],
        'curriculolattes' => ['type' => 'string', 'length' => 50, 'null' => true],
        'atualizacaolattes' => ['type' => 'date', 'null' => true],
        'dataingresso' => ['type' => 'date', 'null' => true],
        'tipocargo' => ['type' => 'string', 'length' => 20, 'null' => true],
        'departamento' => ['type' => 'string', 'length' => 30, 'null' => true],
        'dataegresso' => ['type' => 'date', 'null' => true],
        'motivoegresso' => ['type' => 'string', 'length' => 100, 'null' => true],
        'status' => ['type' => 'string', 'length' => 10, 'null' => true],
        'observacoes' => ['type' => 'text', 'null' => true],
        'user_id' => ['type' => 'integer', 'null' => true],
        'estagiarios_count' => ['type' => 'integer', 'null' => true],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
    ];

    public array $records = [
        [
            'nome' => 'Maria da Silva',
            'cpf' => '12345678900',
            'siape' => '1234567',
            'cress' => '12345',
            'regiao' => 'RJ',
            'codigo_telefone' => '21',
            'telefone' => '2125551234',
            'codigo_celular' => '21',
            'celular' => '21988887777',
            'email' => 'maria@example.com',
            'curriculolattes' => '1234567890123456',
            'atualizacaolattes' => '2026-01-15',
            'dataingresso' => '2010-03-01',
            'tipocargo' => 'efetivo',
            'departamento' => 'Fundamentos',
            'status' => 'ativo',
            'observacoes' => 'Observação de teste',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
        [
            'nome' => 'João Souza',
            'cpf' => '98765432100',
            'siape' => '7654321',
            'email' => 'joao@example.com',
            'dataingresso' => '1998-07-08',
            'tipocargo' => 'efetivo',
            'departamento' => 'Política Social',
            'dataegresso' => '2020-12-31',
            'motivoegresso' => 'Aposentadoria',
            'status' => 'aposentado',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
        [
            'nome' => 'Ana Lima',
            'siape' => '1122334',
            'email' => 'ana@example.com',
            'tipocargo' => 'substituto',
            'departamento' => 'Métodos e técnicas',
            'status' => 'inativo',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
    ];
}
