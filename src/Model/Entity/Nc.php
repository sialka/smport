<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Nc extends Entity
{
    protected $_accessible = [
        '*' => true,
    ];
    
    
    protected function _getMesTrabalho()
    {
        return $this->_properties['ano'] . '/' .
            $this->_properties['mes'];
    }    

    protected function _getMesOcorrencia()
    {
        
        $ano = substr($this->_properties['ano'], 2, 2);
        
        $meses = [
            '01' => 'jan',
            '02' => 'fev',
            '03' => 'mar',
            '04' => 'abr',
            '05' => 'mai',
            '06' => 'jun',
            '07' => 'jul',
            '08' => 'ago',
            '09' => 'set',
            '10' => 'out',
            '11' => 'nov',
            '12' => 'dez',
        ];
        
        return $meses[$this->_properties['mes']] . '/' .$ano;
    }    
}