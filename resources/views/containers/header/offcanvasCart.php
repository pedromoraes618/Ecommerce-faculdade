<?php
include "../../../../app/Http/Controllers/Cart.php";
?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel"><i class="bi bi-cart3"></i> Carrinho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <?php
    if ($dados_usuario) {
        $produtosCart = $dados_usuario['produtos_cart'];
        $qtd_cart = $dados_usuario['qtd_cart'];
        if ($qtd_cart > 0) {
            $total = 0;
            $registro = 0;
    ?>
            <div class="offcanvas-body">
                <?php
                foreach ($produtosCart as $linha) {
                    include "../card-produto/modelo_cart.php"; //modelo carrinho
                }; ?>
            </div>
            <div class="offcanvas-footer p-2">
                <div class="p-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fs-5 fw-semibold ">
                                Subtotal
                            </span>
                            <br>
                            <span class="fs-6"><?= $registro ?> <?= $registro > 1 ? 'Itens' : 'Item' ?></span>
                        </div>
                        <div>
                            <div class="fs-5 fw-semibold text-end">
                                <?= real_format($total); ?>
                            </div>
                            <div class="text-muted" style="color:#2980b9">
                                <?php if ($qtd_parcela > 0) {
                                    $totalParcelado = real_format($total / $qtd_parcela);
                                    echo "até $qtd_parcela x de $totalParcelado sem juros</div>";
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <?php if ($freteGratis == "true") { ?>
                            <div class="mb-3">
                                <small><i class="bi bi-info-circle"></i> <b>FRETE GRÁTIS</b>
                                    <?php if ($freteCondicaoValorEstado > 0 and $freteCondicaoValorEstado == $freteCondicaoValorForaEstado) {
                                        // Calcula a porcentagem da progressbar
                                        $porcentagem = min(100, ($total / $freteCondicaoValorEstado) * 100);
                                    ?>
                                        Subtotal acima de <?= real_format($freteCondicaoValorEstado); ?>.
                                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="<?= $porcentagem ?>" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-dark" style="width: <?= $porcentagem ?>%"></div>
                                        </div>
                                    <?php } else { ?>

                                        <?php if ($freteCondicaoValorEstado > 0) { ?>
                                            Para o estado de <?= $estado_empresa; ?> - acima de <?= real_format($freteCondicaoValorEstado); ?>.
                                        <?php }
                                        if ($freteCondicaoValorForaEstado > 0) { ?>
                                            Demais estados - acima de <?= real_format($freteCondicaoValorForaEstado); ?>.
                                    <?php }
                                    } ?>
                                </small>
                            </div>
                        <?php } ?>
                    </div>
                    <div>
                        <a type="button" class="btn-danger finalize-purchase rounded btn" href="?checkout=true">Iniciar Compra</a>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <div class="offcanvas-body">
                <?php
                include "../../component/cartEmpty.php"; //modelo de carrinho vazio
                ?>
            </div>
    <?php }
    }; ?>
</div>