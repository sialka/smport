<?php
    $nav = [
        'Cultos' => ''
    ];
?>
<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">
    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->Flash->render() ?>
    </div>
</div>

<div class="container-row p-2">
    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-xs-12">

        <div class="col-12 p-0 mb-2">
            <a class="btn btn-success no-radius" href="/Cultos/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>

            <button class="btn btn-info no-radius ml-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa fa-filter fa-sm"></i>
                <span class="">Filtro</span>
            </button>
        </div>

        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-xs-12">

                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header no-radius" style="border-bottom: 0px">
                        <?= $this->element('search', [ 'search' => 'Por localidade' ]); ?>
                    </div>

                    <!-- BODY -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered p-0 m-0">
                            <thead>
                                <tr class="">
                                    <?= $this->element('th_sort', [ 'th' => ['20%', 'Cultos.Localidade.nome', __('Localidades') ] ]); ?>
                                    <?= $this->element('th_sort', [ 'th' => ['10%', 'Cultos.dia', __('Semana') ] ]); ?>
                                    <?= $this->element('th_sort', [ 'th' => ['5%', 'Cultos.hora', __('Hora') ] ]); ?>
                                    <?= $this->element('th_sort', [ 'th' => ['10%', 'Cultos.tipo', __('Tipo') ] ]); ?>
                                    <th class="text-right" width="35%"></th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                <?php foreach ($cultos as $culto): ?>
                                    <tr class="align-middle">
                                        <td class="text-left px-3"><?= $culto->Localidades->nome; ?></td>
                                        <td class="text-left px-3"><?= $utils['semana_id_longo'][$culto->dia]; ?></td>
                                        <td class="text-left px-3"><?= $aevOptions['hora'][$culto->hora]; ?></td>
                                        <td class="text-left px-3"><?= $aevOptions['tipo'][$culto->tipo]; ?></td>
                                        <td class="text-left px-3">
                                            <a class="btn btn-link"  href="/Cultos/view/<?= $culto->id;?>">
                                                <i class="fa fa-search text-primary"></i>
                                            </a>
                                            <a class="btn btn-link" href="/Cultos/edit/<?= $culto->id;?>"
                                                data-confirm = "Tem certeza que deseja editar o culto?">
                                                <i class="fa fa-pencil-alt text-success"></i>
                                            </a>
                                            <a class="btn btn-link" href="/Cultos/delete/<?= $culto->id;?>"
                                                data-confirm = "Tem certeza que deseja excluir o culto?">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                    <!-- FOOTER -->
                    <div class="card-footer no-radius p-0 m-0">
                        <?php echo $this->element('pager'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Filtro -->
<?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">


                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Filtro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row normal">
                        <label for="id" class="normal strong col-4">Localidade</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('localidade',
                                    array(
                                        'id'          => 'localidade',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'        => 'text',
                                        'placeholder' => '-',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>

                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Dia</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('dia',
                                    array(
                                        'id'          => 'nome',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'    => 'select',
                                        'options' => ['' => ''] + $utils['semana_id_longo'],
                                        'placeholder' => 'Nome',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>

                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Hora</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('hora',
                                    array(
                                        'class'   => 'form-control no-radius w-100',
                                        'id'      => 'setor',
                                        'type'    => 'select',
                                        'options' => ['' => ''] + $aevOptions['hora'],
                                        'div'     => false,
                                        'label'   => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>


                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Municipio</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('municipio',
                                    array(
                                        'class' => 'form-control no-radius w-100',
                                        'id'    => 'municipio_id',
                                        'type'  => 'select',
                                        'options' => ['' => ''] + $aevOptions['municipio'],
                                        'div'   => false,
                                        'label' => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-footer">

                    <button type="submit" class="btn btn-success normal no-radius">
                        <i class="fa fa-check pr-1"></i>
                        Consultar
                    </button>

                    <a class="btn btn-info no-radius normal" href="/Cultos/index/clear">
                        <i class="fa fa-window-close fa-sm"></i>
                        <span class="">Limpar</span>
                    </a>
                    <button type="button" class="btn btn-link no-link text-primary normal" data-dismiss="modal">Cancelar</button>

                </div>



            </div>
        </div>
    </div>

<?= $this->Form->end() ?>

<script>

    $(document).ready(function() {

        // ToolTip
        $('[data-toggle="tooltip"]').tooltip();

        // Modal
        <?= $this->element('modal_confirm'); ?>

    });

</script>
