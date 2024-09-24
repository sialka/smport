<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
    protected $_accessible = [
        'nome' => true,
        'email' => true,
        'status' => true,
        'username' => true,
        'password' => true,
        'status'  => true,
        'created' => true,
        'modified' => true,
    ];

    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($password){
        if (strlen($password) > 0) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($password);
        }
    }

    protected function _setUsername($username){
        return mb_strtoupper($username);
    }

    protected function _setNome($nome){
        $arr   = explode(" ", $nome);
        $final = "";

        foreach($arr as $parte){

            if(in_array(mb_strtoupper($parte), ['de', 'da', 'das', 'do', 'dos']) ){
                $final .= mb_strtoupper($parte). " ";
            }else{
                $final .= ucwords(mb_strtoupper($parte)) . " ";
            }

        }

        return trim($final);
    }

    protected function _setEmail($email){
        return mb_strtoupper($email);
    }

}
