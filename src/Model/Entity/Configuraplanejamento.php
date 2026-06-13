<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Configuraplanejamento Entity
 */
class Configuraplanejamento extends Entity
{
    protected array $_accessible = [
        'usuarioplanejamento_id' => true,
        'nome' => true,
        'semestre' => true,
        'versao' => true,
        'ativo' => true,
        'created' => true,
        'modified' => true,
    ];
}
