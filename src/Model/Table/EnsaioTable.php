<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class EnsaioTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('ensaio');
        $this->setDisplayField('localidade_id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


        $this->hasOne('Localidades', [
            'className'         => 'Localidades',
            'bindingKey'        => 'localidade_id',
            'foreignKey'        => 'id',
            'propertyName'      => 'Localidades',
        ]);

        $this->hasOne('Horarios', [
            'className'         => 'Horarios',
            'bindingKey'        => 'horario_id',
            'foreignKey'        => 'id',
            'propertyName'      => 'Horarios',
        ]);

    }

    public function aevOptions(){

        $horariosTable = TableRegistry::get('horarios');
        $horarios = $horariosTable->find('list')->order(['hora' => 'asc'])->toArray();

        return [
            'hora' => $horarios,
            'semana' => [
                1 => '1ª Semana',
                2 => '2ª Semana',
                3 => '3ª Semana',
                4 => '4ª Semana',
                5 => 'Ultima Semana'
            ],
        ];

    }

}
