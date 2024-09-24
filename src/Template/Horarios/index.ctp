<?php
    $nav = [
        'Horarios' => ''
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
            <a class="btn btn-success no-radius" href="/Horarios/add">
                <i class="fa fa-plus fa-sm"></i>
                <span class="">Novo</span>
            </a>
        </div>

        <div class="row">
            <div class="col-12">

                <!-- CARD -->
                <div class="card shadow no-radius border-1">

                    <!-- HEADER -->
                    <div class="card-header" style="border-bottom: 0px">
                        <?= $this->element('search', [ 'search' => 'Por Id ou Hora' ]); ?>
                    </div>

                    <!-- BODY -->
                    <div class="card-body no-border p-0 m-0">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered p-0 m-0">
                                <thead>
                                    <tr class="">
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Horarios.id', __('Id') ] ]); ?>
                                        <?= $this->element('th_sort', [ 'th' => ['10%', 'Horarios.hora', __('Horarios') ] ]); ?>
                                        <th class="text-left" width="80%"></th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle">
                                    <?php foreach ($horarios as $horario):  ?>
                                        <tr class="align-middle">
                                            <td class="text-center"><?= h($horario->id) ?></td>
                                            <td class="text-center"><?= h($horario->hora) ?></td>
                                            <td class="text-left">
                                                <a class="btn btn-link"  href="/Horarios/view/<?= $horario->id;?>">
                                                    <i class="fa fa-search text-primary"></i>
                                                </a>
                                                <a class="btn btn-link" href="/Horarios/edit/<?= $horario->id;?>"
                                                    data-confirm = "Tem certeza que deseja editar a localidade?">
                                                    <i class="fa fa-pencil-alt text-success"></i>
                                                </a>
                                                <a class="btn btn-link" href="/Horarios/delete/<?= $horario->id;?>"
                                                    data-confirm = "Tem certeza que deseja excluir a localidade?">
                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- FOOTER -->
                    <div class="card-footer p-0 m-0" style="border-top: 0px">
                        <?php echo $this->element('pager'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>

    $(document).ready(function() {

        // ToolTip
        $('[data-toggle="tooltip"]').tooltip();

    });

</script>
