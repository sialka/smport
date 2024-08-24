<?php
    $nav = [
        'Setores' => ''
    ];
?>
<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">
    <div class="col-12">
        <?= $this->Flash->render() ?>
    </div>
</div>

<div class="container-row">
    <div class="col-12">

        <?= $this->element('mobile'); ?>    

        <div class="col-12 p-0 mb-2 mobile-hide">
            
            <a class="btn btn-success no-radius" href="/Setores/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>
            
            <button class="btn btn-info no-radius ml-1" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-filter fa-sm"></i>
                <span class="">Filtro</span>
            </button>          
            
        </div>            

        <div class="row mobile-hide">
            <div class="col-12 mt-2 mb-2">
                
                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">

                        <?= $this->element('search', [ 'search' => 'Por código ou localidade' ]); ?>

                    </div>                            

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">         

                        <div class="table-responsive table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table table-bordered p-0 m-0" style="border-bottom: 0px solid white">
                                <thead>
                                    <tr class="">
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Localidades.codigo', __('Setor') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['30%', 'Localidades.nome', __('Nome') ] ]); ?>                                        
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Localidades.status', __('Status') ] ]); ?>
                                        <th class="text-right" width="50%"></th>
                                        <th class="text-right" width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody class="tdMiddleAlign">
                                    <?php foreach ($setores as $setor): ?>
                                        <tr class="vAlignMiddle">
                                            <td class="text-left px-3"><?= h($setor->setor) ?></td>
                                            <td class="text-left px-3"><?= h($setor->nome) ?></td>                                            
                                            <td class="text-center">
                                                <?= $this->element('status', [ 'status' => $aevOptions['status'][$setor->status] ]); ?>
                                            </td>
                                            <td class="text-center px-3"></td>
                                            <td class="text-center px-3">                                                
                                                <div class="dropdown d-block">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm no-radius py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right -py-2 -m-0" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/Setores/view/<?= $setor->id;?>">
                                                            <i class="fa fa-search text-primary"></i>
                                                            Visualizar
                                                        </a>
                                                        <a class="dropdown-item" href="/Setores/edit/<?= $setor->id;?>"
                                                            data-confirm = "Tem certeza que deseja editar o Setor?">
                                                            <i class="fa fa-pencil-alt text-success"></i>
                                                            Editar
                                                        </a>
                                                        <a class="dropdown-item" href="/Setores/delete/<?= $setor->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o Setor?">
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
                        
                    <div class="form-row normal">
                        <label for="id" class="normal strong col-4">Setor</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('setor',
                                    array(
                                        'id'          => 'setor',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'        => 'number',
                                        'min' => 0,
                                        'max' => 10,
                                        'placeholder' => '0',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>
                        
                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Nome</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('nome',
                                    array(
                                        'id'          => 'nome',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'        => 'text',
                                        'placeholder' => 'Nome',
                                        'div'         => false,
                                        'label'       => false,
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
                                        'options' => ['' => '', '1' => 'Ativo', '0' => 'Inativo'],
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
                    
                    <a class="btn btn-info no-radius normal" href="/Setores/index/clear">
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
