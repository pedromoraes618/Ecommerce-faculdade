<?php
include "db/conn.php";
include "helps/funcao.php";


$empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
$diretorio_logo = "../../../$empresa/img/ecommerce/logo/logo.png";

$nomeEcommerce = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '64', 'cl_valor')); //nome do ecommerce
$nomeSite = utf8_encode(consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa')); //nome do ecommerce
$pixel_id = (consulta_tabela('tb_parametros', 'cl_id', '95', 'cl_valor')); //pixel id
$status_pixel = (consulta_tabela('tb_parametros', 'cl_id', '97', 'cl_valor')); //status do pixel facebook ativo (S) ou inativo (N)


$subTituloPagina = "";
if (isset($_GET['company'])) { //empresa
    $subTituloPagina = " - Informação";
} elseif (isset($_GET['products-filter'])) { //categorias
    $subTituloPagina = " - Filtro";
} elseif (isset($_GET['product-details'])) { //detalhes do produto
    $subTituloPagina = " - Detalhe";
} elseif (isset($_GET['forgot-password'])) { //resetar senha
    $subTituloPagina = " - Esqueçeu a senha";
} elseif (isset($_GET['confirm-email'])) { //confirmar email
    $subTituloPagina = " - Confirmar Email";
} elseif (isset($_GET['checkout'])) { //carrinho
    $subTituloPagina = " - Checkout";
} elseif (isset($_GET['confirm-order'])) { //carrinho
    $subTituloPagina = " - Confirmar";
} elseif (isset($_GET['order-completed'])) { //carrinho
    $subTituloPagina = " - Completo";
} elseif (isset($_GET['user'])) { //carrinho
    $subTituloPagina = " - Usuário";
}
$ecommerce_title = "$nomeSite/$nomeEcommerce$subTituloPagina";
$logo_icon = "$url_init/$empresa/img/ecommerce/logo/logo.png";



// $produtos = []; // Array para armazenar os produtos
// $query = " SELECT prd.*,prd.cl_id as produtoid,prd.cl_descricao as produto,sub.cl_descricao as categoria FROM tb_produtos as prd left join tb_subgrupo_estoque as sub on sub.cl_id = prd.cl_grupo_id where prd.cl_status_ativo ='SIM'";
// $resultados = consulta_linhas_tb_query($conecta, $query);
// if ($resultados) {
//     foreach ($resultados as $linha) {
//         $productID = ($linha['produtoid']);
//         $codigo = ($linha['cl_codigo']);
//         $descricao = utf8_encode($linha['produto']);
//         $valor_unitario = ($linha['cl_preco_venda']);
//         $categoria = utf8_encode($linha['categoria']);
//         $referencia = utf8_encode($linha['cl_referencia']);
//         $destaque = utf8_encode($linha['cl_destaque']);
//         $condicao = utf8_encode($linha['cl_condicao']);
//         $estoque = utf8_encode($linha['cl_estoque']);
//         $grupo_id = utf8_encode($linha['cl_grupo_id']);
//         $fabricante = utf8_encode($linha['cl_fabricante']);

//         $preco_promocao = ($linha['cl_preco_promocao']);
//         $data_validade_promocao = utf8_encode($linha['cl_data_valida_promocao']);

//         if ($estoque > 0) {
//             $estoque_tag = "InStock";
//         } else {
//             $estoque_tag = "OutOfStock";
//         }

//         if ($condicao == "USADO") {
//             $condicao_tag = "UsedCondition";
//         } else {
//             $condicao_tag = "UsedCondition";
//         }

//         $produto_descricao = $descricao . " - " . $referencia;

//         $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
//         $img_produto = consulta_tabela_query(
//             $conecta,
//             "select * from tb_imagem_produto where cl_codigo_nf ='$codigo' order by cl_ordem asc limit 1",
//             'cl_descricao'
//         );
//         $diretorio_imagem  = $img_produto == "" ? "$empresa/$imagem_produto_default" : "$empresa/img/produto/$img_produto";

//         if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //em promoção
//             $valor_unitario = $preco_promocao;
//         }

//         $url_produto = "$url_init/$nomeEcommerce/?product-details=$productID&$descricao";
//         $url_produto_img = "$url_init/$diretorio_imagem";
//         $moeda  = "BRL";

//         $produto = [
//             "productID" => $productID,
//             "name" => $produto_descricao,
//             "description" => $produto_descricao,
//             "url" => $url_produto,
//             "image" => $url_produto_img,
//             "brand" => $fabricante,
//             "offers" => [
//                 [
//                     "@type" => "Offer",
//                     "price" => $valor_unitario,
//                     "priceCurrency" => $moeda,
//                     "itemCondition" => "https://schema.org/$condicao_tag",
//                     "availability" => "https://schema.org/$estoque_tag"
//                 ]
//             ],
//             "additionalProperty" => [
//                 [
//                     "@type" => "PropertyValue",
//                     "propertyID" => $grupo_id,
//                     "value" => $categoria
//                 ]
//             ]
//         ];

//         // Adiciona o produto ao array de produtos
//         $produtos[] = $produto;
//     }
// }



// $datasetId = consulta_tabela('tb_parametros', 'cl_id', '95', 'cl_valor'); //id pixel
// $accessToken = consulta_tabela('tb_parametros', 'cl_id', '96', 'cl_valor'); //token pixel
// $ativoPixel = consulta_tabela('tb_parametros', 'cl_id', '97', 'cl_valor'); //pixel ativo