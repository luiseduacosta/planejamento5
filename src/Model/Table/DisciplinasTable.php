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
            ->integer('creditos')
            ->allowEmptyString('creditos');

        $validator
            ->scalar('carga_horaria')
            ->allowEmptyString('carga_horaria');

        $validator
            ->integer('periodo_diurno')
            ->inList('periodo_diurno', [1, 2, 3, 4, 5, 6, 7, 8])
            ->allowEmptyString('periodo_diurno');

        $validator
            ->integer('periodo_noturno')
            ->inList('periodo_noturno', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
            ->allowEmptyString('periodo_noturno');

        $validator
            ->scalar('requisitos')
            ->allowEmptyString('requisitos');

        $validator
            ->boolean('optativa', '1 => Sim 0 => Não')
            ->allowEmptyString('optativa');

        $validator
            ->scalar('departamento')
            ->allowEmptyString('departamento', 'create');

        $validator
            ->scalar('curriculo')
            ->maxLength('curriculo', 4)
            ->allowEmptyString('curriculo');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes', 'create');

        return $validator;
    }
}
