$('.btn-register-member').click(function () {
    modalRegister();
});


function modalRegister() {
    $(".btn-close").trigger('click'); //fechar o modal
    $.ajax({
        type: 'GET',
        data: "page=auth&containers=register",
        url: "resources/views/containers/auth/register.php",
        success: function (result) {
            return $("main .modal-externo").html(result) + $("#modal_register").modal('show');;
        },
    });
}

// $(document).ready(function () {
//     ScrollReveal().reveal('.container-group-member', {
//         delay: 300,
//         distance: '50px',
//         origin: 'bottom',
//         opacity: 0,
//         duration: 500,
//         easing: 'ease-in-out'
//     });
// })