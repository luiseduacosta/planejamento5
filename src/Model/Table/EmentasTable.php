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
        $this->setDisplayField('titulo');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Configuraplanejamentos', [
            'foreignKey' => 'configuraplanejamento_id',
        ]);

        $this->belongsTo('Disciplinas', [
            'foreignKey' => 'disciplina_id',
        ]);

        $this->belongsTo('Optativas', [
            'foreignKey' => 'optativa_id',
        ]);

        $this->belongsTo('Docentes', [
            'foreignKey' => 'docente_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('configuraplanejamento_id')
            ->allowEmptyString('configuraplanejamento_id')
            ->integer('disciplina_id')
            ->allowEmptyString('disciplina_id')
            ->integer('optativa_id')
            ->allowEmptyString('optativa_id')
            ->integer('docente_id')
            ->allowEmptyString('docente_id')
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->allowEmptyString('titulo')
            ->scalar('ementa')
            ->allowEmptyString('ementa');
        return $validator;
    }
}
