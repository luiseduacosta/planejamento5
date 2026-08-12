<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class HorariosFixture extends TestFixture
{
    public array $records = [
        [
            'id' => 1,
            'ordem' => 1,
            'horario' => '08:00-10:00',
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
    ];
}
