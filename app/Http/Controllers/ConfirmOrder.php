<?php

$nome_do_arquivo = __FILE__;


if (isset($_POST['form'])) {
    include "../../../db/conn.php";
    include "../../../helps/funcao.php";


    $retornar = array();
    $acao = $_POST['acao'];
    $ambientePagamento = consulta_tabela('tb_parametros', 'cl_id', '70', 'cl_valor');
    $tokenPagamentoHomologacao = consulta_tabela('tb_parametros', 'cl_id', '71', 'cl_valor');
    $tokenPagamentoProducao = consulta_tabela('tb_parametros', 'cl_id', '72', 'cl_valor');
    $pixel_status = consulta_tabela('tb_parametros', 'cl_id', 97, 'cl_valor'); //verificar o status do pixel
    $dados_pixel = []; //dadis pixel api conversão
    $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

    //$tokenPagamentoProducao = "APP_USR-2348807058961-021415-67c5f595783c7472179428152ab67b67-1682207570";

    if ($ambientePagamento == "1") { //homologacao
        $tokenPagamento = $tokenPagamentoHomologacao;
    } elseif ($ambientePagamento == "2") { //producao
        $tokenPagamento = $tokenPagamentoProducao;
    }

    if ($acao == "create") { //crição do pedido
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $pedidoID = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_id');
        $valor_produto = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_valor_produto');
        $status_pagamento = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_status_pagamento');
        $status_compra = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_status_compra');
        $valida_transportadora = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_id_simulacao_frete');
        $data_expirar = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_data_expirar');
        $pagamento_id = consulta_tabela('tb_pedido_loja', 'cl_codigo_nf', $codigo_nf, 'cl_pagamento_id');
        $pedido = rand(100000000000, 999999999999);

        if (empty($codigo_nf)) {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>
                Pedido não encontrado. Se você estiver enfrentando algum problema com o seu pedido, por favor, entre em contato conosco ou refaça o pedido para que possamos ajudá-lo a concluí-lo                </div>
              </div>";
        } elseif ($valor_produto == 0) {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>O seu carrinho está vazio!</div>
              </div>";
        } elseif ($status_compra == "CANCELADO") {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>Esse pedido foi Cancelado!</div>
              </div>";
        } elseif ($status_pagamento == "approved") {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>Esse pedido já foi concluido!</div>
              </div>";
        } elseif ($valida_transportadora == "") {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>É necessario selecionar uma opção de frete</div>
              </div>";
        } elseif ((!empty($data_expirar) and ($data >= $data_expirar))) {
            $status["errors"]['warning'] = "
            <div class='alert alert-warning' role='alert'>
                <div>
                O tempo para finalizar o seu pedido expirou. Por favor, refaça o pedido para continuar</div>
              </div>";
        }

        if (isset($status["errors"]['warning'])) {
            $retornar["data"] = array("status" => false, "response" => $status["errors"]);
            echo json_encode($retornar); //retornando o array
            exit;
        }


        if (!empty($codigo_nf) and  !empty($pagamento_id)) { //gerar um novo pagamento de um pedido já existente
            mysqli_begin_transaction($conecta);


            $query_pedido = "INSERT INTO `tb_pedido_loja` (
    `cl_pedido`, `cl_codigo_nf`, `cl_data`, `cl_usuario_id`, `cl_nome`, `cl_cpf_cnpj`, 
    `cl_email`, `cl_telefone`, `cl_cep`, `cl_cidade`, `cl_estado`, `cl_endereco`, 
    `cl_bairro`, `cl_numero`, `cl_complemento`, `cl_valor_frete`, `cl_valor_produto`, 
    `cl_desconto`, `cl_valor_cupom`, `cl_valor_liquido`, `cl_cupom`, 
    `cl_pagamento_id_interno`, `cl_status_compra`, `cl_transportadora`, `cl_data_expirar`, 
    `cl_id_simulacao_frete`, `cl_fbp_pixel`, `cl_fbc_pixel`
)
SELECT 
    '$pedido', 
    '$codigo_nf_novo', 
    '$data', -- Usando a data atual para o novo registro
    `cl_usuario_id`, 
    `cl_nome`, 
    `cl_cpf_cnpj`, 
    `cl_email`, 
    `cl_telefone`, 
    `cl_cep`, 
    `cl_cidade`, 
    `cl_estado`, 
    `cl_endereco`, 
    `cl_bairro`, 
    `cl_numero`, 
    `cl_complemento`, 
    `cl_valor_frete`, 
    `cl_valor_produto`, 
    `cl_desconto`, 
    `cl_valor_cupom`, 
    `cl_valor_liquido`, 
    `cl_cupom`, 
    `cl_pagamento_id_interno`, 
    `cl_status_compra`, 
    `cl_transportadora`, 
    `cl_data_expirar`, 
    `cl_id_simulacao_frete`, 
    `cl_fbp_pixel`, 
    `cl_fbc_pixel`
FROM `tb_pedido_loja`
WHERE `cl_codigo_nf` = '$codigo_nf' ";
            $insert_pedido = mysqli_query($conecta, $query_pedido);
            if ($insert_pedido) {
                $pedidoID  = mysqli_insert_id($conecta); //gerar um novo pedido pois foi gerando um novo pagamento_id -- correção de um bug encontrado no dia 09/09/2024
            }

            $query_item = "INSERT INTO `tb_produto_pedido_loja` (
    `cl_codigo_nf`, `cl_data`, `cl_produto_id`, `cl_descricao`, `cl_referencia`, 
    `cl_quantidade`, `cl_valor` ) SELECT 
    '$codigo_nf_novo',
    '$data',
    `cl_produto_id`, 
    `cl_descricao`, 
    `cl_referencia`, 
    `cl_quantidade`, 
    `cl_valor`
    FROM `tb_produto_pedido_loja`
    WHERE `cl_codigo_nf` = '$codigo_nf'";
            $insert_item_pedido = mysqli_query($conecta, $query_item);
            if ($insert_pedido and $insert_item_pedido) {
                mysqli_commit($conecta);
            } else {
                mysqli_rollback($conecta);
                $retornar["data"] = array("status" => false, "message" => "Ops, o site está apresentando um mau funcionamento,
                Lamentamos o inconveniente, mas estamos trabalhando para resolver o problema o mais rápido possível. Por favor, tente acessar novamente em alguns minutos");

                $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - Erro crítico: falha ao tentar gerar um novo pedido a partir de um pedido existente.");
                registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
                echo json_encode($retornar);
                exit;
            }
        } elseif ($codigo_nf != "") {
            $query = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd left join tb_forma_pagamento as fpg on fpg.cl_id = pd.cl_pagamento_id_interno
             where cl_codigo_nf = '$codigo_nf'";
            $consulta = mysqli_query($conecta, $query);
            if ($consulta) {
                $qtd_registro = mysqli_num_rows($consulta);
                if ($qtd_registro > 0) {
                    $linha = mysqli_fetch_assoc($consulta);
                    $nome = utf8_encode($linha['cl_nome']);
                    $email = ($linha['cl_email']);
                    $cpfcnpj = ($linha['cl_cpf_cnpj']);
                    $telefone = ($linha['cl_telefone']);
                    $endereco = utf8_encode($linha['cl_endereco']);
                    $bairro = utf8_encode($linha['cl_bairro']);
                    $numero = utf8_encode($linha['cl_numero']);
                    $complemento = utf8_encode($linha['cl_complemento']);
                    $cep = utf8_encode($linha['cl_cep']);
                    $cidade = utf8_encode($linha['cl_cidade']);
                    $estado = utf8_encode($linha['cl_estado']);
                    $forma_pagamento = ($linha['formapagamento']);
                    $transportadora = utf8_encode($linha['cl_transportadora']);
                    $pagamento_id_interno = ($linha['cl_pagamento_id_interno']);
                    $cupom = ($linha['cl_cupom']);
                    $fbp = ($linha['cl_fbp_pixel']);
                    $fbc = ($linha['cl_fbc_pixel']);


                    $valor_frete = ($linha['cl_valor_frete']);
                    $valor_produto = ($linha['cl_valor_produto']);
                    $valor_desconto = ($linha['cl_desconto']);
                    $valor_liquido = ($linha['cl_valor_liquido']);

                    $produtos = auth('') != false ? auth('') : cookieAuth($codigo_nf);
                    $produtosCart = $produtos['produtos_cart'];

                    $currency = "BRL"; // Substitua pela moeda real do pedido
                    // Preparar o array de produtos
                    $contents = [];
                    foreach ($produtosCart as $item) {
                        $contents[] = [
                            'id' => $item['cl_produto_id'], // Substitua pelo ID real do produto
                            'quantity' => $item['cl_quantidade'], // ou qualquer lógica para definir a quantidade
                            // Adicione outros campos conforme necessário para cada item do produto
                        ];
                    }

                    $dados_pixel = [
                        'user_data' => [
                            "external_id" => hash('sha256', $codigo_nf),
                            "fn" => hash('sha256', $nome),
                            "em" => hash('sha256', $email),
                            "zp" =>  hash('sha256', $cep),
                            "ph" => hash('sha256', $telefone),
                            "ct" =>  hash('sha256', $cidade),
                            "st" =>  hash('sha256', $estado),
                            "fbp" => $fbp,
                            "fbc" => $fbc,
                            "client_user_agent" =>  hash('sha256', $_SERVER['HTTP_USER_AGENT']),
                            "client_ip_address" =>  hash('sha256', $_SERVER['REMOTE_ADDR']),
                        ],
                        'pagina' => '?checkout',
                        "valor_total" => $valor_liquido,
                        "forma_pagamento" => utf8_encode(strtolower($forma_pagamento)),
                        "produtos" => ($contents), // Certifique-se de que $produtosCart é um array válido
                        "pixel_status" => $pixel_status,
                    ];
                }
            }
        }





        $gerarPagamentoStatus = gerarPagamentoMercadoPago($tokenPagamento, $pedidoID)['data']['status'];
        if ($gerarPagamentoStatus == true) {
            $retornar["data"] = array("status" => true, "dados_pixel" => $dados_pixel, "link_externo" => gerarPagamentoMercadoPago($tokenPagamento, $pedidoID)['data']['link_externo']);
        } else {
            $retornar["data"] = array("status" => false, "message" => "Ops, o site está apresentando um mau funcionamento,
             Lamentamos o inconveniente, mas estamos trabalhando para resolver o problema o mais rápido possível. Por favor, tente acessar novamente em alguns minutos");
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - " . gerarPagamentoMercadoPago($tokenPagamento, $pedidoID)['data']['message']);
            registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
        }
    }

    mysqli_close($conecta);
    echo json_encode($retornar); //retornando o array
}




if (isset($_GET['containers'])) {
    $container = $_GET['containers'];
    if ($container == "confirm_order") {

        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
        $email_empresa = consulta_tabela("tb_parametros", "cl_id", '74', 'cl_valor');

        $estado_empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_estado'); //estado da empresa

        $freteGratis = consulta_tabela('tb_parametros', 'cl_id', '87', 'cl_valor');
        $freteCondicaoValorEstado = consulta_tabela('tb_parametros', 'cl_id', '88', 'cl_valor'); //FRETE GRATIS PARA DENTRO DO ESTADO
        $freteCondicaoValorForaEstado = consulta_tabela('tb_parametros', 'cl_id', '89', 'cl_valor'); //FRETE GRATIS PARA FORA DO ESTADO

        $codigo_nf = isset($_GET['code']) ? $_GET['code'] : '';
        if (auth('') != false) {
            $dados_usuario = auth($codigo_nf); // Supondo que a função auth('') retorna os dados do usuário e dos produtos
        } else {
            $dados_usuario = cookieAuth($codigo_nf);
        }

        $qtd_registro = 0;
        $query = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = pd.cl_pagamento_id_interno where pd.cl_codigo_nf = '$codigo_nf' ";
        $consulta = mysqli_query($conecta, $query);
        if ($consulta) {
            $qtd_registro = mysqli_num_rows($consulta);
            if ($qtd_registro > 0) {
                $linha = mysqli_fetch_assoc($consulta);
                $pedido = ($linha['cl_pedido']);
                $nome = utf8_encode($linha['cl_nome']);
                $email = ($linha['cl_email']);
                $cpfcnpj = ($linha['cl_cpf_cnpj']);
                $telefone = ($linha['cl_telefone']);
                $endereco = utf8_encode($linha['cl_endereco']);
                $bairro = utf8_encode($linha['cl_bairro']);
                $numero = utf8_encode($linha['cl_numero']);
                $complemento = utf8_encode($linha['cl_complemento']);
                $cep = ($linha['cl_cep']);
                $cidade = utf8_encode($linha['cl_cidade']);
                $estado = utf8_encode($linha['cl_estado']);
                $transportadora = utf8_encode($linha['cl_transportadora']);
                $formapagamento = utf8_encode($linha['formapagamento']);


                $valor_frete = ($linha['cl_valor_frete']);
                $valor_produto = ($linha['cl_valor_produto']);
                $valor_desconto = ($linha['cl_desconto']);
                $valor_liquido = ($linha['cl_valor_liquido']);
                $status_compra = utf8_encode($linha['cl_status_compra']);
                $status_pagamento = utf8_encode($linha['cl_status_pagamento']);
                $valor_cupom = ($linha['cl_valor_cupom']);

                if ($valor_frete == 0) {
                    $valor_frete = "Grátis";
                }
            }
        }
    }
}
