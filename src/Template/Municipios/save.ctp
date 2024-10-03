<?php

use App\Model\Entity\Municipio;

switch ($mode) {
    case 'add':
        $page = 'Novo';
        $title = 'Municipio';
        break;
    case 'edit':
        $page = 'Editar';
        $title = 'Alterar Municipio';
        break;
    case 'view':
        $page = 'Dados';
        $title = 'Visualizar';
        break;
}

$nav = [
    'Municipios' => '/Municipios/index',
    "{$page}" => '',
];

?>

<?= $this->element('breadcrumb', ['nav' => $nav]); ?>

<div class="container-row mt-2">
    <div class="col-xxl-4 offset-xxl-4 col-xl-6 offset-xl-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">

        <?= $this->Form->create($municipio, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>

        <div class="card shadow border-1 no-radius mb-4 m-1">

            <div class="card-header py-3">
                <h6 class="strong p-0 m-0 text-dark">
                    <i class="fa-solid fa-city"></i>
                    <?= $title ?>
                </h6>
            </div>

            <div class="card-body normal">

                <div class="row">
                    <div class="form-group col-lg-3 col-md-6 col-sm-6 col-sx-6">
                        <label for="codigo" class="strong">Id:</label>
                        <?php
                        if ($mode == "add") {
                            echo __("<label class='form-control disabled no-radius text-center'><strong>0</strong></label>");
                        }else{
                            echo __("<label class='form-control disabled no-radius text-center'><strong>{$municipio->id}</strong></label>");
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <label for="nome" class="strong">Nome:</label>
                        <?php
                        echo $this->Form->input('nome',
                                array(
                                    'class'       => 'form-control no-radius',
                                    'id'          => 'nome',
                                    'placeholder' => 'Informe o nome do Municipio',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                            );
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
                                        'value' => strlen($municipio->created) > 0 ? $municipio->created->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
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
                                        'value' => strlen($municipio->modified) > 0 ? $municipio->modified->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
                                    )
                            )
                            ?>
                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="card-footer bg-light">
                <div class="text-end">

                    <?php if ($mode != "view") { ?>

                        <button type="submit" class="btn btn-success no-radius">
                            <i class="fa fa-check"></i>
                            Salvar
                        </button>

                    <?php } ?>


                    <a class="btn btn-light no-radius no-link" href="/Municipios/index">
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

        <?php
        if (in_array($mode, ['edit', 'view'])): ?>
                    $('#codigo').attr('readonly', 'readonly');
                    $('#codigo').attr('disabled', 'disabled');
        <?php
        endif; ?>

        <?php
        if (in_array($mode, ['view'])): ?>
                $('input, select, check, radio, textarea').attr('readonly', 'readonly');
                $('input, select, check, radio, textarea').attr('disabled', 'disabled');
        <?php
        endif; ?>


    });
</script>
