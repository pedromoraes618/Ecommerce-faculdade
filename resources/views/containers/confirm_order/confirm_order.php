<?php
include "../../../../app/Http/Controllers/ConfirmOrder.php";
?>
<div class="container-lg checkout confirm-order mt-3 p-0">
    <?php if ($qtd_registro > 0) {
        if ($status_compra == "ANDAMENTO") {
    ?>
            <form id="confirmOrder">
                <div class="row g-4">
                    <div class="col-md-5 col-lg-4  order-md-last order-2 text-center bloco-right">
                        <div class="p-3 border-0 shadow gx-5 rounded">
                            <!-- <div class="col-md mt-3 mb-2 mt-md-5"><img src="public/imagens/baner/disco_2.png" width="200" class="img-fluid" alt=""></div> -->
                            <?php
                            if ($dados_usuario) :
                                $produtosCart = $dados_usuario['produtos_cart'];
                                $qtd_cart = $dados_usuario['qtd_cart'];
                                if ($qtd_cart > 0) {
                                    $total = 0;
                                    $registro = 0;
                                    foreach ($produtosCart as $linha) {
                                        include "../card-produto/modelo_confirm_order.php"; //modelo

                                    };
                                }
                            endif;
                            ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted">Subtotal</div>
                                    <div class="text-muted"><?= real_format($valor_produto); ?></div>
                                </div>
                                <?php if ($valor_frete > 0) { ?>
                                    <div class="d-flex justify-content-between">
                                        <div class="text-muted">Frete</div>
                                        <div class="text-muted"><?= real_format($valor_frete); ?></div>
                                    </div>
                                <?php } ?>
                                <?php if ($valor_desconto > 0) { ?>
                                    <div class="d-flex justify-content-between">
                                        <div class="text-muted">Desconto</div>
                                        <div class="text-muted">- <?= real_format($valor_desconto); ?></div>
                                    </div>
                                <?php } ?>
                                <?php if ($valor_cupom > 0) { ?>
                                    <div class="d-flex justify-content-between">
                                        <div class="text-muted">Cupom</div>
                                        <div class="text-muted">- <?= real_format($valor_cupom); ?></div>
                                    </div>
                                <?php } ?>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <div class="fw-bold">Total</div>
                                    <div class="fw-bold"><?= real_format($valor_liquido); ?></div>
                                </div>

                                <div id="warning"></div>
                                <div class="feedback-warning mb-2"></div>

                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit" id="btn_confirm_order" class="payment rounded">Realizar Pagamento</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 col-lg-8 order-1 confirm-order-description">
                        <div class="border  p-2 rounded mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <p class="fw-normal mb-0 fw-semibold">Confirmação da compra</p>
                            </div>
                            <div class="flex-grow-2 d-flex">
                                <div class="p-2"><i class="bi bi-person"></i></div>
                                <div class="p-2  flex-grow-1">
                                    <p class='mb-0'><?= $nome ?></p>
                                    <p class='mb-0'><?= $cpfcnpj ?></p>
                                    <p class='mb-0'><?= $email ?></p>
                                    <p class='mb-0'><?= $telefone ?></p>
                                </div>
                                <small class="p-2 fw-semibold">
                                    <a href="?checkout=true&order=<?= $_GET['order']; ?>&code=<?= $_GET['code']; ?>" class="text-decoration-none alter-cep text-dark">Alterar</a></small>
                            </div>
                            <hr class="m-1">
                            <div class="flex-grow-2 d-flex">
                                <div class="p-2"><i class="bi bi-geo-alt"></i></div>
                                <div class="p-2  flex-grow-1">
                                    <p class='mb-0'><?= $endereco . " " . $numero; ?></p>
                                    <p class='mb-0'><?= $cep . " " . $bairro ?></p>
                                    <p class='mb-0'><?= $cidade . " - " . $estado ?></p>
                                </div>
                                <small class="p-2 fw-semibold">
                                    <a href="?checkout=true&order=<?= $_GET['order']; ?>&code=<?= $_GET['code']; ?>" class="text-decoration-none alter-cep text-dark">Alterar</a></small>
                            </div>
                            <hr class="m-1">
                            <div class=" flex-grow-2 d-flex">
                                <div class="p-2"><i class="bi bi-truck"></i></div>
                                <div class="p-2  flex-grow-1">
                                    <p class="mb-1"><?= $transportadora; ?></p>
                                </div>
                                <small class="p-2 fw-semibold">
                                    <a href="?checkout=true&order=<?= $_GET['order']; ?>&code=<?= $_GET['code']; ?>" class="text-decoration-none alter-cep text-dark">Alterar</a></small>
                            </div>
                            <hr class="m-1">
                            <div class=" flex-grow-2 d-flex">
                                <div class="p-2"><i class="bi bi-wallet"></i></div>
                                <div class="p-2  flex-grow-1">
                                    <p class="mb-1"><?= $formapagamento; ?></p>
                                </div>
                                <small class="p-2 fw-semibold">
                                    <a href="?checkout=true&order=<?= $_GET['order']; ?>&code=<?= $_GET['code']; ?>" class="text-decoration-none alter-cep text-dark">Alterar</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php
        } elseif ($status_compra == "CONCLUIDO" and (auth('') != false)) { //pedido finalizado com sucesso, usuário autenticado
        ?>
            <div style="max-width:300px;margin:0 auto">
                <div class="card rounded-0 border-1">
                    <div class="card-body">
                        <h5 class="mb-1">Pedido confirmado! #<?= $pedido; ?></h5>
                        <p>Veja detalhes do seu pedido.</p>
                        <div>
                            <a href="?user=order" class="btn btn-sm btn-dark">Veja o seu pedido</a>
                        </div>

                    </div>
                </div>
            </div>
        <?php
        } elseif ($status_compra == "CONCLUIDO" and (auth('') == false)) { //pedido finalizado com sucesso, usuário não autenticado
        ?>
            <div style="max-width:600px;margin:0 auto">
                <div class="card rounded-0 border-1">
                    <div class="card-body">
                        <h5 class="mb-1">Pedido Confirmado! #<?= $pedido; ?></h5>
                        <p class="mb-1">Esteja atento à sua caixa de entrada! Em breve,
                            receberá um e-mail importante de <?= $email_empresa ?>
                            com detalhes sobre o seu pedido.</p>
                        <p>O e-mail será enviado para
                            <?= $email; ?>. Caso encontre algum problema,
                            entre em contato conosco para correção.</p>
                    </div>
                </div>
            </div>
        <?php
        } elseif ($status_compra == "CANCELADO") {
        ?>
            <div style="max-width:600px;margin:0 auto">
                <div class="card rounded-0 border-1">
                    <div class="card-body">
                        <h5 class="mb-1">Pedido cancelado. #<?= $pedido; ?></h5>
                        <p class="mb-3">Esse pedido foi cancelado</p>
                        <?php
                        include "../../component/cartProductAdd.php";
                        ?>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        include "../../component/orderNotFound.php";
    }
    ?>
</div>

<script src="public/js/containers/confirm_order/confirm_order.js"> </script>