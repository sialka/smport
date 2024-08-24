<?php
    $nav = [
        'Mês de Trabalho' => ''
    ];
?>
<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row normal">
    <div class="col-12">
                
        <div class="col-12 p-0 mb-2 ">

            <a class="btn btn-success no-radius normal" href="/Ncs/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>
            
            <button class="btn btn-info no-radius normal ml-1" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-filter fa-sm"></i>
                <span class="">Filtro</span>
            </button>               
            
        </div>

        <div class="row">
            <div class="col-12 mt-2 mb-2">

                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">

                        <?= $this->element('search', [ 'search' => 'Por Mês ou Ano' ]); ?>

                    </div>                            

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">         

                        <div class="table-responsive table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table table-bordered p-0 m-0" style="border-bottom: 0px solid white">
                                <thead>
                                    <tr class="normal strong">                                   
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Ucs.ano', __('Ano') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%' ,'Ucs.mes', __('Mês') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Ucs.controle', __('Controle') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Ucs.status', __('Status') ] ]); ?>
                                        <th class="text-center"    width="05%"><?= __('Ativo')?></th>
                                        <th class="text-right"     width="55%"></th>
                                        <th class="text-right"     width="05%"></th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php foreach ($ncs as $nc): ?>
                                        <tr class="normal">
                                            <td class="text-left px-3"><?= h($nc->ano) ?></td>
                                            <td class="text-left px-3"><?= h($nc->mes) ?></td>
                                            <td class="text-left px-3"><?= h($nc->controle) ?></td>
                                            <td class="text-center px-3">
                                                <?= $this->element('status', [ 'status' => $aevOptions['status'][$nc->status] ]); ?>
                                            </td>
                                            <td class="text-center px-3">
                                                <?php if($nc->selecionado){ ?>
                                                    <i class="fa fa-check-circle text-primary"></i>
                                                <?php }  ?>                                              
                                            </td>
                                            <td class="text-left px-3"></td>
                                            <td class="text-center px-3">
                                                <div class="dropdown d-block">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm no-radius normal py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right no-radius" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/Ncs/status/<?= $nc->id;?>">                                                                    
                                                            <?php 

                                                                if($nc->status) {
                                                                    echo '<i class="fa fa-times text-danger pr-2"></i> ';
                                                                    echo 'Inativar';
                                                                }else{
                                                                    echo '<i class="fa fa-check text-success pr-2"></i> ';
                                                                    echo 'Ativar' ;
                                                                }

                                                            ?>
                                                        </a>
                                                        <?php if($nc->nc == 0) { ?>
                                                        <a class="dropdown-item" href="/Ncs/delete/<?= $nc->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o lançamento?">
                                                            <i class="fas fa-trash-alt text-danger pr-2"></i>
                                                            Excluir
                                                        </a>
                                                        <?php } ?>

                                                        <?php if($nc->selecionado == false && $nc->status == true) { ?>
                                                        <a class="dropdown-item" href="/Ncs/ativar/<?= $nc->id;?>"
                                                            data-confirm = "Tem certeza que deseja deixar esse mês como padrão de lançamentos?">
                                                            <i class="fas fa-check-circle text-primary pr-2"></i>
                                                            Selecionar
                                                        </a>
                                                        <?php } ?>

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
                        
                    <div class="form-row normal">
                        <label for="id" class="normal strong col-4">Ano</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('ano',
                                    array(
                                        'id'          => 'ano',
                                        'class'       => 'form-control no-radius w-100 text-center',
                                        'type'        => 'select',
                                        'options'     => ['' => ''] + $aevOptions['anos'],
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>                            
                    </div>                                                
                        
                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Mês</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('mes',
                                    array(
                                        'id'          => 'mes',
                                        'class'       => 'form-control no-radius w-100 text-center',
                                        'type'        => 'select',
                                        'options'     => ['' => ''] + $aevOptions['meses'],
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>                        
                      
                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Controle</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('controle',
                                    array(
                                        'id'      => 'controle',
                                        'class'   => 'form-control no-radius w-100',
                                        'type'    => 'number',                                                
                                        'div'     => false,
                                        'label'   => false,
                                    )
                            )
                            ?>
                        </div>                        
                    </div>
                        
                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Status</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('status',
                                    array(
                                        'class' => 'form-control no-radius w-100',
                                        'id'    => 'status',
                                        'type'  => 'select',
                                        'options' => ['' => ''] + $aevOptions['status'],
                                        'div'   => false,
                                        'label' => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>
                        
                    <div class="form-row normal mt-2">                        
                        <label for="id" class="normal strong col-4">Selecionado</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('selecionado',
                                    array(
                                        'class' => 'form-control no-radius w-100',
                                        'id'    => 'selecionado',
                                        'type'  => 'select',
                                        'options' => ['' => '', '1' => 'Ativo'],
                                        'div'   => false,
                                        'label' => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>                        
                        
                </div>

                <div class="modal-footer bg-footer">
                    
                    <button type="submit" class="btn btn-success normal no-radius">
                        <i class="fa fa-check pr-1"></i>
                        Consultar
                    </button>
                    
                    <a class="btn btn-info no-radius normal" href="/Ncs/index/clear">
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

        // ToolTip
        $('[data-toggle="tooltip"]').tooltip();

        // Modal
        <?= $this->element('modal_confirm'); ?>

    });

</script>
