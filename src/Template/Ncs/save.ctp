<?php
    switch ($mode) {
        case 'add':
            $page = 'Novo';
            $title = 'Mês de Trabalho - Criando';
            break;
    }

        $nav = [
        'Mês de Trabalho' => '/Ncs/index',
        'Criando'         => '',
    ];
?>

<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">
    <div class="col-4 offset-4">
        
        <?= $this->Form->create($nc, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>

        <div class="card shadow border-1 no-radius mb-4">

            <div class="card-header">
                <h6 class="normal strong p-0 m-0 text-primary">
                    <i class="fa fa-calendar pr-2"></i>
                    <?= $title ?>
                </h6>
            </div>

            <div class="card-body normal">     

                <div class="row">
                 
                    <div class="form-group col-6">
                        <label for="mes" class="font-weight-bold">Mês</label>
                        <?=
                        $this->Form->input('mes',
                                array(
                                    'class'              => 'form-control text-center normal no-radius',
                                    'id'                 => 'mes',
                                    'type'               => 'select',
                                    'options'            => $aevOptions['meses'],
                                    'div'                => false,
                                    'label'              => false,
                                    'required'
                                )
                        )
                        ?>
                    </div>

                    <div class="form-group col-6">
                        <label for="ano" class="font-weight-bold">Ano</label>
                        <?=
                        $this->Form->input('ano',
                                array(
                                    'class'              => 'form-control text-center normal no-radius',
                                    'id'                 => 'ano',                                    
                                    'type'               => 'select',
                                    'options'            => $aevOptions['anos'],
                                    'div'                => false,
                                    'label'              => false,
                                    'required',
                                )
                        )
                        ?>
                    </div>

                </div>

            </div>
            <div class="card-footer bg-light">
                <div class="text-right">

                    <?php if($mode != "view") {?>

                            <button type="submit" class="btn btn-success no-radius normal ">
                                <i class="fa fa-check"></i>
                                Salvar
                            </button>

                    <?php } ?>

                    <a class="btn btn-link no-link normal" href="/Ncs/index">
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
    
    const data = new Date();
    const ano = data.getFullYear();
    
    $('#ano').val(ano);

});

</script>
