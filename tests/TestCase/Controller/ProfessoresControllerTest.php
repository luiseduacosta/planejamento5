<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ProfessoresControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function testIndex(): void
    {
        $this->get('/professores');
        $this->assertResponseOk();
    }
}
