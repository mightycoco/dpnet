<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null)
 */
class EventsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('events');
        $this->displayField('id');
        $this->primaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('datasource', 'create')
            ->notEmpty('datasource');

        $validator
            ->requirePresence('event_description', 'create')
            ->notEmpty('event_description');

        $validator
            ->requirePresence('event_name', 'create')
            ->notEmpty('event_name');

        $validator
            ->requirePresence('event_approval', 'create')
            ->notEmpty('event_approval');

        $validator
            ->dateTime('event_start')
            ->allowEmpty('event_start');

        $validator
            ->dateTime('event_end')
            ->allowEmpty('event_end');

        $validator
            ->requirePresence('place_name', 'create')
            ->notEmpty('place_name');

        $validator
            ->requirePresence('loc_city', 'create')
            ->notEmpty('loc_city');

        $validator
            ->requirePresence('loc_country', 'create')
            ->notEmpty('loc_country');

        $validator
            ->requirePresence('loc_street', 'create')
            ->notEmpty('loc_street');

        $validator
            ->requirePresence('loc_zip', 'create')
            ->notEmpty('loc_zip');

        $validator
            ->numeric('loc_latitude')
            ->requirePresence('loc_latitude', 'create')
            ->notEmpty('loc_latitude');

        $validator
            ->numeric('loc_longitude')
            ->requirePresence('loc_longitude', 'create')
            ->notEmpty('loc_longitude');

        return $validator;
    }
}
