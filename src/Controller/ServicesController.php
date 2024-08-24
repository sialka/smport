<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;
use Cake\Datasource\ConnectionManager;

class ServicesController extends AppController {            

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Services.senha' => 'asc',            
        ]
    ];

    
    public function initialize() {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Conditions', [
            'prefixSession'      => 'ccb',
            'delimiter'          => '__',
            'pipe'               => '-',
            'char_case'          => 1,
            'tables_names'       => [],
            'try_resolve_fields' => true,
            'listenRequestClear' => [
                'index' => [
                    'param' => 'clear'
                ],
            ],
            'listenRequestPiped' => [
                'index' => [
                    'model'        => 'Services',
                    'pkAlias'      => __('id'),
                    'blockPkPiped' => true,
                ]
            ]
        ]);

        $this->Auth->allow('index');
    }

    public function index() {    
       
        $conversion = array(
            'Services' => array(
                'id'              => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'senha'           => array('name' => 'senha', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Código'), 'ignore' => array('')),
                'status_ficha'    => array('name' => 'status_ficha', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                'status_envelope' => array('name' => 'status_envelope', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Setor'), 'ignore' => array('')),                
                '_all'            => array('name' => ['Services.senha', 'Localidades.nome'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Services'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Services', $conversion, [], null, null);  
        $_conditions['conditions'] += ['Services.setor' => 4];         

        $services = $this->paginate($this->Services->find('all')->contain(['Localidades'])->where($_conditions['conditions']));                

        $this->aevOptions();

        $localidadesTable = TableRegistry::get('Localidades');                    

        $igrejas = $localidadesTable->find('list', [
            'keyField' => 'nome',
            'valueField' => 'nome']
            )->where(['setor' => '4'])->toArray();

        $this->resumo();
        
        $perfil = $this->request->session()->read('perfil');

        $this->set('perfil', $perfil);
        $this->set('igrejas', $igrejas);
        $this->set('services', $services);
        $this->set('_conditions', $_conditions['stringFilter']);                  

    } 

    public function add(){
        
        $senha = $this->Services->newEntity();

        if ($this->request->is('post')) { 

            $data = $this->request->data;                                   
            
            //$valida = $this->Services->validacoes($data['senha'], $data['localidade_id'], 'add', null);                         
            $valida = $this->Services->validacoes($data, 'add', null);                         
            
            if($valida['status']){
                $this->Flash->error($valida['erro']);                

                return $this->redirect(['controller' => 'Services', 'action' => 'add']);
            }

            $new = $this->Services->patchEntity($senha, $data);                                             
            $new->setor = 4;
            
            $senha = $data['senha'] == '' ? $new->senha = 0 : $data['senha'];                        

            $save = $this->Services->save($new);
                        
            if ($save) {   

                $this->Flash->success(__('A senha '.$new->senha.' foi identificada com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Services', 'action' => 'index']); 

            } else {

                $this->Flash->error(__('Não foi possivel salvar a identificação da senha !!!'));

                return $this->redirect(['controller' => 'Services', 'action' => 'add']);
            }

        }        

        $this->aevOptions();
        
        $this->set('mode', 'add');
        $this->set('senha', $senha);
        $this->render("save");
    }    

    public function delete($id = null){
        
        $senha = $this->Services->get($id);    
        
        $delete = $this->Services->delete($senha);               
        
        if($delete){
            $this->Flash->success(__("Senha excluída com sucesso !!!"));
        }else{
            $this->Flash->error(__("Não foi possivel deletar a senha !!!"));
        }
        
        return $this->redirect(['controller' => 'Services', 'action' => 'index']);        
    }

    public function view($id = null){
        
        $senha = $this->Services->get($id, ['contain' => ['Localidades']]); 
        
        $this->aevOptions();

        $this->set('senha', $senha);
        $this->set('mode', 'view');
        $this->render('save');
    }
    
    public function edit($id = null){

        $senha = $this->Services->get($id, ['contain' => ['Localidades']]); 

        if ($this->request->is('post')) {

            $data = $this->request->data;            

            $valida = $this->Services->validacoes(0, $data['localidade_id'], 'edit', $id);            
            
            if($valida['status']){
                $this->Flash->error($valida['erro']);                

                return $this->redirect(['controller' => 'Services', 'action' => "edit/{$id}"]);
            }

            $new = $this->Services->patchEntity($senha, $data);     

            if ($this->Services->save($new)) {
                $this->Flash->success(__('A senha <strong>' .$new->senha.' </strong> foi alterada com sucesso !!!'));
                return $this->redirect(['controller' => 'Services', 'action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possivel altera a senha <strong>' .$new->senha.' </strong>  !!!'));
                return $this->redirect(['controller' => 'Services', 'action' => 'index']);
            }
        }

        $this->aevOptions();
        $this->set('senha', $senha);
        $this->set('mode', 'edit');
        $this->render('save');
    }
    
    public function chamarFicha()    
    {

        // ToDo filtro por setor via Session

        $data = $this->request->data;   
        
        $senha = $data['senha_ficha'];
        $fala = $data['senha_ficha'];
                
        if($data['senha_ficha'] == 0){
            $this->Flash->error(__('Não existe senha 0, favor revisar !!!'));
            return $this->redirect("/services");
        }        

        $panelTable = TableRegistry::get('Panels');
        
        $panel = $panelTable->newEntity();
        $panel->senha = $senha;
        $panel->fala = $fala;
        $panel->tipo = $data['tipo'];
        $panel->setor = 4; // ToDo        
                
        if ($panelTable->save($panel)) {     
            $this->Flash->success(__("Conferência de Ficha: Senha ".$senha." enviada para o Painel com sucesso !!!"));

            $this->request->session()->write('last_senha_ficha', $data['senha_ficha']);                              
        }else{
            $this->Flash->error(__('Erro ao chamar a senha !!!'));
        }
        
        return $this->redirect("/services");

    }

    public function chamarReserva($senha = null)    
    {

        // ToDo filtro por setor via Session

        $data = $this->request->data;      
        
        $senha = $data['senha_reserva'];
        $fala = $data['senha_reserva'];
                
        if($data['senha_reserva'] == 0){
            $this->Flash->error(__('Não existe senha 0, favor revisar !!!'));
            return $this->redirect("/services");
        }        

        $panelTable = TableRegistry::get('Panels');
        
        $panel = $panelTable->newEntity();
        $panel->senha = $senha;
        $panel->fala = $fala;
        $panel->tipo = $data['tipo'];
        $panel->setor = 4; // ToDo        
                
        if ($panelTable->save($panel)) {     
            $this->Flash->success(__('Reserva de Roupa: Senha enviada para o Painel com sucesso !!!'));

            $this->request->session()->write('last_senha_reserva', $data['senha_reserva']);
        }else{
            $this->Flash->error(__('Erro ao chamar a senha !!!'));
        }
        
        return $this->redirect("/services");

    }

    public function chamarEnvelope($senha = null){       
        
        // ToDo filtro por setor via Session
        
        $data = $this->request->data;  
        //debug($data);exit;

        $senha = $data['localidade'];
        $fala = $data['localidade'];

        if($data['tipo'] == 3 && $data['falar'] !== ''){
            $fala = $data['falar'];
        }

        $panelTable = TableRegistry::get('Panels');
        
        $panel = $panelTable->newEntity();        
        $panel->senha = $senha;
        $panel->fala = $fala;
        $panel->setor = 4;
        $panel->tipo = $data['fala']=='0' ? $data['tipo'] : 4;

        if ($panelTable->save($panel)) {     
            $this->Flash->success(__('Conferência de envelope: Senha enviada para o Painel com sucesso !!!'));
    
            $this->request->session()->write('last_senha_envelope', $data['fala']);

            $panel_normal = $panel->tipo == 4 ? 1 : 0;
            $this->request->session()->write('panel-normal', $panel_normal);

        }else{
            $this->Flash->error(__('Erro ao chamar a senha !!!'));
        }
        
        return $this->redirect("/services");
    }

    public function reiniciar() {

        // ToDo: Filtrar por Setor

        $connection = ConnectionManager::get('default');
        $connection->execute('TRUNCATE TABLE services'); 
        $connection->execute('TRUNCATE TABLE panels'); 

        $this->Flash->success(__('Reunião Reiniciado com sucesso !!!'));

        return $this->redirect("/services");

    }

    private function resumo() {
        
        $resumo = $this->Services->find('all')->contain(['Localidades'])->where(['Services.setor' => 4])->order(['senha' => 'asc'])->toArray();       
        
        $status = $this->aevOptions();        

        $data = [];
        foreach($resumo as $dado){

            $arr = [                 
                'localidade' => $dado->Localidades->nome,
                'senha' => $dado->senha,
                'ficha' => $status['status_fichas_save'][$dado->status_ficha],
                'envelope' => $status['status_envelopes_save'][$dado->status_envelope],                
            ];
            array_push($data, $arr);

        }

        $this->set('resumo', $data);
    }

  }