<?php
    $nav = [
        'Regional' => ''
    ];
?>
<?= $this->element('breadcrumb', [ 'nav' => $nav ]); ?>

<div class="container-row">
    <div class="col-12">
        <?= $this->Flash->render() ?>
    </div>
</div>

<div class="container-row">
    <div class="col-12">

        <div class="col-12 p-0 mb-2 mobile-hide">

            <a class="btn btn-success no-radius" href="/Regional/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>

            <button class="btn btn-info no-radius ml-1" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-filter fa-sm"></i>
                <span class="">Filtro</span>
            </button>

        </div>

        <div class="row mobile-hide">
            <div class="col-12 mt-2 mb-2">

                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header p-2 m-0 d-flex justify-content-between">

                        <?= $this->element('search', [ 'search' => 'Por código ou localidade' ]); ?>

                    </div>

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">

                        <div class="table-responsive table-striped table-sm table-hover m-0" style="overflow-x: visible;">
                            <table id="tableResults" class="table table-bordered p-0 m-0" style="border-bottom: 0px solid white">
                                <thead>
                                    <tr class="">
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Regional.id', __('Código') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['20%', 'Regional.Localidades.nome', __('Localidades') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Regional.Localidades.Municipio.id', __('Municipio') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Regional.data', __('Data') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['05%', 'Regional.hora', __('Hora') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Regional.palavra', __('Palavra') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['20%', 'Regional.regionais', __('Atendimento') ] ]); ?>
                                        <th class="text-center" width="10%">Avaliação</th>
                                        <th class="text-right" width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody class="tdMiddleAlign">
                                    <?php foreach ($regional as $local): ?>
                                        <tr class="vAlignMiddle">
                                            <td class="text-center px-3"><?= h($local->id) ?></td>
                                            <td class="text-left px-3"><?= h($local->Localidades->nome) ?></td>
                                            <td class="text-left px-3"><?= h($local->Localidades->Municipios->nome); ?></td>
                                            <td class="text-left px-3"><?= h($local->data->format('d/m/Y')); ?></td>
                                            <td class="text-left px-3"><?= h($aevOptions['hora'][$local->horario_id]); ?></td>
                                            <td class="text-left px-3"><?= h($local->palavra) ?></td>
                                            <td class="text-left px-3"><?= h($local->regionais) ?></td>
                                            <td class="text-center px-3 text-warning">
                                                <?php
                                                    for ($i = 1; $i <= $local->avaliacao; $i++) {
                                                        echo '<i class="fa fa-star fa-sm"></i>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-center px-3">
                                                <div class="dropdown d-block">
                                                    <button class="btn btn-primary dropdown-toggle no-radius btn-sm py-0" type="button" id="acoesListar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opções
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right -py-2 -m-0" aria-labelledby="acoesListar">
                                                        <a class="dropdown-item"  href="/Regional/view/<?= $local->id;?>">
                                                            <i class="fa fa-search text-primary"></i>
                                                            Visualizar
                                                        </a>
                                                        <a class="dropdown-item" href="/Regional/edit/<?= $local->id;?>"
                                                            data-confirm = "Tem certeza que deseja editar o ensaio Regional?">
                                                            <i class="fa fa-pencil-alt text-success"></i>
                                                            Editar
                                                        </a>
                                                        <a class="dropdown-item" href="/Regional/delete/<?= $local->id;?>"
                                                            data-confirm = "Tem certeza que deseja excluir o ensaio Regional?">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                            Excluir
                                                        </a>
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
                        <label for="id" class="normal strong col-4">Id</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('id',
                                    array(
                                        'id'          => 'id',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'        => 'number',
                                        'placeholder' => '0',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                        </div>
                    </div>

                    <div class="form-row normal mt-2">
                        <label for="id" class="normal strong col-4">Localidade</label>
                        <div class="col-8">
                            <?=
                            $this->Form->input('nome',
                                    array(
                                        'id'          => 'nome',
                                        'class'       => 'form-control no-radius w-100',
                                        'type'        => 'text',
                                        'placeholder' => 'Nome',
                                        'div'         => false,
                                        'label'       => false,
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
                                        'class'   => 'form-control no-radius w-100',
                                        'id'      => 'municipio',
                                        'type'    => 'select',
                                        'options' => ['' => ''] + $aevOptions,
                                        'div'     => false,
                                        'label'   => false,
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

                    <a class="btn btn-info no-radius normal" href="/Regional/index/clear">
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
