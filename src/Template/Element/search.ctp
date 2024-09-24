<div class="row">
    <div class="col-xxl-8 col-xl-8 col-md-6 col-sm-6 col-xs-12 small p-2">
        <?php if (!empty($_conditions)) { ?>
            <?= "<span class='strong'>" .'Filtros Aplicados: </span><span>' . $_conditions .'</span>'?>
        <?php }else{ ?>
            <?= "<span class='strong'>" .'Filtros Aplicados: '.'</span>'?>
        <?php } ?>
    </div>
    <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-6 col-xs-12 text-left">
        <?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

            <div class="input-group small -row m-0 p-0">
                <label for="theme" class="col-form-label strong px-2">Buscar: </label>
                <?= $this->Form->text(
                    '_all',
                    array(
                        'class'         => 'form-control border-1 small -col-10 border-r-0',
                        'id'            => '_all',
                        'placeholder'   => $search
                    )
                ) ?>
                <span class="input-group-text bg-white no-radius border-l-0" >
                    <i class="fas fa-search fa-sm"></i>
                </span>

            </div>
        <?= $this->Form->end() ?>
    </div>
</div>
