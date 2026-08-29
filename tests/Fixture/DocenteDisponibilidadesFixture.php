<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DocenteDisponibilidadesFixture extends TestFixture
{
    public array $records = [
        [
            'docente_id' => 1,
            'configuraplanejamento_id' => 1,
            'disponivel' => true,
            'motivo' => null,
            'observacoes' => null,
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
        [
            'docente_id' => 2,
            'configuraplanejamento_id' => 1,
            'disponivel' => false,
            'motivo' => 'Licença médica',
            'observacoes' => 'Retorna no próximo semestre',
            'created' => '2026-01-01 10:00:00',
            'modified' => '2026-01-01 10:00:00',
        ],
    ];
}
