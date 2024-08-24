<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Network\Session;

class PanelsController extends AppController {            
    
    public function initialize() {
        parent::initialize();

        $this->Auth->allow('index');
    }

    public function index() {    

        // Setup - Voz
        $setupTable = TableRegistry::get('Setup');
        $voz = $setupTable->find()->where(['chave' => 'voz'])->First();
        $fala = $voz == null ? "0" : $voz->valor;
        $this->set('sintetizador', $fala);

        $dados = [];
        $pagina_index = 0;   

        $senhas = $this->Panels->find('all')->order(['id' => 'ASC'])->where(['status' => true, 'setor' => 4])->toArray();         
        
        if(count($senhas) != 0){  

            $recupera_session = $this->request->session()->read('painel-senha');

            //debug($recupera_session);
            
            foreach ($senhas as $senha) {               
                
                $item = "{$senha->fala},{$senha->senha},{$senha->tipo}";                

                array_push($recupera_session, $item);

                $senha->status = false;
                $this->Panels->save($senha);                    
            }  

            //debug($recupera_session);exit;
            $this->request->session()->write('painel-senha', $recupera_session);
            

        } else {
            
            
            $servicesTable = TableRegistry::get('Services');
            
            $dados = $servicesTable->find('all')->order(['senha' => 'asc'])->where(['Services.setor' => '4', 'Services.senha !=' => 0])->contain(['Localidades'])->toArray();           
            
            $senha = 0;

            # 2. Total de Registros
            $senhas_total = count($dados);                        

            # 3. Total de Paginas 
            $get_pagina = $this->pagination();           
            
            $pagina_total = $get_pagina[$senhas_total];                        

            # 4. Total corrente                               
            
            if ($this->request->query == null ) {
            
                $pagina_index = 0;
            }else{

                $pagina_index = $this->request->query['page'];                

                if($pagina_index >= $pagina_total){                
                    $pagina_index = 0;               
                }else{                                  
                    $pagina_index = $pagina_index + 3;                              
                }

            }            
            
            $dados = array_slice($dados,$pagina_index, 3);
            $tipo = null;
            
        }

        $this->aevOptions();
        
        $this->set('dados', $dados);
        $this->set('pagina_index',$pagina_index);        
    }    


    /**
    * Pagination:
    * - Responsavel por informar o inicio do slice em dados.
    */    
    private function pagination() {                
        # reg =  Registros na tabela
        # senhas_total = Total de senhas
        # pag = Pagina
        # controle = chavamento para senhas por pagina
        # chaveamento = apenas para que value comece em 1 

        $senhas_total = 30;
        $pag = 0;
        $controle = 1;
        $paginas = [0];

        $chaveamento = true;
        
        for ($reg = 1; $reg <= $senhas_total; $reg++) {                                     
            
            if ($pag == 0) {
                //$pag=1;                
            }

            $paginas += [$reg => $pag];

            if($controle == 3){                                
                
                if ($chaveamento){ 
                    //$pag = 0; 
                    //$chaveamento = false;
                }
                $pag += 3;
                $controle = 0;
            }

            $controle += 1;             
        } 

        return $paginas;
    }


}
