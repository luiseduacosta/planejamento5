<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class DocenteDisponibilidade extends Entity
{
    protected array $_accessible = [
        'docente_id' => true,
        'configuraplanejamento_id' => true,
        'disponivel' => true,
        'motivo' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
        'docente' => true,
        'configuraplanejamento' => true,
    ];
}
