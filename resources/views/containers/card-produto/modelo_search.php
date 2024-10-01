<?php
$id = ($linha['cl_id']);
$codigo = ($linha['cl_codigo']);
$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);
$condicao = ($linha['cl_condicao']);
$produto_pai = ($linha['cl_produto_pai']);
$span_condicao = $condicao == "USADO" ? "Usado" : '';
$marca = utf8_encode($linha['cl_fabricante']);
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

$class_fav = verificaProdutoAuth($id)['qtd_fav'] > 0 ? 'fav-true' : '';
$span_cart = verificaProdutoAuth($id)['qtd_cart'] > 0 ? 'Remover' : 'Adicionar';

// var_dump(verificaProdutoAuth($id)['qtd_fav']);
// $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";
if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
    $valor = real_format($preco_promocao);
} else {
    // Se não houver promoção, mostrar apenas o preço normal e centralizar
    $valor = real_format($preco_venda);
}
/*produtos com variação recebem o preço no valor de a partir de */
$span_add_valor = $produto_pai == 1 ? "<small>A partir de </small><br>" : '';

?>
<div class="card bg-body-tertiary rounded-0 border-0 ">
    <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none  row position-relative d-flex pb-1 pt-1 align-items-center card-body ">
        <div class="col-2 col-md-auto position-relative">
            <img width="50" class="img-fluid" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?> ?>">
        </div>
        <div class="col-10 ">
            <div class="info mb-1 row">
                <div class="col-8">
                    <p class="title fw-medium mb-1"><?= $titulo; ?> </p>
                    <small class="subtitle text-muted"><?= $span_subtitle; ?></small>
                </div>
                <div class="valor col-4 text-end"><?= $span_add_valor . $valor; ?></div>
            </div>
        </div>
    </a>
</div>
<hr class="m-0">