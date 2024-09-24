<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class CultosController extends AppController {
    
    public $paginate = [
        'limit' => 17,
        'order' => [
            'Cultos.dia' => 'asc',
        ]
        ];

    public function initialize() {

        parent::initialize();
        $this->request->session()->write('layout', 'admin');  
        
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
                    'model'        => 'Cultos',
                    'pkAlias'      => __('Localidade'),
                    'blockPkPiped' => true,
                ]
            ]
        ]);

    }

    public function beforeRender(Event $event) {
        parent::beforeRender($event);
        
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);        

        if ($this->request->is('ajax') || (in_array('application/json', $this->request->accepts()))) {
            //$this->Security->config('unlockedActions', ['index']);
        }

    }

    public function index() {
        
        $conversion = array(
            'Cultos' => array(                
                'localidade' => array('name' => 'Localidades.nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Localidade'), 'ignore' => array('')),
                'dia'        => array('name' => 'dia',        'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Dia'), 'ignore' => array('')),
                'hora'       => array('name' => 'hora',       'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Hora'), 'ignore' => array('')),
                'municipio' => array('name' => 'Localidades.municipio_id',  'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Municipio'), 'ignore' => array('')),
                '_all'       => array('name' => ['Localidades.nome','id'], 'operations' => ['LIKE',''], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Cultos'] = $this->request->data;
        }

        //$_conditions = $this->Conditions->filter('Localidades', $conversion, [], null, null);
        $_conditions = $this->Conditions->filter('Cultos', $conversion, [], null, null);
        //debug($_conditions);
        //$_conditions['conditions'] += ['Localidades.setor' => 4];        

        $cultos = $this->paginate($this->Cultos->find('all')->contain(['Localidades.Municipios'])->where($_conditions['conditions']));
        
        $this->aevOptions();        
        $this->uteis();
        $this->set('cultos', $cultos);        
        $this->set('_conditions',   $_conditions['stringFilter']);
        
    }

    public function add(){

        $culto = $this->Cultos->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;            

            $new = $this->Cultos->patchEntity($culto, $data);     

            //debug([$data, $new]);exit;

            if ($this->Cultos->save($new)) {                
                
                $this->Flash->success(__('O culto foi adicionada com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Cultos', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar o culto";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Cultos', 'action' => 'add']);
            }
        }              

        $this->aevOptions();        
        $this->uteis();
        $this->set('culto', $culto);
        $this->set('mode', 'add');
        $this->render('save');
    }
    
    public function edit($id = null){
        $culto = $this->Cultos->get($id, ['contain' => ['Localidades']]);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Cultos->patchEntity($culto, $data);

            if ($this->Cultos->save($new)) {
                $this->Flash->success(__('O culto foi alterado com sucesso !!!'));
                return $this->redirect(['controller' => 'Cultos', 'action' => 'index']);
            } else {
                $erros = $new->errors();
                if($erros){
                    foreach($erros as $key => $value){
                        $this->Flash->error(__(implode(' ', $value)));
                    }
                }
            }
        }

        $this->aevOptions();        
        $this->uteis();
        $this->set('culto', $culto);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $culto = $this->Cultos->get($id, ['contain' => ['Localidades']]);

        $this->aevOptions();        
        $this->uteis();
        $this->set('culto', $culto);
        $this->set('mode', 'view');
        $this->render('save');
    }    

    public function delete($id = null){

        $culto = $this->Cultos->get($id);

        if($culto){

            $resul = $this->Cultos->delete($culto);

            if ($resul){
                $this->Flash->success(__('O culto foi removido com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o culto'));
            }
        }

        return $this->redirect(['controller' => 'Cultos', 'action' => 'index']);
    }
    
    public function aevOptions() {

        $aevOptions = $this->Cultos->aevOptions();         
        
        $this->set('aevOptions', $aevOptions);
    }


    public function uteis(){

        $utils = $this->Utils();
     
        $this->set('utils', $utils);
    }
    
}
