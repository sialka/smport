<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class SetupController extends AppController {

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Setup.key' => 'asc',
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
                    'model'        => 'Setup',
                    'pkAlias'      => __('id'),
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
            $this->Security->config('unlockedActions', ['index']);
        }

    }

    public function index() {

        $conversion = array(
            'Localidades' => array(
                'id'     => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'key' => array('name' => 'key', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Código'), 'ignore' => array('')),
                'value'   => array('name' => 'value', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                '_all'   => array('name' => ['Setup.key', 'Setup.value'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Setup'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Setup', $conversion, [], null, null);       

        $setup = $this->paginate($this->Setup->find('all')->where($_conditions['conditions']));
        
        $this->aevOptions();
        $this->set('setup', $setup);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }    

    public function add(){

        $setup = $this->Setup->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;            

            $new = $this->Setup->patchEntity($setup, $data);

            if ($this->Setup->save($new)) {                
                
                $this->Flash->success(__('A chave <strong>' .$new->key.'</strong> foi adicionada com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Setup', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar a chave <strong> {$new->key}: </strong></p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Setup', 'action' => 'add']);
            }
        }      
        

        $this->aevOptions();
        $this->set('setup', $setup);
        $this->set('mode', 'add');
        $this->render('save');
    }

    
    public function edit($id = null){

        $setup = $this->Setup->get($id);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Setup->patchEntity($setup, $data);

            if ($this->Setup->save($new)) {
                $this->Flash->success(__('A chave <strong>' .$new->chave.' </strong> foi alterada com sucesso !!!'));

                return $this->redirect(['controller' => 'Setup', 'action' => 'index']);
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
        $this->set('setup', $setup);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $setup = $this->Setup->get($id);

        $this->aevOptions();
        $this->set('setup', $setup);
        $this->set('mode', 'view');
        $this->render('save');
    }

    public function delete($id = null){

        $setup = $this->Setup->get($id);

        if($setup){

            $resul = $this->Setup->delete($setup);

            if ($resul){
                $this->Flash->success(__('A chave <strong>' .$setup->chave.'</strong> foi removida com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover a chave ' .$setup->chave));
            }
        }

        return $this->redirect(['controller' => 'Setup', 'action' => 'index']);
    }

    public function aevOptions() {

        //$aevOptions = $this->Localidades->aevOptions();

        //$this->set('aevOptions', $aevOptions);
    }

    
}