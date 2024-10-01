

const forgot_password = new URLSearchParams(window.location.search).get('forgot-password');//pegar valor do parametro da url

/*abrir o modal para se registrar */
$("#register").click(function (e) {
    e.preventDefault()
    modalRegister()
})

$("#forgotPassowrd").click(function (e) {
    e.preventDefault()
    modalforgotPassword()
})


$('#login').submit(function (e) {
    e.preventDefault();
    var formulario = $(this);
    login(formulario);
});

function modalMsgConfimEmail(email) {
    $(".btn-close").trigger('click'); //fechar o modal
    $.ajax({
        type: 'GET',
        data: "page=auth&containers=confirmEmailPedding&email=" + email,
        url: "resources/views/containers/auth/confirmEmailMessagePending.php",
        success: function (result) {
            return $("main .modal-externo").html(result) + $("#modal_confirm_email_message_pending").modal('show');;
        },
    });
}


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
function modalforgotPassword() {
    $(".btn-close").trigger('click'); //fechar o modal
    $.ajax({
        type: 'GET',
        data: "page=auth&containers=forgotPassword",
        url: "resources/views/containers/auth/forgotPassword.php",
        success: function (result) {
            return $("main .modal-externo").html(result) + $("#modal_forgot_password").modal('show');;
        },
    });
}

function login(dados) {

    // Remover classes is-invalid de todos os elementos
    dados.find('.is-invalid').removeClass('is-invalid');
    dados.find('.invalid-feedback').text('');

    $(".span-loader").css("display", "block");
    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=auth&acao=login&" + dados.serialize(),
            url: "app/Http/Controllers/Auth/Login.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {
                $(".btn-close").trigger('click'); //fechar o modal
                if (forgot_password) {
                    window.location.href = "./"
                } else {
                    location.reload()
                }
            } else {
                // console.log($data.response)
                if ($data.response !== undefined) {  //erro de usuário
                    $.each($data.response, function (key, value) {

                        // Adicionar classe is-invalid e mensagem de erro aos elementos correspondentes
                        // dados.find('[name="' + key + '"]').addClass('is-invalid');
                        // dados.find('[name="' + key + '"]').siblings('.invalid-feedback').html(value);
                        $("#" + key).addClass("is-invalid")
                        $(".feedback-" + key).addClass("invalid-feedback").html(value)
                    });
                    $("#send_confirmation").click(function () {//reenviar email de confirmação de registro
                        sendConfirmationRegister($("#email").val())
                    })
                } else { //erro de aplicação
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: $data.message,
                        timer: 7500,

                    })
                }
            }
            $(".span-loader").css("display", "none");
        }

        function falha() {
            console.log("erro");
        }
    }, 10);
}


function sendConfirmationRegister(email) {
    $(".span-loader").css("display", "block");
    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=auth&acao=sendConfirmationRegister&email=" + email,
            url: "app/Http/Controllers/Auth/Register.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {
                modalMsgConfimEmail(email)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $data.message,
                    timer: 7500,
                })
            }
            $(".span-loader").css("display", "none");
        }
        function falha() {
            console.log("erro");
        }
    }, 10);
}
