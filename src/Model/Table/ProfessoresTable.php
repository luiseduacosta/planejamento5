<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use function is_string;
use Cake\I18n\FrozenTime;

/**
 * Professores Model
 */
class ProfessoresTable extends Table
{
    public const STATUS_ATIVO = 'ativo';
    public const STATUS_APOSENTADO = 'aposentado';
    public const STATUS_INATIVO = 'inativo';

    private const STATUS_NORMALIZATION_MAP = [
        'active' => self::STATUS_ATIVO,
        'activo' => self::STATUS_ATIVO,
        'retired' => self::STATUS_APOSENTADO,
        'inactive' => self::STATUS_INATIVO,
        'inactivo' => self::STATUS_INATIVO,
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
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always',
                ],
            ],
        ]);

        $this->hasMany('Planejamentos', [
            'foreignKey' => 'docente_id',
        ]);

        $this->hasMany('DocenteDisponibilidades', [
            'foreignKey' => 'docente_id',
            'dependent' => true,
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
            ->scalar('codigo_telefone')
            ->maxLength('codigo_telefone', 2)
            ->allowEmptyString('codigo_telefone');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 20)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('codigo_celular')
            ->maxLength('codigo_celular', 2)
            ->allowEmptyString('codigo_celular');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 20)
            ->allowEmptyString('celular');

        $validator
            ->scalar('departamento')
            ->maxLength('departamento', 30)
            ->allowEmptyString('departamento');

        $validator
            ->scalar('email')
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
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        $validator
            ->scalar('status')
            ->maxLength('status', 10)
            ->inList('status', [
                self::STATUS_ATIVO,
                self::STATUS_APOSENTADO,
                self::STATUS_INATIVO,
            ], 'Status deve ser um de: ativo, aposentado, inativo.')
            ->allowEmptyString('status');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->integer('estagiarios_count')
            ->allowEmptyString('estagiarios_count');

        return $validator;
    }

    /**
     * Application rules: block deletion of a docente that still has planejamentos.
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete(
            fn($entity, $operation) => !$this->Planejamentos->exists(['docente_id' => $entity->id]),
            'hasPlanejamentos',
            ['errorField' => 'id', 'message' => 'O docente possui planejamentos vinculados e não pode ser excluído.'],
        );

        return $rules;
    }

    /**
     * Normalizes status aliases ("active" -> "ativo"...) before validation.
     * An empty status is dropped so the current value (or the "ativo"
     * default) is kept instead of overwriting it with an empty string.
     */
    public function beforeMarshal(EventInterface $_event, ArrayObject $data, ArrayObject $_options): void
    {
        unset($_event, $_options);

        $status = $data['status'] ?? null;
        if ($status === '') {
            // An empty status keeps the current value (or the "ativo" default on create).
            unset($data['status']);

            return;
        }
        if (!is_string($status)) {
            return;
        }

        $data['status'] = self::STATUS_NORMALIZATION_MAP[$status] ?? $status;
    }

    /**
     * Ensure timestamps are set if the Timestamp behavior could not (fixtures
     * created the columns after the table schema was cached).
     */
    public function beforeSave(EventInterface $event, $entity, ArrayObject $options): void
    {
        unset($event, $options);

        $now = FrozenTime::now();

        if ($entity->isNew() && $entity->get('created') === null) {
            $entity->set('created', $now);
        }
        if ($entity->get('modified') === null) {
            $entity->set('modified', $now);
        }
    }
}
