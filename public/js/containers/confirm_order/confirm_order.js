const code = new URLSearchParams(window.location.search).get('code'); // pesquisa pela descrição
$("#confirmOrder").submit(function (e) {
    e.preventDefault();
    var formulario = $(this);
    createOrder(formulario);
})
function createOrder(dados) {
    $("#btn_confirm_order").prop('disabled', true);//desabilitar o botão
    $(".span-loader").css("display", "block");

    // Remover classes is-invalid de todos os elementos
    dados.find('.is-invalid').removeClass('is-invalid');
    dados.find('.invalid-feedback').text('');
    $(".span-loader").html('<div class="loader"></div>');
    $("#btn_confirm_order").html("Rederecionando para o pagamento");//desabilitar o botão

    setTimeout(function () {
        $.ajax({
            type: "POST",
            data: "form=true&acao=create&codigo_nf=" + code,
            url: "app/Http/Controllers/ConfirmOrder.php",
            async: false
        }).then(sucesso).fail(falha).always(function () {

        });
        function sucesso(data) {
            var $data = $.parseJSON(data)["data"];
            if ($data.status == true) {

                if ($data.dados_pixel.pixel_status === 'S') {
                    var produtos = ($data.dados_pixel.produtos); // Converte a string JSON em um objeto JavaScript
                    // Obtém os IDs dos produtos do array recebido do PHP
                    var contentIds = produtos.map(function (produto) {
                        return produto.id;
                    });

                    userData = $data.dados_pixel.user_data
                    // Monta os parâmetros para o evento InitiateCheckout
                    var dados_pixel = {
                        event: 'AddPaymentInfo',
                        content_type: 'product',
                        content_ids: contentIds, // IDs dos produtos no seu catálogo
                        contents: $data.dados_pixel.produtos, // Parse do JSON para objetos JavaScript
                        value: $data.dados_pixel.valor_total,
                        currency: 'BRL',
                        external_id: userData.external_id,
                        fn: userData.fn,
                        em: userData.em,
                        zp: userData.zp,
                        ph: userData.ph,
                        ct: userData.ct,
                        st: userData.st,
                        fbp: userData.fbp,
                        fbc: userData.fbc,
                        client_user_agent: userData.client_user_agent,
                        client_ip_address: userData.client_ip_address
                    };

                    // Chama a função do pixel para enviar o evento
                    fbq('track', 'AddPaymentInfo', dados_pixel);
                }

                window.location.href = $data.link_externo//redirecionar para o link de pagamento

            } else {
                if ($data.response !== undefined) { //erro de usuário
                    $.each($data.response, function (key, value) {
                        $("#" + key).addClass("is-invalid")
                        $(".feedback-" + key).addClass("invalid-feedback").html(value)
                    });
                    // Scroll para a primeira div com a classe "is-invalid"
                    if ($(document).height() > $(window).height()) {
                        const firstInvalidElement = $(".is-invalid")[0];
                        if (firstInvalidElement) {
                            firstInvalidElement.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                } else { //erro de aplicação
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: $data.message,
                        timer: 7500,

                    })
                }
                $("#btn_confirm_order").prop('disabled', false);//desabilitar o botão
            }
        }

        function falha() {
            console.log("erro");
        }
        
    }, 10);

    $(".span-loader").fadeOut(); // Esconde o loader

}
