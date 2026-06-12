<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class EmentasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('ementas');
        $this->setDisplayField('disciplina_id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Disciplinas', [
            'foreignKey' => 'disciplina_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('disciplina_id')
            ->notEmptyString('disciplina_id')
            ->scalar('conteudo_programatico')
            ->allowEmptyString('conteudo_programatico')
            ->scalar('objetivos')
            ->allowEmptyString('objetivos')
            ->scalar('bibliografia_basica')
            ->allowEmptyString('bibliografia_basica')
            ->scalar('bibliografia_complementar')
            ->allowEmptyString('bibliografia_complementar');
        return $validator;
    }
}
