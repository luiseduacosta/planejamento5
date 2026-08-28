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
            ->maxLength('cpf', 14)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('siape')
            ->maxLength('siape', 20)
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
            ->scalar('telefone')
            ->maxLength('telefone', 20)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 20)
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
            ->scalar('tipocargo')
            ->maxLength('tipocargo', 20)
            ->allowEmptyString('tipocargo');

        $validator
            ->date('dataegresso')
            ->allowEmptyDate('dataegresso');

        $validator
            ->scalar('motivoegresso')
            ->maxLength('motivoegresso', 100)
            ->allowEmptyString('motivoegresso');

        $validator
            ->scalar('status')
            ->maxLength('status', 10)
            ->allowEmptyString('status');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        return $validator;
    }

    public function beforeMarshal(\Cake\Event\EventInterface $_event, ArrayObject $data, ArrayObject $_options): void
    {
        unset($_event, $_options);

        $status = $data['status'] ?? null;
        if (!\is_string($status)) {
            return;
        }

        // Normaliza caixa/espaços antes de procurar na tabela de alias para
        // ficar consistente com DocentesController::canonicalStatus().
        $status = mb_strtolower(trim($status));
        $data['status'] = self::STATUS_NORMALIZATION_MAP[$status] ?? $status;
    }
}
