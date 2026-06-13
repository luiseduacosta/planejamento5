<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Disciplinas Model
 */
class DisciplinasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('disciplinas');
        $this->setDisplayField('disciplina');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Planejamentos', [
            'foreignKey' => 'disciplina_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('codigo')
            ->maxLength('codigo', 50)
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('disciplina')
            ->maxLength('disciplina', 200)
            ->requirePresence('disciplina', 'create')
            ->notEmptyString('disciplina');

        $validator
            ->scalar('creditos')
            ->allowEmptyString('creditos');

        $validator
            ->integer('carga_horaria')
            ->allowEmptyString('carga_horaria');

        $validator
            ->integer('periodo_diurno')
            ->allowEmptyString('periodo_diurno');

        $validator
            ->integer('periodo_noturno')
            ->allowEmptyString('periodo_noturno');

        $validator
            ->scalar('requisitos')
            ->allowEmptyString('requisitos');

        $validator
            ->scalar('optativa')
            ->allowEmptyString('optativa');

        $validator
            ->scalar('departamento')
            ->allowEmptyString('departamento');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        return $validator;
    }
}
