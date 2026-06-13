<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ConfiguraplanejamentosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('configuraplanejamentos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarioplanejamentos', [
            'foreignKey' => 'usuarioplanejamento_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('Planejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('usuarioplanejamento_id')
            ->notEmptyString('usuarioplanejamento_id')
            ->scalar('nome')
            ->maxLength('nome', 100)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome')
            ->scalar('semestre')
            ->maxLength('semestre', 20)
            ->requirePresence('semestre', 'create')
            ->notEmptyString('semestre')
            ->integer('versao')
            ->allowEmptyString('versao')
            ->boolean('ativo')
            ->allowEmptyString('ativo');
        return $validator;
    }
}
