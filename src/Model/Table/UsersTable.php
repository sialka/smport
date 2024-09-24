<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UsersTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->hasOne('Perfil', [
            'className'         => 'Perfil',
            'bindingKey'        => 'id',
            'foreignKey'        => 'user_id',
            'propertyName'      => 'Perfil',
            
        ]);          
        
    }

    public function validationDefault(Validator $validator)
    {

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email'], 'E-mail já está em uso'));
        $rules->add($rules->isUnique(['login'], 'Usuário já está em uso'));

        return $rules;
    }

    public function aevOptions(){

        $options = [
            'theme' => [
                0 => 'Padrão'
            ],
            'status' => [
                1 => 'Ativo',
                0 => 'Inativo',
            ],
        ];

        return $options;
    }
    
    public function ValidaSenha($data){                
        
        if(empty($data['password']) && empty($data['confirma'])){
            return true;
        }
        
        if($data['password'] === $data['confirma']){
          return true;
        }
        
        return false;        
        
    }
    
    
    public function gerarSenha($tamanho = 8, $maiusculas = false, $numeros = true, $simbolos = true) {
        
        $lmin       = 'abcdefghijklmnopqrstuvwxyz';
        $lmai       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num        = '1234567890';
        $simb       = '!@#$%*-';
        $retorno    = '';
        $caracteres = '';

        $caracteres .= $lmin;
        if ($maiusculas)
            $caracteres .= $lmai;
        if ($numeros)
            $caracteres .= $num;
        if ($simbolos)
            $caracteres .= $simb;

        $len = strlen($caracteres);
        
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand    = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }
        
        return $retorno;
    }
    
    public function salvarPerfil($id, $data){
        
        $perfilTable = TableRegistry::get('Perfil');        
        $perfil      = $perfilTable->find()->where(['user_id' => $id])->first();        
        $upSenha     = 0;
        
        // Verificando senhas
        if(!empty($data['password'])){
            $upSenha++;
        }
        if(!empty($data['confirma'])){
            $upSenha++;
        }
        
        if($upSenha == 1){
            debug('stop');exit;
            return false;
        }
        
        if($perfil){
            
            $save                  = $perfil;
                    
        }else{

            $save                  = $perfilTable->newEntity();
            $save->user_id         = $id;

        }
        
        if(isset($data['Perfil'])) {
        
            $save->admin           = $data['Perfil']['admin']; 
            $save->cad_igrejas     = $data['Perfil']['cad_igrejas']; 
            $save->cad_mestrabalho = $data['Perfil']['cad_mestrabalho']; 
            $save->cad_planilhas   = $data['Perfil']['cad_planilhas']; 

            if($data['Perfil']['admin'] == 1){
                $save->cad_igrejas     = 1; 
                $save->cad_mestrabalho = 1; 
                $save->cad_planilhas   = 1;             
            }

            if($perfilTable->save($save)){
                return true;
            }

            return false;
        
        }
        
        return true;
    }

    public function validaPerfil($user, $data, $prop){
        
        if(isset($data)){
            $user->$prop = $data;
        }

        return $user;
    }

        
}
