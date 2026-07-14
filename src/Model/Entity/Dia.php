<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dia Entity
 *
 * @property int $id
 * @property string $dia
 * @property int $ordem
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Dia extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'dia' => true,
        'ordem' => true,
        'created' => true,
        'modified' => true,
    ];
}
