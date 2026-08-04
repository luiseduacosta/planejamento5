<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DocenteDisponibilidadesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('docente_disponibilidades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Configuraplanejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
            'joinType' => 'INNER',
        ]);

        // Garante que a coluna TINYINT do MySQL seja sempre hidratada como
        // boolean do PHP (em vez de string "0"/"1" ou int 0/1). Sem isso,
        // (bool)"0" = true, pois string não-vazia → true, e displays como
        // "Disponível" mesmo quando o registro diz indisponível.
        $this->getSchema()->setColumnType('disponivel', 'boolean');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('docente_id')
            ->notEmptyString('docente_id');

        $validator
            ->integer('configuraplanejamento_id')
            ->notEmptyString('configuraplanejamento_id');

        $validator
            ->boolean('disponivel')
            ->notEmptyString('disponivel');

        $validator
            ->scalar('motivo')
            ->maxLength('motivo', 100)
            ->allowEmptyString('motivo');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['docente_id'], 'Docentes'), ['errorField' => 'docente_id']);
        $rules->add($rules->existsIn(['configuraplanejamento_id'], 'Configuraplanejamentos'), ['errorField' => 'configuraplanejamento_id']);

        return $rules;
    }

    public function findForDocente(SelectQuery $query, array $options): SelectQuery
    {
        $docenteId = $options['docente_id'] ?? null;
        if ($docenteId === null) {
            return $query;
        }

        return $query->where(['DocenteDisponibilidades.docente_id' => $docenteId]);
    }
}

