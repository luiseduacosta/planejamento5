<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Optativa Entity
 */
class Optativa extends Entity
{
    protected array $_accessible = [
        'codigo' => true,
        'nome' => true,
        'carga_horaria' => true,
        'ementa' => true,
        'created' => true,
        'modified' => true,
    ];
}
