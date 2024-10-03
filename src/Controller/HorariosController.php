<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\PaginatorHelper;

class HorariosController extends AppController {

    public $paginate = [
        'limit' => 17,
        'order' => [
            'Horarios.hora' => 'asc',
        ],
        'templates' => [
            'sort' => '',
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
                    'model'        => 'Horarios',
                    'pkAlias'      => __('Horarios'),
                    'blockPkPiped' => true,
                ]
            ]
        ]);

        //$result = $this->Paginator;
        //debug($result);exit;


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
            'Horarios' => array(
                'localidade' => array('name' => 'localidade', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Código'), 'ignore' => array('')),
                'dia'        => array('name' => 'dia',        'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                'hora'       => array('name' => 'hora',       'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Setor'), 'ignore' => array('')),
                'municipio_id' => array('name' => 'municipio_id',  'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Status'), 'ignore' => array('')),
                '_all'       => array('name' => ['localidade'], 'operations' => ['LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Horarios'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Horarios', $conversion, [], null, null);

        $horarios = $this->paginate($this->Horarios->find('all')->where($_conditions['conditions']));

        $this->set('horarios', $horarios);
        $this->set('_conditions',   $_conditions['stringFilter']);

    }

    public function add(){

        $horario = $this->Horarios->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Horarios->patchEntity($horario, $data);

            //debug([$data, $new]);exit;

            if ($this->Horarios->save($new)) {

                $this->Flash->success(__('O horario foi adicionada com sucesso !!!'));

                return $this->redirect(['controller' => 'Horarios', 'action' => 'index']);

            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar o horario";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();

                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);

                return $this->redirect(['controller' => 'Horario', 'action' => 'add']);
            }
        }

        $this->set('horario', $horario);
        $this->set('mode', 'add');
        $this->render('save');
    }

    public function edit($id = null){
        $horario = $this->Horarios->get($id);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Horarios->patchEntity($horario, $data);

            if ($this->Horarios->save($new)) {
                $this->Flash->success(__('O horario foi alterado com sucesso !!!'));
                return $this->redirect(['controller' => 'Horarios', 'action' => 'index']);
            } else {
                $erros = $new->errors();
                if($erros){
                    foreach($erros as $key => $value){
                        $this->Flash->error(__(implode(' ', $value)));
                    }
                }
            }
        }

        $this->set('horario', $horario);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $horario = $this->Horarios->get($id);

        $this->set('horario', $horario);
        $this->set('mode', 'view');
        $this->render('save');
    }


    public function delete($id = null){

        $horario = $this->Horarios->get($id);

        if($horario){

            $resul = $this->Horarios->delete($horario);

            if ($resul){
                $this->Flash->success(__('O horario foi removido com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o horario'));
            }
        }

        return $this->redirect(['controller' => 'Horarios', 'action' => 'index']);
    }

}
