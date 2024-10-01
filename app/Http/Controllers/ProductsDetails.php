<?php
$nome_do_arquivo = __FILE__;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_GET['component'])) {
    include "../../../../db/conn.php";
    include "../../../../helps/funcao.php";
    $usuarioID = auth('') !== false ? auth('')['id'] : '';

    $component = $_GET['component'];
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
    $query = "SELECT * From tb_duvida_loja where cl_produto_id = '$product_id' and cl_origem_mensagem='0' order by cl_data desc, cl_usuario_id desc ";
    $consulta_duvidas = mysqli_query($conecta, $query);
    if ($consulta_duvidas) {
        $qtd_duvidas = mysqli_num_rows($consulta_duvidas);
    } else {
        $erro = str_replace("'", "", mysqli_error($conecta));
        $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - page == products_details / erro - $erro");
        registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
    }
}

if (isset($_GET['page'])) {
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    if ($page == "products_details") {
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $products_details = $_GET['product-details'];

        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); // Diretório raiz do sistema gerenciador
        $nome_ecommerce = consulta_tabela('tb_parametros', 'cl_id', '64', "cl_valor");
        $time_cookie = 30 * 24 * 60 * 60;

        $opcao_subtitle = (consulta_tabela('tb_parametros', 'cl_id', 134, 'cl_valor')); //mostrar a refencia ou marca no card do produto
        $produto_pai = consulta_tabela('tb_produtos', 'cl_id', $products_details, 'cl_produto_pai');
        if ($produto_pai == 1) { //verificação se o produto selecionado tem variante, se sim, será feito uma alteração no id para o produto variante
            $codigo =  consulta_tabela('tb_produtos', 'cl_id', $products_details, 'cl_codigo');
            $products_details_variante =  consulta_tabela('tb_produtos', 'cl_codigo_nf_pai', $codigo, 'cl_id');
            $products_details = !empty($products_details_variante) ? $products_details_variante : $products_details;
        }


        $select = "SELECT prd.*,prd.cl_id as produtoid,grup.cl_descricao as grupo,cat.cl_descricao as categoria, cat.cl_mensagem, md.cl_descricao as und FROM tb_produtos 
        as prd left join tb_unidade_medida as md on md.cl_id = prd.cl_und_id 
        left join tb_subgrupo_estoque as cat on cat.cl_id = prd.cl_grupo_id
         left join tb_grupo_estoque as grup on grup.cl_id = cat.cl_grupo_id
         where prd.cl_id = $products_details and (prd.cl_tipo_id ='1' or prd.cl_tipo_id ='8') 
          and prd.cl_status_ativo = 'SIM'  ";
        $consultar_produtos = mysqli_query($conecta, $select); // Consulta
        if ($consultar_produtos) {
            $qtd_prd = mysqli_num_rows($consultar_produtos); // Quantidade de produtos
            if ($qtd_prd > 0) {
                $linha = mysqli_fetch_assoc($consultar_produtos);
                $id = ($linha['produtoid']);

                $codigo = ($linha['cl_codigo']);
                $imagem_capa = consulta_tabela("tb_imagem_produto", 'cl_descricao', $codigo . "_1", 'cl_descricao'); //imagem capa
                $extensao_img_capa = consulta_tabela("tb_imagem_produto", 'cl_descricao', $codigo . "_1", 'cl_extensao'); //imagem capa
                $class_fav = verificaProdutoAuth($id)['qtd_fav'] > 0 ? 'fav-true' : '';
                $text_fav = verificaProdutoAuth($id)['qtd_fav'] > 0 ? '<i class="bi bi-check fs-5"></i><span class="open-favorite">Ver favoritos</span></small>' :
                    '<i class="bi bi-heart"></i> Adicionar aos favoritos';

                $titulo = utf8_encode($linha['cl_descricao']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $grupo_id = utf8_encode($linha['cl_grupo_id']);
                $unidade = utf8_encode($linha['und']);
                $und_id = utf8_encode($linha['cl_und_id']);

                $grupo_pai = utf8_encode($linha['grupo']);
                $categoria = utf8_encode($linha['categoria']);
                $categoria = $grupo_pai . "-" . $categoria;
                $descricao_produto = utf8_encode($linha['cl_descricao_extendida_delivery']);
                $mensagem_grupo = utf8_encode($linha['cl_mensagem']);

                $preco_venda = ($linha['cl_preco_venda']);
                $preco_promocao = ($linha['cl_preco_promocao']);
                $data_validade_promocao = ($linha['cl_data_valida_promocao']);
                $estoque = ($linha['cl_estoque']);
                $condicao = ($linha['cl_condicao']);
                $destaque = ($linha['cl_destaque']);
                $codigo_nf_pai = ($linha['cl_codigo_nf_pai']);
                $variantes_opcao = ($linha['cl_variacao']); //coluan que comtem os id das variações
                $fabricante = ($linha['cl_fabricante']);
                
                if (!empty($variantes_opcao)) {
                    $variantes_opcao = array_map('trim', explode(',', $variantes_opcao));
                } else {
                    $variantes_opcao = array();
                }

                $span_condicao = $condicao == "USADO" ? "<span class='badge rounded-0  text-bg-danger'>$condicao</span>" : '';
                $span_destaque = $destaque == "SIM" ? '<span class="fw-semibold badge rounded-0 bg-primary">DESTAQUE</span>' : '';
                $span_formato =  "<span class='fw-semibold badge rounded-0 bg-secondary'>$unidade</span>";
                $span_add = "<div>$span_formato $span_destaque $span_condicao</div>";

                $link_compartilhar =  $_SERVER['SERVER_NAME'] . "/$nome_ecommerce/?product-details=$id&$titulo";



                $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
                $img_produto = consulta_tabela_query(
                    $conecta,
                    "select * from tb_imagem_produto where cl_codigo_nf ='$codigo' order by cl_ordem asc limit 1",
                    'cl_descricao'
                );
                if ($img_produto == "") {
                    $diretorio_imagem = "$url_init_img/$empresa/$imagem_produto_default";
                } else {
                    $diretorio_imagem = "$url_init_img/$empresa/img/produto/$img_produto";
                }

                // $span_cart = verificaProdutoAuth($id)['qtd_cart'] ? 'Remover' : 'Adicionar';
                if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
                    $valores = "<span class='promo-price  fs-4' > " . real_format($preco_promocao) . "</span>  <small class='original-price-promo text-muted text-decoration-line-through fs-5'>" . real_format($preco_venda) . "</small>";
                    $total = $preco_promocao;
                } else {
                    // Se não houver promoção, mostrar apenas o preço normal e centralizar
                    $valores = "<span class='original-price  fs-4'>" . real_format($preco_venda) . "</span>";
                    $total = $preco_venda;
                }


                // Verifica se o cookie "product_visit" existe, armazenar produtos visitados pelo usuário em um cookie
                if (isset($_COOKIE["product_visit"])) {

                    $product_visit_cookie = json_decode($_COOKIE["product_visit"], true);

                    // Verifica se o produto já está no array de visitas
                    $productIndexVisit = array_search($id, array_column($product_visit_cookie, 'id'));

                    if ($productIndexVisit === false) {
                        // O produto não está no array, adicionando ao cookie
                        $product_visit_cookie[] = array("id" => $id);
                        // Atualiza o cookie com o novo valor
                        setcookie("product_visit", json_encode($product_visit_cookie), time() + $time_cookie, "/");

                        $valida_visualizacao_produto = consulta_tabela('tb_metricas_produtos', 'cl_produto_id', $id, 'cl_id'); // Verificar se já existe o produto na tabela
                        if (!empty($valida_visualizacao_produto)) {
                            // Realizar o update na tabela de métricas dos produtos na coluna visualização
                            $query = "UPDATE `tb_metricas_produtos` SET `cl_visualizacao` = `cl_visualizacao` + 1 WHERE `cl_id` = '$valida_visualizacao_produto'";
                            $update = mysqli_query($conecta, $query);
                        } else {
                            // Adicionar info na métrica de produtos
                            $query = "INSERT INTO `tb_metricas_produtos` (`cl_visualizacao`, `cl_produto_id`) VALUES ('1', '$id')";
                            $insert = mysqli_query($conecta, $query);
                        }
                    }
                } else {
                    // Inicializa o array de produtos visitados
                    $product_visit_cookie = array(array("id" => $id));
                    // Define o cookie com o array inicial
                    setcookie("product_visit", json_encode($product_visit_cookie), time() + $time_cookie, "/");

                    $valida_visualizacao_produto = consulta_tabela('tb_metricas_produtos', 'cl_produto_id', $id, 'cl_id'); // Verificar se já existe o produto na tabela
                    if (!empty($valida_visualizacao_produto)) {
                        // Realizar o update na tabela de métricas dos produtos na coluna visualização
                        $query = "UPDATE `tb_metricas_produtos` SET `cl_visualizacao` = `cl_visualizacao` + 1 WHERE `cl_id` = '$valida_visualizacao_produto'";
                        $update = mysqli_query($conecta, $query);
                    } else {
                        // Adicionar info na métrica de produtos
                        $query = "INSERT INTO `tb_metricas_produtos` (`cl_visualizacao`, `cl_produto_id`) VALUES ('1', '$id')";
                        $insert = mysqli_query($conecta, $query);
                    }
                }



                /*consultar produto Similares */
                $select = "SELECT prd.*,prd.cl_id as produtoid, cat.cl_mensagem   FROM tb_produtos 
        as prd left join tb_subgrupo_estoque as cat on cat.cl_id = prd.cl_grupo_id
            where ( prd.cl_grupo_id ='$grupo_id' or prd.cl_referencia ='$referencia' ) and prd.cl_estoque >0 
            and  prd.cl_status_ativo = 'SIM' and prd.cl_tipo_id ='1' order by rand()";
                $consultar_produtos_similares = mysqli_query($conecta, $select); // Consulta
                if ($consultar_produtos_similares) {
                    $qtd_prd_similares = mysqli_num_rows($consultar_produtos_similares);
                } else {
                    $erro = str_replace("'", "", mysqli_error($conecta));
                    $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo -  consultar_produtos_similares / erro - $erro");
                    registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
                }



                /*CONSULTAR PARCELAS DISPONIVEL */
                $qtd_parcela = 0;
                $query = "SELECT max(cl_parcelamento_sem_juros) as qtd,cl_id,cl_tipo_pagamento_id FROM tb_forma_pagamento WHERE  cl_ativo_delivery='S' and cl_ativo='S' group by cl_id order by cl_parcelamento_sem_juros desc";
                $resultados = mysqli_query($conecta, $query);
                if ($resultados) {
                    $linha = mysqli_fetch_assoc($resultados);
                    $qtd_parcela = $linha['qtd'];
                    $tipo_pagamento = utf8_encode($linha['cl_tipo_pagamento_id']);
                    $icone_fpg_parcela = consulta_tabela('tb_tipo_pagamento', 'cl_id', $tipo_pagamento, 'cl_icone');
                }

                /*CONSULTAR POSIBILDADE DE DESCONTO */
                $descontoFpg = 0;
                $query = "SELECT max(cl_desconto) as desconto,cl_id,fpg.* FROM tb_forma_pagamento as fpg where cl_ativo_delivery='S' and cl_ativo='S' 
         group by cl_id order by cl_desconto desc";
                $resultados = mysqli_query($conecta, $query);
                if ($resultados) {
                    $linha = mysqli_fetch_assoc($resultados);
                    $descontoFpg = $linha['desconto'];
                    $descricaoFpg = utf8_encode($linha['cl_descricao']);
                    $tipo_pagamento = utf8_encode($linha['cl_tipo_pagamento_id']);
                    $icone_fpg_desconto = consulta_tabela('tb_tipo_pagamento', 'cl_id', $tipo_pagamento, 'cl_icone');
                }


                if (!empty($codigo_nf_pai)) {
                    $valida_variacoes = consulta_tabela('tb_produtos', 'cl_codigo', $codigo_nf_pai, 'cl_codigo');
                    $codigo_variacao = $valida_variacoes;
                } else {
                    /*variações */
                    $valida_variacoes = consulta_tabela('tb_produtos', 'cl_codigo_nf_pai', $codigo, 'cl_id');
                    $codigo_variacao = $codigo;
                }


                /*pixel */
                if (auth('') !== false) {
                    $dados = ['pagina' => "?product-details=true&id=$id"];
                    $dados_usuario = auth('')['dados_usuario'];
                    $dados = [
                        'dados_usuario' => $dados_usuario,
                        'dados' => $dados,
                        'produto' => [
                            'produtoID' => $id,
                            'descricao' => $titulo
                        ]
                    ];
                    pixel('viewContentDetProd', $dados);
                }
            }
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - page == products_details / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
        }
    }
}
// $ip = $_SERVER['REMOTE_ADDR'];
// $client_agent = $_SERVER['HTTP_USER_AGENT'];



if (isset($_POST['form'])) {
    include "../../../db/conn.php";
    include "../../../helps/funcao.php";

    require '../../../public/lib/vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../../public/lib/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../../public/lib/vendor/phpmailer/phpmailer/src/SMTP.php';

    $retornar = array();
    foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
        ${$name} = utf8_decode($value);
        ${$name} = str_replace("'", "", ${$name});
    }
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
    $codigo_nf = md5(uniqid(time())); //gerar um novo codigo para nf

    if ($acao == "productQuestion") { //review e comentario
        if (auth('') === false) { //usuário não está logado
            $retornar["data"] = array("status" => false, "authentication" => false);
            echo json_encode($retornar); //retornando o array
            exit;
        } else {
            mysqli_begin_transaction($conecta);
            if (empty($pergunta)) {
                $retornar["errors"]["pergunta"] = required("sua dúvida");
            }

            if (isset($retornar["errors"])) {
                $retornar["data"] = array("status" => false, "response" => $retornar["errors"]);
                echo json_encode($retornar); //retornando o array
                exit;
            }

            $usuarioID = auth('')['id'];
            $email_usuario = auth('')['dados_usuario']['email'];
            $nome_usuario = auth('')['dados_usuario']['nome'];

            $descricao_produto = utf8_encode(consulta_tabela('tb_produtos', 'cl_id', $product_id, 'cl_descricao'));

            $query = "INSERT INTO  `tb_duvida_loja` (`cl_data`, `cl_codigo_nf`, `cl_usuario_id`, `cl_origem_mensagem`,
             `cl_produto_id`, `cl_mensagem`) 
             VALUES ('$data', '$codigo_nf', '$usuarioID', '0', '$product_id', '$pergunta' )";
            $insert = mysqli_query($conecta, $query);
            if ($insert) {
                $retornar["data"] = array("status" => true, "message" => "Pergunta registrada com sucesso! ");
                $pergunta = utf8_encode($pergunta);

                $mail = new PHPMailer(true);

                $html = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2>Nova Pergunta de Cliente</h2>
                    <p>Você recebeu uma nova pergunta de um cliente sobre um produto da loja. Veja os detalhes abaixo:</p>
                    <p><strong>Produto:</strong> $descricao_produto, Código: $product_id </p>
                    <p><strong>Cliente:</strong> $nome_usuario</p>
                    <p><strong>Pergunta:</strong> $pergunta</p>
                    <p>Por favor, responda o mais breve possível.</p>
                    <p style='color: #888;'>Acesse a área do gerenciado para responder á essa pergunta.</p>
                </div>";
                sendEmailDefaultFromAdm($mail, "Dúvida sobre produto na loja", "Dúvida sobre produto na loja", $html);

                mysqli_commit($conecta);
            } else {
                mysqli_rollback($conecta);

                $retornar["data"] = array("status" => false, "message" => "Ops, o site está apresentando um mau funcionamento,
                Lamentamos o inconveniente, mas estamos trabalhando para resolver o problema o mais rápido possível. Por favor, tente acessar novamente em alguns minutos");
                $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - Tentativa sem sucesso de registrar dúvida ao produto de código $product_id");
                registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
            }
        }
    }
    echo json_encode($retornar);
}
