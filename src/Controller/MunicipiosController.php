<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class MunicipiosController extends AppController {    

    public $paginate = [
        'limit' => 17,
        'order' => [
            'Municipios.nome' => 'asc',
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
                    'model'        => 'Municipios',
                    'pkAlias'      => __('Municipios'),
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
    }

    public function index() {
        
        $conversion = array(
            'Municipios' => array(                
                'nome' => array('name' => 'nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                '_all' => array('name' => ['nome'], 'operations' => ['LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );        

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Municipios'] = $this->request->data;
        }
        
        $_conditions = $this->Conditions->filter('Municipios', $conversion, [], null, null);       

        $municipios = $this->paginate($this->Municipios->find('all')->where($_conditions['conditions']));        
                
        $this->set('municipios', $municipios);
        $this->set('_conditions', $_conditions['stringFilter']);        
    }

    public function add(){

        $municipio = $this->Municipios->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;            

            $new = $this->Municipios->patchEntity($municipio, $data);                 

            if ($this->Municipios->save($new)) {                
                
                $this->Flash->success(__('O municipio foi adicionada com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Municipios', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar o municipio";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'municipio', 'action' => 'add']);
            }
        }      

        $this->set('municipio', $municipio);
        $this->set('mode', 'add');
        $this->render('save');
    }
    
    public function edit($id = null){
        $municipio = $this->Municipios->get($id);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Municipios->patchEntity($municipio, $data);

            if ($this->Municipios->save($new)) {
                $this->Flash->success(__('O municipio foi alterado com sucesso !!!'));
                return $this->redirect(['controller' => 'Municipios', 'action' => 'index']);
            } else {
                $erros = $new->errors();
                if($erros){
                    foreach($erros as $key => $value){
                        $this->Flash->error(__(implode(' ', $value)));
                    }
                }
            }
        }
        
        $this->set('municipio', $municipio);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $municipio = $this->Municipios->get($id);
        
        $this->set('municipio', $municipio);
        $this->set('mode', 'view');
        $this->render('save');
    }
    

    public function delete($id = null){

        $municipio = $this->Municipios->get($id);

        if($municipio){

            $resul = $this->Municipios->delete($municipio);

            if ($resul){
                $this->Flash->success(__('O municipio foi removido com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o municipio'));
            }
        }

        return $this->redirect(['controller' => 'Municipios', 'action' => 'index']);
    }  
    
    public function favorito($id = null) {

        $municipios = $this->Municipios->query();
        $municipios->update()->set(['favorito' => '0'])->execute();        
        
        $municipio = $this->Municipios->get($id);
        $municipio->favorito = '1';

        if ($this->Municipios->save($municipio)) {
            $this->Flash->success(__('O municipio foi alterado com sucesso !!!'));        
        } else {
            $this->Flash->error(__('Não foi possivel favoritar o municipio'));
        }

        return $this->redirect(['controller' => 'Municipios', 'action' => 'index']);

    }
    
}
