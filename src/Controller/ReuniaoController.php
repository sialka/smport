<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\I18n\Date;

class ReuniaoController extends AppController {

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Ordenacao.id' => 'asc',
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
                    'model'        => 'Reuniao',
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
            'Reuniao' => array(
                'id'        => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'nome'      => array('name' => 'Localidades.nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Localidade'), 'ignore' => array('')),
                'municipio' => array('name' => 'Localidades.municipio_id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Municipio'), 'ignore' => array('')),
                '_all'      => array('name' => ['Localidades.id', 'Localidades.nome'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Reuniao'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Reuniao', $conversion, [], null, null);
        //$_conditions['conditions'] += ['Localidades.setor' => 4];

        $reuniao = $this->paginate($this->Reuniao->find('all')->contain(['Localidades.Municipios'])->where($_conditions['conditions']));

        //debug($batismo);exit;

        $this->aevOptions();
        $this->set('reuniao', $reuniao);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }

    public function add(){

        $reuniao = $this->Reuniao->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Reuniao->patchEntity($reuniao, $data);

            $dataUS = $this->converte_date($data['data']);

            $new->data = $dataUS;
            $new->dia_semana = $this->converte_semana_dia($dataUS);

            if ($this->Reuniao->save($new)) {

                $this->Flash->success(__('A reunião foi adicionado com sucesso !!!'));

                return $this->redirect(['controller' => 'Reuniao', 'action' => 'index']);

            } else {

                $error_list = "<p class='mt-2'>Não foi possivel adicionar o Reuniao !</p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();

                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);

                return $this->redirect(['controller' => 'Reuniao', 'action' => 'add']);
            }
        }


        $this->aevOptions();
        $this->set('reuniao', $reuniao);
        $this->set('mode', 'add');
        $this->render('save');
    }

    public function edit($id = null){
        $reuniao = $this->Reuniao->get($id, ['contain' => ['Localidades']]);

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $dataUS = $this->converte_date($data['data']);

            $new = $this->Reuniao->patchEntity($reuniao, $data);

            $new->data = $dataUS;
            $new->dia_semana = $this->converte_semana_dia($dataUS);

            if ($this->Reuniao->save($new)) {
                $this->Flash->success(__('A Reunião foi alterada com sucesso !!!'));
                return $this->redirect(['controller' => 'Reuniao', 'action' => 'index']);
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
        $this->set('reuniao', $reuniao);
        $this->set('mode', 'edit');
        $this->render('save');
    }

    public function view($id = null){
        $reuniao = $this->Reuniao->get($id, ['contain' => ['Localidades']]);

        $this->aevOptions();
        $this->set('reuniao', $reuniao);
        $this->set('mode', 'view');
        $this->render('save');
    }

    public function delete($id = null){

        $reuniao = $this->Reuniao->get($id);

        if($reuniao){

            $resul = $this->Reuniao->delete($reuniao);

            if ($resul){
                $this->Flash->success(__('A reunião foi removido com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o batismo'));
            }
        }

        return $this->redirect(['controller' => 'Reuniao', 'action' => 'index']);
    }

    public function aevOptions() {

        $aevOptions = $this->Reuniao->aevOptions();

        $this->set('aevOptions', $aevOptions);
    }

}
