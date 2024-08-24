
<div class="d-flex align-self-center strong">
    <?php if (!empty($_conditions)) { ?>                                        
        <?= __('Filtros Aplicados').': ' . $_conditions ?>                                         
    <?php }else{ ?>
        <?= __('Filtros Aplicados').':  ' ?>                                         
    <?php } ?>
</div>

<div class="p-0 m-0">
    <?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

        <div class="input-group normal row m-0 p-0">
            <label for="theme" class="col-form-label strong px-2">Buscar: </label>
            <?= $this->Form->text(
                '_all',
                array(
                    'class'         => 'form-control border-1 small col-10 border-r-0',                    
                    'id'            => '_all',
                    'placeholder'   => $search
                )
            ) ?>

            <div class="input-group-append">
                <div class="input-group-text bg-white no-radius border-l-0" >
                    <i class="fas fa-search fa-sm"></i>
                </div>
            </div>
        </div>
    <?= $this->Form->end() ?>
</div>

