<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Docente Entity
 *
 * @property int $id
 * @property string $nome
 * @property string|null $titulo
 * @property string|null $departamento
 * @property string|null $email
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Docente extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nome' => true,
        'titulo' => true,
        'departamento' => true,
        'email' => true,
        'created' => true,
        'modified' => true,
    ];
}
