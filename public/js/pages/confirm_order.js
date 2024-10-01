const params = new URLSearchParams(window.location.search);
$(document).ready(function () {
    $(".span-loader").html('<div class="loader"></div>');
    $(window).on("load", function () {
        $.ajax({
            type: 'GET',
            data: "page=inicial&containers=baner_2",
            url: "resources/views/containers/initial/carousel_information.php",
            success: function (result) {
                return $(".main .promo").html(result);
            },
        });

        $.ajax({
            type: 'GET',
            data: "page=confirm-order&layouts=topo&header=2",
            url: "resources/views/layouts/header.php",
            success: function (result) {
                return $(".main .header").html(result);
            },
        });

        $.ajax({
            type: 'GET',
            data: "page=confirm-order&layouts=breadcrumb&" + params,
            url: "resources/views/layouts/breadcrumb.php",
            success: function (result) {
                return $(".main .breadcrumb").html(result);
            },
        });

        $.ajax({
            type: 'GET',
            data: "page=confirm-order&containers=confirm_order&" + params,
            url: "resources/views/containers/confirm_order/confirm_order.php",
            success: function (result) {
                return $(".main .conteudo-1").html(result);
            },
        });

        $.ajax({
            type: 'GET',
            data: "page=confirm-order&layouts=footer",
            url: "resources/views/layouts/footer.php",
            success: function (result) {
                return $(".main .footer").html(result);
            },
        });


        // ScrollReveal().reveal('.conteudo-1', { delay: 400 });
        // ScrollReveal().reveal('.conteudo-2', { delay: 400 });
        
        $(".span-loader").fadeOut(); // Esconde o loader

    });

});