<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DocentesFixture extends TestFixture
{
    public array $records = [
        [
            'nome' => 'Maria da Silva',
            'cpf' => '12345678900',
            'siape' => '1234567',
            'cress' => '12345',
            'regiao' => 'RJ',
            'telefone' => '2125551234',
            'celular' => '21988887777',
            'email' => 'maria@example.com',
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
