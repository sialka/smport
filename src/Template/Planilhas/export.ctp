<?php
$nav = [
    'Planilha' => '/Planilhas/index',
    'Exportar' => '',
];

echo $this->element('breadcrumb', [ 'nav' => $nav ]); 
?>

<div class="container-row normal">
    <div class="col-lg-6 col-md-4 col-sm-12 offset-lg-3 offset-md-4">



            <!-- Body -->
            <div class="card-body p-2">

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 offset-lg-3 offset-md-3">
                        <?= $this->Form->create("", array('class' => 'form-horizontal -needs-validation', 'type' => 'post', 'novalidate')) ?>
                        
                        <!-- CARD -->
                        <div class="card shadow no-radius">
                            
                            <!-- HEADER -->                            
                            <div class="card-header p-2 m-0 d-flex justify-content-center">
                                <div class="px-2 text-success">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div>
                                    <span class="p-0 strong"><?= __("Exportanto Arquivo Excel (.xls)") ?></span>                                
                                </div>
                            </div>                            
                            
                            <!-- BODY -->
                            <div class="card-body no-border p-0 m-0">         
                                
                                <div class="container py-3">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <label for="dt_lancamento" class="strong">Escolha o mês</label>
                                        <?=
                                        $this->Form->input('mesTrabalho',
                                                array(
                                                    'class'              => 'form-control text-center normal no-radius',
                                                    'id'                 => 'mes_trabalho',
                                                    'type'               => 'select',    
                                                    'options'            => $mesTrabalho,
                                                    'div'                => false,
                                                    'label'              => false,
                                                    'required'
                                                )
                                        )
                                        ?>
                                    </div>                 
                                </div>
                                
                            </div>
                            
                            <!-- FOOTER -->
                            <div class="card-footer bg-light">
                                <div class="text-right">
                                    
                                    <button type="submit" class="btn btn-success no-radius normal ">
                                        <i class="fa fa-check"></i>
                                        Gerar
                                    </button>           
                                    <a class="btn btn-link no-link normal" href="/Planilhas/index">
                                        <i class="fa fa-reply"></i>
                                        Voltar
                                    </a>                                            

                                </div>
                            </div>
                            
                        </div>
                        
                        <?= $this->Form->end() ?>
                        
                    </div>
                </div>
                
            </div>
            

    </div>
</div>

