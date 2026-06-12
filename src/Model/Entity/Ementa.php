<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ementa Entity
 */
class Ementa extends Entity
{
    protected array $_accessible = [
        'disciplina_id' => true,
        'conteudo_programatico' => true,
        'objetivos' => true,
        'bibliografia_basica' => true,
        'bibliografia_complementar' => true,
        'created' => true,
        'modified' => true,
    ];
}
