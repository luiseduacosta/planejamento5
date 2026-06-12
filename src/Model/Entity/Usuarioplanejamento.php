<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usuarioplanejamento Entity
 */
class Usuarioplanejamento extends Entity
{
    protected array $_accessible = [
        'username' => true,
        'password' => true,
        'role' => true,
        'email' => true,
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
}
