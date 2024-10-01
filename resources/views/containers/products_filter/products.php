<?php include "../../../../app/Http/Controllers/ProductsFilter.php";
if (isset($consultar_produtos) && $qtd_prd > 0) {
?>
    <?php if ($status_baner_secao == 'S' and !empty($baner_secao)) { ?>
        <div class="baner-category d-flex mb-4">
            <img src="<?= "$url_init_img/$empresa/img/ecommerce/baner/$baner_secao" ?>" alt="">
        </div>
    <?php } ?>

    <div class="d-flex mb-3">
        <div>
            <h4 class="mb-0 fw-mediun"><?= $title_session; ?></h4>
            <p class="text-muted mb-0" style="font-size: 0.8em;"> Produtos (<?= $qtd_prd; ?>)</p>
        </div>
    </div>
    <div class="row row-cols-2 row-cols-md-4 g-3 products-box">
        <?php while ($linha = mysqli_fetch_assoc($consultar_produtos)) { ?>
            <div class="mb-3">
                <?php include "../card-produto/modelo_1.php"; ?>
            </div>
        <?php }; ?>
    </div>
    <?php if ($total_pages > 1) { ?>
        <div class=" mt-5 d-flex justify-content-center ">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li <?php if ($current_page > 1) { ?>onclick="product(<?= $current_page - 1; ?>)" <?php }; ?> class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li onclick="product(<?= $i; ?>)" class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <a href="#" class="page-link"><?php echo $i; ?></a>
                        </li>
                    <?php }; ?>
                    <li <?php if ($current_page != $total_pages) { ?> onclick="product(<?= $current_page + 1; ?>)" <?php } ?> class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">

                        <a class="page-link" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
<?php
    };
} else {
    include "../../component/filterNotFound.php";
};
?>

<script src="public/js/containers/products_filter/products.js"> </script>