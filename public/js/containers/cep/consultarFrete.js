
const produtoID = new URLSearchParams(window.location.search).get('product-details'); // pesquisa pela descrição

$(document).ready(function () {
    var cep = $("#cep").val();
    if (cep == "") {
        loadCepFromLocalStorage()
    }
    $('#consultarFrete').submit(function (e) {
        e.preventDefault();
        var formulario = $(this);

        consultarFrete(formulario, produtoID);
    });

    $('#consultarFrete').submit();
})

function consultarFrete(dados, id) {
    // Remover classes is-invalid de todos os elementos
    dados.find('.is-invalid').removeClass('is-invalid');
    dados.find('.invalid-feedback').text('');
    $(".option-frete").html('')

    // $(".span-loader").css("display", "block");
    // $(".option-frete").html("<div class='span-loader position-absolute' ><div class='loader'></div></div>")

    if ($(".option-frete").length) {
        $(".option-frete").append("<div class='span-loader position-absolute'><div class='loader'></div></div>");
    }
    
    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=frete&acao=consultarFrete&tipo=consulta&produtoID=" + id + "&" + dados.serialize(),
            url: "app/Http/Controllers/Frete.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {
                $("#cep").val($data.cep)
                $(".option-frete").html($data.response);

                saveCepToLocalStorage()//salvar o cep no localstorage
                // console.log($data.response)
            } else {
                // console.log($data.response)
                if ($data.response !== undefined) { //erro de usuário
                    $.each($data.response, function (key, value) {

                        // Adicionar classe is-invalid e mensagem de erro aos elementos correspondentes
                        // dados.find('[name="' + key + '"]').addClass('is-invalid');
                        // dados.find('[name="' + key + '"]').siblings('.invalid-feedback').html(value);
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

        }

        function falha() {
            console.log("erro");
        }

    }, 100);

    $(".span-loader").fadeOut(); // Esconde o loader


}



function saveCepToLocalStorage() {
    var cep_localstorage = localStorage.getItem('cep');
    if (cep_localstorage !== null) {
        localStorage.removeItem('cep');
    }

    var cep = $("#cep").val();
    if (cep !== '') {
        localStorage.setItem('cep', cep);
    }

}

function loadCepFromLocalStorage() {
    var cep = localStorage.getItem('cep');
    if (cep !== null) {
        $("#cep").val(cep);

    }
}