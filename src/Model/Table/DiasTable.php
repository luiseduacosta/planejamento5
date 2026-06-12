<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dias Model
 *
 * @method \App\Model\Entity\Dia newEmptyEntity()
 * @method \App\Model\Entity\Dia newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Dia> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dia get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Dia findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Dia patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Dia> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dia|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Dia saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Dia* deleteOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 */
class DiasTable extends Table
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

        $this->setTable('dias');
        $this->setDisplayField('dia');
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
            ->scalar('dia')
            ->maxLength('dia', 50)
            ->requirePresence('dia', 'create')
            ->notEmptyString('dia');

        $validator
            ->integer('ordem')
            ->requirePresence('ordem', 'create')
            ->notEmptyString('ordem');

        return $validator;
    }
}
