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

class NcsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('ncs');
        $this->setDisplayField('ano');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');        

    }

    public function validationDefault(Validator $validator)
    {
        $validator->notEmpty('mes', __('Informe o mês'));
        $validator->notEmpty('ano', __('Informe o ano'));

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {

        $rules->add($rules->isUnique(['ano','mes'], 'O mês e ano de trabalho já está cadastrado'));


        return $rules;
    }
    
    public function status(Entity $entity){
        
        if($entity->selecionado){
           return false; 
        }
        
        if($entity->status){
            $entity->status = false;
        }else{
            $entity->status = true;
        }
        
        if($this->save($entity)){
            $this->atualizaMesTrabalho();
            
            return true;
        }
        
        return false;        
    }
    
    public function remove(Entity $entity){
        
        if($entity->controle == 0){
            $this->delete($entity);
            return true;
        }
        
        return false;        
    }
    
    public function ativar(Entity $entity){

        if($entity->status == true){
            
            // 1. Remove quem esta ativo
            $todos = $this->find()->where(['selecionado' => true])->toArray();
            foreach($todos as $reg){
                $reg->selecionado = false;
                $this->save($reg);
            }
            
            // 2. Ativar o novo mês
            $entity->selecionado = true;
            $this->save($entity);
            
            // 3. Atualiza o sistema
            $this->atualizaMesTrabalho();
            
            return true;
        }
        
        return false;                
    }
    
    public function atualizaMesTrabalho() {
        
        $session   = new Session();
        $mes       = false;
        $meses     = false;
        
        $ncs_mes   = $this->find()->where(['selecionado' => true])->first();
        //$ncs_meses = $this->find()->where(['status' => true, 'selecionado' => false])->order(['Ncs.ano' => 'DESC', 'Ncs.mes' => 'DESC'])->limit(3)->toArray();
        $ncs_meses = $this->find()->where(['status' => true, 'selecionado' => false])->order(['Ncs.ano' => 'DESC', 'Ncs.mes' => 'DESC'])->toArray();
        
        if ($ncs_mes){
            $mes = $ncs_mes;
        }
        
        if ($ncs_mes){
            $meses = $ncs_meses;
        }        
        
        $session->write('mes', $mes);
        $session->write('meses', $meses); 
        
        return;
    }

    public function aevOptions(){        
        
        $setupTable = TableRegistry::get('Setup');
        
        $time        = Time::now();
        $year_old    = Time::now();
        $year_future = Time::now();
        $year_old->modify('-3 years');
        $year_future->modify('+1 years');
        
        $setupEntity = $setupTable->find()->where(['id' => 1])->first();
        $_anos       = explode(";", $setupEntity->anos);
        $anos        = [];
        
        foreach($_anos as $ano){
            if(strlen($ano) > 0){                
                $anos += [$ano => $ano];
            }
        }        
        
        $options = [
            'meses' => [
                '01' => '01 - Janeiro',
                '02' => '02 - Fevereiro',
                '03' => '03 - Março',
                '04' => '04 - Abril',
                '05' => '05 - Maio',
                '06' => '06 - Junho',
                '07' => '07 - Julho',
                '08' => '08 - Agosto',
                '09' => '09 - Setembro',
                '10' => '10 - Outubro',
                '11' => '11 - Novembro',
                '12' => '12 - Dezembro',
            ],
            'anos' => $anos,
            'status' => [
                true => 'Ativo',
                false => 'Inativo',
            ]
        ];

        return $options;
    }
    
    public function controle($id){        
        
        $entity   = $this->find()->where(['id' => $id])->first();
        
        if($entity){
            $entity->controle += 1;
            $this->save($entity);
        }else{
            return false;
        }
    
        return $entity->controle;
    }
    
    public function getMesTrabalhoAtivos(){
        
        $ativos = $this->find('list', ['keyField' => 'id', 'valueField' => 'mesTrabalho'])
                ->where(['status' => true])
                ->order(['ano' => 'DESC', 'mes' => 'DESC'])
                ->toArray();
        
        return $ativos;
    }
}
