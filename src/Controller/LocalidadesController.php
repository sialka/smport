<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class LocalidadesController extends AppController {

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Localidades.id' => 'asc',
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
                    'model'        => 'Localidades',
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
                'id'        => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),                
                'nome'      => array('name' => 'Localidades.nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Localidade'), 'ignore' => array('')),                                
                'municipio' => array('name' => 'Localidades.municipio_id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Municipio'), 'ignore' => array('')),                                
                '_all'      => array('name' => ['Localidades.id', 'Localidades.nome'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Localidades'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Localidades', $conversion, [], null, null);
        //$_conditions['conditions'] += ['Localidades.setor' => 4];        

        $localidades = $this->paginate($this->Localidades->find('all')->contain(['Municipios'])->where($_conditions['conditions']));        

        //debug($localidades);

        $this->aevOptions();
        $this->set('localidades', $localidades);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }

    public function add(){

        $localidade = $this->Localidades->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Localidades->patchEntity($localidade, $data);

            if ($this->Localidades->save($new)) {                
                
                $this->Flash->success(__('A localidade <strong>' .$new->nome.'</strong> foi adicionada com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Localidades', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar a Localidade <strong> {$new->nome}: </strong></p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Localidades', 'action' => 'add']);
            }
        }      
        

        $this->aevOptions();
        $this->set('localidade', $localidade);
        $this->set('mode', 'add');
        $this->render('save');
    }

    public function edit($id = null){
        $localidade = $this->Localidades->get($id);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Localidades->patchEntity($localidade, $data);

            if ($this->Localidades->save($new)) {
                $this->Flash->success(__('A localidade <strong>' .$new->nome.' </strong> foi alterada com sucesso !!!'));
                return $this->redirect(['controller' => 'Localidades', 'action' => 'index']);
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
        $this->set('localidade', $localidade);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $localidade = $this->Localidades->get($id);

        $this->aevOptions();
        $this->set('localidade', $localidade);
        $this->set('mode', 'view');
        $this->render('save');
    }

    public function delete($id = null){

        $localidade = $this->Localidades->get($id);

        if($localidade){

            $resul = $this->Localidades->delete($localidade);

            if ($resul){
                $this->Flash->success(__('A localidade <strong>' .$localidade->nome.'</strong> foi removida com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover a localidade ' .$localidade->nome));
            }
        }

        return $this->redirect(['controller' => 'Localidades', 'action' => 'index']);
    }

    public function aevOptions() {

        //$aevOptions = $this->Localidades->aevOptions();

        //$this->set('aevOptions', $aevOptions);

        
        $cultosTable = TableRegistry::get('Cultos');                    

        $aevOptions = $cultosTable->aevOptions();

        $this->set('aevOptions', $aevOptions);
    }


}
