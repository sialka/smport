<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MunicipiosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('municipios');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        

    }
  
    public function aevOptions(){

        $municipios = $this->find('list')->order(['nome' => 'asc'])->toArray();       
        
        $aevOptions = [
            'municipio' => $municipios,     
        ];

        return $aevOptions;
    }
}
