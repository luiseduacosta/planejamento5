<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class DocentesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Docentes',
        'app.Configuraplanejamentos',
        'app.DocenteDisponibilidades',
        'app.Planejamentos',
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
        $this->get('/docentes');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseContains('João Souza');
    }

    public function testIndexStatusFilter(): void
    {
        $this->get('/docentes?status=ativo');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseNotContains('João Souza');
    }

    public function testIndexStatusFilterAcceptsAlias(): void
    {
        $this->get('/docentes?status=retired');
        $this->assertResponseOk();
        $this->assertResponseContains('João Souza');
        $this->assertResponseNotContains('Maria da Silva');
    }

    public function testIndexDisponibilidadeFilter(): void
    {
        $this->get('/docentes?configuraplanejamento_id=1');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
        $this->assertResponseNotContains('João Souza');
    }

    public function testViewIsPublic(): void
    {
        $this->get('/docentes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria da Silva');
    }

    public function testAddRequiresLogin(): void
    {
        $this->get('/docentes/add');
        $this->assertRedirectContains('/users/login');
    }

    public function testAddForbiddenForPlainUser(): void
    {
        $this->loginAs('user');
        $this->enableCsrfToken();
        $this->post('/docentes/add', ['nome' => 'Não Deve Salvar']);
        $this->assertRedirectContains('/users/login');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $this->assertFalse($docentes->exists(['nome' => 'Não Deve Salvar']));
    }

    public function testAddAsEditor(): void
    {
        $this->loginAs('editor');
        $this->enableCsrfToken();
        $this->post('/docentes/add', [
            'nome' => 'Novo Docente',
            'email' => 'novo@example.com',
            'status' => 'ativo',
        ]);
        $this->assertRedirectContains('/docentes/view');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $query = $docentes->find()->where(['nome' => 'Novo Docente']);
        $this->assertSame(1, $query->count());
        $this->assertSame('ativo', $query->first()->status);
    }

    public function testAddKeepsAtivoWhenStatusEmpty(): void
    {
        $this->loginAs('editor');
        $this->enableCsrfToken();
        $this->post('/docentes/add', [
            'nome' => 'Docente Sem Status',
            'status' => '',
        ]);
        $this->assertRedirectContains('/docentes/view');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $docente = $docentes->find()->where(['nome' => 'Docente Sem Status'])->first();
        $this->assertNotNull($docente);
        $this->assertSame('ativo', $docente->status);
    }

    public function testEditAsAdmin(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/docentes/edit/3', [
            'nome' => 'Ana Lima Alterada',
            'status' => 'aposentado',
        ]);
        $this->assertRedirectContains('/docentes/view/3');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $docente = $docentes->get(3);
        $this->assertSame('Ana Lima Alterada', $docente->nome);
        $this->assertSame('aposentado', $docente->status);
    }

    public function testDeleteBlockedWhenPlanejamentosExist(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/docentes/delete/1');
        $this->assertRedirectContains('/docentes');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $this->assertNotNull($docentes->findById(1)->first());
    }

    public function testDeleteAsAdmin(): void
    {
        $this->loginAs('admin');
        $this->enableCsrfToken();
        $this->post('/docentes/delete/3');
        $this->assertRedirectContains('/docentes');

        $docentes = TableRegistry::getTableLocator()->get('Docentes');
        $this->assertNull($docentes->findById(3)->first());
    }
}
