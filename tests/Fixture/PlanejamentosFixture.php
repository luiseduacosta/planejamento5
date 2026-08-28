<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PlanejamentosFixture extends TestFixture
{
    public array $records = [
        [
            'disciplina_id' => 1,
            'docente_id' => 1,
            'configuraplanejamento_id' => 1,
            'sala_id' => null,
            'dia_id' => null,
            'horario_id' => null,
            'periodo' => null,
            'turno' => null,
            'observacoes' => null,
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
    ];
}
