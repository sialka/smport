<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use \Cake\ORM\TableRegistry;

class UpperBehavior extends Behavior {

    private $configs = [];

    public function initialize(array $config) {
        $this->configs = $config;
    }

    public function beforeSave(Event $event, EntityInterface $entity) {
        $schema = $this->_table->schema();
        foreach ($schema->columns() as $key => $value) {
            if (isset($this->configs['except']) && in_array($value, $this->configs['except'])) {
                continue;
            }

            if ($schema->columnType($value) === 'string' || $schema->columnType($value) === 'text') {
                $entity->{$value} = mb_strtoupper($entity->{$value});
            }
        }
    }
}
