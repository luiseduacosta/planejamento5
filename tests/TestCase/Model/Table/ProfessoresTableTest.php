<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class ProfessoresTableTest extends TestCase
{
    protected array $fixtures = [
        'app.Professores',
        'app.Configuraplanejamentos',
        'app.DocenteDisponibilidades',
        'app.Planejamentos',
        'app.Users',
    ];

    protected ProfessoresTable $Professores;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Professores = TableRegistry::getTableLocator()->get('Professores');
    }

    public function testValidationNomeRequired(): void
    {
        $docente = $this->Professores->newEntity(['nome' => '']);
        $this->assertFalse($this->Professores->save($docente));
        $this->assertArrayHasKey('nome', $docente->getErrors());
    }

    public function testValidationNomeMaxLength(): void
    {
        $docente = $this->Professores->newEntity(['nome' => str_repeat('a', 201)]);
        $errors = $docente->getErrors();
        $this->assertArrayHasKey('nome', $errors);
        $this->assertArrayHasKey('maxLength', $errors['nome']);
    }

    public function testValidationStatusInList(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => 'bogus']);
        $errors = $docente->getErrors();
        $this->assertArrayHasKey('status', $errors);
        $this->assertArrayHasKey('inList', $errors['status']);
    }

    public function testValidationEmailFormat(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'email' => 'not-an-email']);
        $errors = $docente->getErrors();
        $this->assertArrayHasKey('email', $errors);
    }

    public function testBeforeMarshalNormalizesStatusAliases(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => 'active']);
        $this->assertSame('ativo', $docente->status);

        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => 'retired']);
        $this->assertSame('aposentado', $docente->status);

        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => 'inactive']);
        $this->assertSame('inativo', $docente->status);
    }

    public function testBeforeMarshalKeepsCanonicalStatus(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => 'aposentado']);
        $this->assertSame('aposentado', $docente->status);
    }

    public function testBeforeMarshalDropsEmptyStatus(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Teste', 'status' => '']);
        $this->assertNull($docente->status);
    }

    public function testSaveSetsTimestamps(): void
    {
        $docente = $this->Professores->newEntity(['nome' => 'Novo Docente']);
        $saved = $this->Professores->save($docente);

        $this->assertNotFalse($saved);
        $this->assertNotNull($saved->created);
        $this->assertNotNull($saved->modified);
    }

    public function testDeleteBlockedWhenPlanejamentosExist(): void
    {
        $docente = $this->Professores->get(1);
        $this->assertFalse($this->Professores->delete($docente));
        $this->assertNotNull($this->Professores->findById(1)->first());
    }

    public function testDeleteCascadesDocenteDisponibilidades(): void
    {
        $docente = $this->Professores->get(2);
        $this->assertTrue($this->Professores->delete($docente));

        $this->assertNull($this->Professores->findById(2)->first());
        $disponibilidades = TableRegistry::getTableLocator()->get('DocenteDisponibilidades');
        $this->assertFalse($disponibilidades->exists(['docente_id' => 2]));
    }

    public function testDeleteAllowedWithoutDependents(): void
    {
        $docente = $this->Professores->get(3);
        $this->assertTrue($this->Professores->delete($docente));
        $this->assertNull($this->Professores->findById(3)->first());
    }
}

