<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Disciplina Entity
 *
 * @property int $id
 * @property string $codigo
 * @property string $nome
 * @property int|null $carga_horaria
 * @property string|null $ementa
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
        'nome' => true,
        'carga_horaria' => true,
        'ementa' => true,
        'created' => true,
        'modified' => true,
    ];
}
