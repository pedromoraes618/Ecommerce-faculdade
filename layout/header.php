<div class="header pt-3 pb-3">
    <div class="container">
        <div class="d-flex align-items-center row d-flex justify-content-between">
            <div class="col-auto col-sm-auto pb-3">
                <a class="text-decoration-none " id="logo" href="./">
                    <img src="public/imagem/logo/logotipo.jpg" alt="logo"  width="70" height="50" alt="Logo" class="rounded">
                </a>
            </div>

            <div class="col-auto order-md-2 pb-3">
                <ul class="list-inline mb-0 d-flex justify-content-center align-items-center">
                    <li class="list-inline-item position-relative">
                        <a href="#" class="text-dark open-favorite mx-1"><i class="bi bi-heart fs-5 text-light"></i></a>
                        <span style="font-size: 0.69em;" class="position-absolute top-0 start-100 
                        translate-middle badge rounded-pill bg-danger 
                        qtd-fav">
                            0 <span class="visually-hidden">Favoritos</span>
                        </span>
                    </li>
                    <li class="list-inline-item position-relative"><a href="#" class="text-dark open-cart mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart"><i class="bi bi-cart3 fs-5 text-light"></i></a>
                        <span style="font-size: 0.69em;" class="position-absolute top-0 start-100 translate-middle badge 
                        rounded-pill bg-danger 
                        qtd-cart">
                            1 <span class="visually-hidden">Carrinho</span>
                        </span>
                    </li>
                    <li class="list-inline-item dropdown">
                        <a href="#" class="dropdown-toggle text-light text-decoration-none mx-1" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person fs-4 text-light"></i> </a>
                        <ul class="dropdown-menu " aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" id="login" href="#">Login</a></li>
                            <li><a class="dropdown-item" href="#" id="register">Registre-se</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-md order-md-1">
                <div class="search-box ecommerce-search-box position-relative" style="max-width: 600px;margin:0 auto">
                    <form id="search" class="position-relative" method="get">
                        <div class="input-group">
                            <input class="form-control search-input
                             search form-control-sm border-end-0" type="search" name="products-filter" id="products-filter" placeholder="Pesquise.." value="" aria-label="Search" wfd-id="id0">
                            <button class="btn btn-outline-secondary btn-search bg-light border-start-0" type="submit" id="button-addon2"><i class="bi bi-search text-dark"></i></button>
                        </div>
                        <div class="filter-search"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>