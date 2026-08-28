<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class TurmaotpsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Turmaotps',
        'app.Configuraplanejamentos',
        'app.Docentes',
        'app.Dias',
        'app.Horarios',
        'app.Salas',
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

    /**
     * O harness de integração não persiste automaticamente, entre uma
     * requisição e a próxima, o que a aplicação escreveu na sessão: cada
     * requisição nova reinicia a sessão apenas com os dados definidos via
     * session(). Este helper copia o estado da sessão ao final da última
     * requisição (preservado em $_SESSION pelo Server ao fechar a sessão)
     * para a próxima requisição, simulando o mesmo usuário na mesma sessão
     * do navegador.
     */
    private function carrySessionFromLastRequest(): void
    {
        $data = $_SESSION ?? [];
        if ($data !== []) {
            $this->session($data);
        }
    }

    public function testIndexIsAccessibleWithoutAuthentication(): void
    {
        $this->get('/turmaotps');
        $this->assertResponseOk();
        $this->assertResponseContains('Turmas de OTP');
        $this->assertResponseContains('Segunda-feira');
        $this->assertResponseContains('Maria Silva');
    }

    public function testViewIsAccessibleWithoutAuthentication(): void
    {
        $this->get('/turmaotps/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Turma de OTP');
        $this->assertResponseContains('08:00-10:00');
    }

    public function testAddRedirectsToLoginWhenUnauthenticated(): void
    {
        $this->enableCsrfToken();
        $this->post('/turmaotps/add', [
            'configuraplanejamento_id' => 1,
            'turmaotp' => 'B',
        ]);
        $this->assertRedirectContains('/users/login');
    }

    public function testAddAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/turmaotps/add', [
            'configuraplanejamento_id' => 1,
            'turno' => 'noturno',
            'periodo' => 2,
            'turmaotp' => 'B',
            'docente_id' => 1,
            'dia_id' => 1,
            'horario_id' => 1,
            'sala_id' => 1,
            'observacoes' => 'Turma criada no teste.',
        ]);
        $this->assertRedirect(['action' => 'index']);

        $table = $this->fetchTable('Turmaotps');
        $this->assertSame(3, $table->find()->count());
        $this->assertNotNull($table->find()->where(['turmaotp' => 'B'])->first());
    }

    public function testAddIsForbiddenForReadOnly(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('readonly');
        $this->post('/turmaotps/add', [
            'configuraplanejamento_id' => 1,
            'turmaotp' => 'C',
        ]);
        $this->assertRedirectContains('/users/login');

        $table = $this->fetchTable('Turmaotps');
        $this->assertSame(2, $table->find()->count());
    }

    public function testEditAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/turmaotps/edit/1', [
            'configuraplanejamento_id' => 1,
            'turno' => 'noturno',
            'periodo' => 2,
            'turmaotp' => 'C',
        ]);
        $this->assertRedirect(['action' => 'index']);

        $table = $this->fetchTable('Turmaotps');
        $updated = $table->get(1);
        $this->assertSame('C', $updated->turmaotp);
        $this->assertSame('noturno', $updated->turno);
    }

    public function testDeleteAsAdmin(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('admin');
        $this->post('/turmaotps/delete/1');
        $this->assertRedirect(['action' => 'index']);

        $table = $this->fetchTable('Turmaotps');
        $this->assertSame(1, $table->find()->count());
    }

    public function testDeleteIsForbiddenForEditor(): void
    {
        $this->enableCsrfToken();
        $this->loginAs('editor');
        $this->post('/turmaotps/delete/1');
        $this->assertRedirectContains('/users/login');

        $table = $this->fetchTable('Turmaotps');
        $this->assertSame(2, $table->find()->count());
    }

    public function testIndexFiltersBySelectedConfiguraplanejamento(): void
    {
        $this->get('/turmaotps?configuraplanejamento_id=2');
        $this->assertResponseOk();
        $this->assertResponseContains('BB');
        $this->assertResponseNotContains('Segunda-feira');
    }

    public function testIndexFreezesSelectedConfiguraplanejamentoInSession(): void
    {
        // Escolha explícita congela a configuração na sessão...
        $this->get('/turmaotps?configuraplanejamento_id=2');
        $this->carrySessionFromLastRequest();
        // ...e o próximo acesso sem parâmetro continua usando a mesma.
        $this->get('/turmaotps');
        $this->assertResponseOk();
        $this->assertResponseContains('BB');
        $this->assertResponseNotContains('Segunda-feira');
    }

    public function testIndexUsesActiveConfiguraplanejamentoByDefault(): void
    {
        // Sem escolha na URL, usa a configuração ativa (id 1, ativo=true).
        $this->get('/turmaotps');
        $this->assertResponseOk();
        $this->assertResponseContains('Segunda-feira');
        $this->assertResponseNotContains('BB');
    }

    public function testAddShowsSelectedConfiguraplanejamentoAndFreezesSession(): void
    {
        $this->loginAs('admin');
        $this->get('/turmaotps/add?configuraplanejamento_id=2');
        $this->assertResponseOk();
        // O select do formulário mostra a configuração escolhida.
        $this->assertResponseContains('value="2" selected');

        // A escolha feita no add congela a configuração para a sessão toda.
        $this->carrySessionFromLastRequest();
        $this->get('/turmaotps');
        $this->assertResponseOk();
        $this->assertResponseContains('BB');
        $this->assertResponseNotContains('Segunda-feira');
    }

    public function testAddDefaultsToActiveConfiguraplanejamento(): void
    {
        $this->loginAs('admin');
        $this->get('/turmaotps/add');
        $this->assertResponseOk();
        // Sem escolha na URL, o formulário já vem com a configuração ativa (1).
        $this->assertResponseContains('value="1" selected');
    }
}
