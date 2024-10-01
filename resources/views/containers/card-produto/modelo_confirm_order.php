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
$span_add = "<div class='card-span-add'>$span_condicao</div>";



$registro += $quantidade;
if ($estoque > 0) {
    // $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";
    if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
        $valores = "<small class='original-price-promo text-decoration-line-through ' 
style='font-size:0.7em'>" . real_format($preco_venda) . "</small>
<span class='promo-price fw-semibold'> " . real_format($preco_promocao * $quantidade) .
            "</span>";
        $total += $preco_promocao * $quantidade;
    } else {
        // Se não houver promoção, mostrar apenas o preço normal e centralizar
        $valores = "<span class='original-price fw-semibold'>" . real_format($preco_venda * $quantidade) . "</span>";
        $total += $preco_venda * $quantidade;
    }
?>

    <div class="card  bg-body-tertiary border-0  mb-2 ">
        <div class="row g-1 position-relative d-flex justify-content-start align-items-center">
            <div class="col-3">
                <img class="img-thumbnail  bg-body-tertiary  border-0" width="100" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?>">
                <?= $span_add; ?>
            </div>

            <div class="col-9">
                <div class="card-body p-2">
                    <div class="text-start">
                        <p class="card-title mb-2 lh-sm"><?= $titulo . " x " . $quantidade; ?> </p>
                        <p class="card-subtitle"><?= $referencia; ?></p>
                    </div>
                    <div class="price text-end"><?= $valores; ?></div>
                </div>
            </div>
        </div>
    </div>
<?php
};
