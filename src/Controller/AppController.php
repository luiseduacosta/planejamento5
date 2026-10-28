<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Configuraplanejamento;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Session key holding the id of the active configuraplanejamento (semestre).
     * The value persists until the user explicitly selects another semester.
     */
    protected const ACTIVE_CONFIG_SESSION_KEY = 'Configuraplanejamento.ativo';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        // Load Authentication and Authorization components
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');

        $this->Authentication->addUnauthenticatedActions([
            'display',
        ]);
    }

    /**
     * Shares the active configuraplanejamento with every view, so the layout
     * can render the global "Semestre ativo" selector.
     */
    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        $configuracaoAtiva = $this->getActiveConfiguraplanejamento();
        $this->set('configuracaoAtiva', $configuracaoAtiva);

        // Options for the navbar semester switcher (only worth querying when
        // the selector will actually be rendered).
        $semestreAtivoOptions = [];
        if ($configuracaoAtiva !== null && $this->request->getAttribute('identity') !== null) {
            $rows = $this->fetchTable('Configuraplanejamentos')->find()
                ->select(['id', 'semestre', 'versao'])
                ->orderBy(['semestre' => 'DESC', 'versao' => 'DESC'])
                ->all();
            foreach ($rows as $row) {
                $semestreAtivoOptions[$row->id] = $row->semestre . ' - ' . ($row->versao ?? '1');
            }
        }
        $this->set('semestreAtivoOptions', $semestreAtivoOptions);
    }

    /**
     * Resolves the active configuraplanejamento (semester) for the whole app.
     *
     * Priority:
     *  1. The id persisted in the session (validated to still exist, else cleared);
     *  2. The record flagged ativo=true in the database;
     *  3. The most recently modified record ("last semester" fallback).
     */
    protected function getActiveConfiguraplanejamento(): ?Configuraplanejamento
    {
        $table = $this->fetchTable('Configuraplanejamentos');

        if ($table->find()->count() === 0) {
            return null;
        }

        $sessionId = $this->request->getSession()->read(static::ACTIVE_CONFIG_SESSION_KEY);
        if ($sessionId !== null) {
            $fromSession = $table->find()->where(['id' => (int)$sessionId])->first();
            if ($fromSession !== null) {
                return $fromSession;
            }
            // Stale session value (record deleted): drop it and re-resolve.
            $this->request->getSession()->delete(static::ACTIVE_CONFIG_SESSION_KEY);
        }

        $ativo = $table->find()
            ->where(['ativo' => true])
            ->orderBy(['modified' => 'DESC'])
            ->first();
        if ($ativo !== null) {
            return $ativo;
        }

        return $table->find()
            ->orderBy(['modified' => 'DESC'])
            ->first();
    }

    /**
     * Id of the active configuraplanejamento, ready to be used as the default
     * value by any controller; null when no semester exists yet.
     */
    protected function getActiveConfiguraplanejamentoId(): ?int
    {
        $config = $this->getActiveConfiguraplanejamento();

        return $config?->id;
    }

    /**
     * Persists the given configuraplanejamento as the active semester in the
     * session. Pass null to forget the persisted choice (e.g. after deleting
     * the active record), letting the DB flag / last-semester rule take over.
     */
    protected function setActiveConfiguraplanejamentoId(?int $id): void
    {
        $session = $this->request->getSession();
        if ($id === null) {
            $session->delete(static::ACTIVE_CONFIG_SESSION_KEY);

            return;
        }
        $session->write(static::ACTIVE_CONFIG_SESSION_KEY, $id);
    }
}
