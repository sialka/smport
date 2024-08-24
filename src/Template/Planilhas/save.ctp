<?php
    switch ($mode) {
        case 'add':
            $page = 'Novo';
            $title = 'Lançamento';
            break;
        case 'edit':
            $page = 'Editar';
            $title = 'Editar';
            break;
        case 'view':
            $page = 'Dados';
            $title = 'Visualizar';
            break;
    }

    $nav = [
        'Planilha' => '/Planilhas/index',
        'Lançamento'   => '',
    ];
    
    $lancamento = $planilha->dt_lancamento === null ? 00 : $planilha->dt_lancamento->i18nFormat('dd');
    $vencimento = $planilha->dt_vencimento === null ? 00 : $planilha->dt_vencimento->i18nFormat('dd');
    $pagamento  =  $planilha->dt_pagamento === null ? 00 : $planilha->dt_pagamento->i18nFormat('dd');
    $setor      = "";
    
    if (isset($planilha->Localidades)) {
        $setor = $aevOptions['setores'][$planilha->Localidades->setor];
    }else {
        $setor = '';
    }
    
?>

<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row normal">    
    <div class="col-12">
        
        <?= $this->Form->create($planilha, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>

        <div class="card no-radius shadow mb-4">

            <div class="card-header">
                <h6 class="normal strong p-0 m-0 text-primary">
                    <i class="fas fa-file-excel"></i>
                    <?= $title ?>
                </h6>
            </div>

            <div class="card-body normal">

                <?php if(in_array($mode,['edit', 'view'])) { ?>
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-sx-12">
                            <label for="id" class="strong">ID</label>
                            <?=
                            $this->Form->input('id',
                                array(
                                    'class'       => 'form-control no-radius normal',
                                    'id'          => 'id',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'readonly',
                                    'disabled',
                                )
                            )
                            ?>
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-sx-12">
                            <label for="id" class="strong">NC</label>
                            <?=
                            $this->Form->input('nc',
                                array(
                                    'class'       => 'form-control no-radius normal',
                                    'id'          => 'nc',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'readonly',
                                    'disabled',
                                )
                            )
                            ?>
                        </div>
                        <div class="form-group col-lg-2 col-md-3 col-sm-3 col-sx-12">
                            <label for="id" class="strong">Setor</label>
                            <?=
                            $this->Form->input('Localidades.setor',
                                array(
                                    'class'       => 'form-control no-radius normal',
                                    'id'          => 'nc',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'value'       => $setor,
                                    'readonly',
                                    'disabled',
                                )
                            )
                            ?>
                        </div>                        
                    </div>
                
                <?php } ?>

                <div class="row">

                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="nome" class="strong">Localidade</label>
                        <div class="teste">
                            <?php
                            echo $this->Form->input('Localidades.nome',
                                array(
                                    'class'       => 'form-control no-radius typeahead normal',
                                    'id'          => 'igreja',
                                    'placeholder' => '',
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required',
                                )
                            );
                            echo $this->Form->input('localidade_id',
                                array(
                                    'class'       => 'display-none',
                                    'id'          => 'localidade_id',                                    
                                    'type'        => 'text',
                                    'label'       => false,
                                )
                            );
                            ?>
                        </div>
                    </div>

                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="dt_lancamento" class="strong">Dia do Lançamento</label>
                        <?=
                        $this->Form->input('dt_lancamento',
                                array(
                                    'class'              => 'form-control text-center normal no-radius',
                                    'id'                 => 'dt_lancamento',
                                    'type'               => 'number',
                                    'div'                => false,
                                    'label'              => false,
                                    'value'              => $lancamento,
                                    'min'                => "1",
                                    'max'                => "31",
                                    'required'
                                )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="dt_vencimento" class="strong">Dia do Vencimento</label>
                        <?=
                        $this->Form->input('dt_vencimento',
                                array(
                                    'class'              => 'form-control text-center normal no-radius',
                                    'id'                 => 'dt_vencimento',
                                    'placeholder'        => __(''),
                                    'type'               => 'number',                                    
                                    'div'                => false,
                                    'label'              => false,
                                    'value'              => $vencimento,
                                    'min'                => "1",
                                    'max'                => "31",                                    
                                    'required',
                                )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="dt_pagamento" class="strong">Dia do Pagamento</label>
                        <?=
                        $this->Form->input('dt_pagamento',
                                array(
                                    'class'              => 'form-control text-center normal no-radius',
                                    'id'                 => 'dt_pagamento',
                                    'placeholder'        => __(''),
                                    'type'               => 'number',
                                    'div'                => false,
                                    'label'              => false,
                                    'value'              => $pagamento,
                                    'min'                => "1",
                                    'max'                => "31",                                    
                                    'required',
                                )
                        )
                        ?>
                    </div>                    

                    
                   <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label for="nota_fiscal" class="strong">Nota Fiscal</label>
                        <?=
                        $this->Form->input('nota_fiscal',
                                array(
                                    'class'       => 'form-control text-center normal no-radius',
                                    'id'          => 'nota_fiscal',
                                    'placeholder' => __('0'),
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required',
                                )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label for="valor" class="strong">Valor Pago</label>
                        <?=
                        $this->Form->input('valor',
                                array(
                                    'class'       => 'form-control text-center normal no-radius',
                                    'id'          => 'valor',
                                    'placeholder' => __('0'),
                                    'type'        => 'text',
                                    'div'         => false,
                                    'label'       => false,
                                    'required',
                                )
                        )
                        ?>
                    </div>
                    

                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="fornecedor_nome" class="strong">Fornecedor</label>
                        <?=
                        $this->Form->input('fornecedor_nome',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'fornecedor_nome',
                                'placeholder' => __(''),
                                'type'        => 'text',
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>                    
                    
                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="cotacoes" class="strong">3 Cotações ?</label>
                        <?=
                        $this->Form->input('cotacoes',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'cotacoes',
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['cotacoes'],
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>
                    

                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="pagto_atraso" class="strong">Pagamento em atraso ?</label>
                        <?=
                        $this->Form->input('pagto_atraso',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'pagto_atraso',
                                'placeholder' => __(''),
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['atraso'],
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>                    

                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="visto" class="strong">Visto de Lançamento no SIGA ?</label>
                        <?=
                        $this->Form->input('visto',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'visto',
                                'placeholder' => __(''),
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['visto'],
                                'div'         => false,
                                'label'       => false,
                                'required'
                            )
                        )
                        ?>
                    </div>


                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="media" class="strong">Valor fora da Média ?</label>
                        <?=
                        $this->Form->input('media',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'media',
                                'placeholder' => __(''),
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['media'],
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>
                    
                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="serv_tomados" class="strong">Serviços Tomados ?</label>
                        <?=
                        $this->Form->input('serv_tomados',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'serv_tomados',
                                'placeholder' => __(''),
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['servico'],
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>


                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <label for="falta_contrato" class="strong">Falta de Contrato</label>
                        <?=
                        $this->Form->input('falta_contrato',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'falta_contrato',
                                'placeholder' => __(''),
                                'type'        => 'select',
                                'options'     => ['' => ''] + $aevOptions['contrato'],
                                'div'         => false,
                                'label'       => false,
                                'required',
                            )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12 ">
                        <label for="ocorrencias" class="strong">Outras Ocorrências</label>
                        <?=
                        $this->Form->input('ocorrencias',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'ocorrencias',
                                'placeholder' => __(''),
                                'type'        => 'text',
                                'div'         => false,
                                'label'       => false,
                            )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12 ">
                        <label for="obs" class="strong">Observação</label>
                        <?=
                        $this->Form->input('obs',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'obs',
                                'placeholder' => __(''),
                                'type'        => 'text',
                                'div'         => false,
                                'label'       => false,
                            )
                        )
                        ?>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12 ">
                        <label for="retorno" class="strong">Retorno</label>
                        <?=
                        $this->Form->input('retorno',
                            array(
                                'class'       => 'form-control text-center normal no-radius',
                                'id'          => 'retorno',
                                'placeholder' => __(''),
                                'type'        => 'text',
                                'div'         => false,
                                'label'       => false,
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

                    <a class="btn btn-link no-link normal" href="/Planilhas/index">
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
    
    $('#nc').mask('0000000000');
    $('#valor').mask('999999999999999,99');
    
    $('#dt_lancamento').mask('99');
    $('#dt_vencimento').mask('99');
    $('#dt_pagamento').mask('99');

    <?php if (in_array($mode, ['edit','view'])) { ?>
            $('#codigo').attr('readonly', 'readonly');
            $('#codigo').attr('disabled', 'disabled');
    <?php } ?>

    <?php if (in_array($mode, ['view'])) { ?>
        $('input, select, check, radio, textarea').attr('readonly', 'readonly');
        $('input, select, check, radio, textarea').attr('disabled', 'disabled');
    <?php } ?>

    <?= $this->element('typeahead'); ?>
    
    function recarregarTypeAheadTeste() {
        $('.teste .typeahead').typeahead('destroy');

        options = {
            displayKey:     'nome',
            url:            '/Localidades/index',
            model:          'localidades',
            suggestion:     ['codigo','nome'],
            selector:       '.teste',
            modelAlias:     'localidades',
            //width:          '300px',
            suggestionStyle: 'font-size: 100%;',
            fillFields: [
                { selector: '#localidade_id', field: 'id' },
            ],
            delay : 500
        };
        LoadSearchTypeAhead(options);
    }

    recarregarTypeAheadTeste();

});

</script>
