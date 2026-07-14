<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Docentes Model
 */
class DocentesTable extends Table
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

        $this->setTable('docentes');
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
            ->maxLength('nome', 200)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('cpf')
            ->allowEmptyString('cpf');

        $validator
            ->scalar('siape')
            ->allowEmptyString('siape');

        $validator
            ->scalar('cress')
            ->allowEmptyString('cress');

        $validator
            ->scalar('regiao')
            ->allowEmptyString('regiao');

        $validator
            ->scalar('telefone')
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->allowEmptyString('celular');

        $validator
            ->scalar('departamento')
            ->allowEmptyString('departamento');

        $validator
            ->email('email', false)
            ->allowEmptyString('email');

        $validator
            ->date('dataingresso')
            ->allowEmptyDate('dataingresso');

        $validator
            ->scalar('tipocargo')
            ->allowEmptyString('tipocargo');
            
        $validator
            ->date('dataegresso')
            ->allowEmptyDate('dataegresso');

        $validator
            ->scalar('motivoegresso')
            ->allowEmptyString('motivoegresso');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

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
