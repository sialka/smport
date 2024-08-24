<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Network\Session;

class PanelsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('panels');
        $this->setDisplayField('senha');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        

    }
    
}
