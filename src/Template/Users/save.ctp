<?php
    
    switch ($mode) {
        case 'add':
            $page = 'Novo';
            $title = 'Usuário (Adicionar)';
            break;
        case 'edit':
            $page = 'Edição';
            $title = 'Usuário (Editar)';
            break;
        case 'view':
            $page = 'Visualização';
            $title = 'Usuário (Consultar)';
            break;
    }

    $nav = [
        'Usuários' => '/Users/index',
        $page   => '',
    ];
?>

<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">
    <div class="col-6 offset-3">

        <?= $this->Form->create($user, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>

        <div class="card shadow mb-4">

            <div class="card-header">
                <h6 class="normal strong p-0 m-0 text-primary">
                    <i class="fas fa-id-badge pr-2"></i>
                    <?= $title ?>
                </h6>
            </div>

            <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Usuário</a>
                    </li>
                    
                    <?php if($mode == 'edit') { ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Senha</a>
                    </li>
                    <?php } ?>
                    
                    <?php if($perfil['admin']) { ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link -disabled" id="messages-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Perfil</a>
                    </li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    
                    <!-- Dados -->
                    <div class="tab-pane border-tab p-4 active" id="profile" role="tabpanel" aria-labelledby="profile-tab">                        
                        
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 has-validation">
                                <label for="username" class="strong">Usuário</label>
                                <?php if($mode == "add") { 
                                
                                    echo $this->Form->input('username',
                                        array(
                                            'class'       => 'form-control no-radius',
                                            'id'          => 'username',
                                            'type'        => 'text',
                                            'div'         => false,
                                            'label'       => false,
                                            'placeholder' => 'Informe o Login de acesso',
                                            'required'                                            
                                        )
                                    );                                    
                                
                                }else{
                        
                                    echo __("<label for='' class='form-control no-radius disabled'>{$user->username}</label>");
                        
                                } ?>                       

                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="form-group col-12">
                                <label for="nome" class="strong">Nome Completo</label>
                                <?=
                                $this->Form->input('nome',
                                    array(
                                        'class'       => 'form-control no-radius',
                                        'id'          => 'nome',
                                        'type'        => 'text',
                                        'div'         => false,
                                        'label'       => false,
                                        'placeholder' => 'Infrome o Nome completo',
                                        'required'
                                    )
                                );
                                ?>                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                <label for="email" class="strong">E-Mail</label>
                                <?=
                                $this->Form->input('email',
                                    array(
                                        'class'       => 'form-control no-radius',
                                        'id'          => 'email',
                                        'type'        => 'email',
                                        'div'         => false,                                        
                                        'label'       => false,
                                        'placeholder' => 'Informe o e-mail',
                                        'required'
                                    )
                                )
                                ?>
                            </div>
                        </div>

                        <?php                         
                        if($perfil['admin']) { ?>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="status" class="strong">Status</label>
                                <?=
                                $this->Form->input('status',
                                    array(
                                        'class'       => 'form-control no-radius',
                                        'id'          => 'status',
                                        'type'        => 'select',
                                        'options'     => $aevOptions['status'],
                                        'div'         => false,
                                        'label'       => false,
                                        'required'
                                    )
                                )
                                ?>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    
                    <?php if($mode == 'edit') { ?>
                    <!-- Senha -->
                    <div class="tab-pane border-tab p-4" id="password" role="tabpanel" aria-labelledby="password-tab"> 
                        
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="password" class="strong">Nova Senha</label>
                                <?php
                                if ($user_auth['id'] == $user->id || $perfil['admin']) { 
                                    echo $this->Form->input('password',
                                        array(
                                            'class'       => 'form-control no-radius',
                                            'id'          => 'password',
                                            'type'        => 'password',
                                            'div'         => false,
                                            'label'       => false,
                                            'value'       => '',
                                            'placeholder' => 'Informe uma nova senha',
                                        )
                                    );
                                }else{
                                    echo '<label class="form-control no-radius label-disabled">***</label>';
                                }                                    
                                ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="confirma" class="strong">Confirme a Senha</label>
                                <?php
                                if ($user_auth['id'] == $user->id || $perfil['admin']) { 
                                    echo $this->Form->input('confirma',
                                        array(
                                            'class'       => 'form-control no-radius',
                                            'id'          => 'confirma',
                                            'type'        => 'password',
                                            'div'         => false,
                                            'label'       => false,
                                            'value'       => '',
                                            'placeholder' => 'Repita a nova senha',
                                        )
                                    );
                                }else{
                                    echo '<label class="form-control no-radius label-disabled">***</label>';
                                }
                                        
                                ?>
                            </div>
                        </div>

                    </div>
                    <?php } ?>

                    <!-- Settings -->
                    <?php if($perfil['admin']) { ?>
                    <div class="tab-pane border-tab p-4" id="settings" role="tabpanel" aria-labelledby="settings-tab">       
                        
                        <h4 class="strong m-0 mb-2 text-normal text-primary">Módulos Permitidos</h4>                 
                        
                        <div class="row col-12 mb-1">                            
                            <div class="form-check">                                                                
                                <?=                                
                                $this->Form->checkbox('mod_admin', 
                                        array(                                            
                                            'class'       => 'form-check-input no-radius',
                                            'id'          => 'mod_admin',                                            
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                    );
                                ?>                      
                                <label for="mod_admin" class="form-check-label strong">Administrador</label>
                            </div>                                                
                        </div>                         

                        <div class="row col-12 mb-1">                            
                            <div class="form-check">                                                                
                                <?=                                
                                $this->Form->checkbox('mod_user', 
                                        array(                                            
                                            'class'       => 'form-check-input no-radius',
                                            'id'          => 'mod_user',                                            
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                    );
                                ?>                      
                                <label for="mod_user" class="form-check-label strong">Usuários</label>
                            </div>                                                
                        </div>                        

                        <div class="row col-12 mb-1">                            
                            <div class="form-check">                                
                                <?=                                
                                $this->Form->checkbox('mod_localidade', 
                                        array(                                            
                                            'class'       => 'form-check-input no-radius',
                                            'id'          => 'mod_localidade',
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                    );
                                ?>                      
                                <label for="mod_localidade" class="form-check-label strong">Localidades</label>
                            </div>                                                
                        </div>
                        
                        <div class="row col-12 mb-1">                            
                            <div class="form-check">                                
                                <?=                                
                                $this->Form->checkbox('mod_setores', 
                                        array(                                            
                                            'class'       => 'form-check-input no-radius',
                                            'id'          => 'mod_setores',
                                            'div'         => false,
                                            'label'       => false,
                                        )
                                    );
                                ?>                      
                                <label for="mod_setores" class="form-check-label strong">Setores</label>
                            </div>                                                
                        </div>
                        
                        <div class="row col-12">                            
                            <div class="form-check">                                
                                <?=
                                $this->Form->checkbox('mod_atendimento', 
                                        array(                                            
                                            'class'       => 'form-check-input no-radius',
                                            'id'          => 'mod_atendimento',
                                            'div'         => false,
                                            'label'       => false,                                            
                                        )
                                    );
                                ?>
                                <label for="mod_atendimento" class="form-check-label strong">Atendimento</label>                            
                            </div>                                                
                        </div>                        

                    </div>
                    <?php } ?>
                    
                </div>

            </div>





            <div class="card-footer bg-light">
                <div class="text-right">

                    <?php if($mode != "view") {?>

                            <button type="submit" class="btn btn-success no-radius">
                                <i class="fa fa-check"></i>
                                Salvar
                            </button>

                    <?php } ?>

                    <a class="btn btn-link no-link" href="/Users/index">
                        <i class="fa fa-reply"></i>
                        Voltar
                    </a>

                </div>
            </div>

        </div>
        <?= $this->Form->end() ?>

    </div>
</div>

<script>
$(document).ready(function(){
    
    let modoEdit = false;

    <?php if (in_array($mode, ['edit','view'])) { ?>
            $('#username').attr('readonly', 'readonly');
            $('#username').attr('disabled', 'disabled');
    <?php } ?>

    <?php if (in_array($mode, ['view'])) { ?>
        $('input, select, check, radio, textarea').attr('readonly', 'readonly');
        $('input, select, check, radio, textarea').attr('disabled', 'disabled');
    <?php } ?>

    $('#myTab a').on('click', function (event) {
        event.preventDefault()
        $(this).tab('show')
    }); 
    /*
    function isAdmin(){                
        
        if(modoEdit){
            $('#cad_igrejas').prop( "checked" , true)
            $('#cad_mestrabalho').prop( "checked" , true)
            $('#cad_planilhas').prop( "checked" , true)
            $('#cad_igrejas').prop( "disabled" , true)
            $('#cad_mestrabalho').prop( "disabled" , true)
            $('#cad_planilhas').prop( "disabled" , true)            
        }
        modoEdit = true;
    }
    
    function isCustom(){        
        
        if(modoEdit){
            $('#cad_igrejas').prop( "checked" , false)
            $('#cad_mestrabalho').prop( "checked" , false)
            $('#cad_planilhas').prop( "checked" , false)
            $('#cad_igrejas').prop( "disabled" , false)
            $('#cad_mestrabalho').prop( "disabled" , false)
            $('#cad_planilhas').prop( "disabled" , false)            
        }
        modoEdit = true;        
    }

    
    $("input[id=perfil-admin-0]").on('change', function() {
        
        if ($(this).val() == true) {
            isAdmin();
        } 
       
        if ($(this).val() == false) {            
            isCustom();
        } 
       
    }).parent().find("input[id=perfil-admin-0]:checked").change();    

    $("input[id=perfil-admin-1]").on('change', function() {
        
        if ($(this).val() == true) {
            isAdmin();
        } 
       
        if ($(this).val() == false) {
           isCustom();
        } 
       
    }).parent().find("input[id=perfil-admin-1]:checked").change();    
    */
    <?php  /*  
    if($mode == "edit" && $user->Perfil->admin == 1) { 
        echo 'isAdmin()';
    }  */
    ?>
});
</script>
