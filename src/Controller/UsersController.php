<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {
    
    public $paginate = [
        'limit' => 25,
        'order' => [
            'Users.nome' => 'asc',
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
                    'model'        => 'Users',
                    'pkAlias'      => __('Código'),
                    'blockPkPiped' => true,
                ]
            ]
        ]);
        

        $this->Auth->allow(['logout', 'login']);
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
      
        $this->checkAccess();
        
        $conversion = array(
            'Users' => array(
                'id'       => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'nome'     => array('name' => 'nome', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Nome'), 'ignore' => array('')),
                'username' => array('name' => 'username', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Login'), 'ignore' => array('')),
                'email'    => array('name' => 'image', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Email'), 'ignore' => array('')),
                '_all'     => array('name' => ['Users.nome', 'Users.username'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Users'] = $this->request->data;
        }

        $_conditions = $this->Conditions->filter('Users', $conversion, [], null, null);

        $users = $this->paginate($this->Users->find('all')->where($_conditions['conditions']));

        $perfil = $this->request->session()->read('perfil');            
        
        $this->aevOptions();
        $this->set('perfil', $perfil );
        $this->set('users', $users);
        $this->set('_conditions',   $_conditions['stringFilter']);
    }

    public function add($id = null){
        
        $this->checkAccess();
        //$this->isAdmin();
        
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->data;

            $new = $this->Users->patchEntity($user, $data);
            
            $senha = $this->Users->gerarSenha(8, false, true, true);
            
            $new->password = $senha;

            $new = $this->Users->validaPerfil($user, $data['mod_admin'], "mod_admin");
            $new = $this->Users->validaPerfil($user, $data['mod_user'], "mod_user");
            $new = $this->Users->validaPerfil($user, $data['mod_localidade'], "mod_localidade");
            $new = $this->Users->validaPerfil($user, $data['mod_setores'], "mod_setores");
            $new = $this->Users->validaPerfil($user, $data['mod_atendimento'], "mod_atendimento");

            if ($this->Users->save($new)) {                
                
                $this->Flash->success(__("O usuário foi gerado com sucesso. Senha de acesso <strong>{$senha}</strong>"));
                
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel criar a usuário <strong> {$new->nome}: </strong></p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Users', 'action' => 'add']);
            }

        }        
        
        $perfil = $this->request->session()->read('perfil');

        $this->aevOptions();
        $this->set('perfil', $perfil );
        $this->set('mode', 'add');
        $this->set('user', $user);
        $this->render("save");
    }

    public function edit($id = null){
        
        #$id = $this->validacaoID($id);        
        
        $user = $this->Users->get($id);
        
        if ($this->request->is('post')) {

            $data = $this->request->data;
            
            if(!$this->Users->ValidaSenha($data)){
                $this->Flash->error(__('Não foi possivel alterar a senha do usuário <strong>' .$user->nome.'</strong>.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }                        
            
            if(empty($data['password'])){
                unset($data['password']);
            }
            
            $new = $this->Users->patchEntity($user, $data);                                    
                        
            $new = $this->Users->validaPerfil($user, $data['mod_admin'], "mod_admin");
            $new = $this->Users->validaPerfil($user, $data['mod_user'], "mod_user");
            $new = $this->Users->validaPerfil($user, $data['mod_localidade'], "mod_localidade");
            $new = $this->Users->validaPerfil($user, $data['mod_setores'], "mod_setores");
            $new = $this->Users->validaPerfil($user, $data['mod_atendimento'], "mod_atendimento");

            if ($this->Users->save($new)) {                
                
                if($this->Users->save($new)){                
                    $this->Flash->success(__('O usuário <strong>' .$new->nome.' </strong> foi alterado(a) com sucesso !!!'));                    
                }else{
                    $this->Flash->error(__('Erro ao mudar o perfil do usuário <strong>' .$new->nome.' </strong> !!!'));                    
                }
                
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
                
            } else {
                $erros = $new->errors();
                if($erros){
                    foreach($erros as $key => $value){
                        $this->Flash->error(__(implode(' ', $value)));
                    }
                }
            }

        }

        $user_auth = $this->request->session()->read('Auth')['User'];                
        
        $perfil = $this->request->session()->read('perfil');        
        
        $this->aevOptions();
        $this->set('perfil', $perfil );
        $this->set('user_auth', $user_auth);
        $this->set('mode', 'edit');
        $this->set('user', $user);
        $this->render("save");
    }

    public function view($id = null){
        
        //$id = $this->validacaoID($id);
        
        $user = $this->Users->get($id);
                
        $user_auth = $this->Auth->user();        

        $perfil = $this->request->session()->read('perfil');                
        
        $this->aevOptions();
        $this->set('perfil', $perfil );
        $this->set('user_auth', $user_auth);
        $this->set('mode', 'view');
        $this->set('user', $user);
        $this->render("save");
    }

    public function delete($id = null){
        $user = $this->Users->get($id);

        if($user){

            $resul = $this->Users->delete($user);

            if ($resul){
                $this->Flash->success(__('O usuário <strong>' .$user->nome.'</strong> foi removido(a) com sucesso !!!'));
            }else{
                $this->Flash->error(__('Não foi possivel remover o usuário ' .$user->name));
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login(){

        $existe = $this->request->session()->read('painel-senha');
        
        if (!$existe) {            
            $this->request->session()->write('painel-senha', []);        
        }
        
        if($this->request->is('post')){

            try {

                $user = $this->Auth->identify();

                if ($user) {                    
                    
                    $this->Auth->setUser($user);
                    
                    $nome_completo = explode(" ", $user['nome']);
                    $iniciaisAll = explode(" ", $user['nome']);                    
                    $iniciais = count($iniciaisAll) == 1 ?  substr($iniciaisAll[0],0,1)."" : substr($iniciaisAll[0],0,1) . substr($iniciaisAll[count($iniciaisAll)-1],0,1);               
                    
                    $perfil = [
                        'id'   => $user['id'],
                        'admin' => $user['mod_admin'],
                        'user' => $user['mod_user'],
                        'localidade' => $user['mod_localidade'],
                        'setores' => $user['mod_setores'],
                        'atendimento' => $user['mod_atendimento'],
                    ];
                                        
                    $this->request->session()->write('logado', $nome_completo[0]);
                    $this->request->session()->write('perfil', $perfil);
                    $this->request->session()->write('iniciais', $iniciais);
                    
                    $this->carregarMesTrabalho();
                    
                    return $this->redirect($this->Auth->redirectUrl());
                }

                $erro = __('Acesso não autorizado');
                
                $this->Flash->auth_error($erro);
                
            } catch (\PDOException $e) {
                
                echo 'Exceção capturada: ', $e->getMessage(), "\n";exit;                
                //$this->Flash->auth_error('A aplicação está desativada !!!');
            }


            return $this->redirect($this->referer());
        }

        $this->set('user', $this->Users->newEntity());
    }

    public function logout() {
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function perfil() {
        
        $id = $this->Auth->user('id');               
        
        return $this->redirect(['controller' => 'Users', 'action' => 'edit', $id]);
        
    }

    public function aevOptions() {
        $aevOptions = $this->Users->aevOptions();

        $this->set('aevOptions', $aevOptions);
    }
    
    public function checkAccess(){
        
        $perfil = $this->request->session()->read('perfil');        
        
        if(!$perfil['user']){
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
        
    }
    
    // Filtrando Perfis: Admin x Comun
    public function validacaoID($id){

        $perfilEntity = (object) $this->request->session()->read('perfil');
        $isAdmin      = $perfilEntity->admin;        
        
        if(!$isAdmin){
            
            if($perfilEntity->id != $id) {
                return $perfilEntity->user_id;
            }            
        }
        
        return $id;
    }

}
