const token = new URLSearchParams(window.location.search).get('code');

$('#resetPassword').submit(function (e) {
    e.preventDefault();
    var formulario = $(this);
    resetPassword(formulario);
});

function modalResetPasswordMessage() {
    $(".btn-close").trigger('click'); //fechar o modal
    $.ajax({
        type: 'GET',
        data: "page=auth&containers=login",
        url: "resources/views/containers/auth/resertPasswordMessage.php",
        success: function (result) {
            return $("main .modal-externo").html(result) + $("#modal_reset_password_message").modal('show');;
        },
    });
}

function resetPassword(dados) {
    // Remover classes is-invalid de todos os elementos

    dados.find('.is-invalid').removeClass('is-invalid');
    dados.find('.invalid-feedback').text('');
    $("#next").prop("disabled", true);
    $(".span-loader").css("display", "block");

    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=auth&acao=resetPassword&token=" + token + "&" + dados.serialize(),
            url: "app/Http/Controllers/Auth/ForgotPassword.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {
                modalResetPasswordMessage()
            } else {
                if ($data.response !== undefined) { //erro de usuário
                    $.each($data.response, function (key, value) {
                        $("#" + key).addClass("is-invalid")
                        $(".feedback-" + key).addClass("invalid-feedback").html(value)
                    });
                } else { //erro de aplicação
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: $data.message,
                        timer: 7500,

                    })

                }
            }
            $("#next").prop("disabled", false);
            $(".span-loader").css("display", "none");
        }
        function falha() {
            console.log("erro");
        }
    }, 10);
}