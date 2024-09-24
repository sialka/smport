<?php
switch ($mode) {
    case 'add':
        $page = 'Novo';
        $title = 'Nova Localidade';
        break;
    case 'edit':
        $page = 'Editar';
        $title = 'Alterar Localidade';
        break;
    case 'view':
        $page = 'Dados';
        $title = 'Visualizar';
        break;
}

$nav = [
    'Localidades' => '/Places/index',
    'Adicionar' => '',
];

$session = $this->request->session()->read('localidade');

if($session != null){
  $localidade = $session;
}

?>

<?= $this->element('breadcrumb', ['nav' => $nav]); ?>

<div class="container-row mt-2">
    <div class="col-xxl-4 offset-xxl-4 col-xl-6 offset-xl-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">

        <?= $this->Form->create($culto, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>

        <div class="card shadow border-1 no-radius mb-4">

            <div class="card-header py-3">
                <h6 class="strong p-0 m-0 text-secondary">
                    <i class="fas fa-map-marker-alt"></i>
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
                            echo __("<label class='form-control disabled no-radius text-center'><strong>{$culto->id}</strong></label>");
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 igrejas">
                        <label for="nome" class="strong">Localidade:</label>
                        <?php
                        echo $this->Form->input('Localidades.nome',
                                array(
                                    'class'       => 'form-control no-radius typeahead',
                                    'id'          => 'localidade',
                                    'placeholder' => 'Nome completo da Localidade',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                                );
                        echo $this->Form->input('localidade_id',
                                array(
                                    'class'       => 'form-control hide',
                                    'id'          => 'localidade_id',
                                    'placeholder' => '',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                                );

                        ?>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="nome" class="strong">Dia:</label>
                        <?=
                        $this->Form->input('dia',
                                array(
                                    'class'       => 'form-control no-radius',
                                    'id'          => 'dia',
                                    'placeholder' => '',
                                    'type'        => 'select',
                                    'options'     => ['' => ''] + $utils['semana_id_longo'],
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                        )
                        ?>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="nome" class="strong">Hora:</label>
                        <?=
                        $this->Form->input('hora',
                                array(
                                    'class'       => 'form-control no-radius',
                                    'id'          => 'hora',
                                    'placeholder' => '',
                                    'type'        => 'select',
                                    'options'     => ['' => ''] + $aevOptions['hora'],
                                    'div'         => false,
                                    'label'       => false,
                                    'required'
                                )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="nome" class="strong">Tipo de Culto:</label>
                        <?=
                        $this->Form->input('tipo',
                                array(
                                    'class'       => 'form-control no-radius',
                                    'id'          => 'tipo',
                                    'placeholder' => '',
                                    'type'        => 'select',
                                    'options'     => ['' => ''] + $aevOptions['tipo'],
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
                                        'value' => strlen($culto->created) > 0 ? $culto->created->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
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
                                        'value' => strlen($culto->modified) > 0 ? $culto->modified->format('d/m/Y H:i:s') : "00/00/0000 00:00:00",
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

                    <a class="btn no-link btn-light no-radius" href="/Cultos/index">
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

    <?= $this->element('typeahead'); ?>

    function recarregarTypeAheadIgrejas() {
        $('.igreja .typeahead').typeahead('destroy');

        options = {
            displayKey:     'nome',
            url:            '/Localidades/index',
            model:          'localidades',
            suggestion:     ['nome','Municipios.nome'],
            selector:       '.igrejas',
            modelAlias:     'localidades',
            suggestionStyle: 'font-size: 100%;',
            fillFields: [
                { selector: '#localidade_id', field: 'id' },
            ],
            delay : 500
        };
        LoadSearchTypeAhead(options);
    }

    recarregarTypeAheadIgrejas();

    });
</script>
