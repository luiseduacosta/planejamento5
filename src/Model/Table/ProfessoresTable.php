<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Professores Model
 */
class ProfessoresTable extends Table
{
    private const STATUS_NORMALIZATION_MAP = [
        'active' => 'ativo',
        'activo' => 'ativo',
        'retired' => 'aposentado',
        'inactive' => 'inativo',
        'inactivo' => 'inativo',
    ];

    /**
     * Initialize method
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('professores');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Planejamentos', [
            'foreignKey' => 'docente_id',
        ]);

        $this->hasMany('DocenteDisponibilidades', [
            'foreignKey' => 'docente_id',
        ]);
    }

    /**
     * Default validation rules.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('nome')
            ->maxLength('nome', 50)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 15)
            ->allowEmptyString('cpf');

        $validator
            ->integer('siape')
            ->allowEmptyString('siape');

        $validator
            ->scalar('cress')
            ->maxLength('cress', 10)
            ->allowEmptyString('cress');

        $validator
            ->scalar('regiao')
            ->maxLength('regiao', 2)
            ->allowEmptyString('regiao');

        $validator
            ->integer('codigo_telefone')
            ->allowEmptyString('codigo_telefone');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 15)
            ->allowEmptyString('telefone');

        $validator
            ->integer('codigo_celular')
            ->allowEmptyString('codigo_celular');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 15)
            ->allowEmptyString('celular');

        $validator
            ->scalar('departamento')
            ->maxLength('departamento', 30)
            ->allowEmptyString('departamento');

        $validator
            ->email('email', false)
            ->maxLength('email', 255)
            ->allowEmptyString('email');

        $validator
            ->date('dataingresso')
            ->allowEmptyDate('dataingresso');

        $validator
            ->date('dataegresso')
            ->allowEmptyDate('dataegresso');

        $validator
            ->scalar('motivoegresso')
            ->maxLength('motivoegresso', 100)
            ->allowEmptyString('motivoegresso');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->integer('estagiario_count')
            ->allowEmptyString('estagiario_count');

        $validator
            ->integer('estagiarios_count')
            ->allowEmptyString('estagiarios_count');

        return $validator;
    }

    public function beforeMarshal(\Cake\Event\EventInterface $_event, ArrayObject $data, ArrayObject $_options): void
    {
        unset($_event, $_options);

        $status = $data['status'] ?? null;
        if (!\is_string($status)) {
            return;
        }

        $data['status'] = self::STATUS_NORMALIZATION_MAP[$status] ?? $status;
    }
}
