<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Turmaotp extends Entity
{
    protected array $_accessible = [
        'configuraplanejamento_id' => true,
        'turno' => true,
        'periodo' => true,
        'turmaotp' => true,
        'docente_id' => true,
        'dia_id' => true,
        'horario_id' => true,
        'sala_id' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
        'configuraplanejamento' => true,
        'docente' => true,
        'dia' => true,
        'horario' => true,
        'sala' => true,
    ];
}
