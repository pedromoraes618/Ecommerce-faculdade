<?php
$id = $linha['idproduto'];
$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);
$quantidade = ($linha['cl_quantidade']);
$variantes_opcao = ($linha['cl_variacao']); //coluan que comtem os id das variações

$codigo = ($linha['cl_codigo']);

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
$span_add = "<div class='card-span-add'>$span_condicao</div>";

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
if ($estoque > 0) {
    // $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";

    if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
        $valores = "<small class='original-price-promo text-muted text-decoration-line-through ' 
     style='font-size:0.7em'>" . real_format($preco_venda) . "</small>
<span class='promo-price fw-semibold'> " . real_format($preco_promocao * $quantidade) ."</span>";
        $total += $preco_promocao * $quantidade;
    } else {
        // Se não houver promoção, mostrar apenas o preço normal e centralizar
        $valores = "<span class='original-price fw-semibold'>" . real_format($preco_venda * $quantidade) . "</span>";
        $total += $preco_venda * $quantidade;
    }

?>
    <div class="card  bg-body-tertiary border-0 position-relative mb-2 ">
        <div class="row g-1 position-relative d-flex justify-content-start align-items-center">
            <div class="position-relative col-3">
                <!-- <img src="..." class="img-fluid rounded-start" alt="..."> -->
                <img class="img-thumbnail bg-body-tertiary  border-0 " width="100" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?>">
                <?= $span_add; ?>
            </div>
            <div class="col-9">
                <div class="card-body p-2">
                    <div class="text-start">
                        <p class="product-title card-title mb-2 lh-sm"><?= $titulo . " x " . $quantidade; ?> </p>
                        <p class="product-subtitle card-subtitle"><?= $referencia; ?></p>
                        <div class="product-extra-info card-info"><?= $span_variacao; ?></div>
                    </div>
                    <div class="price text-end"><?= $valores; ?></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>



