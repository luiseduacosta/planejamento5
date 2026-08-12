<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DiasFixture extends TestFixture
{
    public array $records = [
        [
            'id' => 1,
            'ordem' => 1,
            'dia' => 'Segunda-feira',
            'created' => '2026-07-14 10:00:00',
            'modified' => '2026-07-14 10:00:00',
        ],
    ];
}
