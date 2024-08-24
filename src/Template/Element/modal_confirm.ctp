   
//Modal
$('a[data-confirm]').click(function(e){
    let href = $(this).attr('href');

    pergunta  = e.currentTarget.getAttribute('data-confirm');

    // Atribuindo modal ao dom
    if(!$('#confirm-delete').length){


        let modal = '<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirm-deleteTitle" aria-hidden="true">';
        modal = modal + '<div class="modal-dialog modal-dialog-centered" role="document">';
        modal = modal + '<div class="modal-content"><div class="modal-header bg-primary text-white p-2">';
        modal = modal + '<h5 class="modal-title" id="exampleModalLongTitle">AVISO</h5>';
        modal = modal + '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        modal = modal + '<span aria-hidden="true">&times;</span></button></div>';
        modal = modal + '<div class="modal-body">'+pergunta+'</div>';
        modal = modal + '<div class="modal-footer p-2">';
        modal = modal + '<a class="btn btn-success no-radius" id="dataConfirmOK">';
        modal = modal + '<i class="fa fa-check"></i> Confirmar</a>';
        modal = modal + '<button type="button" class="btn btn-link no-link" data-dismiss="modal">';
        modal = modal + '<i class="fa fa-reply"></i> Cancelar</button>'
        modal = modal + '</div></div></div></div>';

        $('body').append(modal);
    }

    // Atribuindo href
    $('#dataConfirmOK').attr('href', href);
    // Abrindo modal
    $('#confirm-delete').modal({show:true});

    return false;
});
