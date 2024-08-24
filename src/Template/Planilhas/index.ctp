<?php
    $nav = [
        'Planilha' => ''
    ];
?>
<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row normal">
    <div class="col-12">
        
        <!-- Buttons -->
        <div class="col-12 p-0 mb-2 ">
            <a class="btn btn-success no-radius normal" href="/Planilhas/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>
            
            <button class="btn btn-info no-radius normal ml-1" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-filter fa-sm"></i>
                <span class="">Filtro</span>
            </button>       
            
            <a class="btn btn-warning no-radius normal" href="/Planilhas/export">
                <i class="fa fa-download fa-sm"></i>
                <span class="">Exportar</span>
            </a>                        

        </div>

        <!-- Card -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card shadow no-radius border-1">
                            
                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">
                        <?= $this->element('search', [ 'search' => 'Por NC ou Lançamento' ]); ?>
                    </div>                            
                            
                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">
                        
                        <div class="table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table-responsive-lg table table-bordered p-0 m-0 no-border">
                                <thead>
                                    <tr class="normal strong">                                   
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Planilhas.nc', __('NC') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%' ,'Planilhas.dt_lancamento', __('Lançamento') ] ]); ?>                                        
                                        <th class="text-left px-3" width="20%"><?= __('Casa de Oração') ?></th>
                                        <th class="text-left px-3" width="10%"><?= __('Setor') ?></th>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Planilhas.dt_vencimento', __('Vencimento') ] ]); ?>                                        
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Planilhas.nota_iscal', __('Nota Fiscal') ] ]); ?>   
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Planilhas.valor', __('Valor') ] ]); ?>                                        
                                        <?= $this->element('th_sort', [ 'th' => ['20%', 'Planilhas.fornecedor_nome', __('Fornecedor') ] ]); ?>
                                        <th class="text-right"     width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php foreach ($planilhas as $planilha): ?>
                                        <tr class="normal">
                                            <td class="text-left   px-3"><?= h($planilha->nc) ?></td>
                                            <td class="text-center px-3"><?= $planilha->dt_lancamento->i18nFormat('dd/MM/yyyy'); ?></td>
                                            <td class="text-left   px-3">
												<?php if(isset($planilha->Localidades)){
													echo "BR ".$planilha->Localidades->codigo . " - " .$planilha->Localidades->nome ;
												}else{
													echo '<span class="text-danger">Igreja não Localizada</span>';
												} ?>													
											</td>                                            
                                            <td class="text-left   px-3">
												<?php if(isset($planilha->Localidades)){
													echo $aevOptions['setores'][$planilha->Localidades->setor]; 
												}else{
													echo '<span class="text-danger">Setor não Localizado</span>';
												} ?>											
											</td>                                            
                                            <td class="text-center px-3"><?= $planilha->dt_vencimento->i18nFormat('dd/MM/yyyy'); ?></td>
                                            <td class="text-right  px-3"><?= h($planilha->nota_fiscal) ?></td>
                                            <td class="text-right  px-3"><?= "R$ " . number_format($planilha->valor, 2, ',', '.'); ?></td>
                                            <td class="text-left   px-3"><?= mb_strtoupper($planilha->fornecedor_nome); ?></td>
                                            <td class="text-center px-3">
                                                <div class="dropdown d-block">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm no-radius normal py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right no-radius border-1" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/Planilhas/view/<?= $planilha->id;?>">
                                                            <i class="fa fa-search text-primary"></i>
                                                            Visualizar
                                                        </a>
                                                        <a class="dropdown-item" href="/Planilhas/edit/<?= $planilha->id;?>"
                                                            data-confirm = "Tem certeza que deseja editar o lançamento?">
                                                            <i class="fa fa-pencil-alt text-success"></i>
                                                            Editar
                                                        </a>
                                                        <a class="dropdown-item" href="/Planilhas/delete/<?= $planilha->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o lançamento?">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                            Excluir
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                            
                    <!-- FOOTER -->
                    <div class="card-footer p-0 m-0"> 
                        <?php echo $this->element('pager'); ?>
                    </div>
                            
                </div>
            </div>
        </div>                
              
    </div>
</div>

<!-- Modal Filtro -->
<?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">


                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Filtro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row normal p-0 m-0">    
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">NC</label>
                                <?= $this->Form->text(
                                    'nc',
                                    array(
                                        'class'         => 'form-control no-radius',
                                        'id'            => 'nc',
                                        'placeholder'   => '0'
                                    )
                                ) ?>
                            </div>
                        </div>                        
                        
                        <div class="col-6">    
                            <div class="form-group">
                                <label for="id" class="normal strong">Lançamento</label>
                                <?=
                                 $this->Form->input('dt_lancamento',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'dt_lancamento',
                                             'type'               => 'text',
                                             'data-provide'       => 'datepicker-inline',
                                             //'data-date-format'   => 'dd/mm/yyyy',
                                             'data-date-language' => 'pt-BR',
                                             'placeholder'        => '00/00/0000',
                                             'div'                => false,
                                             'label'              => false,                                          
                                         )
                                 )
                                 ?>               
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Vencimento</label>
                                <?=
                                 $this->Form->input('dt_vencimento',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'dt_vencimento',
                                             'type'               => 'text',
                                             'data-provide'       => 'datepicker-inline',
                                             //'data-date-format'   => 'dd/mm/yyyy',
                                             'data-date-language' => 'pt-BR',
                                             'placeholder'        => '00/00/0000',
                                             'div'                => false,
                                             'label'              => false,                                             
                                         )
                                 )
                                 ?>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Pagamento</label>
                                <?=
                                 $this->Form->input('dt_pagamento',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'dt_pagamento',
                                             'type'               => 'text',
                                             'data-provide'       => 'datepicker-inline',
                                             //'data-date-format'   => 'dd/mm/yyyy',
                                             'data-date-language' => 'pt-BR',
                                             'placeholder'        => '00/00/0000',
                                             'div'                => false,
                                             'label'              => false,                                          
                                         )
                                 )
                                 ?>
                            </div>                            
                        </div>                    
                    
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Localidade</label>
                                <?=
                                $this->Form->input('igreja',
                                        array(
                                            'class'       => 'form-control no-radius',
                                            'id'          => 'igreja',
                                            'type'        => 'text',
                                            'placeholder' => 'Igreja',
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                )
                                ?>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Código</label>
                                <?=
                                $this->Form->input('codigo',
                                        array(
                                            'class'       => 'form-control no-radius',
                                            'id'          => 'codigo',
                                            'type'        => 'text',
                                            'placeholder' => '21-0000',
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                )
                                ?>
                            </div>
                        </div>      
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Nota Fiscal</label>
                                <?=
                                 $this->Form->input('nota_fiscal',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'nota_fiscal',
                                             'type'               => 'number',                                             
                                             'div'                => false,
                                             'label'              => false,                                          
                                         )
                                 )
                                 ?>
                            </div>                            
                        </div>  
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Valor</label>
                                <?=
                                 $this->Form->input('valor',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'nota_fiscal',
                                             'type'               => 'number',
                                             'step'               =>  0.01,
                                             'div'                => false,
                                             'label'              => false,                                          
                                         )
                                 )
                                 ?>
                            </div>                            
                        </div>                            
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="id" class="normal strong">Fornecedor</label>
                                <?=
                                 $this->Form->input('fornecedor_nome',
                                         array(
                                             'class'              => 'form-control text-center no-radius',
                                             'id'                 => 'fornecedor_nome',
                                             'type'               => 'text',
                                             'placeholder'        => '',
                                             'div'                => false,
                                             'label'              => false,                                          
                                         )
                                 )
                                 ?>
                            </div>                            
                        </div>                        
                        
                        <div class="col-6">                        
                            <div class="form-group">
                                <label for="id" class="normal strong">Setores</label>
                                <div class="col-12 m-0 p-0">
                                    <?=
                                    $this->Form->input('setor',
                                            array(
                                                'class'   => 'form-control no-radius w-100',
                                                'id'      => 'fornecedor_nome',
                                                'type'    => 'select',
                                                'options' => ['' => ''] + $aevOptions['setores'],
                                                'div'     => false,
                                                'label'   => false,
                                            )
                                    )
                                    ?>
                                </div>
                            </div>                            
                        
                        </div>
                        
                    </div>
                    
                </div>

                <div class="modal-footer bg-footer">
                    
                    <button type="submit" class="btn btn-success normal no-radius">
                        <i class="fa fa-check pr-1"></i>
                        Consultar
                    </button>
                    
                    <a class="btn btn-info no-radius normal" href="/Planilhas/index/clear">
                        <i class="fa fa-window-close fa-sm"></i>
                        <span class="">Limpar</span>
                    </a>                     
                    <button type="button" class="btn btn-link no-link text-primary normal" data-dismiss="modal">Cancelar</button>   
                    
                </div>



            </div>
        </div>
    </div>

<?= $this->Form->end() ?>

<script>

    $(document).ready(function() {
        
        $('#valor').mask('999999999999999,99');        

        // ToolTip
        $('[data-toggle="tooltip"]').tooltip();

        // Modal
        <?= $this->element('modal_confirm'); ?>

    });

</script>
