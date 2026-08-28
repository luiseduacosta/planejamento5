<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TurmaotpsTable;
use Cake\TestSuite\TestCase;

class TurmaotpsTableTest extends TestCase
{
    protected array $fixtures = [
        'app.Turmaotps',
        'app.Configuraplanejamentos',
        'app.Docentes',
        'app.Dias',
        'app.Horarios',
        'app.Salas',
    ];

    private TurmaotpsTable $Turmaotps;

    public function setUp(): void
    {
        parent::setUp();
        $this->Turmaotps = $this->fetchTable('Turmaotps');
    }

    public function tearDown(): void
    {
        unset($this->Turmaotps);
        parent::tearDown();
    }

    public function testValidationRequiresTurmaotp(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 1,
            'turmaotp' => '',
        ]);
        $this->assertNotEmpty($entity->getErrors()['turmaotp'] ?? []);
    }

    public function testValidationTurmaotpMaxLengthTwenty(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 1,
            'turmaotp' => str_repeat('A', 21),
        ]);
        $this->assertNotEmpty($entity->getErrors()['turmaotp'] ?? []);
    }

    public function testValidationRequiresConfiguraplanejamentoId(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'turmaotp' => 'A',
        ]);
        $this->assertNotEmpty($entity->getErrors()['configuraplanejamento_id'] ?? []);
    }

    public function testValidationAllowsNullOptionalFields(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 1,
            'turmaotp' => 'A',
        ]);
        $this->assertEmpty($entity->getErrors());
    }

    public function testSaveValidRecord(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 1,
            'turno' => 'noturno',
            'periodo' => 3,
            'turmaotp' => 'D',
            'docente_id' => 1,
            'dia_id' => 1,
            'horario_id' => 1,
            'sala_id' => 1,
        ]);
        $this->assertNotFalse($this->Turmaotps->save($entity));
        $this->assertSame(3, $this->Turmaotps->find()->count());
    }

    public function testSaveAllowsNullOptionalForeignKeys(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 1,
            'turmaotp' => 'F',
        ]);
        $this->assertNotFalse($this->Turmaotps->save($entity));
    }

    public function testSaveRejectsNonExistentConfiguraplanejamento(): void
    {
        $entity = $this->Turmaotps->newEntity([
            'configuraplanejamento_id' => 999,
            'turmaotp' => 'E',
        ]);
        $this->assertFalse($this->Turmaotps->save($entity));
        $this->assertNotEmpty($entity->getErrors()['configuraplanejamento_id'] ?? []);
    }
}
