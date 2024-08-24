<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class AdminController extends AppController {

    public function initialize() {

        parent::initialize();
        $this->request->session()->write('layout', 'admin');  

    }

    public function beforeRender(Event $event) {
        parent::beforeRender($event);        
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);        
    }

    public function index() {
        

        /*
        $conversion = array(
            'Localidades' => array(
                'id'     => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'codigo' => array('name' => 'codigo', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Código'), 'ignore' => array('')),
                'nome'   => array('name' => 'nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                'setor'  => array('name' => 'setor', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Setor'), 'ignore' => array('')),
                'status' => array('name' => 'status', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Status'), 'ignore' => array('')),
                '_all'   => array('name' => ['Localidades.codigo', 'Localidades.nome'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Localidades'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Localidades', $conversion, [], null, null);
        $_conditions['conditions'] += ['Localidades.setor' => 4];        

        $localidades = $this->paginate($this->Localidades->find('all')->where($_conditions['conditions']));

        $this->aevOptions();
        $this->set('localidades', $localidades);
        $this->set('_conditions',   $_conditions['stringFilter']);
        */
    }
    

}
