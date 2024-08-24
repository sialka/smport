<?php
    $nav = [
        'Atendimentos' => '/Services/index',
        'Senha'        => '',
    ];   
?>

<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">

    <div class="col-6 offset-3">
        
        <?= $this->Form->create($senha, array('class' => 'form-horizontal needs-validation', 'type' => 'post', 'novalidate')) ?>
        
        <div class="row">
            <div class="col-12">
            <?= $this->Flash->render() ?>
            </div>
        </div>

        <div class="card shadow border-1 no-radius mb-4">

            <div class="card-header bg-primary">
                <h6 class="p-0 m-0 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                        <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                        <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                        <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                    </svg>
                    <span class="text-normal strong ml-2">Identificação de Senha</span>
                </h6>
            </div>

            <div class="card-body text-normal">     

                <div class="row">
                    <div class="form-group col-6">
                        <label for="mes" class="font-weight-bold">Senha</label>
                        <?php                        
                        if ($mode != "add" && $senha['senha'] != 0) {
                            echo __("<label class='form-control disabled no-radius'>{$senha['senha']}</label>");
                        } else {
                            echo $this->Form->input('senha',
                                array(
                                    'class'              => 'form-control text-center no-radius',
                                    'id'                 => 'senha',
                                    'type'               => 'number',  
                                    'placeholder'        => 'Informe uma senha',
                                    'min'                => 0,
                                    'max'                => 30,                                    
                                    'div'                => false,
                                    'label'              => false,   
                                    'required'                                 
                                )
                            );
                        }
                        ?>
                    </div>
                </div>
                    
                <div class="row">
                    <div class="form-group col-12">
                        <label for="localidade" class="font-weight-bold">Localidade</label>
                        <div class="igrejas">
                        <?php
                        echo $this->Form->input('Localidades.nome',
                            array(
                                'class'              => 'form-control text-center text-normal typeahead no-radius',
                                'id'                 => 'localidade',
                                'type'               => 'text',    
                                'placeholder'        => 'Informe o nome da localidade',
                                'div'                => false,
                                'label'              => false,
                                'required'
                            )
                        );
                        echo $this->Form->input('localidade_id',
                            array(
                                'class'       => 'display-none',
                                'id'          => 'localidade_id',                                    
                                'type'        => 'text',
                                'label'       => false,
                                'required'
                            )
                        );
                        ?>                        
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <label for="mes" class="font-weight-bold">Status Ficha</label>
                        <?=
                        $this->Form->input('status_ficha',
                                array(
                                    'class'              => 'form-control text-left no-radius',
                                    'id'                 => 'status_ficha',
                                    'type'               => 'select',                                    
                                    'options'            => ['' => 'Informe o status'] + $aevOptions['status_fichas_save'],
                                    'div'                => false,
                                    'label'              => false,
                                    'required'
                                )
                        )
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <label for="ano" class="font-weight-bold">Status Envelope</label>
                        <?=
                        $this->Form->input('status_envelope',
                                array(
                                    'class'              => 'form-control text-left no-radius',
                                    'id'                 => 'status_envelope',                                    
                                    'type'               => 'select',
                                    'options'            => ['' => 'Informe o status'] + $aevOptions['status_envelopes_save'],
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

                            <button type="submit" class="btn btn-success no-radius">
                                <i class="fa fa-check"></i>
                                Salvar
                            </button>

                    <?php } ?>

                    <a class="btn btn-link no-link" href="/Services/index">
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
    
    <?php if (in_array($mode, ['edit','view'])) { ?>
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
            suggestion:     ['codigo','nome'],
            selector:       '.igrejas',
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

    recarregarTypeAheadIgrejas();


});
</script>
