<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlanejamentosTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('planejamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Disciplinas', [
            'foreignKey' => 'disciplina_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Configuraplanejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Salas', [
            'foreignKey' => 'sala_id',
        ]);
        $this->belongsTo('Dias', [
            'foreignKey' => 'dia_id',
        ]);
        $this->belongsTo('Horarios', [
            'foreignKey' => 'horario_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('disciplina_id')->notEmptyString('disciplina_id')
            ->integer('docente_id')->allowEmptyString('docente_id')
            ->integer('configuraplanejamento_id')->notEmptyString('configuraplanejamento_id')
            ->integer('periodo')->allowEmptyString('periodo')
            ->scalar('turno')->allowEmptyString('turno')
            ->integer('sala_id')->allowEmptyString('sala_id')
            ->integer('dia_id')->allowEmptyString('dia_id')
            ->integer('horario_id')->allowEmptyString('horario_id')
            ->scalar('observacoes')->allowEmptyString('observacoes');
        return $validator;
    }
}
