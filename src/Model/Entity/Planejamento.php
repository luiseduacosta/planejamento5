<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Planejamento Entity
 */
class Planejamento extends Entity
{
    protected array $_accessible = [
        'disciplina_id' => true,
        'docente_id' => true,
        'configuraplanejamento_id' => true,
        'periodo' => true,
        'turno' => true,
        'sala_id' => true,
        'dia_id' => true,
        'horario_id' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
    ];
}
