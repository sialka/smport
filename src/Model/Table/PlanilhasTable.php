<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PlanilhasTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('planilhas');
        $this->setDisplayField('mes_ocorrencia');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->hasOne('Localidades', [
            'className'         => 'Localidades',
            'bindingKey'        => 'localidade_id',
            'foreignKey'        => 'id',
            'propertyName'      => 'Localidades',
            
        ]);        
        
        $this->hasOne('Ncs', [
            'className'         => 'Ncs',
            'bindingKey'        => 'nc_id',
            'foreignKey'        => 'id',
            'propertyName'      => 'Ncs',
            
        ]);          

    }

    public function validationDefault(Validator $validator)
    {
        //$validator->notEmpty('codigo', __('Informe o código da Localidade'));
        //$validator->notEmpty('nome', __('Informe o nome da Localidade'));

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {

        //$rules->add($rules->isUnique(['codigo'], 'O código da localidade informado já está em uso'));
        //$rules->add($rules->isUnique(['nome'], 'O nome da localidade informado já está em uso'));

        return $rules;
    }
    
    public function deletar(Entity $entity){
        
        $entity->status = false;
        
        if($this->save($entity)){
            
            return true;
        }
        
        return false; 
        
    }
    
    public function getSaldos($mesTrabalho){
                        
        $setor0 = 0;
        $setor1 = 0;
        $setor2 = 0;
        $setor3 = 0;
        $setor4 = 0;

        $query = $this->find()->contain('Localidades')->where(['mes_trabalho' => $mesTrabalho])->toArray();
		
        foreach($query as $igreja){            
			
			if(!isset($igreja->Localidades->setor)){
				continue;
			}
			
            if($igreja->Localidades->setor == '0'){
                $setor0 += $igreja->valor;
            }

            if($igreja->Localidades->setor == '1'){
                $setor1 += $igreja->valor;
            }

            if($igreja->Localidades->setor == '2'){
                $setor2 += $igreja->valor;
            }

            if($igreja->Localidades->setor == '3'){
                $setor3 += $igreja->valor;
            }

            if($igreja->Localidades->setor == '4'){
                $setor4 += $igreja->valor;
            }

        }    

        $saldos = [
            'setor0' => $setor0,
            'setor1' => $setor1,
            'setor2' => $setor2,
            'setor3' => $setor3,
            'setor4' => $setor4,
        ];       
        
        
        $total = $setor0 + $setor1 + $setor2 + $setor3 + $setor4;
        
        if($total != 0){            
        
            $pSt0 = ($setor0 * 100) / $total;
            $pSt1 = ($setor1 * 100) / $total;
            $pSt2 = ($setor2 * 100) / $total;
            $pSt3 = ($setor3 * 100) / $total;
            $pSt4 = ($setor4 * 100) / $total;
        
        }else{
        
            $pSt0 = $pSt1 = $pSt2 = $pSt3 = $pSt4 = 0;
            
        }
        
        $porcentagens = [
            'setor0' => round($pSt0, 4),
            'setor1' => round($pSt1, 4),
            'setor2' => round($pSt2, 4),
            'setor3' => round($pSt3, 4),
            'setor4' => round($pSt4, 4),            
        ];
        
        return [
            'saldos'       => $saldos, 
            'porcentagens' => $porcentagens
        ];
    }

    public function aevOptions(){
        
        $igrejasTable = TableRegistry::get('Localidades');
        
        $setores = $igrejasTable->aevOptions()['setores'];

        $options = [            
            'cotacoes'    => [2 => 'N/A', 1 => 'Sim', 0 => 'Não'],
            'visto'       => [1 => 'Sim', 0 => 'Não'],
            'media'       => [2 => 'N/A', 1 => 'Sim', 0 => 'Não'],
            'atraso'      => [2 => 'N/A', 1 => 'Sim', 0 => 'Não'],
            'servico'     => [1 => 'Sim', 0 => 'Não'],
            'contrato'    => [2 => 'N/A', 1 => 'Sim', 0 => 'Não'],
            'setores'     => $setores,
        ];

        return $options;
    }
    
    public function importarXls($arquivoUpload){        
        
        $data = [
            'status'  => 'OK',
            'message' => '',
            'data'    => '',
        ];
        
        $resul = $this->baixarArquivoXls($arquivoUpload);
        if($resul['status'] == 'NOK'){
            $data['status']  = 'NOK';
            $data['message'] = 'Erro no upload do arquivo xls';
            return $data;
        }
        
        $arquivo = $resul['data'];
        
        $lancamentos = $this->extrairDadosXls($arquivo);
        if(sizeof($lancamentos) == 0){
            $data['status']  = 'NOK';
            $data['message'] = 'Erro ao extrair dados do arquivo';
            return $data;            
        }

        $resul = $this->deletarArquivo($arquivo);
        if($resul['status'] == 'NOK'){
            $data['status']  = 'NOK';
            $data['message'] = $resul['message'];
            return $data;
        }
        
        $resul = $this->registrarLancamentos($lancamentos);
        if($resul['status'] == 'NOK'){
            $data['status']  = 'NOK';
            $data['message'] = $resul['message'];
            $data['data']    = $resul['data'];
            return $data;
        }        
        
        $data['status']  = $resul['status'];
        $data['message'] = $resul['message'];
        $data['data']    = $resul['data'];
        return $data;
    }
    
    private function baixarArquivoXls($arquivoUpload){
        
        $data = [
            'status'  => 'OK',
            'message' => '',
            'data'    => '',
        ];
        
        if(empty($arquivoUpload['tmp_name']) || empty($arquivoUpload['name']) || empty($arquivoUpload['type'])){
            $data['status'] = 'NOK';
            return $data;
        }
        
        $fileType  = 'Xlsx';
        $fileName  = $arquivoUpload['tmp_name'];
        $reader    = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        
        $arquivo_extensao = explode(".", $arquivoUpload['name']);                
        $limparNomeArquivo = preg_replace("/[^a-zA-Z0-9-]/", "-", strtr(utf8_decode(trim($arquivo_extensao[0])), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),"aaaaeeiooouuncAAAAEEIOOOUUNC-"));
        $novoNomeArquivo   = strtolower($limparNomeArquivo.'.'.$arquivo_extensao[1]);        
        
        $reader->setReadDataOnly(true);               

        $spreadsheet    = $reader->load($fileName);

        $destino = WWW_ROOT . "files" . DS . $novoNomeArquivo; 

        if(move_uploaded_file($fileName, $destino)){ 
            $data['data'] = $destino;
            return $data;
        }               
        
        $data['status'] = 'NOK';
        return $data;
    }
    
    private function extrairDadosXls($arquivo){
                
        try {
            
            $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($arquivo);
            
            $sheet       = $spreadsheet->getActiveSheet();
            $data        = [];
            
            foreach ($sheet->getRowIterator(2) as $row) {
                
                $cellInterator = $row->getCellIterator();
                $cellInterator->setIterateOnlyExistingCells(false);            

                $colunas_vazias = 0;
                $rowData        = [];
                $colunaComData  = [5, 6, 12, 13];
                $colunaPosicao  = 0;

                foreach ($cellInterator as $cell) {
                    
                    if (!is_null($cell)) {
                        $value = $cell->getCalculatedValue();     
                        

                        if (empty($value)){
                            $colunas_vazias++;
                        }

                        if (in_array($colunaPosicao, $colunaComData)) { 
                            $dataConvertida = Date::excelToDateTimeObject($value)->format('Y-m-d');
                            array_push($rowData, $dataConvertida);
                        }else{
                            array_push($rowData, $value);
                        }
                        
                    }

                    if($colunas_vazias > 23){                        
                        break;
                    }
                    $colunaPosicao++;
                }

                if($colunas_vazias > 23){                    
                    break;
                }                    
                array_push($data, $rowData);
            }              
            
        } catch (Exception $ex) {            
            return $data;
        }              
        
        return $data;
    }
    
    private function deletarArquivo($arquivo){
            
        $data = [
            'status'  => 'OK',
            'message' => '',
            'data'    => '',
        ];
        
        if(!file_exists($arquivo)){
           $data['status']  = 'NOK';
           $data['message'] = 'Erro não encontrei o arquivo para deletar';            
           return $data;
        }
       
        if(!unlink($arquivo)){
           $data['status']  = 'NOK';
           $data['message'] = 'Erro ao deletar o arquivo';
           return $data;
        } 
        
        return $data;
    }
    
    private function registrarLancamentos($lancamentos){
        
        $usuariosTable    = TableRegistry::get('Users');  
        $localidadesTable = TableRegistry::get('Localidades'); 
        $ncsTable         = TableRegistry::get('Ncs'); 
        
        $usuario = $usuariosTable->find()->where(['username' => 'sistema'])->first();       
        
        $aevOptions = $this->aevOptions();
        
        $data = [
            'status'  => 'OK',
            'message' => '',
            'data'    => '',
        ];
        
        $igrejasNaoImportadas = [];
        $acertos              = 0;
        $erros                = 0;
        
        foreach($lancamentos as $lancamento){
            
            $entity = [
                'localidade_id'   => 0,
                'dt_lancamento'   => $lancamento[5],
                'dt_vencimento'   => $lancamento[12],
                'dt_pagamento'    => $lancamento[13],
                'nc'              => 0,
                'cotacoes'        => 0,
                'visto'           => 0,
                'media'           => 0,
                'pagto_atraso'    => 0,
                'nota_fiscal'     => strlen($lancamento[14]) == 0 ? '' : $lancamento[14],
                'valor'           => $lancamento[15],
                'serv_tomados'    => 0,
                'falta_contrato'  => 0,
                'fornecedor_nome' => $lancamento[18],
                'ocorrencias'     => $lancamento[20],
                'obs'             => $lancamento[21],
                'retorno'         => $lancamento[22],
                'user_id'         => $usuario->id,
                'status'          => true,
                'nc_id'           => 0,
                'mes_trabalho'    => 0,
            ];            
            
            $cod_igreja = substr($lancamento[3], 3, 7);            
            $igreja     = $localidadesTable->find()->where(['codigo' => $cod_igreja])->first();
            if(!$igreja){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Igreja não cadastrada - {$cod_igreja}",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                
            }
            $entity['localidade_id'] = $igreja->id;            
            
            $mesTrabalho = substr($lancamento[6], 5, 2);
            $anoTrabalho = substr($lancamento[6], 0, 4);
            $entity['mes_trabalho'] = $mesTrabalho .'-'. $anoTrabalho;
            
            
            foreach($aevOptions['cotacoes'] as $key => $value){            
                if(mb_strtolower($lancamento[8]) == mb_strtolower($value)){
                    $cotacao = $key;
                }
            }                        
            // Tratamento - Caso Atípico
            if(mb_strtolower($lancamento[8]) == mb_strtolower("NA")){
                $cotacao = 2; // N/A
            }
            if(!isset($cotacao)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro na coluna 3 cotações",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                                
            }            
            $entity['cotacoes'] = $cotacao;
            
            
            foreach($aevOptions['visto'] as $key => $value){                
                if(mb_strtolower($lancamento[9]) == mb_strtolower($value)){
                    $visto = $key;
                }
            }
            if(!isset($visto)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro na coluna visto de lançamento no Siga",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                                
            }                        
            $entity['visto'] = $visto;
            
            foreach($aevOptions['media'] as $key => $value){
                if(mb_strtolower($lancamento[10]) == mb_strtolower($value)){
                    $media = $key;
                }
            }
            // Tratamento - Caso Atípico
            if(mb_strtolower($lancamento[8]) == mb_strtolower("NA")){
                $media = 2; // N/A
            }            
            if(!isset($media)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro na coluna valor fora da média",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                                
            }             
            $entity['media'] = $media;
            
            foreach($aevOptions['atraso'] as $key => $value){
                if(mb_strtolower($lancamento[11]) == mb_strtolower($value)){
                    $atraso = $key;
                }
            }
            // Tratamento - Caso Atípico
            if(mb_strtolower($lancamento[11]) == mb_strtolower("NA")){
                $atraso = 2; // N/A
            }                        
            if(!isset($atraso)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro na coluna pagamento em atraso",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                                
            }             
            $entity['pagto_atraso'] = $atraso;            
            
            foreach($aevOptions['servico'] as $key => $value){
                if(mb_strtolower($lancamento[16]) == mb_strtolower($value)){
                    $servico = $key;
                }
            }
            if(!isset($servico)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro na coluna serviços tomados",
                ];
                array_push($igrejasNaoImportadas, $erro); 
                $erros++;
                continue;                                
            }             
            $entity['serv_tomados'] = $servico; 

            $contrato = 2;
            foreach($aevOptions['contrato'] as $key => $value){            
                if(mb_strtolower($lancamento[17]) == mb_strtolower($value)){
                    $contrato = $key;
                }
            }
            $entity['falta_contrato'] = $contrato; 

            $ncs     = $ncsTable->find()->where(['mes' => $mesTrabalho, 'ano' => $anoTrabalho])->first();
            if(!$ncs){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Mês de trabalho não cadastrado - {$mesTrabalho}/{$anoTrabalho}",
                ];
                array_push($igrejasNaoImportadas, $erro);
                $erros++;
                continue;                                
            }
            $entity['nc_id'] = $ncs->id;
            
            $controle = $ncsTable->controle($ncs->id);
            $entity['nc'] = $controle;
                        
            $filtro = [
                'localidade_id'   => $entity['localidade_id'],
                'dt_lancamento'   => $entity['dt_lancamento'],
                'dt_vencimento'   => $entity['dt_vencimento'],
                'dt_pagamento'    => $entity['dt_pagamento'],
                'nota_fiscal'     => $entity['nota_fiscal'],
                'valor'           => $entity['valor'],
                'fornecedor_nome' => $entity['fornecedor_nome'],
                'mes_trabalho'    => $entity['mes_trabalho'],
            ];
            
            $resul = $this->find()->where($filtro)->first();
            if($resul){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Lançamento já está cadastrado",
                ];
                array_push($igrejasNaoImportadas, $erro);
                $erros++;
                continue;
            }
            
            $planilhaEntity = $this->newEntity($entity);
            
            if(!$this->save($planilhaEntity)){
                $erro = [
                    'lancamento' => $lancamento[1],
                    'localidade' => $lancamento[2],
                    'erro'       => "Erro ao registrar o lançamento",
                ];
                array_push($igrejasNaoImportadas, $erro);                
                $erros++;
            }else{
                $acertos++;
            }
            
        }
        
        $data['message'] = "Acertos: {$acertos} - Erros: {$erros}";
        $data['data']    = $igrejasNaoImportadas;
        
        return $data;
    }
}
