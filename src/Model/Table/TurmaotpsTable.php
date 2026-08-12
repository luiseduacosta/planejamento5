<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TurmaotpsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('turmaotps');
        $this->setDisplayField('turmaotp');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Configuraplanejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
        ]);
        $this->belongsTo('Dias', [
            'foreignKey' => 'dia_id',
        ]);
        $this->belongsTo('Horarios', [
            'foreignKey' => 'horario_id',
        ]);
        $this->belongsTo('Salas', [
            'foreignKey' => 'sala_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('configuraplanejamento_id')
            ->notEmptyString('configuraplanejamento_id');

        $validator
            ->scalar('turno')
            ->maxLength('turno', 10)
            ->allowEmptyString('turno');

        $validator
            ->integer('periodo')
            ->allowEmptyString('periodo')
            ->range('periodo', [1, 10], __('O período deve estar entre 1 e 10.'));

        $validator
            ->scalar('turmaotp')
            ->maxLength('turmaotp', 20)
            ->requirePresence('turmaotp', 'create')
            ->notEmptyString('turmaotp');

        $validator
            ->integer('docente_id')
            ->allowEmptyString('docente_id');

        $validator
            ->integer('dia_id')
            ->allowEmptyString('dia_id');

        $validator
            ->integer('horario_id')
            ->allowEmptyString('horario_id');

        $validator
            ->integer('sala_id')
            ->allowEmptyString('sala_id');

        $validator
            ->scalar('observacoes')
            ->maxLength('observacoes', 255)
            ->allowEmptyString('observacoes');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Campos opcionais (docente/dia/horario/sala) passam quando nulos;
        // quando preenchidos, o valor precisa existir na tabela relacionada.
        $rules->add($rules->existsIn(['configuraplanejamento_id'], 'Configuraplanejamentos'), ['errorField' => 'configuraplanejamento_id']);
        $rules->add($rules->existsIn(['docente_id'], 'Docentes'), ['errorField' => 'docente_id']);
        $rules->add($rules->existsIn(['dia_id'], 'Dias'), ['errorField' => 'dia_id']);
        $rules->add($rules->existsIn(['horario_id'], 'Horarios'), ['errorField' => 'horario_id']);
        $rules->add($rules->existsIn(['sala_id'], 'Salas'), ['errorField' => 'sala_id']);

        return $rules;
    }
}
