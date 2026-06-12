<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * Handle user authentication (login/logout)
 */
class UsersController extends AppController
{
    /**
     * Before filter - allow login without authentication
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        
        // Allow login action without being logged in
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    /**
     * Login action
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        
        // Try to authenticate user
        $result = $this->Authentication->getResult();
        
        // If user is logged in, redirect to dashboard
        if ($result->isValid()) {
            $this->Flash->success(__('Bem-vindo, {0}!', $this->request->getAttribute('identity')->username));
            
            return $this->redirect([
                'controller' => 'Planejamentos',
                'action' => 'listar'
            ]);
        }
        
        // Show error on POST if authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Usuário ou senha inválidos. Tente novamente.'));
        }
    }

    /**
     * Logout action
     */
    public function logout()
    {
        $this->Flash->success(__('Até mais! Você foi deslogado.'));
        
        return $this->redirect($this->Authentication->logout());
    }

    /**
     * Profile action (view/edit own profile)
     */
    public function profile()
    {
        // Get current user
        $user = $this->Authentication->getIdentity();
        
        if (!$user) {
            $this->Flash->error(__('Você precisa estar logado.'));
            return $this->redirect(['action' => 'login']);
        }
        
        $this->set('user', $user);
    }
}
