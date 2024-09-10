<div class="baner mb-4">
    <div class="owl-carousel baner-1 ">
        <img class="item img-fluid" src="public/imagem/baner/modelo1.svg"  style="min-height: 200px;" alt="">
        <img class="item img-fluid" src="public/imagem/baner/modelo2.svg" style="min-height: 200px;" alt="">
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.owl-carousel.baner-1').owlCarousel({
            items: 1, // Número de itens visíveis no carrossel
            loop: true,
            margin: 0, // Espaçamento entre os cards
            nav: false, // Mostrar setas de navegação
            dots: false, // Ocultar pontos de navegação
            //   navText: ['<i class="bi bi-chevron-left"></i>', '<i class="bi bi-chevron-right"></i>'], // Ícones das setas de navegação (ajuste os caminhos conforme necessário)
            autoplay: true,
            autoplayTimeout: 7000,

        });
    });
</script>