<?php
$id = $linha['idproduto'];
$titulo = utf8_encode($linha['cl_descricao']);
$referencia = utf8_encode($linha['cl_referencia']);
$preco_venda = ($linha['cl_preco_venda']);
$preco_promocao = ($linha['cl_preco_promocao']);
$data_validade_promocao = ($linha['cl_data_valida_promocao']);
$estoque = ($linha['cl_estoque']);

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
$span_add = "<div class='card-span-add'>$span_destque $span_condicao</div>";

// $span_cart = verificaProdutoAuth($id)['qtd_cart'] > 0 ? 'Remover' : 'Adicionar';


// $diretorio_imagem = "../../../../../$empresa/img/produto/$imagem_capa$extensao_img_capa";

if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
    $valores = "<p class='fw-semibold mb-0'>" . real_format($preco_promocao) . "</p>";
} else {
    // Se não houver promoção, mostrar apenas o preço normal e centralizar
    $valores = "<p class='fw-semibold mb-0'>" . real_format($preco_venda) . "</p>";
}
?>

<div class="card bg-body-tertiary border-0 p-0 card-prd-visit">
    <a href="?product-details=<?= $id; ?>&<?= $titulo; ?>" class="text-decoration-none text-dark">
        <div class="row position-relative d-flex align-items-center p-0">
            <div class="col-3 position-relative">
                <!-- <img src="..." class="img-fluid rounded-start" alt="..."> -->
                <img class="border-0 border img-card-offcanvas rounded" width="60" src='<?= $diretorio_imagem; ?>' alt="<?= $titulo; ?>">
            </div>
            <div class="col-9">
                <div class="card-body">
                    <div class="card-title mb-2 0h-sm">
                        <span><?= (strlen($titulo) > 20) ? substr($titulo, 0, 20) . '...' : $titulo; ?></span>
                    </div>
                    <div class="card-subtitle lh-sm mb-1"><span><?= $referencia; ?></span></div>
                    <div class="price"><?= $valores; ?></div>
                </div>
            </div>
        </div>
    </a>
</div>