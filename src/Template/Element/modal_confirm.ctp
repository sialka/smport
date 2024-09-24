
//Modal
$('a[data-confirm]').click(function(e){
    let href = $(this).attr('href');
    console.log(href)

    pergunta  = e.currentTarget.getAttribute('data-confirm');

    // Atribuindo modal ao dom
    if(!$('#confirm-delete').length){
        console.log('modal')

        let modal = '<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirm-deleteTitle" aria-hidden="true">';
        modal = modal + '<div class="modal-dialog modal-dialog-centered" role="document">';
        modal = modal + '<div class="modal-content no-radius"><div class="modal-header no-radius bg-secondary text-white p-2">';
        modal = modal + '<h5 class="modal-title" id="exampleModalLongTitle">AVISO</h5>';
        modal = modal + '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        modal = modal + '</div>';
        modal = modal + '<div class="modal-body">'+pergunta+'</div>';
        modal = modal + '<div class="modal-footer p-2">';
        modal = modal + '<a class="btn btn-success no-radius" id="dataConfirmOK">';
        modal = modal + '<i class="fa fa-check"></i> Confirmar</a>';
        modal = modal + '<button type="button" class="btn btn-light no-link" data-bs-dismiss="modal">';
        modal = modal + '<i class="fa fa-reply"></i> Cancelar</button>'
        modal = modal + '</div></div></div></div>';

        console.log(modal)
        $('body').append(modal);
    }

    // Atribuindo href
    $('#dataConfirmOK').attr('href', href);

    // Abrindo modal
    var myModal = new bootstrap.Modal(document.getElementById('confirm-delete'), {
        keyboard: false
    });

    myModal.show()

    return false;
});

