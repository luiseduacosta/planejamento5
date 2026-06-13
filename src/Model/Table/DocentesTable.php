<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Docentes Model
 */
class DocentesTable extends Table
{
    /**
     * Initialize method
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('docentes');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Planejamentos', [
            'foreignKey' => 'docente_id',
        ]);

        $this->hasMany('DocenteDisponibilidades', [
            'foreignKey' => 'docente_id',
        ]);
    }

    /**
     * Default validation rules.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nome')
            ->maxLength('nome', 200)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('cpf')
            ->allowEmptyString('cpf');

        $validator
            ->scalar('siape')
            ->allowEmptyString('siape');

        $validator
            ->scalar('cress')
            ->allowEmptyString('cress');

        $validator
            ->scalar('regiao')
            ->allowEmptyString('regiao');

        $validator
            ->scalar('telefone')
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->allowEmptyString('celular');

        $validator
            ->scalar('departamento')
            ->allowEmptyString('departamento');

        $validator
            ->email('email', false)
            ->allowEmptyString('email');

        $validator
            ->date('dataingresso')
            ->allowEmptyDate('dataingresso');

        $validator
            ->date('dataegresso')
            ->allowEmptyDate('dataegresso');

        $validator
            ->scalar('motivoegresso')
            ->allowEmptyString('motivoegresso');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
