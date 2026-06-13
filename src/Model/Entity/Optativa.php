<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Optativa Entity
 * @property int $id
 * @property string $codigo
 * @property string $disciplina
 * @property int $creditos
 * @property string $carga_horaria
 * @property int $periodo_diurno
 * @property int $periodo_noturno
 * @property string $requistos
 * @property boolean $optativa
 * @property string $departamento
 * @property string $observacoes
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Optativa extends Entity
{
    protected array $_accessible = [
        'codigo' => true,
        'disciplina' => true,
        'creditos' => true,
        'carga_horaria' => true,
        'periodo_diurno' => true,
        'periodo_noturno' => true,
        'requisitos' => true,
        'optativa' => true,
        'departamento' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
    ];
}
