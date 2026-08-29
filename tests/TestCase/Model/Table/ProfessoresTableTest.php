<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfessoresTable;
use Cake\TestSuite\TestCase;

class ProfessoresTableTest extends TestCase
{
    protected ?ProfessoresTable $Professores = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Professores = $this->getTableLocator()->get('Professores');
    }

    protected function tearDown(): void
    {
        unset($this->Professores);
        parent::tearDown();
    }

    public function testValidationDefault(): void
    {
        $professor = $this->Professores->newEmptyEntity();
        $this->Professores->patchEntity($professor, [
            'nome' => 'Professor Teste',
            'cpf' => '123.456.789-00',
            'email' => 'teste@exemplo.com',
            'status' => 'ativo',
        ]);
        $this->assertEmpty($professor->getErrors());
    }
}
