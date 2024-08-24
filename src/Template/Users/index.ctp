<?php
    $nav = [
        'Usuarios' => ''
    ];       
    
    echo $this->element('breadcrumb', [ 'nav' => $nav ]); 
?>

<div class="container-row">
    <div class="col-12">
        <?= $this->Flash->render() ?>
    </div>
</div>

<div class="container-row">
    <div class="col-12">

        <?= $this->element('mobile'); ?>    
        
        <div class="row mobile-hide">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2 mb-2">
                
                <?php if($perfil['admin']): ?>
                <div class="col-12 p-0 mb-2">
                    <a class="btn btn-success no-radius" href="/Users/add">
                        <i class="fa fa-plus fa-sm"></i>
                        <span class="">Novo</span>
                    </a>
                </div>  
                <?php endif; ?>
                
                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">

                        <?= $this->element('search', [ 'search' => 'Por Nome ou usuário' ]); ?>

                    </div>                                                    

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">                                         

                        <div class="table-responsive-sm table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table table-bordered p-0 m-0" style="border-bottom: 0px solid white">
                                <thead>
                                    <tr>
                                        <?= $this->element('th_sort', [ 'th' => ['30%', 'Users.name', __('Nome') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Users.username', __('Usuário') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Users.email', __('E-mail') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Users.status', __('Status') ] ]); ?>
                                        <th class="text-center" width="40%"></th>
                                        <th class="text-center" width="05%"></th>
                                    </tr>
                                </thead>
                                <tbody class="tdMiddleAlign">
                                    <?php foreach ( (object) $users as $user): ?>
                                        <tr class="vAlignMiddle">
                                            <td class="text-left px-3"><?= h($user->nome) ?></td>
                                            <td class="text-left px-3"><?= h($user->username) ?></td>
                                            <td class="text-left px-3"><?= h($user->email) ?></td>
                                            <td class="text-center px-3">
                                                <?= $this->element('status', [ 'status' => $aevOptions['status'][$user->status] ]); ?>
                                            </td>
                                            <td class="text-center px-3"></td>
                                            <td class="text-center px-3">
                                                <div class="dropdown d-block">
                                                    <button class="dropdown-toggle btn btn-primary btn-sm no-radius py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right -py-2 -m-0" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/Users/view/<?= $user->id;?>">
                                                            <i class="fa fa-search text-primary"></i>
                                                            Visualizar
                                                        </a>
                                                        
                                                        <?php if($perfil['id'] == $user->id || $perfil['admin']): ?>
                                                        <a class="dropdown-item" href="/Users/edit/<?= $user->id;?>"
                                                            data-confirm = "Tem certeza que deseja editar o usuário?">
                                                            <i class="fa fa-pencil-alt text-success"></i>
                                                            Editar
                                                        </a>
                                                        <?php endif; ?>

                                                        <?php if($perfil['admin']): ?>
                                                        <a class="dropdown-item" href="/Users/delete/<?= $user->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o usuário?">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                            Excluir
                                                        </a>
                                                        <?php endif; ?>
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

        // Modal
        <?= $this->element('modal_confirm'); ?>

    });

</script>
