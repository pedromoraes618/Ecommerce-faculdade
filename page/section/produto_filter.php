<div class="container my-5">
    <div class="titl-section">
        <h5>Blusas</h5>
    </div>
    <hr>
    <div class="d-flex justify-content-between  mb-5">
        <div class="d-flex flex-wrap">
            <div class="dropdown mx-2 mb-2">
                <button class="btn border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Tamanho
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">P</a></li>
                    <li><a class="dropdown-item" href="#">M</a></li>
                    <li><a class="dropdown-item" href="#">G</a></li>
                </ul>
            </div>
            <div class="dropdown mx-2 mb-2">
                <button class="btn border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Marca
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Marisa</a></li>
                    <li><a class="dropdown-item" href="#">HAVAIANAS </a></li>
                </ul>
            </div>
            <div class="dropdown mx-2 mb-2">
                <button class="btn border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Preço
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">R$ 0,00 - R$ 100,00</a></li>
                    <li><a class="dropdown-item" href="#">R$ 100,00 - R$ 200,00</a></li>
                    <li><a class="dropdown-item" href="#">R$ 200,00 - R$ 300,00</a></li>
                </ul>
            </div>
        </div>
        <div>
            <div class="dropdown mx-2 mb-2">
                <button class="btn border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Ordenar por:
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Preço</a></li>
                    <li><a class="dropdown-item" href="#">A-Z</a></li>
                    <li><a class="dropdown-item" href="#">Z-A</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <?php for ($i = 0; $i <= 10; $i++) { ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 border mb-4">
                    <a href="?page=details-product&id=2&product=Polo Piquet Manga Curta Com Bolso Vivo Preta">
                        <img src="public/imagem/produto/modelo1.svg" class="card-img-top rounded" alt="Produto">
                    </a>
                    <div class="favorite-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="card-body">
                        <p class="card-title text-muted mb-1">Polo Piquet Manga Curta Com Bolso Vivo Preta</p>
                        <div class="card-text mb-3">
                            <div class="fw-semibold">R$ 239,90 </div>
                            <div> até 2x de R$ 119,95</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>