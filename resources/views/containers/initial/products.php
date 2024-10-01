<?php include "../../../../app/Http/Controllers/Initial.php"; ?>

<?php
if ($secao_navegar_marcadores == "S") {
    if ($consulta_marcadores and $qtd_marcadores >= 4) {
?>
        <div class="pt-2 pb-2 mb-4 d-flex justify-content-center">
            <div class="section-markers d-flex justify-content-center" >
                <?php while ($linha = mysqli_fetch_assoc($consulta_marcadores)) { ?>
                    <div class="mx-2">
                        <?php include "markers.php"; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}

if ($secao_novidades == "S") {
    if ($consulta_produtos_novidades) {
        if ($qtd_prd_lancamento > 4) { ?>

            <div class="p-0 mb-4 mb-md-5 section-new">
                <div class="d-flex justify-content-between">
                    <div class="mb-2">
                        <h4 class=""><?= $titulo_secao_novidade; ?></h4>
                    </div>
                    <!-- <div><a class="fw-bolder text-decoration-none" href="?products-filter&news=true">Veja mais <i class="bi bi-chevron-right"></i></a></div> -->
                </div>

                <div class="row mb-md-3 row-cols-2 row-cols-sm-4 row-cols-md-5 g-3">
                    <?php while ($linha = mysqli_fetch_assoc($consulta_produtos_novidades)) { ?>
                        <div class="mb-3">
                            <?php include "../card-produto/modelo_1.php"; ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-center mb-2 mt-2">
                    <a href="?products-filter&news=true" class="btn btn-view-more rounded-0 btn-outline-secondary border-1">Ver mais</a>
                </div>
            </div>
<?php
        };
    } else {
        include "../../component/sectionMaintenance.php";
    };
}; ?>

<?php
if ($secao_desconto == "S") {
    if ($consulta_produtos_desconto) {
        if ($qtd_prd_desconto > 4) { ?>
            <div class="p-0 mb-4 mb-md-5 section-discount rounded session-desconto text-dark">
                <div class="d-flex justify-content-between">
                    <div class="mb-2">
                        <h4 class=" "><?= $titulo_secao_desconto; ?></h4>
                    </div>
                    <div><a class="fs-6 text-decoration-none text-dark " href="?products-filter&discount=true">Veja mais <i class="bi bi-chevron-right"></i></a></div>
                </div>
                <div class="owl-carousel ofertas ">
                    <?php while ($linha = mysqli_fetch_assoc($consulta_produtos_desconto)) { ?>
                        <div class="mb-3">
                            <?php include "../card-produto/modelo_1.php"; ?>
                        </div>
                    <?php } ?>
                </div>
                <!-- <div class="d-flex justify-content-center mb-2 mt-2">
                <a  href="?products-filter&discount=true" class="btn btn-view-more rounded btn-danger border-0">Ver mais</a>
            </div> -->
            </div>
<?php
        };
    } else {
        include "../../component/sectionMaintenance.php";
    };
}; ?>

<?php if ($consulta_produtos_catalogo) { ?>
    <div class="p-0 mb-4 mb-md-5 section-catalog">
        <div class="d-flex justify-content-between">
            <div class="mb-2">
                <h4 class=""><?= $titulo_secao_catalogo; ?></h4>
            </div>
            <!-- <div><a class="fw-bolder text-decoration-none">Veja mais <i class="bi bi-chevron-right"></i></a></div> -->
        </div>
        <div class="row mb-md-3  row-cols-2 row-cols-sm-4  row-cols-md-5 g-3">
            <?php while ($linha = mysqli_fetch_assoc($consulta_produtos_catalogo)) {
            ?>
                <div class="mb-3">
                    <?php include "../card-produto/modelo_1.php"; ?>
                </div>
            <?php } ?>
        </div>
        <div class="d-flex justify-content-center mb-2 mt-2">
            <a href="?products-filter&catalog=true" class="btn btn-view-more rounded-0 btn-outline-secondary ">Ver mais</a>
        </div>
    </div>
<?php
} else {
    include "../../component/sectionMaintenance.php";
};
?>
<?php
if ($secao_mais_buscados == "S") {
    if ($consulta_produtos_mais_buscados) {
        if ($qtd_prd_mais_buscados > 4) { ?>
            <div class="p-0 mb-4 mb-md-5 rounded section-most-searched text-dark">
                <div class="d-flex justify-content-between">
                    <div class="mb-2">
                        <h4 class=" "><?= $titulo_secao_mais_buscados; ?></h4>
                    </div>
                </div>
                <div class="owl-carousel mais_buscados ">
                    <?php while ($linha = mysqli_fetch_assoc($consulta_produtos_mais_buscados)) { ?>
                        <div class="mb-3">
                            <?php include "../card-produto/modelo_1.php"; ?>
                        </div>
                    <?php } ?>
                </div>

            </div>
<?php
        }
    } else {
        include "../../component/sectionMaintenance.php";
    }
}; ?>

<script src="public/js/containers/initial.js"> </script>