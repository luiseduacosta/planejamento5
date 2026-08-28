<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class DocentesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Docentes',
        'app.Configuraplanejamentos',
    ];

    private function loginAs(string $role): void
    {
        $this->session([
            'Auth' => [
                'id' => 1,
                'email' => 'admin@example.com',
                'role' => $role,
            ],
        ]);
    }

    public function testIndexIsAccessibleWithoutAuthentication(): void
    {
        $this->get('/docentes');
        $this->assertResponseOk();
        // Padrão da sessão nova: apenas docentes ativos.
        $this->assertResponseContains('Maria Silva');
        $this->assertResponseContains('Carlos Oliveira');
        $this->assertResponseNotContains('José Souza');
        $this->assertResponseNotContains('Ana Lima');
    }

    public function testIndexWithStatusAllShowsEveryStatus(): void
    {
        $this->get('/docentes?status[]=all');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria Silva');
        $this->assertResponseContains('José Souza');
        $this->assertResponseContains('Ana Lima');
        $this->assertResponseContains('Carlos Oliveira');
    }

    public function testIndexWithStatusAposentadoShowsOnlyAposentados(): void
    {
        $this->get('/docentes?status[]=aposentado');
        $this->assertResponseOk();
        $this->assertResponseContains('José Souza');
        $this->assertResponseNotContains('Maria Silva');
        $this->assertResponseNotContains('Carlos Oliveira');
    }

    public function testIndexFiltersByDepartamento(): void
    {
        $this->get('/docentes?status[]=all&departamento=' . urlencode('Política Social'));
        $this->assertResponseOk();
        $this->assertResponseContains('José Souza');
        $this->assertResponseNotContains('Maria Silva');
    }

    public function testIndexSortsByNome(): void
    {
        $this->get('/docentes?status[]=all&sort=nome&direction=asc');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria Silva');
    }

    public function testViewIsAccessibleWithoutAuthentication(): void
    {
        $this->get('/docentes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Maria Silva');
        $this->assertResponseContains('Disponibilidade por Semestre');
    }

    public function testAddRedirectsToLoginWhenUnauthenticated(): void
    {
        $this->enableCsrfToken();
        $this->post('/docentes/add', ['nome' => 'Novo Docente']);
        $this->assertRedirectContains('/users/login');
    }

    public function testAddAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/docentes/add', [
            'nome' => 'Novo Docente',
            'siape' => '5555555',
            'email' => 'novo.docente@example.com',
            'departamento' => 'Fundamentos',
            'tipocargo' => 'efetivo',
            'status' => 'ativo',
        ]);
        $this->assertRedirectContains('/docentes/view/');

        $table = $this->fetchTable('Docentes');
        $this->assertSame(5, $table->find()->count());

        $novo = $table->find()->where(['nome' => 'Novo Docente'])->first();
        $this->assertNotNull($novo);
        $this->assertSame('efetivo', $novo->tipocargo);
        $this->assertSame('novo.docente@example.com', $novo->email);
        $this->assertNotNull($novo->created);
        $this->assertNotNull($novo->modified);
    }

    public function testAddNormalizesStatusAlias(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/docentes/add', [
            'nome' => 'Docente Com Alias',
            'status' => 'retired',
        ]);
        $this->assertRedirectContains('/docentes/view/');

        $table = $this->fetchTable('Docentes');
        $docente = $table->find()->where(['nome' => 'Docente Com Alias'])->first();
        $this->assertNotNull($docente);
        $this->assertSame('aposentado', $docente->status);
    }

    public function testAddIsForbiddenForReadOnly(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('readonly');
        $this->post('/docentes/add', ['nome' => 'Negado']);
        $this->assertRedirectContains('/users/login');

        $table = $this->fetchTable('Docentes');
        $this->assertSame(4, $table->find()->count());
    }

    public function testEditAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/docentes/edit/1', [
            'nome' => 'Maria Silva Atualizada',
            'departamento' => 'Fundamentos',
            'status' => 'aposentado',
        ]);
        $this->assertRedirectContains('/docentes/view/1');

        $table = $this->fetchTable('Docentes');
        $updated = $table->get(1);
        $this->assertSame('Maria Silva Atualizada', $updated->nome);
        $this->assertSame('aposentado', $updated->status);
    }

    public function testDeleteAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/docentes/delete/1');
        $this->assertRedirect(['action' => 'index']);

        $table = $this->fetchTable('Docentes');
        $this->assertSame(3, $table->find()->count());
    }

    public function testDeleteIsForbiddenForEditor(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('editor');
        $this->post('/docentes/delete/1');
        $this->assertRedirectContains('/users/login');

        $table = $this->fetchTable('Docentes');
        $this->assertSame(4, $table->find()->count());
    }
}
