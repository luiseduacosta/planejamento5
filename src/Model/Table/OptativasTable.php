<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class OptativasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('optativas');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('codigo')->maxLength('codigo', 50)->requirePresence('codigo', 'create')->notEmptyString('codigo')
            ->scalar('nome')->maxLength('nome', 200)->requirePresence('nome', 'create')->notEmptyString('nome')
            ->integer('carga_horaria')->allowEmptyString('carga_horaria')
            ->scalar('ementa')->allowEmptyString('ementa');
        return $validator;
    }
}
