<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Salas Model
 *
 * @method \App\Model\Entity\Sala newEmptyEntity()
 * @method \App\Model\Entity\Sala newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Sala> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sala get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Sala findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Sala patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Sala> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sala|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Sala saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Sala* deleteOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 */
class SalasTable extends Table
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

        $this->setTable('salas');
        $this->setDisplayField('sala');
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
            ->scalar('sala')
            ->maxLength('sala', 100)
            ->requirePresence('sala', 'create')
            ->notEmptyString('sala');

        return $validator;
    }
}
