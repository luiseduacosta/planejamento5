<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ementa Entity
 * 
 * @property int $id
 * @property int $configuraplanejamento_id
 * @property int $disciplina_id
 * @property int $optativa_id
 * @property int $docente_id
 * @property string $titulo
 * @property string $ementa
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
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
