<?php
    $nav = [
        'Municipio' => ''
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

        <div class="col-12 p-0 mb-2 mobile-hide">
            
            <a class="btn btn-success no-radius" href="/municipios/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>
            
        </div>            

        <div class="row mobile-hide">
            <div class="col-12 mt-2 mb-2">
                
                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">

                        <?= $this->element('search', [ 'search' => 'Por Id ou Hora' ]); ?>

                    </div>                            

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">         

                        <div class="table-responsive table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table table-bordered p-0 m-0" style="border-bottom: 0px solid white">
                                <thead>
                                    <tr class="">
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'municipio.id', __('Id') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['20%', 'municipio.nome', __('Municipio') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['20%', 'municipio.favorito', __('Favorito') ] ]); ?>
                                        <th class="text-right" width="30%"></th>
                                        <th class="text-right" width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody class="tdMiddleAlign">
                                    <?php foreach ($municipios as $municipio):  ?>
                                        <tr class="vAlignMiddle">
                                            <td class="text-left px-3"><?= h($municipio->id) ?></td>
                                            <td class="text-left px-3"><?= h($municipio->nome) ?></td>
                                            <td class="text-left px-3">
                                                <?php 
                                                if($municipio->favorito == '0'){
                                                    echo '';
                                                }else{
                                                    echo "<span class='badge badge-success'><i class='fa fa-check'></i></span>";
                                                }                                                 
                                                ?>
                                            </td>
                                            <td class="text-center px-3"></td>
                                            <td class="text-center px-3">
                                                <div class="dropdown d-block">
                                                    <button class="btn btn-primary dropdown-toggle no-radius btn-sm py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right -py-2 -m-0" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/municipios/view/<?= $municipio->id;?>">
                                                            <i class="fa fa-search text-primary"></i>
                                                            Visualizar
                                                        </a>
                                                        <a class="dropdown-item" href="/municipios/edit/<?= $municipio->id;?>"
                                                            data-confirm = "Tem certeza que deseja editar o municipio?">
                                                            <i class="fa fa-pencil-alt text-info"></i>
                                                            Editar
                                                        </a>
                                                        <a class="dropdown-item" href="/municipios/favorito/<?= $municipio->id;?>"
                                                            data-confirm = "Tem certeza que deseja favoritar esse municipio?">
                                                            <i class="fa fa-check text-success"></i>
                                                            Favorito
                                                        </a>                                                        
                                                        <a class="dropdown-item" href="/municipios/delete/<?= $municipio->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o municipio?">
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


<script>

    $(document).ready(function() {

        // ToolTip
        $('[data-toggle="tooltip"]').tooltip();

    });

</script>
