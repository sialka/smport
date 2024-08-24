<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class HorariosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('horarios');
        $this->setDisplayField('hora');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        

    }

    public function aevOptions(){

        //$horariosTable = TableRegistry::get('horarios');                            
        $horarios = $this->find('list')->order(['hora' => 'asc'])->toArray();
        
        $aevOptions = [        
            'hora' => $horarios,        
        ];

        return $aevOptions;
    }
  
}
