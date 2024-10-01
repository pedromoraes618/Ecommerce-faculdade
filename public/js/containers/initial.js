$(document).ready(function () {

    $('.owl-carousel.ofertas').owlCarousel({
        items: 1, // Número de itens visíveis no carrossel
        loop: true,
        margin: 15, // Espaçamento entre os cards
        nav: false, // Mostrar setas de navegação
        dots: false, // Ocultar pontos de navegação
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 2 // Número de itens visíveis em telas menores
            },
            740: {
                items: 4 // Número de itens visíveis em telas médias
            },
            992: {
                items: 5 // Número de itens visíveis em telas maiores
            }
        }
    })
    $('.owl-carousel.mais_buscados').owlCarousel({
        items: 1, // Número de itens visíveis no carrossel
        loop: true,
        margin: 15, // Espaçamento entre os cards
        nav: false, // Mostrar setas de navegação
        dots: false, // Ocultar pontos de navegação
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 2 // Número de itens visíveis em telas menores
            },
            740: {
                items: 4 // Número de itens visíveis em telas médias
            },
            992: {
                items: 5 // Número de itens visíveis em telas maiores
            }
        }
    })


    // ScrollReveal().reveal('.section-new, .section-discount, .section-catalog, .section-most-searched', {
    //     delay: 0,
    //     distance: '50px',
    //     origin: 'top',
    //     opacity: 0,
    //     duration: 400,
    //     easing: 'ease-in-out'
    // });



    // ScrollReveal().reveal('.section-new, .section-discount, .section-catalog, .section-most-searched', {
    //     delay: 300,
    //     distance: '50px',
    //     origin: 'bottom',
    //     opacity: 0,
    //     duration: 500,
    //     easing: 'ease-in-out'
    // });
});





