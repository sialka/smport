<div class="externa">
    <div class="card shadow no-radius">
        <div class="card-body">
            <?= $this->Form->create('', ['class' => 'form-horizontal']) ?>

                <div class="card-header bg-white no-border">       
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 m-0">PIA</h1>                        
                        <p class="small text-gray-900 m-0">Painel de Atendimento</p>
                    </div>
                </div>           

                <div class="card-body m-0 p-0 -bg-danger">

                    <?= $this->Flash->render() ?>

                    <div class="d-flex justify-content-around -bg-warning my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-right-0 no-radius bg-white">
                                <i class="fa fa-user"></i> 
                            </span>
                            <?=
                            $this->Form->input(
                                    'username',
                                    array(
                                        'class'       => 'form-control border-left-0 no-radius text-center',
                                        'id'          => 'username',
                                        'placeholder' => __('Informe o usuário'),
                                        'type'        => 'text',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            );
                            ?>
                        </div>
                    </div>                        

                    <div class="d-flex justify-content-around my-2">                            
                        <div class="input-group-prepend">
                            <span class="input-group-text border-right-0 no-radius bg-white">
                                <i class="fa fa-lock"></i> 
                            </span>                                                        
                            <?=
                            $this->Form->input(
                                    'password',
                                    array(
                                        'class'       => 'form-control border-left-0 no-radius text-center',
                                        'id'          => 'password',
                                        'placeholder' => __('Informe a senha'),
                                        'type'        => 'password',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            );
                            ?>
                        </div>                            
                    </div> 

                </div>

                <div class="card-footer bg-white no-border">
                    <div class="text-center">
                        <?= $this->Form->button(__('Acessar'), ['class' => 'btn btn-primary no-radius']) ?>
                        <a class="btn btn-success no-radius" href="/panels?page=1" target="_blank">
                            <!--i class="fa fa-panel fa-sm"></i-->
                            <span class="">Painel</span>
                        </a>  
                    </div>
                </div>

            <?= $this->Form->end() ?>
        </div>        
    </div>    
</div>


