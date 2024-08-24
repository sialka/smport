<?php
switch ($mode) {
    case 'add':
        $page = 'Novo';
        $title = 'Nova Chave';
        break;
    case 'edit':
        $page = 'Editar';
        $title = 'Alterar a Chave';
        break;
    case 'view':
        $page = 'Dados';
        $title = 'Visualizar';
        break;
}

$nav = [
    'Setup' => '/Setup/index',
    $page => '',
];

$session = $this->request->session()->read('setor');

if($session != null){
  $localidade = $session;
}

?>

<?= $this->element('breadcrumb', ['nav' => $nav]); ?>

<div class="container-row">
    <div class="col-4 offset-4">
        
        <?= $this->Form->create($setup, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>
        
        <div class="card shadow border-1 no-radius mb-4">

            <div class="card-header py-3">
                <h6 class="strong p-0 m-0 text-primary">                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 640 512">
                        <path d="M160 64c0-35.3 28.7-64 64-64L576 0c35.3 0 64 28.7 64 64l0 288c0 35.3-28.7 64-64 64l-239.2 0c-11.8-25.5-29.9-47.5-52.4-64l99.6 0 0-32c0-17.7 14.3-32 32-32l64 0c17.7 0 32 14.3 32 32l0 32 64 0 0-288L224 64l0 49.1C205.2 102.2 183.3 96 160 96l0-32zm0 64a96 96 0 1 1 0 192 96 96 0 1 1 0-192zM133.3 352l53.3 0C260.3 352 320 411.7 320 485.3c0 14.7-11.9 26.7-26.7 26.7L26.7 512C11.9 512 0 500.1 0 485.3C0 411.7 59.7 352 133.3 352z"/>
                    </svg>
                    <?= $title ?>
                </h6>
            </div>

            <div class="card-body normal">

                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-sx-12">
                        <label for="codigo" class="strong">Chave</label>
                        <?php            
                            echo
                            $this->Form->input('chave',
                                    array(
                                        'class'       => 'form-control no-radius',
                                        'id'          => 'chave',
                                        'placeholder' => 'informe a chave',
                                        'type'        => 'text',
                                        'div'         => false,
                                        'label'       => false,
                                        'required'
                                    )
                            );                        
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <label for="nome" class="strong">Valor</label>
                        <?=
                        $this->Form->input('valor',
                                array(
                                    'class'       => 'form-control no-radius',
                                    'id'          => 'valor',
                                    'placeholder' => 'Informe o valor',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                        )
                        ?>
                    </div>
                </div>

                <?php if (in_array($mode, ['view'])) { ?>

                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="created" class="font-weight-bold">Criando</label>
                            <?=
                            $this->Form->input('created',
                                    array(
                                        'class' => 'form-control',
                                        'id' => 'created',
                                        'type' => 'text',
                                        'div' => false,
                                        'label' => false,
                                        'value' => strlen($setup->created) > 0 ? $setup->created->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
                                    )
                            )
                            ?>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label for="modified" class="font-weight-bold">Modificado</label>
                            <?=
                            $this->Form->input('modified',
                                    array(
                                        'class' => 'form-control',
                                        'id' => 'modified',
                                        'type' => 'text',
                                        'div' => false,
                                        'label' => false,
                                        'value' => strlen($setup->modified) > 0 ? $setup->modified->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
                                    )
                            )
                            ?>
                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="card-footer bg-light">
                <div class="text-right">

                    <?php if ($mode != "view") { ?>

                        <button type="submit" class="btn btn-success no-radius">
                            <i class="fa fa-check"></i>
                            Salvar
                        </button>

                    <?php } ?>


                    <a class="btn btn-link no-link" href="/Setores/index">
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
    $(document).ready(function () {

    <?php if (in_array($mode, ['edit', 'view'])) { ?>
                $('#codigo').attr('readonly', 'readonly');
                $('#codigo').attr('disabled', 'disabled');
    <?php } ?>

    <?php if (in_array($mode, ['view'])) { ?>
                $('input, select, check, radio, textarea').attr('readonly', 'readonly');
                $('input, select, check, radio, textarea').attr('disabled', 'disabled');
    <?php } ?>

    });
</script>
