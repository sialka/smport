<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Panel extends Entity
{
    
  protected $_accessible = [
    '*' => true,
  ];
  
}