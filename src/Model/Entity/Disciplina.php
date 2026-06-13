<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Disciplina Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $disciplina
 * @property string|null $creditos
 * @property string|null $carga_horaria
 * @property int|null $periodo_diurno
 * @property int|null $periodo_noturno
 * @property string|null $requisitos
 * @property string|null $optativa
 * @property string|null $departamento
 * @property string|null $observacoes
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Disciplina extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
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
