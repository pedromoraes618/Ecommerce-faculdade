<?php
$id = ($linha['produtoid']);

$codigo = ($linha['cl_codigo']);

$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);
$condicao = ($linha['cl_condicao']);
$destaque = ($linha['cl_destaque']);
$produto_pai = ($linha['cl_produto_pai']);
$marca = utf8_encode($linha['cl_fabricante']);

if ($opcao_subtitle == "1") { //referencia
    $span_subtitle = $referencia;
} elseif ($opcao_subtitle == "2") { //marca
    $span_subtitle = $marca;
} else {
    $span_subtitle = '';
}

$span_destque = $destaque == "SIM" ? '<span class="item-emphasis bg-primary fw-semibold">DESTAQUE</span>' : '';
$span_condicao = $condicao == "USADO" ? "<span class='item-condition badge rounded-0 bg-danger fw-semibold'>$condicao</span>" : '';
$span_add = "<div class='card-span-add'>$span_destque $span_condicao</div>";
$imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34"); //imagem default do item
$qtd_min_mais_procurado = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "125"); //quantidade min para ser considerado mais procurado

$img_produto = consulta_tabela_query(
    $conecta,
    "select * from tb_imagem_produto where cl_codigo_nf ='$codigo' order by cl_ordem asc limit 1",
    'cl_descricao'
);
$diretorio_imagem  = $img_produto == "" ? "$url_init_img/$empresa/$imagem_produto_default" : "$url_init_img/$empresa/img/produto/$img_produto";

$class_fav = verificaProdutoAuth($id)['qtd_fav'] > 0 ? 'fav-true' : '';
$span_cart = verificaProdutoAuth($id)['qtd_cart'] > 0 ? 'Remover' : 'Adicionar';

$span_add_valor = $produto_pai == 1 ? "<small>A partir de </small>" : '';
// var_dump(verificaProdutoAuth($id)['qtd_fav']);
// $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";
if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
    $valores = "<span class='original-price-promo text-decoration-line-through'>" . real_format($preco_venda) . "</span>
    <span class='promo-price'> " . real_format($preco_promocao) .
        "</span>";
    $total = $preco_promocao;
} else {
    // Se não houver promoção, mostrar apenas o preço normal e centralizar
    $valores = "<span class='original-price'>" . real_format($preco_venda) . "</span>";
    $total = $preco_venda;
}


/*mais procurados */
$span_mais_buscado = '';
$posicao_mais_buscados = consulta_linhas_tb_query($conecta, "SELECT (@row_number := @row_number + 1) AS position, tb.* FROM 
(SELECT * FROM `tb_metricas_produtos` WHERE cl_visualizacao >= $qtd_min_mais_procurado ORDER BY cl_visualizacao DESC LIMIT 4) AS tb, (SELECT @row_number := 0) AS rn ");
if ($posicao_mais_buscados) {
    foreach ($posicao_mais_buscados as $linha) {
        $posicao = ($linha['position']);
        $produto_id = ($linha['cl_produto_id']);
        if ($produto_id == $id) {
            $span_mais_buscado = "<div class='card-span-footer'><span class='item-most-searched badge rounded-0 bg-danger fw-semibold'>$posicao" . "º Nos Mais Procurados</span></div>";
        }
    }
}

/*CONSULTAR PARCELAS DISPONIVEL */
$qtd_parcela = 0;
$query = "SELECT max(cl_parcelamento_sem_juros) as qtd,cl_id,cl_tipo_pagamento_id FROM tb_forma_pagamento WHERE  cl_ativo_delivery='S' and cl_ativo='S' group by cl_id order by cl_parcelamento_sem_juros desc";
$resultados = mysqli_query($conecta, $query);
if ($resultados) {
    $linha = mysqli_fetch_assoc($resultados);
    $qtd_parcela = $linha['qtd'];
}


?>

<div class="item position-relative shadow-sm">
    <div class="product-card card border-0">
        <div class="product-image-wrapper text-decoration-none img product-card-header position-relative">
            <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>">
                <img class="img-thumbnail rounded-0 p-0 border-0" src='<?= $diretorio_imagem  ?>' alt=" <?= $titulo; ?>">
            </a>
            <?= $span_add; ?>
        </div>

        <div class="product-info product-details-text p-2 product-card-body">
            <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none">
                <p class="product-title card-title mb-2 lh-sm"><?= $titulo;  ?></p>
            </a>
            <p class="product-subtitle card-subtitle text-muted mb-2"><?= $span_subtitle; ?></p>
            <div class="product-price price text-dark mb-2">
                <div>
                    <?= $span_add_valor . $valores; ?>
                </div>
                <?php if ($qtd_parcela > 0) { ?>
                    <div class="price-installment text-muted text-danger">
                        <small>
                            <?php
                            $totalParcelado = real_format($total / $qtd_parcela);
                            echo "$qtd_parcela" . "x de $totalParcelado sem juros";
                            ?>
                        </small>
                    </div>
                <?php } ?>
            </div>
            <div class="product-extra-info info-add">
                <?= $span_mais_buscado; ?>
            </div>
        </div>

        <div class="d-flex justify-content-center product-card-footer bg-body-tertiary">
            <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="btn-card btn-card-left"><i class="bi bi-eye"></i> Detalhes</a>
            <?php if ($produto_pai != 1) { ?>
                <a href="#" class="btn-card btn-card-right " onclick="updateCart(this,<?= $id; ?>,1,'adicionar')"><i class="bi bi-cart-plus "></i> Comprar</a>
            <?php } ?>
        </div>
    </div>

    <div class="favorite-icon mb-3 <?= $class_fav; ?>" onclick="updateFavorite(this,<?= $id; ?>)">
        <i class="bi bi-heart-fill"></i>
    </div>

</div>