<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TurmaotpsFixture extends TestFixture
{
    public array $records = [
        [
            'id' => 1,
            'configuraplanejamento_id' => 1,
            'turno' => 'diurno',
            'periodo' => 1,
            'turmaotp' => 'A',
            'docente_id' => 1,
            'dia_id' => 1,
            'horario_id' => 1,
            'sala_id' => 1,
            'observacoes' => '',
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
        [
            'id' => 2,
            'configuraplanejamento_id' => 2,
            'turno' => 'noturno',
            'periodo' => 2,
            'turmaotp' => 'BB',
            'docente_id' => null,
            'dia_id' => null,
            'horario_id' => null,
            'sala_id' => null,
            'observacoes' => '',
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
    ];
}
