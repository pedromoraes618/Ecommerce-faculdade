<?php
$id = $linha['idproduto'];
$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);
$quantidade = ($linha['cl_quantidade']);
$codigo = ($linha['cl_codigo']);
$produto_pai = ($linha['cl_produto_pai']);
$marca = utf8_encode($linha['cl_fabricante']);


$imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
$img_produto = consulta_tabela_query(
    $conecta,
    "select * from tb_imagem_produto where cl_codigo_nf ='$codigo' order by cl_ordem asc limit 1",
    'cl_descricao'
);
$diretorio_imagem  = $img_produto == "" ? "$url_init_img/$empresa/$imagem_produto_default" : "$url_init_img/$empresa/img/produto/$img_produto";

$registro += $quantidade;
$condicao = ($linha['cl_condicao']);
$destaque = ($linha['cl_destaque']);
$span_destque = $destaque == "SIM" ? '<span class="item-emphasis bg-primary fw-semibold">DESTAQUE</span>' : '';
$span_condicao = $condicao == "USADO" ? "<span class='item-condition badge rounded-0 bg-danger fw-semibold'>$condicao</span>" : '';
$span_add = "<div class='card-span-add'>$span_destque $span_condicao</div>";

// $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";
$span_estoque = $estoque < 1 ? "<span class='badge text-bg-dark'>Sem estoque</span>" : "";

if ($estoque > 0) {
    if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
        $valores = "<small class='original-price-promo text-muted text-decoration-line-through ' style='font-size:0.7em'>" . real_format($preco_venda) . "</small></br>
<span class='promo-price fw-semibold'> " . real_format($preco_promocao * $quantidade) .
            "</span>";
        $total += $preco_promocao * $quantidade;
    } else {
        // Se não houver promoção, mostrar apenas o preço normal e centralizar
        $valores = "<span class='original-price fw-semibold'>" . real_format($preco_venda * $quantidade) . "</span>";
        $total += $preco_venda * $quantidade;
    }
}


/*o que será mostrado no subtitle*/
if ($opcao_subtitle == "1") { //referencia
    $span_subtitle = $referencia;
} elseif ($opcao_subtitle == "2") { //marca
    $span_subtitle = $marca;
} else {
    $span_subtitle = '';
}

$variantes_opcao = ($linha['cl_variacao']); //coluan que comtem os id das variações
$span_variacao = '';
if (!empty($variantes_opcao)) {
    $variantes_opcao = array_map('trim', explode(',', $variantes_opcao));
    // Consulta que agrupa pelas descrições e ordena pela ordem de opção
    foreach ($variantes_opcao as $variante) {
        $resultados = consulta_linhas_tb_query($conecta, "
          SELECT * FROM tb_variante_produto
          WHERE cl_id='$variante'");
        if ($resultados) {
            foreach ($resultados as $linha) {
                $variacao_id = utf8_encode($linha['cl_id']);
                $descricao = utf8_encode($linha['cl_descricao']);
                $tipo = utf8_encode($linha['cl_tipo']);
                $valor = utf8_encode($linha['cl_valor']);
                $span_variacao .= " <small class='text-dark mb-1'>($descricao: $valor)</small>";
            }
        }
    }
}
?>
<div class="card bg-body-tertiary border-0 mb-3 ">
    <div class="row  position-relative d-flex align-items-center ">
        <a href="#" class="delete-product  text-dark" onclick="updateCart(this,<?= $id ?>,1,'remover')">
            <i class="bi bi-x-circle-fill mx-2 position-absolute top-0 end-0 pe-auto"></i>
        </a>
        <div class="col-4 position-relative">
            <!-- <img src="..." class="img-fluid rounded-start" alt="..."> -->
            <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none">
                <img width="120" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?> ?>">
            </a>
            <?= $span_add; ?>
        </div>

        <div class="col-8">
            <div class="card-body">
                <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none mb-2">
                    <div class="product-card-body mb-2">
                        <p class="card-title"><?= $titulo; ?> </p>
                        <p class="card-subtitle mb-1"><?= $span_subtitle . " " . $span_estoque; ?></p>
                        <div class="card-info"><?= $span_variacao; ?></div>
                    </div>
                </a>
                <?php if ($estoque > 0) { ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex " data-quantity="data-quantity">
                            <button class="btn btn-custom btn-sm btn-outline border border-1 p-0" data-type="minus" onclick="qtdCart(this,<?= $id ?>,<?= $quantidade - 1 ?>)"> <i style="font-size:1.5em;" class="bi bi-dash decrement"></i></button>

                            <input readonly class="form-control text-center input-spin-none bg-transparent
                          border-1 outline-none p-0" style="width:40px;" id="qtd_prd_cart" type="text" min="1" value="<?= $quantidade; ?>">

                            <button class="btn btn-sm btn-custom btn-outline border border-1  p-0" data-type="plus">
                                <i style="font-size:1.5em;" onclick="qtdCart(this,<?= $id ?>,<?= $quantidade + 1 ?>)" class="bi bi-plus increment"></i></button>
                        </div>
                        <div class="card-text"><?= $valores; ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>