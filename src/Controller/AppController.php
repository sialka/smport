<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;

class AppController extends Controller
{
    protected $varForSerialize      = [];

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
        
    }

    public function beforeFilter(Event $event){
       $this->Security->config('blackHoleCallback', 'blackhole');
    }

    public function beforeRender(Event $event){        

        if (!isset($this->viewVars['_notSerialize'])) {
            if (($this->request->is('ajax')) || (in_array($this->response->type(), ['application/json', 'application/xml']))) {
                if (sizeof($this->varForSerialize) <= 0) {
                    if (isset($this->_notSerialize) && is_array($this->_notSerialize)) {
                        $this->set('_serialize', array_diff(array_keys($this->viewVars), $this->_notSerialize));
                    } else {
                        $this->set('_serialize', array_keys($this->viewVars));
                    }
                } else {
                    $this->set('_serialize', $this->varForSerialize);
                }
            }
        }

        $layout = $this->request->session()->read('layout');

        //debug("Layout: ".$layout);

        if($layout == 'default'){
            $this->viewBuilder()->setLayout('default');
        }else{

            if($layout == 'admin'){            
               $this->viewBuilder()->setLayout('admin');
            }else{
                $this->viewBuilder()->setLayout('login');
            }
        }

    }    

    public function blackhole($type, \Cake\Controller\Exception\SecurityException $exception) {
        throw $exception;
        if ($exception->getMessage() === 'Request is not SSL and the action is required to be secure') {
            // Reword the exception message with a translatable string.
            // $exception->setMessage(__('Please access the requested page through HTTPS'));
        }

        // Re-throw the conditionally reworded exception.
        //$this->Flash->error(__('Houve uma tentativa de burlar o preenchimento de algum campo. Processo cancelado'));
        //return $this->redirect('/');
        // Alternatively, handle the error, e.g. set a flash message &
        // redirect to HTTPS version of the requested page.
    }

    public function utils(){

        $util = [
            'semana_longo' => ['DOMINGO','SEGUNDA-FEIRA','TERÇA-FEIRA','QUARTA-FEIRA','QUINTA-FEIRA','SEXTA-FEIRA','SABADO'],
            'semana_curto' => ['DOM','SEG','TER','QUA','QUI','SEX','SAB'],
            'semana_us_curto' => [
                'Sunday' => 'DOM',
                'Monday' => 'SEG',
                'Tuesday' => 'TER',
                'Wednesday' => 'QUA',
                'Thursday' => 'QUI',
                'Friday' => 'SEX',
                'Saturday' => 'SAB',
            ],
            'semana_us_longo' => [
                'Sunday' => 'DOMINGO',
                'Monday' => 'SEGUNDA-FEIRA',
                'Tuesday' => 'TERÇA-FEIRA',
                'Wednesday' => 'QUARTA-FEIRA',
                'Thursday' => 'QUINTA-FEIRA',
                'Friday' => 'SEXTA-FEIRA',
                'Saturday' => 'SABADO',
            ],
            'semana_dia_us' => [
                'Sunday' => '1',
                'Monday' => '2',
                'Tuesday' => '3',
                'Wednesday' => '4',
                'Thursday' => '5',
                'Friday' => '6',
                'Saturday' => '7',
            ],
            'semana_id_curto' => [
                '1' => 'DOM',
                '2' => 'SEG',
                '3' => 'TER',
                '4' => 'QUA',
                '5' => 'QUI',
                '6' => 'SEX',
                '7' => 'SAB',
            ],
            'semana_id_longo' => [
                '1' => 'DOMINGO',
                '2' => 'SEGUNDA-FEIRA',
                '3' => 'TERÇA-FEIRA',
                '4' => 'QUARTA-FEIRA',
                '5' => 'QUINTA-FEIRA',
                '6' => 'SEXTA-FEIRA',
                '7' => 'SABADO',
            ],

        ];

        return $util;
    }

}
