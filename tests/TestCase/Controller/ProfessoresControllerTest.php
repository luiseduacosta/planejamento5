<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ProfessoresControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Professores',
        'app.Configuraplanejamentos',
        'app.DocenteDisponibilidades',
        'app.Planejamentos',
        'app.Users',
    ];

    protected function loginAs(string $role): void
    {
        $this->session([
            'Auth' => [
                'id' => 1,
                'email' => 'admin@example.com',
                'role' => $role,
            ],
        ]);
    }

    public function testIndexIsPublic(): void
    {
        $this->get('/professores');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseContains('João Souza');
    }

    public function testIndexStatusFilter(): void
    {
        $this->get('/professores?status=ativo');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseNotContains('João Souza');
    }

    public function testIndexStatusFilterAcceptsAlias(): void
    {
        $this->get('/professores?status=retired');
        $this->assertResponseOk();
        $this->assertResponseContains('João Souza');
        $this->assertResponseNotContains('Maria da Silva');
    }

    public function testIndexDisponibilidadeFilter(): void
    {
        $this->get('/professores?configuraplanejamento_id=1');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseNotContains('João Souza');
    }

    public function testViewIsPublic(): void
    {
        $this->get('/professores/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
    }

    public function testAddRequiresLogin(): void
    {
        $this->get('/professores/add');
        $this->assertRedirectContains('/users/login');
    }

    public function testAddForbiddenForPlainUser(): void
    {
        $this->loginAs('user');
        $this->enableCsrfToken();
        $this->post('/professores/add', ['nome' => 'Não Deve Salvar']);
        $this->assertRedirectContains('/users/login');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $this->assertFalse($professores->exists(['nome' => 'Não Deve Salvar']));
    }

    public function testAddAsEditor(): void
    {
        $this->loginAs('editor');
        $this->enableCsrfToken();
        $this->post('/professores/add', [
            'nome' => 'Novo Docente',
            'email' => 'novo@example.com',
            'status' => 'ativo',
        ]);
        $this->assertRedirectContains('/professores/view');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $query = $professores->find()->where(['nome' => 'Novo Docente']);
        $this->assertSame(1, $query->count());
        $this->assertSame('ativo', $query->first()->status);
    }

    public function testAddKeepsAtivoWhenStatusEmpty(): void
    {
        $this->loginAs('editor');
        $this->enableCsrfToken();
        $this->post('/professores/add', [
            'nome' => 'Docente Sem Status',
            'status' => '',
        ]);
        $this->assertRedirectContains('/professores/view');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $docente = $professores->find()->where(['nome' => 'Docente Sem Status'])->first();
        $this->assertNotNull($docente);
        $this->assertSame('ativo', $docente->status);
    }

    public function testEditAsAdmin(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/professores/edit/3', [
            'nome' => 'Ana Lima Alterada',
            'status' => 'aposentado',
        ]);
        $this->assertRedirectContains('/professores/view/3');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $docente = $professores->get(3);
        $this->assertSame('Ana Lima Alterada', $docente->nome);
        $this->assertSame('aposentado', $docente->status);
    }

    public function testDeleteBlockedWhenPlanejamentosExist(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/professores/delete/1');
        $this->assertRedirectContains('/professores');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $this->assertNotNull($professores->findById(1)->first());
    }

    public function testDeleteAsAdmin(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/professores/delete/3');
        $this->assertRedirectContains('/professores');

        $professores = TableRegistry::getTableLocator()->get('Professores');
        $this->assertNull($professores->findById(3)->first());
    }
}

