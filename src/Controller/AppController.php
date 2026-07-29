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

use Cake\Controller\Controller;

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
     * Session key that keeps the active planning configuration for the session.
     */
    protected const ACTIVE_CONFIGURAPLANEJAMENTO_SESSION_KEY = 'Configuraplanejamento.ativo';

    /**
     * Resolve the active planning configuration id for the current session.
     *
     * Resolution order:
     * 1. The value stored in the session (the one the user is working with),
     *    kept until the user switches to another configuration.
     * 2. When nothing is selected, the record flagged as `ativo` in the table.
     * 3. When nothing is `ativo`, the most recently modified record.
     *
     * @return int|null The active configuration id or null when none exists.
     */
    protected function getActiveConfiguraplanejamentoId(): ?int
    {
        $session = $this->getRequest()->getSession();
        $table = $this->fetchTable('Configuraplanejamentos');

        $sessionId = $session->read(self::ACTIVE_CONFIGURAPLANEJAMENTO_SESSION_KEY);
        if ($sessionId !== null) {
            if ($table->exists(['id' => (int)$sessionId])) {
                return (int)$sessionId;
            }
            // The stored configuration no longer exists; drop it and fall back.
            $session->delete(self::ACTIVE_CONFIGURAPLANEJAMENTO_SESSION_KEY);
        }

        // Nothing selected: use the configuration flagged as active.
        $configuracao = $table->find()
            ->where(['ativo' => true])
            ->orderBy(['modified' => 'DESC'])
            ->first();

        // Nothing active: use the most recently modified configuration.
        if ($configuracao === null) {
            $configuracao = $table->find()
                ->orderBy(['modified' => 'DESC'])
                ->first();
        }

        return $configuracao?->id;
    }

    /**
     * Persist the active planning configuration id for the whole session.
     *
     * @param int $id The configuration id to keep as active.
     * @return void
     */
    protected function setActiveConfiguraplanejamentoId(int $id): void
    {
        $this->getRequest()->getSession()->write(self::ACTIVE_CONFIGURAPLANEJAMENTO_SESSION_KEY, $id);
    }
}
