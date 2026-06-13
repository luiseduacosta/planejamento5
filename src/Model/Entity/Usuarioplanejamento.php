<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Usuarioplanejamento Entity
 */
class Usuarioplanejamento extends Entity
{
    protected array $_accessible = [
        'email' => true,
        'password' => true,
        'role' => true,
        'nome' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * Hide password from array output
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }
}
