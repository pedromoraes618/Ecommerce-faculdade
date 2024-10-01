<?php
$id = $linha['idproduto'];
$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);
$codigo = ($linha['cl_codigo']);
$marca = utf8_encode($linha['cl_fabricante']);
$produto_pai = ($linha['cl_produto_pai']);

if ($opcao_subtitle == "1") { //referencia
    $span_subtitle = $referencia;
} elseif ($opcao_subtitle == "2") { //marca
    $span_subtitle = $marca;
} else {
    $span_subtitle = '';
}


$imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
$img_produto = consulta_tabela_query(
    $conecta,
    "select * from tb_imagem_produto where cl_codigo_nf ='$codigo' order by cl_ordem asc limit 1",
    'cl_descricao'
);
$diretorio_imagem  = $img_produto == "" ? "$url_init_img/$empresa/$imagem_produto_default" : "$url_init_img/$empresa/img/produto/$img_produto";


$condicao = ($linha['cl_condicao']);
$destaque = ($linha['cl_destaque']);
$span_destque = $destaque == "SIM" ? '<span class="item-emphasis bg-primary fw-semibold">DESTAQUE</span>' : '';
$span_condicao = $condicao == "USADO" ? "<span class='item-condition badge rounded-0 bg-danger fw-semibold'>$condicao</span>" : '';
$span_add = "<div class='card-span-add'>$span_destque $span_condicao</div>";
$span_estoque = $estoque < 1 ? "<span class='badge text-bg-dark'>Sem estoque</span>" : "";

// $span_cart = verificaProdutoAuth($id)['qtd_cart'] > 0 ? 'Remover' : 'Adicionar';


// $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";

if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
    $valores = "<small class='original-price-promo text-muted text-decoration-line-through'  style='font-size:0.7em'>" . real_format($preco_venda) . "</small>
   <span class='promo-price fw-semibold''> " . real_format($preco_promocao) .
        "</span>";
    $total += $preco_promocao;
} else {
    // Se não houver promoção, mostrar apenas o preço normal e centralizar
    $valores = "<span class='original-price fw-semibold''>" . real_format($preco_venda) . "</span>";
    $total += $preco_venda;
}
?>
<div class="card bg-body-tertiary border-0  mb-3 ">
    <div class="row position-relative d-flex align-items-center ">
        <a href="#" class="delete-product  text-dark" onclick="updateFavorite(this,<?= $id ?>)">
            <i class="bi bi-x-circle-fill mx-2 position-absolute top-0 end-0 pe-auto"></i>
        </a>
        <div class="col-4 position-relative">
            <!-- <img src="..." class="img-fluid rounded-start" alt="..."> -->
            <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none">
                <img class="border-0 border img-card-offcanvas" width="120" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?>">
            </a>
            <?= $span_add; ?>
        </div>
        <div class="col-8">
            <div class="card-body">
                <div class="mb-2">
                    <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none mb-2">
                        <p class="card-title mb-2 lh-sm"><?= $titulo; ?> </p>
                    </a>
                    <p class="card-subtitle"><?= $span_subtitle; ?></p>
                </div>
                <div class="price mb-2"><?= $valores; ?></div>
                <?php if ($produto_pai != 1) { ?>
                    <div>
                        <button type="button" class="btn  add-cart border" onclick="updateFavorite(this,<?= $id ?>),
                                    updateCart(this,<?= $id; ?>,1,'adicionar')"> <i class="bi bi-cart-plus mx-2"></i>
                            <span class="rounded span-cart-<?= $id; ?>">Adicionar</span>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>