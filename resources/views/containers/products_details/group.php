<?php include "../../../../app/Http/Controllers/ProductsDetails.php"; ?>

<div class="product-details-container mt-2 mt-md-4">
    <?php if ($consultar_produtos and $qtd_prd > 0) { ?>
        <div class="row g-md-5 mb-5">
            <div class="col-md-6 main-image order-md-2 order-1 mb-2">
                <!-- Coluna para a foto principal, descrição, preço, etc. -->
                <div class="main-image-1 rounded mb-2">
                    <a href="<?= $diretorio_imagem; ?>" data-lightbox="image-1" id="main-image-link">
                        <img class="img-thumbnail p-0 border-0" src="<?= $diretorio_imagem; ?>" alt="">
                    </a>
                </div>

                <div class="d-flex secondary-img order-md-1 order-2">
                    <?php
                    $resultados = consulta_linhas_tb_query($conecta, "select * from tb_imagem_produto where cl_codigo_nf='$codigo' order by cl_ordem asc");
                    if ($resultados) {
                        foreach ($resultados as $linha) {
                            $descricao = $linha['cl_descricao'];
                            $diretorio_img_secundario = "$url_init_img/$empresa/img/produto/$descricao";
                    ?>
                            <div class="left-img me-2 mb-2">
                                <img class="img-thumbnail p-0 border-0" src="<?= $diretorio_img_secundario; ?>" alt="">
                            </div>
                    <?php };
                    }; ?>
                </div>
                <!-- Adicione mais detalhes do produto conforme necessário -->
            </div>

            <div class="col-md-4   product-details-text order-md-3 order-4">
                <div class="mb-3 info-product">
                    <div class="mb-2"><?= $span_add; ?></div>
                    <h4 class="title fw-semibold mb-0"><?= $titulo; ?></h4>
                    <small class="subtitle text-muted"><?= !empty($referencia) ? "REFERÊNCIA: $referencia" : '' ?> </small>
                </div>
                <div class="price"><?= $valores; ?></div>

                <?php if ($qtd_parcela > 0) { ?>
                    <div class="text-muted text-danger">
                        <?php
                        $totalParcelado = real_format($total / $qtd_parcela);
                        echo "$icone_fpg_parcela $qtd_parcela" . "x de $totalParcelado sem juros";
                        ?>
                    </div>
                <?php } ?>

                <?php if ($descontoFpg > 0) { ?>
                    <div class="text-muted mb-1">
                        <?php
                        echo "$icone_fpg_desconto $descontoFpg" . "% de desconto pagando com $descricaoFpg";
                        ?>
                    </div>
                <?php } ?>

                <?php
                if (!empty($valida_variacoes)) {
                ?>
                    <hr>
                <?php
                    // Consulta que agrupa pelas descrições e ordena pela ordem de opção
                    $resultados = consulta_linhas_tb_query($conecta, "
        SELECT * FROM tb_variante_produto
        WHERE cl_produto_pai_codigo_nf='$codigo_variacao'
        GROUP BY cl_descricao, cl_valor
        ORDER BY cl_ordem_opcao,cl_id
    ");

                    if ($resultados) {
                        // Variável para armazenar o último grupo de descrição exibido
                        $descricao_atual = '';
                        $count = 0; // Contador para IDs únicos

                        foreach ($resultados as $linha) {
                            $variacao_id = utf8_encode($linha['cl_id']);
                            $descricao = utf8_encode($linha['cl_descricao']);
                            $tipo = utf8_encode($linha['cl_tipo']);
                            $valor = utf8_encode($linha['cl_valor']);

                            // Verifica se é do tipo lista
                            if ($tipo == "lista") {
                                // Verifica se mudou de grupo de variação (cl_descricao)
                                if ($descricao != $descricao_atual) {
                                    // Fecha o div anterior se houver
                                    if ($descricao_atual != '') {
                                        echo "</div>";
                                    }

                                    // Inicia um novo grupo de opções
                                    echo "<div class='mb-2'>";
                                    echo "<p class='fw-semibold mb-1'>" . $descricao . "</p>";

                                    // Atualiza a descrição atual
                                    $descricao_atual = $descricao;

                                    // Variável para marcar o primeiro rádio do grupo como checked se nenhuma variação estiver selecionada
                                    $primeiro_checked = false;
                                }

                                // Incrementa o contador para IDs únicos
                                $count++;
                                $radio_id = "option" . $count; // Cria um ID único para cada input

                                // Verifica se o ID da variação está no array $variantes_opcao para marcar o rádio como "checked"
                                if (in_array($variacao_id, $variantes_opcao)) {
                                    $checked = "checked";
                                    $primeiro_checked = true; // Marca que já foi selecionado
                                } elseif (!$primeiro_checked) {
                                    // Se for o primeiro item do grupo e ainda não tiver um marcado, marque como checked
                                    $checked = "checked";
                                    $primeiro_checked = true;
                                } else {
                                    $checked = "";
                                }

                                // Exibe as opções dentro do fieldset (radio buttons estilizados)
                                echo "<div class='d-inline-block me-2'>"; // Coloca os radios lado a lado
                                echo "<input type='radio' class='btn-check'   data-variacao='true' name='$descricao' id='$radio_id' autocomplete='off' $checked />";
                                echo "<label class='btn btn-sm btn-outline-secondary rounded' for='$radio_id'>" . $valor . "</label>";
                                echo "</div>"; // Fecha o div do radio button
                            }
                        }

                        // Fecha o último div se necessário
                        if ($descricao_atual != '') {
                            echo "</div>";
                        }
                    }
                }
                ?>
                <?php if ($estoque > 0) { ?>
                    <div class="quantity-controls d-flex justify-content-start align-items-center mb-md-3 mt-md-3">
                        <select id="qtd_prd" class="form-select">
                            <?php for ($i = 1; $i <= $estoque; $i++) { ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                        <button type="button" class="btn rounded add-cart ms-3" onclick="updateCart(this,<?= $id; ?>,
                        document.getElementById('qtd_prd').value,'adicionar')"> <i class="bi bi-cart-plus mx-2"></i>
                            <span class="span-cart-<?= $id; ?>">Adicionar</span></button>
                    </div>
                <?php } else {
                ?>
                    <div class="info d-flex justify-content-center align-items-center mt-3">
                        <div class="alert alert-danger text-center py-3 px-4 w-100" role="alert">
                            <strong>Produto Indisponível</strong>
                            <p class="mb-0">Este item está fora de estoque.</p>
                        </div>
                    </div>
                <?php
                } ?>
                <hr>
                <div id="warning"></div>
                <div class="feedback-warning mb-2"></div>

                <div class="section-cep"></div>
                <div class="d-flex justify-content-between product-details-footer text-muted  mb-2">
                    <div style="cursor: pointer;" class="<?= $class_fav; ?> add-fav-procts-details" <?php if ($class_fav == "") { ?> onclick="updateFavorite(this,<?= $id; ?>)" <?php } ?>>
                        <?= $text_fav; ?>
                    </div>
                    <div>Compartilhar:
                        <a class="text-dark" href="https://api.whatsapp.com/send?text=<?php echo $link_compartilhar; ?>">
                            <i class="bi bi-whatsapp"></i></a>
                        <a class="text-dark" href="https://facebook.com/sharer/sharer.php?u=<?php echo $link_compartilhar; ?>">
                            <i class="bi bi-facebook"></i></a>
                    </div>
                </div>


            </div>
        </div>

        <div class="row gap-5 mb-5 mt-3">
            <div class="col-md-7">
                <ul class="nav nav-tabs mb-2" id="myTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="descricao-tab" data-bs-toggle="tab" href="#descricao" role="tab" aria-controls="descricao" aria-selected="true">Descrição</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="question-tab" data-bs-toggle="tab" href="#question" role="tab" aria-controls="question" aria-selected="false">Perguntas e respostas</a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <!-- Aba de Descrição -->
                    <div class="tab-pane fade show active" id="descricao" role="tabpanel" aria-labelledby="descricao-tab">
                        <div class="description-details-product">
                            <?= $descricao_produto; ?>
                        </div>
                    </div>
                    <!-- Aba de Perguntas -->
                    <div class="tab-pane fade" id="question" role="tabpanel" aria-labelledby="review-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">Perguntas recentes</span>
                            <button type="button" class="btn btn-primary rounded-pill" id="productQuestion">Perguntar</button>
                        </div>
                        <div class="productQuestionList"></div>
                    </div>
                </div>
            </div>

        </div>

        <?php if (($consultar_produtos_similares) and $qtd_prd_similares > 0) { ?>
            <div class="row">
                <div class="col-md">
                    <div class="d-flex justify-content-between mb-1 highlighted-title">
                        <h4>Produtos similares</h4>
                        <!-- <p class="mb-0 text-body-tertiary fw-semibold"><?= $mensagem_grupo; ?></p> -->
                        <!-- <a href="">Ver tudo</a> -->
                    </div>

                    <div class="owl-carousel product-group-1">
                        <?php while ($linha = mysqli_fetch_assoc($consultar_produtos_similares)) { ?>
                            <?php include "../card-produto/modelo_1.php"; ?>
                        <?php }; ?>
                    </div>

                </div>
            </div>

    <?php };
    } else {
        include "../../component/productNotFound.php";
    } ?>
</div>


<script src="public/js/containers/products_details/group.js"> </script>