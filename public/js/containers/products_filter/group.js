var min_preco = document.getElementById("min_preco")
var max_preco = document.getElementById("max_preco")

$(document).ready(function () {


    // Função para fechar o menu quando clicar fora dele
    $(document).click(function (event) {
        if (!$(event.target).closest('.filters-dropdown, .btn-filters-mobile').length) {
            $('.filters-dropdown').removeClass('show');
        }
    });

    // Ouvinte de evento para abrir o menu ao clicar no botão
    $('.btn-filters-mobile').click(function () {
        $('.filters-dropdown').toggleClass('show');
    });
});
$('.rotate-icon').click(function () {
    $(this).toggleClass('rotate-up');
});

function rotateIcon(element) {
    // Verificar se a classe rotate-up está presente no ícone
    if ($(element).find('.rotate-icon').hasClass('rotate-up')) {
        // Remover a classe rotate-up para virar a seta para baixo
        $(element).find('.rotate-icon').removeClass('rotate-up');
    } else {
        // Adicionar a classe rotate-up para virar a seta para cima
        $(element).find('.rotate-icon').addClass('rotate-up');
    }
}
/*ulr */
const params = new URLSearchParams(window.location.search);
const products_filter = params.get('products-filter'); 
const subcategory = params.get('subcategory'); 
const news = params.get('news');
const catalog = params.get('catalog'); 
const discount = params.get('discount');
const page = params.get('page'); 
const formato = params.get('formato');
const markers = params.get('markers');

product(1)

function product(page) {


    var dados = {
        page: "products_filter",
        containers: "products",
        products_filter: products_filter,
        subcategory: subcategory,
        news: news,
        discount: discount,
        catalog: catalog,
        pagination: page,
        formato: formato,
        markers: markers,
        marcadores: [],
        unidades: [], // Inicializa 'marcador' como um array vazio
        marcas: [], // Inicializa 'marca' como um array vazio
        // Adicione outros dados conforme necessário
    };
    // Itera sobre cada elemento input
    $('input[type="text"]').each(function () {
        var idNome = $(this).attr('name'); // Obtém o nome do ID do input
        var valor = $(this).val(); // Obtém o valor do input
        dados[idNome] = valor; // Adiciona o nome do ID como chave e o valor como valor ao objeto dados
    });

    $('input[type="checkbox"]:checked').each(function () {
        var idNome = $(this).attr('name'); // Obtém o nome do ID do input
        var valor = $(this).val(); // Obtém o valor do input
        if (idNome === "marcador") {//array
            dados["marcadores"].push(valor); // Adiciona o valor ao array 'marcador'
        } else if (idNome == "unidade") {
            dados["unidades"].push(valor); // Adiciona o valor ao array 'unidade de medida'
        } else if (idNome == "marca") {
            dados["marcas"].push(valor); // Adiciona o valor ao array 'unidade de medida'
        } else {
            dados[idNome] = valor; // Adiciona o nome do ID como chave e o valor como valor ao objeto dados
        }
    });

    // Itera sobre cada elemento select
    $('select').each(function () {
        var idNome = $(this).attr('name'); // Obtém o nome do ID do select
        var valor = $(this).val(); // Obtém o valor do select
        dados[idNome] = valor; // Adiciona o nome do ID como chave e o valor como valor ao objeto dados
    });


    $.ajax({
        type: 'GET',
        data: dados,
        url: "resources/views/containers/products_filter/products.php",
        success: function (result) {
            $(".main .conteudo .conteudo-1 .group-products").html(result);
        },
    });
}


// // Parâmetros adicionais para o evento Search
// var searchParams = {
//     search_string: products_filter,
// };

// // Envia o evento Search para o Meta Pixel
// fbq('track', 'Search', searchParams);

// // Espera até que o documento esteja completamente carregado

// // Seleciona todos os elementos com a classe "placeholder" e remove-os
