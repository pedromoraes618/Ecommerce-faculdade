
// Obtém o product_id da URL
var product_id = new URLSearchParams(window.location.search).get('product-details');

// Verifica se o product_id não é nulo
if (product_id) {
    // Obtém o array existente do localStorage ou inicializa um novo array
    var products_visit = JSON.parse(localStorage.getItem('products_visit')) || [];

    // Verifica se o product_id já existe no array
    if (!products_visit.includes(product_id)) {
        // Adiciona o novo product_id ao array
        products_visit.push(product_id);

        // Salva o array atualizado no localStorage
        localStorage.setItem('products_visit', JSON.stringify(products_visit));
    }
}




// Adiciona ação ao botão de incremento
$(".increment").on("click", function () {
    var currentValue = parseInt($("#qtd_prd").val());
    $("#qtd_prd").val(currentValue + 1);
});

// Adiciona ação ao botão de decremento
$(".decrement").on("click", function () {
    var currentValue = parseInt($("#qtd_prd").val());
    if (currentValue > 1) {
        $("#qtd_prd").val(currentValue - 1);
    }
});


// $(".open-favorite").click(function () {
//     offcanvasFavorite()
// })

$(document).ready(function () {

    $('.left-img').click(function () {
        // Obtenha o src da imagem clicada
        var src = $(this).find('img').attr('src');
        // Remova a classe "new-image" e adicione novamente para reiniciar a animação
        $('.main-image img').removeClass("new-image").delay(10).queue(function (next) {
            $(this).addClass("new-image");
            next();
        });

        // Substitua o src da imagem principal pelo src da imagem clicada
        $('#main-image-link img').attr('src', src);
        $('#main-image-link').attr('href', src);
    });

    /*carousel de produtos similares */
    $('.owl-carousel.product-group-1').owlCarousel({
        items: 1, // Número de itens visíveis no carrossel
        loop: true,
        margin: 5, // Espaçamento entre os cards
        nav: false, // Mostrar setas de navegação
        dots: false, // Ocultar pontos de navegação
        autoplay: true,
        autoplayTimeout: 6000,
        responsive: {
            0: {
                items: 1.7 // Número de itens visíveis em telas menores
            },
            740: {
                items: 2.9 // Número de itens visíveis em telas médias
            },
            992: {
                items: 5 // Número de itens visíveis em telas maiores
            }
        }
    })


    /*carregar a seção do cep */
    sessaoCep()

})



/*pergunta e respostas */
$("#productQuestion").click(function () {
    modalProductQuestion(product_id)
})
$("#question-tab").click(function () {
    registrationQuestion(product_id)
})

function modalProductQuestion() {//abrir o modal de realizar pergunta
    $(".btn-close").trigger('click'); //fechar o modal
    $.ajax({
        type: 'GET',
        data: "page=products_details&component=product_question",
        url: "resources/views/component/productQuestion.php",
        success: function (result) {
            return $("main .modal-externo").html(result) + $("#modal_product_question").modal('show');
        },
    });
}



function registrationQuestion(product_id) {//perguntas 
    $.ajax({
        type: 'GET',
        data: "component=product_question&product_id=" + product_id,
        url: "resources/views/component/list/productQuestion.php",
        success: function (result) {
            return $("main .productQuestionList").html(result)
        },
    });
}



/*variação */
$("input[type='radio'][data-variacao='true']").on('click', function () {

    // Coletar todas as variações selecionadas (radios)
    let variacoes = [];
    $("input[type=radio][data-variacao='true']:checked").each(function () {
        let name = $(this).attr("name");
        let value = $(this).next("label").text(); // Capturar o valor do label correspondente

        // Adicionar cada variação como um objeto dentro do array
        variacoes.push({ name: name, value: value });
    });

    // Passar o array de variações diretamente
    SelectVariation(product_id, variacoes);

});

function SelectVariation(id, variacoes_param) {
    // Fazer a requisição AJAX
    $(".span-loader").css('display', 'block')
    $.ajax({
        type: "POST",
        data: {
            form: true,
            acao: "SelectVariation",
            productID: id,
            variation: JSON.stringify(variacoes_param) // Convertendo para JSON
        },
        url: "app/Http/Controllers/Cart.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        var $data = $.parseJSON(data)["data"];
        if ($data.status == true) {
            $.ajax({
                type: 'GET',
                data: "page=products_details&containers=group&product-details=" + $data.product_id,
                url: "resources/views/containers/products_details/group.php",
                success: function (result) {
                    return $(".main .conteudo-1").html(result);
                },
            });

        } else if ('response' in $data) {
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
            console.log($data.message)
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $data.message,
                timer: 7500,

            })
        }

    }

    function falha() {
        console.error();
    }
    $(".span-loader").fadeOut(); // Esconde o loader

}