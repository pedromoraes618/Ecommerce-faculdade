<?php
include "../../../../app/Http/Controllers/Checkout.php";
?>
<div class="container-lg checkout mt-3 p-0">
    <?php
    if (($dados_usuario) and ((!empty($data_expirar) and ($data <= $data_expirar)) or empty($codigo_nf))) {

        $produtosCart = $dados_usuario['produtos_cart'];
        $qtd_cart = $dados_usuario['qtd_cart'];
        if ($qtd_cart > 0) {
    ?>
            <form id="checkout">
                <div class="row g-4">
                    <div class="col-md-5 col-lg-4  order-md-last order-2 text-center bloco-right">
                        <div class="p-3 border-0 shadow gx-5 rounded">
                            <!-- <div class="col-md mt-3 mb-2 mt-md-5"><img src="public/imagens/baner/disco_2.png" width="200" class="img-fluid" alt=""></div> -->
                            <?php
                            $total = 0;
                            $registro = 0;
                            foreach ($produtosCart as $linha) {
                                include "../card-produto/modelo_checkout.php"; //modelo produtos
                            }
                            ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between">
                                    <div class="text-muted">Subtotal</div>
                                    <div class="text-muted valorSubTotalCheckout"><?= real_format($total); ?></div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="text-muted">Frete</div>
                                    <div class="text-muted valorFreteCheckout">Calcular..</div>
                                </div>
                                <span class="spanDescontoCheckout"></span>
                                <span class="spanDescontoCupom"></span>
                                <div class="mt-2">
                                    <input type="text" class="form-control" name="cupom" id="cupom" placeholder="Cupom">
                                    <div class="feedback-cupom">
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <div class="fw-bold">Total</div>
                                    <div class="fw-bold valorTotalCheckout"><?= real_format($total); ?></div>
                                </div>
                                <?php if ($total > 0) { ?>
                                    <div class="d-grid gap-2 mb-3">
                                        <button type="submit" id="btn_checkout" class="payment rounded">Avançar para Confirmação</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 col-lg-8 order-1 ">
                        <div class="border p-2 rounded mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <p class="mb-0 fw-semibold">Dados Pessoais</p>
                                <?php
                                if (auth('') === false) :
                                ?>
                                    <small>
                                        <a href="#" id="login" onclick="modalLogin()" class="fw-normal login mb-0 fw-semibold text-decoration-none " style='color:#2980b9'>Fazer login</a>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="row g-3">
                                <div class="col-md">
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo *" value="">
                                    <div class="feedback-nome">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="cpfcnpj" name="cpfcnpj" placeholder="Cpf ou cnpj *" value="">
                                    <div class="feedback-cpfcnpj">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email *">
                                    <div class="feedback-email">
                                    </div>
                                </div>
                                <div class="col-md">
                                    <input type="telefone" class="form-control" name="telefone" id="telefone" placeholder="Telefone *">
                                    <div class="feedback-telefone">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border p-2 rounded mb-3">
                            <p class="fw-normal mb-2 fw-semibold">Forma de Pagamento</p>
                            <div class="forma_pagamento" id="payments">
                                <div class='list-group w-auto'>
                                    <?php
                                    $resultados = consulta_linhas_tb('tb_forma_pagamento', 'cl_ativo', 'S', 'cl_ativo_delivery', 'S');
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $qtd_parcela = null;
                                            $id = $linha['cl_id'];
                                            $descricao = utf8_encode($linha['cl_descricao']);
                                            $desconto = ($linha['cl_desconto']);
                                            $tipo_pagamento = ($linha['cl_tipo_pagamento_id']);
                                            $desconto = ($desconto != "" and $desconto > 0) ? "<small style='color:#2980b9'> $desconto" . "% de desconto</small>" : '';
                                            $parcelamento_sem_juros = utf8_encode($linha['cl_parcelamento_sem_juros']);
                                            $icone = consulta_tabela('tb_tipo_pagamento', 'cl_id', $tipo_pagamento, 'cl_icone');
                                            if (!empty($parcelamento_sem_juros)) {
                                                $qtd_parcela = "<small >Até $parcelamento_sem_juros parcelas sem juros</small>";
                                            }
                                    ?>
                                            <label href='#' class='list-group-item  list-group-item-action d-flex gap-3' style="cursor: pointer;" aria-current='true'>
                                                <input type='radio' name='payment' id="payment" class='payments' value='<?= $id; ?>'>
                                                <div class='row gap-2 w-100 justify-content-between'>
                                                    <div class="col-md">
                                                        <span class='fw-semibold'><?= $descricao ?> </span>
                                                    </div>
                                                    <div class="col-md text-md-end">
                                                        <span class="fw-semibold" style='color:#2980b9'><span><?= $icone; ?></span> <?= $qtd_parcela . " " . $desconto ?> </span>
                                                    </div>
                                                </div>

                                            </label>
                                    <?php

                                        }
                                    }
                                    ?>
                                </div>

                            </div>
                            <div class="feedback-payments"></div>
                        </div>
                        <div class="border p-2 rounded mb-2">
                            <p class="fw-normal mb-2 fw-semibold">Endereço</p>

                            <div class="information-cep mb-2">
                                <div class="row g-3 d-flex align-items-end" style="display: none;">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="cep" name="cep" autocomplete="off" placeholder="Cep *">
                                        <div class="feedback-cep">
                                        </div>
                                    </div>
                                    <div class="col-8"> <span> <a target="_blank" style="text-decoration: none;color:#A028FD" href="https://buscacepinter.correios.com.br/app/endereco/index.php">Não sei meu cep</a>
                                        </span></div>
                                </div>
                            </div>

                            <div class="mb-2 customer-address">
                                <div class="border d-flex">
                                    <div class="p-2 "><i class="bi bi-geo-alt-fill"></i></div>
                                    <div class="p-2 flex-grow-1">
                                        <p class="mb-0 logradouro"></p>
                                        <p class="mb-0"><span class="text-bold cep"></span> - <span class="bairro"></span> </p>
                                        <p class="mb-0"><span class="localidade"></span> - <span class="uf"></span></p>
                                    </div>
                                    <small class="p-2 fw-semibold">
                                        <a href="#" class="text-decoration-none alter-cep text-dark">Alterar</a></small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4 mb-2 customer-address-address" style="display: none;">
                                    <input type="text" class="form-control " id="logradouro" name="logradouro" autocomplete="off" placeholder="Endereço *">
                                    <div class="feedback-logradouro">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2 customer-address-neighborhood" style="display: none;">
                                    <input type="text" class="form-control " id="bairro" name="bairro" autocomplete="off" placeholder="Bairro *">
                                    <div class="feedback-bairro">
                                    </div>
                                </div>


                                <div class="col-md-3 mb-2">
                                    <input type="text" class="form-control  customer-address" id="numero" name="numero" autocomplete="off" placeholder="Nº Casa/Lote *">
                                    <div class="feedback-numero">
                                    </div>
                                </div>



                                <div class="col-md-5">
                                    <input type="text" class="form-control
                              customer-address" name="complemento" autocomplete="off" id="complemento" placeholder="Complemento (Opcional)">
                                    <div class="feedback-complemento">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-auto option-frete" id="option-frete"></div>
                                <div class="feedback-option-frete">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <?php
                                if (auth('') === false) {
                                ?>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" checked id="aceita_termos" name="aceita_termos">
                                        <label class="form-check-label" for="aceita_termos">
                                            Aceito os termos e condições <a href="?company&amp;rules=termsconditions">Veja</a>
                                        </label>
                                        <div class="feedback-aceita_termos"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
    <?php } else {
            include "../../component/cartProductAdd.php";
        }
    } else {
        include "../../component/cartProductAdd.php";
    } ?>
</div>

<script src="public/js/containers/checkout/checkout.js"> </script>