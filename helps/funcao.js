var product_details = new URLSearchParams(window.location.search).get('product-details'); // pesquisa pelo produto especifico



/*funcionalidade para visualizar a senha */
$('#visualizarSenha').on('click', function () {
  var senhaInput = $('#senha');
  var tipo = senhaInput.attr('type');
  if (tipo === 'password') {
    senhaInput.attr('type', 'text');
  } else {
    senhaInput.attr('type', 'password');
  }
});

$('#visualizarSenhaConfirmar').on('click', function () {
  var confirmarSenhaInput = $('#confirmar_senha');
  var tipo = confirmarSenhaInput.attr('type');
  if (tipo === 'password') {
    confirmarSenhaInput.attr('type', 'text');
  } else {
    confirmarSenhaInput.attr('type', 'password');
  }
});


function updateFavorite(element, id) {//adicionar no favorito
  $(".span-loader").html('<div class="loader"></div>');

  $.ajax({
    type: "POST",
    data: "form=Favorite&acao=updateFavorite&productID=" + id,
    url: "app/Http/Controllers/Favorite.php",
    async: false
  }).then(sucesso, falha);

  function sucesso(data) {
    var $data = $.parseJSON(data)["data"];
    if ($data.status == true) {
      offcanvasFavorite()//abrir o offcanvas do carrinho
      $('.qtd-fav').html($data.qtd_fav);


      if ($(element).hasClass('fav-true')) {
        $(element).removeClass('fav-true'); // Remove a classe da div pai se já estiver presente
      } else {
        $(element).addClass('fav-true'); // Adiciona a classe à div que foi clicada
      }

      const dados_pixel = $data.dados_pixel
      if ($data.operacao === "adicionar" && dados_pixel.pixel_status == 'S') {//VERIFICAR SE O PIXEL ESTÁ ATIVO

        fbq('track', 'AddToWishlist', {//pixel
          content_ids: [dados_pixel.id], // 'REQUIRED': array of product IDs
          content_type: dados_pixel.tipo, // RECOMMENDED: Either product or product_group based on the content_ids or contents being passed.
          value: dados_pixel.valor_unitario, // The total monetary value
          currency: dados_pixel.moeda, // The currency of the value
          content_name: dados_pixel.produto_descricao, // The name of the product
          content_category: dados_pixel.categoria, // The category of the product
          email: dados_pixel.user_data.em,
          contents: [{ // Detailed information about the products
            id: dados_pixel.id,
            quantity: 1,
            item_price: dados_pixel.valor_unitario
          }],
          em: dados_pixel.user_data.em,
          client_ip_address: dados_pixel.user_data.client_ip_address,
          client_user_agent: dados_pixel.user_data.client_user_agent,
          fbp: dados_pixel.user_data.fbp, //fbp facebook
          fbc: dados_pixel.user_data.fbc,//fbc facebook
          ct: dados_pixel.user_data.ct,//cidade
          external_id: dados_pixel.user_data.external_id,
        });
      }

    } else {
      Swal.fire({
        icon: 'error',
        title: 'Verifique!',
        text: $data.message,
        timer: 7500,

      })
    }
    $(".span-loader").fadeOut(); // Esconde o loader

  }

  function falha() {
    console.error();
  }
}


function updateCart(element, id, qtd, operacao) {//adicionar no favorito
  $(".span-loader").html('<div class="loader"></div>');

  $.ajax({
    type: "POST",
    data: "form=Favorite&acao=updateCart&productID=" + id + "&qtd=" + qtd + "&operacao=" + operacao,
    url: "app/Http/Controllers/Cart.php",
    async: false
  }).then(sucesso, falha);

  function sucesso(data) {

    var $data = $.parseJSON(data)["data"];
    if ($data.status == true) {
      offcanvasCart()//abrir o offcanvas do carrinho
      $('.qtd-cart').html($data.qtd_cart);
      const dados_pixel = $data.dados_pixel
      if (operacao === "adicionar" && dados_pixel.pixel_status == 'S') {//VERIFICAR SE O PIXEL ESTÁ ATIVO

        fbq('track', 'AddToCart', {//pixel
          content_ids: [dados_pixel.id], // 'REQUIRED': array of product IDs
          content_type: dados_pixel.tipo, // RECOMMENDED: Either product or product_group based on the content_ids or contents being passed.
          value: dados_pixel.valor_unitario, // The total monetary value
          currency: dados_pixel.moeda, // The currency of the value
          content_name: dados_pixel.produto_descricao, // The name of the product
          content_category: dados_pixel.categoria, // The category of the product
          email: dados_pixel.user_data.em,
          contents: [{ // Detailed information about the products
            id: dados_pixel.id,
            quantity: qtd,
            item_price: dados_pixel.valor_unitario
          }],
          em: dados_pixel.user_data.em,
          client_ip_address: dados_pixel.user_data.client_ip_address,
          client_user_agent: dados_pixel.user_data.client_user_agent,
          fbp: dados_pixel.user_data.fbp, //fbp facebook
          fbc: dados_pixel.user_data.fbc,
          ct: dados_pixel.user_data.ct,
          external_id: dados_pixel.user_data.external_id,

        });

      }
      // if ($('.span-cart-' + id).html() == "Adicionar") {
      //   $('.span-cart-' + id).html('Remover') // Remove a classe da div pai se já estiver presente
      // } else {
      //   $('.span-cart-' + id).html('Adicionar') // Remove a classe da div pai se já estiver presente
      // }

    } else {
      Swal.fire({
        icon: 'error',
        title: 'Verifique!',
        text: $data.message,
        timer: 7500,
      })
    }

    $(".span-loader").fadeOut(); // Esconde o loader

  }

  function falha() {
    console.error();
  }
}

function qtdCart(element, id, qtd) {//adicionar no favorito
  $(".span-loader").css("display", "block");

  setTimeout(function () {
    $.ajax({
      type: "POST",
      data: "form=Favorite&acao=qtdCart&productID=" + id + "&qtd=" + qtd,
      url: "app/Http/Controllers/Cart.php",
      async: false
    }).then(sucesso, falha);

    function sucesso(data) {

      var $data = $.parseJSON(data)["data"];
      if ($data.status == true) {
        offcanvasCart()//abrir o offcanvas do carrinho

      } else {
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

  }, 100);
  $(".span-loader").fadeOut(); // Esconde o loader

}


function offcanvasCart() {//canvas do carrinho
  $(".btn-close").trigger('click'); //fechar o modal

  $(".span-loader").css("display", "block");

  setTimeout(function () {
    $.ajax({
      type: 'GET',
      data: { page: 'headers', containers: 'offcanvasCart' },
      url: 'resources/views/containers/header/offcanvasCart.php',
      success: function (result) {
        // Define o conteúdo do offcanvas com o resultado da requisição AJAX
        $("main .offcanvas-open").html(result);

        // Abre o offcanvas depois que o conteúdo foi carregado
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasCart'));
        offcanvas.show();
      },
    });
  }, 100);
  $(".span-loader").fadeOut(); // Esconde o loader

}


function offcanvasFavorite() {//canvas do favorito
  $(".btn-close").trigger('click'); //fechar o modal
  $(".span-loader").css("display", "block");

  setTimeout(function () {
    $.ajax({
      type: 'GET',
      data: { page: 'headers', containers: 'offcanvasFavorite' },
      url: 'resources/views/containers/header/offcanvasFavorite.php',
      success: function (result) {
        // Define o conteúdo do offcanvas com o resultado da requisição AJAX
        $("main .offcanvas-open").html(result);

        // Abre o offcanvas depois que o conteúdo foi carregado
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasFavorite'));
        offcanvas.show();
      },
    });
  }, 100);
  $(".span-loader").fadeOut(); // Esconde o loader

}


function sessaoCep() {//mostrar oção de frete
  
  $.ajax({
    type: 'GET',
    data: "page=products_details&layouts=consultarFrete",
    url: "resources/views/containers/cep/consultarFrete.php",
    success: function (result) {
      return $(".main .section-cep").html(result);
    },
  });
}

function modalLogin() {//modal do login
  $(".btn-close").trigger('click'); //fechar o modal
  $.ajax({
    type: 'GET',
    data: "page=auth&containers=login",
    url: "resources/views/containers/auth/login.php",
    success: function (result) {
      return $("main .modal-externo").html(result) + $("#modal_login").modal('show');;
    },
  });
}