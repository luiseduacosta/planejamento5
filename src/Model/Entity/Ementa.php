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
        'configuraplanejamento_id' => true,
        'disciplina_id' => true,
        'optativa_id' => true,
        'docente_id' => true,
        'titulo' => true,
        'ementa' => true,
        'created' => true,
        'modified' => true,
    ];
}
