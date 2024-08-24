<?php
    $paginator = $this->Paginator->setTemplates([
        'number'     =>  '<li class="page-item"><a href="{{url}}" class="page-link">{{text}}</a></li>',
        'current'    =>  '<li class="page-item active"><a href="{{url}}" class="page-link">{{text}}</a></li>',
        'first'      =>  '<li class="page-item"><a href="{{url}}" class="page-link">&laquo;</a></li>',
        'last'       =>  '<li class="page-item"><a href="{{url}}" class="page-link">&raquo;</a></li>',
        'prevActive' =>  '<li class="page-item"><a href="{{url}}" class="page-link">&lt</a></li>',
        'nextActive' =>  '<li class="page-item"><a href="{{url}}" class="page-link">&gt</a></li>',
    ]);
?>

<div class="p-2 d-flex justify-content-between" id="pager">

    <?php if (!empty($_conditions)) { ?>
        <!--div _class="col-12 p-1">
            < ?php //= '<b>'.__('Filtros Aplicados').':</b> ' . $_conditions ?> 
        </div-->
    <?php } ?>

    <div class="d-flex align-self-center strong">            
        <?php echo $paginator->counter(array('format' => '{{count}} '.__('registro(s) encontrado(s).'), true)); ?>            
    </div>

    <?php if($paginator->numbers()): ?>

    <div style="height: 30px">
        
        <nav arial-label="Page navigation">
            <ul class="pagination pagination-sm justify-self-end">
                <?php
                    echo $paginator->first();
                    if($paginator->hasPrev()){
                        echo $paginator->prev();
                    }

                    echo $paginator->numbers();

                    if($paginator->hasNext()){
                        echo $paginator->next();
                    }
                    echo $paginator->last();
                ?>
            </ul>
        </nav>


    </div>

    <?php endif; ?>

</div>

