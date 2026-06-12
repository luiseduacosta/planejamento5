<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Horarios Model
 *
 * @method \App\Model\Entity\Horario newEmptyEntity()
 * @method \App\Model\Entity\Horario newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Horario> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Horario get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Horario findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Horario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Horario> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Horario|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Horario saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Horario* deleteOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 */
class HorariosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('horarios');
        $this->setDisplayField('horario');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('horario')
            ->maxLength('horario', 50)
            ->requirePresence('horario', 'create')
            ->notEmptyString('horario');

        $validator
            ->integer('ordem')
            ->requirePresence('ordem', 'create')
            ->notEmptyString('ordem');

        return $validator;
    }
}
