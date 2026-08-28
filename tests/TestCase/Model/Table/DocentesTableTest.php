<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocentesTable;
use Cake\TestSuite\TestCase;

class DocentesTableTest extends TestCase
{
    protected array $fixtures = [
        'app.Docentes',
    ];

    private DocentesTable $Docentes;

    public function setUp(): void
    {
        parent::setUp();
        $this->Docentes = $this->fetchTable('Docentes');
    }

    public function tearDown(): void
    {
        unset($this->Docentes);
        parent::tearDown();
    }

    public function testValidationRequiresNome(): void
    {
        $entity = $this->Docentes->newEntity(['nome' => '']);
        $this->assertNotEmpty($entity->getErrors()['nome'] ?? []);
    }

    public function testValidationNomeMaxLength(): void
    {
        $entity = $this->Docentes->newEntity(['nome' => str_repeat('a', 201)]);
        $this->assertNotEmpty($entity->getErrors()['nome'] ?? []);
    }

    public function testValidationRejectsInvalidEmail(): void
    {
        $entity = $this->Docentes->newEntity([
            'nome' => 'Docente Válido',
            'email' => 'not-an-email',
        ]);
        $this->assertNotEmpty($entity->getErrors()['email'] ?? []);
    }

    public function testValidationAllowsAllFormFields(): void
    {
        $entity = $this->Docentes->newEntity([
            'nome' => 'Docente Completo',
            'cpf' => '11122233344',
            'siape' => '9999999',
            'cress' => '54321',
            'regiao' => '7',
            'telefone' => '2122334455',
            'celular' => '2199887766',
            'email' => 'docente.completo@example.com',
            'dataingresso' => '2020-01-02',
            'tipocargo' => 'substituto',
            'departamento' => 'Fundamentos',
            'dataegresso' => null,
            'motivoegresso' => null,
            'status' => 'ativo',
            'observacoes' => 'Observação de teste.',
        ]);
        $this->assertEmpty($entity->getErrors());
    }

    public function testSavePersistsAllFieldsAndTimestamps(): void
    {
        $entity = $this->Docentes->newEntity([
            'nome' => 'Docente Salvo',
            'siape' => '9999999',
            'email' => 'docente.salvo@example.com',
            'dataingresso' => '2020-01-02',
            'tipocargo' => 'substituto',
            'departamento' => 'Fundamentos',
            'status' => 'ativo',
            'observacoes' => 'Observação de teste.',
        ]);
        $saved = $this->Docentes->save($entity);

        $this->assertNotFalse($saved);
        $this->assertSame(5, $this->Docentes->find()->count());
        $this->assertNotNull($saved->created);
        $this->assertNotNull($saved->modified);
        $this->assertSame('substituto', $saved->tipocargo);
        $this->assertSame('2020-01-02', $saved->dataingresso->format('Y-m-d'));
    }

    public function testBeforeMarshalNormalizesStatusAliases(): void
    {
        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => 'retired']);
        $this->assertSame('aposentado', $entity->status);

        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => 'active']);
        $this->assertSame('ativo', $entity->status);

        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => 'inactivo']);
        $this->assertSame('inativo', $entity->status);
    }

    public function testBeforeMarshalNormalizesCaseAndWhitespace(): void
    {
        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => 'Active']);
        $this->assertSame('ativo', $entity->status);

        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => ' Ativo ']);
        $this->assertSame('ativo', $entity->status);
    }

    public function testBeforeMarshalKeepsUnknownStatusUntouched(): void
    {
        $entity = $this->Docentes->newEntity(['nome' => 'Alias', 'status' => 'licença']);
        $this->assertSame('licença', $entity->status);
    }
}
