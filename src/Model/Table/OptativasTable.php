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
        $this->setDisplayField('disciplina');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
    $validator
        ->scalar('codigo')->maxLength('codigo', 50)->requirePresence('codigo', 'create')->notEmptyString('codigo')
        ->scalar('disciplina')->maxLength('disciplina', 200)->requirePresence('disciplina', 'create')->notEmptyString('disciplina')
        ->integer('creditos')->allowEmptyString('creditos')
        ->scalar('carga_horaria')->allowEmptyString('carga_horaria')
        ->integer('periodo_diurno')->allowEmptyString('periodo_diurno')
        ->integer('periodo_noturno')->allowEmptyString('periodo_noturno')
        ->scalar('requisitos')->allowEmptyString('requisitos')
        ->boolean('optativa')->allowEmptyString('optativa')
        ->scalar('departamento')->allowEmptyString('departamento')
        ->scalar('observacoes')->allowEmptyString('observacoes')
        ;
        return $validator;
    }
}
