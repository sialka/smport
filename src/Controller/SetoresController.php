<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class SetoresController extends AppController {

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Setores.setor' => 'asc',
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
                    'model'        => 'Setores',
                    'pkAlias'      => __('Setor'),
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
            'Setores' => array(
                'setor'  => array('name' => 'setor', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Setor'), 'ignore' => array('')),
                'nome'  => array('name' => 'nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                'status'  => array('name' => 'status', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Status'), 'ignore' => array('')),
                '_all'   => array('name' => ['setor', 'nome'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Setores'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Setores', $conversion, [], null, null);
        //$_conditions['conditions'] += ['Localidades.setor' => 4];        

        $setores = $this->paginate($this->Setores->find('all')->where($_conditions['conditions']));        

        $this->aevOptions();
        $this->set('setores', $setores);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }

    public function add(){

        $setor = $this->Setores->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Setores->patchEntity($setor, $data);

            if ($this->Setores->save($new)) {                
                
                $this->Flash->success(__('O setor <strong>' .$new->nome.'</strong> foi adicionado com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Setores', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar o Setor <strong> {$new->nome}: </strong></p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Setores', 'action' => 'add']);
            }
        }      
        

        $this->aevOptions();
        $this->set('setor', $setor);
        $this->set('mode', 'add');
        $this->render('save');
    }

    public function edit($id = null){
        $setor = $this->Setores->get($id);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Setores->patchEntity($setor, $data);

            if ($this->Setores->save($new)) {
                $this->Flash->success(__('O Setor <strong>' .$new->nome.' </strong> foi alterado com sucesso !!!'));
                return $this->redirect(['controller' => 'Setores', 'action' => 'index']);
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
        $this->set('setor', $setor);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $setor = $this->Setores->get($id);

        $this->aevOptions();
        $this->set('setor', $setor);
        $this->set('mode', 'view');
        $this->render('save');
    }

    public function delete($id = null){

        $setor = $this->Setores->get($id);

        if($setor){

            $resul = $this->Setores->delete($setor);

            if ($resul){
                $this->Flash->success(__('O Setor <strong>' .$setor->nome.'</strong> foi removido com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o Setor ' .$setor->nome));
            }
        }

        return $this->redirect(['controller' => 'Setores', 'action' => 'index']);
    }

    public function aevOptions() {

        $aevOptions = [
            'status' => [
                1 => 'Ativo',
                0 => 'Inativo',
            ],
        ];        

        $this->set('aevOptions', $aevOptions);
    }


}
