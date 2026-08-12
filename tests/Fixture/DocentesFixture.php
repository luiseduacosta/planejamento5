<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DocentesFixture extends TestFixture
{
    public array $records = [
        [
            'id' => 1,
            'nome' => 'Maria Silva',
            'cpf' => '12345678901',
            'sexo' => '2',
            'ddd_telefone' => '21',
            'ddd_celular' => '21',
            'departamento' => 'DCC',
            'status' => 'ativo',
        ],
    ];
}
