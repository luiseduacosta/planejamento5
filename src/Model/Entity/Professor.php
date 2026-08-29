<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Professor Entity
 *
 * @property int $id
 * @property string $nome
 * @property string|null $cpf
 * @property int|null $siape
 * @property string|null $cress
 * @property string|null $regiao
 * @property int|null $codigo_telefone
 * @property string|null $telefone
 * @property int|null $codigo_celular
 * @property string|null $celular
 * @property string|null $email
 * @property string|null $curriculolattes
 * @property \Cake\I18n\Date|null $atualizacaolattes
 * @property \Cake\I18n\Date|null $dataingresso
 * @property string|null $departamento
 * @property \Cake\I18n\Date|null $dataegresso
 * @property string|null $motivoegresso
 * @property string $status
 * @property int|null $user_id
 * @property string|null $observacoes
 * @property int|null $estagiarios_count
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Professor extends Entity
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
        'codigo_telefone' => true,
        'telefone' => true,
        'codigo_celular' => true,
        'celular' => true,
        'email' => true,
        'curriculolattes' => true,
        'atualizacaolattes' => true,
        'dataingresso' => true,
        'departamento' => true,
        'dataegresso' => true,
        'motivoegresso' => true,
        'status' => true,
        'user_id' => true,
        'estagiarios_count' => true,
        'observacoes' => true,
        'created' => true,
        'modified' => true,
    ];
}
