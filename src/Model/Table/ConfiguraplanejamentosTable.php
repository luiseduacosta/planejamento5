<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
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

        $this->hasMany('DocenteDisponibilidades', [
            'foreignKey' => 'configuraplanejamento_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('usuarioplanejamento_id')
            ->notEmptyString('usuarioplanejamento_id');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 100)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('semestre')
            ->maxLength('semestre', 20)
            ->requirePresence('semestre', 'create')
            ->notEmptyString('semestre');

        $validator      
            ->integer('versao')
            ->allowEmptyString('versao');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }

    /**
     * Keeps the "one active semester" invariant: whenever a record is saved
     * with ativo=true, every other record is deactivated.
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options): void
    {
        if ($entity->get('ativo') === true) {
            $this->updateAll(['ativo' => false], ['id !=' => $entity->get('id')]);
        }
    }

    /**
     * The app requires one semester to always be active: if no record carries
     * the ativo flag (e.g. the active one was just deleted), activate the most
     * recently modified one ("last semester" default).
     */
    public function afterSaveCommit(EventInterface $event, EntityInterface $entity, \ArrayObject $options): void
    {
        $hasAtivo = $this->find()->where(['ativo' => true])->count() > 0;
        if ($hasAtivo) {
            return;
        }

        $fallback = $this->find()->orderBy(['modified' => 'DESC'])->first();
        if ($fallback !== null) {
            $this->updateAll(['ativo' => true], ['id' => $fallback->get('id')]);
        }
    }
}
