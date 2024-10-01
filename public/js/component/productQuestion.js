//formulario para regsitrar duvidas
$("#question_product_form").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var retorno = productQuestion(product_id, formulario)
})


function productQuestion(product_id, dados) {

    $('input').each(function () {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // $(".span-loader").css("display", "block");
    if ($(".modal-dialog").length) {
        $(".modal-dialog").append("<div class='span-loader position-absolute'><div class='loader'></div></div>");
    }
    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=true&acao=productQuestion&product_id=" + product_id + "&" + dados.serialize(),
            url: "app/Http/Controllers/ProductsDetails.php",
            async: false
        }).then(sucesso).fail(falha).always(function () {
        });
        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: $data.message,
                    showConfirmButton: false,
                    timer: 3500
                })
                registrationQuestion(product_id) //recarregar o quadro de perguntas

                $(".btn-close").trigger('click'); //fechar o modal
            } else if ('authentication' in $data && $data.authentication == false) {
                modalLogin()
            } else if ('response' in $data) { //validação
                $.each($data.response, function (key, value) {
                    $("#" + key).addClass("is-invalid")
                    $(".feedback-" + key).addClass("invalid-feedback").html(value)
                });
            } else { //erro da aplicação
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: $data.message,
                    timer: 7500,

                })
            }
        }

        function falha() {
            console.log("Erro na solicitação AJAX");
        }
    }, 10);
    $(".span-loader").fadeOut(); // Esconde o loader


}