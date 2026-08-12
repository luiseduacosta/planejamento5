<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ConfiguraplanejamentosFixture extends TestFixture
{
    public array $records = [
        [
            'id' => 1,
            'usuarioplanejamento_id' => 1,
            'nome' => 'Configuração 2026.1',
            'semestre' => '2026.1',
            'versao' => 1,
            'ativo' => true,
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
        [
            'id' => 2,
            'usuarioplanejamento_id' => 1,
            'nome' => 'Configuração 2026.2',
            'semestre' => '2026.2',
            'versao' => 1,
            'ativo' => false,
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
    ];
}
