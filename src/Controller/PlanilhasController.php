<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Spreadsheet; 

class PlanilhasController extends AppController {
    
    public $paginate = [
        'limit' => 25,
        'order' => [
            'Planilhas.nc' => 'asc',
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
                    'model'        => 'Planilhas',
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
        
        if(isset($this->request->data['_all'])){
            $all = $this->request->data['_all'];

            if (substr($all, -1) == '/'){            
                $this->request->data['_all'] = substr($all, 0, -1);    
            }
        }
       
        $mes_trabalho = $this->getSessionMesAnoTrabalho();

        $conversion = array(
            'Planilhas' => array(
                'id'              => array('name' => 'id', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('ID'), 'ignore' => array('')),
                'nc'              => array('name' => 'nc', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('NC'), 'ignore' => array('')),
                'dt_lancamento'   => array('name' => 'dt_lancamento', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Data de Lançamento'), 'ignore' => array('')),
                'dt_vencimento'   => array('name' => 'dt_vencimento', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Data de Vencimento'), 'ignore' => array('')),
                'dt_pagamento'    => array('name' => 'dt_pagamento', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Data de Pagamento'), 'ignore' => array('')),
                'codigo'          => array('name' => 'Localidades.codigo', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Código'), 'ignore' => array('')),
                'nota_fiscal'     => array('name' => 'nota_fiscal', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Nota Fiscal'), 'ignore' => array('')),
                'valor'           => array('name' => 'valor', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Valor'), 'ignore' => array('')),
                'fornecedor_nome' => array('name' => 'fornecedor_nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Fornecedor    '), 'ignore' => array('')),
                'igreja'          => array('name' => 'Localidades.nome', 'operation' => 'LIKE', 'coalesce' => false, 'date' => false, 'alias' => __('Localidade'), 'ignore' => array('')),
                'setor'           => array('name' => 'Localidades.setor', 'operation' => '', 'coalesce' => false, 'date' => false, 'alias' => __('Setor'), 'ignore' => array('')),
                '_all'            => array('name' => ['Planilhas.nc', 'Planilhas.dt_lancamento'], 'operations' => ['LIKE', 'LIKE'], 'coalesce' => false, 'date' => false, 'alias' => __('Pesquisa'), 'ignore' => array(''))
            )
        );

        if (isset($this->request->data) && is_array($this->request->data) && (sizeof($this->request->data) >= 1)) {
            $this->request->data['Planilhas'] = $this->request->data;
        }        
        
        $_conditions = $this->Conditions->filter('Planilhas', $conversion, [], null, null);
        $_conditions['conditions'] += ['Planilhas.mes_trabalho' => $mes_trabalho];        
        $_conditions['conditions'] += ['Planilhas.status' => true];        

        $planilhas = $this->paginate($this->Planilhas->find('all')->contain(['Localidades'])->where($_conditions['conditions']));

        $this->aevOptions();
        $this->set('planilhas', $planilhas);
        $this->set('_conditions',   $_conditions['stringFilter']);
  
    }

    public function add(){
        
        $planilha = $this->Planilhas->newEntity();

        if ($this->request->is('post')) {
            
            $ncsTable = TableRegistry::get('Ncs');
            
            $data = $this->request->data;

            $new = $this->Planilhas->patchEntity($planilha, $data);                                             
            
            $user_id = $this->request->session()->read('Auth')['User']['id'];
            $nc_id   = $this->request->session()->read('mes')['id'];
            $nc_mes  = $this->request->session()->read('mes')['mes'];
            $nc_ano  = $this->request->session()->read('mes')['ano'];
            
            
            $new->user_id       = $user_id;
            $new->nc_id         = $nc_id;
            $new->nc            = $ncsTable->controle($nc_id);
            $new->dt_lancamento = "{$nc_ano}-{$nc_mes}-{$data['dt_lancamento']}";
            $new->dt_vencimento = "{$nc_ano}-{$nc_mes}-{$data['dt_vencimento']}";
            $new->dt_pagamento  = "{$nc_ano}-{$nc_mes}-{$data['dt_pagamento']}";
            $new->mes_trabalho  = "{$nc_mes}-{$nc_ano}";

            $save = $this->Planilhas->save($new);
            
            if ($save) {                
                
                $this->Flash->success(__('O lançamento foi adicionado com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Planilhas', 'action' => 'add']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel salvar o lançamento !!! </p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $save->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Planilhas', 'action' => 'add']);
            }

        }

        $this->aevOptions();
        $this->set('mode', 'add');
        $this->set('planilha', $planilha);
        $this->render("save");
    }

    public function edit($id = null){        
        
        $planilha = $this->Planilhas->get($id, ['contain' => ['Localidades', 'Ncs']]); 
        //debug($planilha);        

        if ($this->request->is('post')) {
            
            $ncsTable = TableRegistry::get('Ncs');
            
            $data = $this->request->data;

            $new = $this->Planilhas->patchEntity($planilha, $data);                                             
            
            $user_id = $this->request->session()->read('Auth')['User']['id'];
            $nc_id   = $this->request->session()->read('mes')['id'];
            $nc_mes  = $this->request->session()->read('mes')['mes'];
            $nc_ano  = $this->request->session()->read('mes')['ano'];
            
            
            $new->user_id       = $user_id;
            $new->nc_id         = $nc_id;            
            $new->dt_lancamento = "{$nc_ano}-{$nc_mes}-{$data['dt_lancamento']}";
            $new->dt_vencimento = "{$nc_ano}-{$nc_mes}-{$data['dt_vencimento']}";
            $new->dt_pagamento  = "{$nc_ano}-{$nc_mes}-{$data['dt_pagamento']}";
            $new->mes_trabalho  = "{$nc_mes}-{$nc_ano}";
            
            if ($this->Planilhas->save($new)) {                
                
                $this->Flash->success(__('O lançamento foi alterado com sucesso !!!'));
                
                return $this->redirect(['controller' => 'Planilhas', 'action' => 'add']);
                
            } else {

                $error_list = "<p class='mt-2'>Não foi possivel salvar o lançamento !!! </p>";
                $error_list .= '<ul class="mt-3">';
                $erros = $new->errors();
                                
                if($erros){
                    foreach($erros as $key => $value){
                        $error_list .= "<li>".implode(' ', $value) . "</li>";
                    }
                }
                $error_list .= '</ul>';
                $this->Flash->error($error_list);
                
                return $this->redirect(['controller' => 'Planilhas', 'action' => 'add']);
            }

        }

        $this->aevOptions();
        $this->set('mode', 'edit');
        $this->set('planilha', $planilha);
        $this->render("save");
    }
    
    public function view($id = null){        
        
        $planilha = $this->Planilhas->get($id, ['contain' => ['Localidades', 'Ncs']]);    

        $this->aevOptions();
        $this->set('mode', 'view');
        $this->set('planilha', $planilha);
        $this->render("save");
    }    
    
    public function delete($id = null){
        
        $planilha = $this->Planilhas->get($id);    
        
        $delete = $this->Planilhas->deletar($planilha);               
        
        if($delete){
            $this->Flash->success(__("Lançamento excluído !!!"));
        }else{
            $this->Flash->error(__("Não foi possivel deletar o lançamento !!!"));
        }
        
        return $this->redirect(['controller' => 'Planilhas', 'action' => 'index']);        
    }

    public function import() {          
        
        $resultado = [
            'status' => '',
            'message' => '',
            'data' => '',
        ];
        
        if ($this->request->is('post')){            
            
            $file     = $this->request->data['file'];

            $resultado = $this->Planilhas->importarXls($file);            
            
        }
        
        $this->set('resultado', $resultado['data']);        
        $this->set('mensagem',  $resultado['message']);
    }
    
    public function export() {                
        
        if ($this->request->is('post')) {
            
            $aevOptions = $this->Planilhas->aevOptions();                    
            
            $ncsTable = TableRegistry::get('Ncs');
            
            $nc_id = $this->request->data(['mesTrabalho']);   
            
            $ncEntity = $ncsTable->find()->where(['id' => $nc_id])->first();                       
            
            if(!$ncEntity){
                $this->Flash->error(__('Não foi possivel gerar o arquivo, erro ao localizar o mês de trabalho !!!'));
                return $this->redirect(['controller' => 'Planilhas', 'action' => 'export']);
            }
            
            $data = $this->Planilhas->find()->contain(['Localidades','Ncs'])->where(['Planilhas.nc_id' => $nc_id, 'Planilhas.status' => true])->toArray();            
            
            if($data){
                
                // Populating the table 
                try {
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet(); //instanciando uma nova planilha
                    $sheet       = $spreadsheet->getActiveSheet(); //retornando a aba ativa
                                        
                    // Titulos 
                    $sheet->setCellValue('B1', 'Nº lançamento'); //Definindo a célula A1
                    $sheet->setCellValue('C1', 'Igreja'); //Definindo a célula B1
                    $sheet->setCellValue('D1', 'Código da Igreja');
                    $sheet->setCellValue('E1', 'Setor');
                    $sheet->setCellValue('F1', 'Data de Lançamento');
                    $sheet->setCellValue('G1', 'Mês da ocorrência');
                    $sheet->setCellValue('H1', 'Nº N.C');
                    $sheet->setCellValue('I1', '3 cotações?');
                    $sheet->setCellValue('J1', 'Visto de Lançamento no siga');
                    $sheet->setCellValue('K1', 'Valor fora da Média (Sim,Não)');
                    $sheet->setCellValue('L1', 'Pagamento em Atraso (Sim,Não)');
                    $sheet->setCellValue('M1', 'Data Do Vencimento');
                    $sheet->setCellValue('N1', 'Data do Pagamento');
                    $sheet->setCellValue('O1', 'Nota Fiscal');
                    $sheet->setCellValue('P1', 'Valor Pago');
                    $sheet->setCellValue('Q1', 'Serviços Tomados (Sim,Não)');
                    $sheet->setCellValue('R1', 'Falta de Contrato de Prestação Serv.');
                    $sheet->setCellValue('S1', 'Nome do Fornecedor');
                    $sheet->setCellValue('T1', 'Outras ocorrências');
                    $sheet->setCellValue('U1', 'Observação');
                    $sheet->setCellValue('V1', 'Retorno');
                    
                    // Dimensões
                    
                    // 1.00 -> 1.70   != 0,70                  
                    // 12.20 -> 11.43 != 0,77                    
                    
                    // Altura da Linha
                    $sheet->getRowDimension(1)->setRowHeight(30);
                    
                    // Largura das Colunas
                    $sheet->getColumnDimension('A')->setWidth(1.70);   // 1,00                 
                    $sheet->getColumnDimension('B')->setWidth(12.20);  // 11,43
                    $sheet->getColumnDimension('C')->setWidth(13.34);  // 12,57            
                    $sheet->getColumnDimension('D')->setWidth(17.63);  // 16.86                    
                    $sheet->getColumnDimension('E')->setWidth(14.20);  // 13,43                                        
                    $sheet->getColumnDimension('F')->setWidth(13.48);  // 12,71                    
                    $sheet->getColumnDimension('G')->setWidth(13.91);  // 13,14                    
                    $sheet->getColumnDimension('H')->setWidth(12.06);  // 11,29                    
                    $sheet->getColumnDimension('I')->setWidth(16.20);  // 15,43
                    $sheet->getColumnDimension('J')->setWidth(19.34);  // 18,57
                    $sheet->getColumnDimension('K')->setWidth(16.63);  // 15,86
                    $sheet->getColumnDimension('L')->setWidth(18.77);  // 18,00
                    $sheet->getColumnDimension('M')->setWidth(19.48);  // 18,71
                    $sheet->getColumnDimension('N')->setWidth(18.48);  // 17,71
                    $sheet->getColumnDimension('O')->setWidth(13.91);  // 13,14                    
                    $sheet->getColumnDimension('P')->setWidth(13.91);  // 13,14                    
                    $sheet->getColumnDimension('Q')->setWidth(17.34);  // 16,57
                    $sheet->getColumnDimension('R')->setWidth(18.34);  // 17,57
                    $sheet->getColumnDimension('S')->setWidth(17.06);  // 16,29
                    $sheet->getColumnDimension('T')->setWidth(17.48);  // 16,71
                    $sheet->getColumnDimension('U')->setWidth(14.63);  // 13,86
                    $sheet->getColumnDimension('V')->setWidth(12.06);  // 11,29

                    // Cor                    
                    $sheet->getStyle('B1:V1')
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('D9D9D9');
                    
                    // Font
                    $sheet->getStyle('B1:V1')
                            ->getFont()->setBold(true)
                            ->getColor()->setARGB('000000');
                    
                    // Alinhamento
                    $sheet->getStyle('B1:V1')
                        ->getAlignment()
                        ->setWrapText(true)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    
                    // Borda
                    $sheet->getStyle('B1:V1')
                        ->getBorders()
                            ->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);          
                    
                    $indice  = 0;
                    $column  = 1;
                    $row     = 1;
                    
                    foreach ($data as $key => $linha) {
                        
                        $indice++;
                        $column++;                         
                        $row++;                                                                                       
                        
                        // Alinhamento
                        $sheet->getStyle("B{$column}:V{$column}")
                            ->getAlignment()
                            ->setWrapText(true)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        // Borda
                        $sheet->getStyle("B{$column}:V{$column}")
                            ->getBorders()
                                ->getAllBorders()
                                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);                        
                        
                        $sheet->setCellValue("B{$column}", $indice);
                        $sheet->setCellValue("C{$column}", $linha->Localidades->nome);
                        $sheet->setCellValue("D{$column}", "BR ".$linha->Localidades->codigo);
                        $sheet->setCellValue("E{$column}", $aevOptions['setores'][$linha->Localidades->setor]);
                        $sheet->setCellValue("F{$column}", $linha->dt_lancamento->i18nFormat('dd/MM/yyyy'));
                        $sheet->setCellValue("G{$column}", $linha->Ncs->MesOcorrencia);
                        $sheet->setCellValue("H{$column}", $linha->nc);                        
                        $sheet->setCellValue("I{$column}", $aevOptions['cotacoes'][$linha->cotacoes]);
                        $sheet->setCellValue("J{$column}", $aevOptions['cotacoes'][$linha->visto]);                        
                        $sheet->setCellValue("K{$column}", $aevOptions['media'][$linha->media]);
                        $sheet->setCellValue("L{$column}", $aevOptions['atraso'][$linha->pagto_atraso]);
                        $sheet->setCellValue("M{$column}", $linha->dt_vencimento->i18nFormat('dd/MM/yyyy'));
                        $sheet->setCellValue("N{$column}", $linha->dt_pagamento->i18nFormat('dd/MM/yyyy'));
                        $sheet->setCellValue("O{$column}", $linha->nota_fiscal);
                        $sheet->setCellValue("P{$column}", $linha->valor);
                        $sheet->setCellValue("Q{$column}", $aevOptions['servico'][$linha->serv_tomados]);
                        $sheet->setCellValue("R{$column}", $aevOptions['contrato'][$linha->falta_contrato]);
                        $sheet->setCellValue("S{$column}", $linha->fornecedor_nome);                        
                        $sheet->setCellValue("T{$column}", $linha->ocorrencias);
                        $sheet->setCellValue("U{$column}", $linha->obs);
                        $sheet->setCellValue("V{$column}", $linha->retorno);
                        
                        $sheet->getRowDimension($row)->setRowHeight(15);
                    }
                    

                    $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet); //Instance of a new spreadsheet
                    //$dthora   = date('Ymd_His');
                    $filename = "conselho-fiscal-".$ncEntity->ano."-".$ncEntity->mes.".xlsx"; // naming the table 
                    $writer->save(TMP . "$filename"); //writing the table into a file 

                    $this->response->body(file_get_contents(TMP . "$filename"));
                    $this->response->type('xlsx');
                    $this->response->download($filename);

                    unlink(TMP . "$filename");
                    return $this->response;
                    
                } catch (Exception $e) {
                    $this->Flash->error($e->getMessage());
                    return $this->redirect(['controller' => 'Planilhas', 'action' => 'export']);
                    return false;
                }
            }
            
            $this->Flash->error(__("Não foram encontrados dados para gerar o relatório !!!"));
            return $this->redirect(['controller' => 'Planilhas', 'action' => 'export']);
            
        }
        
        $ncsTable = TableRegistry::get('Ncs');
        $mesTrabalho = $ncsTable->getMesTrabalhoAtivos();
        
        $this->set('mesTrabalho', $mesTrabalho);
    }

    public function aevOptions() {

        $aevOptions = $this->Planilhas->aevOptions();

        $this->set('aevOptions', $aevOptions);
    }


}
