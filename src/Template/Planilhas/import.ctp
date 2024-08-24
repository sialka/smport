<?php
$nav = [
    'Planilha' => '/Planilhas/index',
    'Importar' => '',
];

echo $this->element('breadcrumb', [ 'nav' => $nav ]); 
?>

<div class="container-row normal">
    <div class="col-lg-10 col-md-10 col-sm-12 offset-lg-1 offset-md-1">

            <!-- Body -->
            <div class="card-body p-2">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 -offset-lg-3 -offset-md-3">
                        <?= $this->Form->create("", array('class' => 'form-horizontal ', 'type' => 'post', 'enctype' => "multipart/form-data")) ?>
                        
                        <!-- CARD -->
                        <div class="card shadow no-radius">
                            
                            <!-- HEADER -->                            
                            <div class="card-header p-2 m-0 d-flex justify-content-center">
                                <div class="px-2 text-success">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div>
                                    <span class="p-0 strong"><?= __("Importar Arquivo Excel") ?></span>                                
                                </div>
                            </div>                            
                            
                            <!-- BODY -->
                            <div class="card-body no-border p-0 m-0">         
                                
                                <div class="mx-4 py-3">
                                    
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <label class="normal" for="">Selecione o arquivo</label>
                                            <?=
                                            $this->Form->control('file', array(
                                                'type'   => 'file',
                                                'label'  => false,
                                                'class'  => 'form-control file',
                                                'accept' => ".xls, .xlsx"
                                                    )
                                            );
                                            ?>            
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <label class="normal" for="">Status:</label>
                                            <?php if(!empty($mensagem)): ?>
                                                <label class="strong" for=""><?= $mensagem; ?></label>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <label class="normal" for="">Detalhes da Importação:</label>                                            
                                            <div class="table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                                                <table id="tableResults" class="table-responsive-lg table table-bordered p-0 m-0 no-border">
                                                    <thead>
                                                        <tr class="normal strong">                                   
                                                            <th class="text-left px-3" width="15%"><?= __('Nº Lançamento') ?></th>
                                                            <th class="text-left px-3" width="30%"><?= __('Localidades') ?></th>
                                                            <th class="text-left px-3" width="55%"><?= __('Erros') ?></th>
                                                        </tr>
                                                    </thead>                                   
                                                    <tbody class="">
                                                        <?php if(!empty($resultado)): ?>
                                                            <?php foreach ($resultado as $dado): ?>
                                                                <tr class="normal">
                                                                    <td class="text-left   px-3"><?= $dado['lancamento']; ?></td>
                                                                    <td class="text-left   px-3"><?= $dado['localidade']; ?></td>
                                                                    <td class="text-center px-3"><?= $dado['erro']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>                                                    
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>                                
                            </div>
                            
                            <!-- FOOTER -->
                            <div class="card-footer bg-light">
                                <div class="text-right">
                                    
                                    <button type="submit" class="btn btn-success no-radius normal ">
                                        <i class="fa fa-check"></i>
                                        Importar
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

<script>

    $custom-file-text: (
        pt: "aaa",
        en: "Browse",
        es: "Elegir"
    );    

</script>