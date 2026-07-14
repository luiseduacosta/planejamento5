<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Docente Entity
 *
 * @property int $id
 * @property string $nome
 * @property string |null $cpf
 * @property string |null $siape
 * @property string |null $cress
 * @property string |null $regiao
 * @property string |null $telefone
 * @property string |null $celular
 * @property string |null $email
 * @property \Cake\I18n\Date|null $dataingresso
 * @property string |null $tipocargo
 * @property string |null $departamento
 * @property \Cake\I18n\Date|null $dataegresso
 * @property string |null $motivoegresso
 * @property string |null $observacoes
 * @property string |null $status
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
        'cpf' => true,
        'siape' => true,
        'cress' => true,
        'regiao' => true,
        'telefone' => true,
        'celular' => true,
        'email' => true,
        'dataingresso' => true,
        'tipocargo' => true,
        'departamento' => true,
        'dataegresso' => true,
        'motivoegresso' => true,
        'observacoes' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];
}
