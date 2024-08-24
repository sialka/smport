<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class NcsController extends AppController {

    public $paginate = [
        'limit' => 25,
        'order' => [
            'Ncs.ano' => 'asc',
            'Ncs.mes' => 'desc'
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
                    'model'        => 'Ncs',
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
            'Ncs' => array(
                'id'          => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'mes'         => array('name' => 'mes', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Mês'), 'ignore' => array('')),
                'ano'         => array('name' => 'ano', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Ano'), 'ignore' => array('')),
                'controle'    => array('name' => 'controle', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Controle'), 'ignore' => array('')),
                'status'      => array('name' => 'status', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Status'), 'ignore' => array('')),
                'selecionado' => array('name' => 'selecionado', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Selecionado'), 'ignore' => array('')),
                '_all'        => array('name' => ['Ucs.mes', 'Ucs.ano'], 'operations' => ['', ''], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Ncs'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Ncs', $conversion, [], null, null);        

        $ncs = $this->paginate($this->Ncs->find('all')->where($_conditions['conditions']));
        
        $this->aevOptions();
        $this->set('ncs', $ncs);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }

    public function add(){
        
        $nc = $this->Ncs->newEntity();        

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new     = $this->Ncs->patchEntity($nc, $data);
            $new->controle = 0;
            $new->selecionado = false;

            if ($this->Ncs->save($new)) {                
                
                $this->Flash->success(__("O mês de trabalho <strong>{$new->mes}/{$new->ano}</strong> foi criado com sucesso !!!"));
                
                return $this->redirect(['controller' => 'Ncs', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel criar o mês de trabalho <strong> {$new->mes}/{$new->ano} </strong></p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Ncs', 'action' => 'add']);
            }
        }      
        
        $this->aevOptions();
        $this->set('nc', $nc);
        $this->set('mode', 'add');
        $this->render('save');
    }
    
    public function status($id = null){
        
        $nc = $this->Ncs->get($id);        

        if($nc){

            $resul = $this->Ncs->status($nc);

            if ($resul){
                $this->Flash->success(__("O status do mês de trabalho <strong>{$nc->mes}/{$nc->ano}'</strong> foi alterado com sucesso !!!"));
            }else{
                $this->Flash->error(__("Não foi possivel alterar o status do mês de trabalho <strong>{$nc->mes}/{$nc->ano}'</strong>."));
            }
        }

        return $this->redirect(['controller' => 'Ncs', 'action' => 'index']);
        
    }
    
    public function ativar($id = null){
     
        $nc = $this->Ncs->get($id);        

        if($nc){

            $resul = $this->Ncs->ativar($nc);

            if ($resul){
                $this->Flash->success(__("O status do mês de trabalho <strong>{$nc->mes}/{$nc->ano}</strong> foi selecionado como mês padrão."));
            }else{
                $this->Flash->error(__("Não foi possivel alterar o mês de trabalho <strong>{$nc->mes}/{$nc->ano}'</strong> como mês padrão."));
            }
        }

        return $this->redirect(['controller' => 'Ncs', 'action' => 'index']);        
    }

    public function padrao($id = null){
        
        $nc = $this->Ncs->get($id);        
        
        if($nc){

            $resul = $this->Ncs->ativar($nc);

            if ($resul){
                $this->Flash->success(__("O mês de trabalho foi alterado para <strong>{$nc->mes}/{$nc->ano}</strong> ."));
            }else{
                $this->Flash->error(__("Não foi possivel alterar o mês de trabalho para <strong>{$nc->mes}/{$nc->ano}'</strong>."));
            }
        }

        return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);        
    }
    
    public function delete($id = null){

        $nc = $this->Ncs->get($id);

        if($nc){

            $resul = $this->Ncs->remove($nc);

            if ($resul){
                $this->Flash->success(__("O mês de trabalho <strong>{$nc->mes}/{$nc->ano}'</strong> foi removido com sucesso !!!"));
            }else{
                $this->Flash->error(__("O mês de trabalho <strong>{$nc->mes}/{$nc->ano}'</strong> não pode ser removido !!!"));
            }
        }

        return $this->redirect(['controller' => 'Ncs', 'action' => 'index']);
    }

    public function aevOptions() {

        $aevOptions = $this->Ncs->aevOptions();

        $this->set('aevOptions', $aevOptions);
    }


}
