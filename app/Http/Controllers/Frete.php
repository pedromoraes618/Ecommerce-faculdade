<?php
$nome_do_arquivo = __FILE__;


if (isset($_POST['form'])) {
    include "../../../db/conn.php";
    include "../../../helps/funcao.php";


    //Load Composer's autoloader
    $retornar = array();
    $acao = $_POST['acao'];
    $tipo = $_POST['tipo'];
    $nomeCookieFrete = "frete_lgbrd";
    $time_cookie = 30 * 24 * 60 * 60;
    $valor_total_venda = 0;
    $peso_total_produto = 0;
    $apiFrete = consulta_tabela('tb_parametros', 'cl_id', '86', 'cl_valor');
    $enderecoRetirada =  utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '91', 'cl_valor')); //retirada do produto

    if ($acao == "consultarFrete") { //consultar o frete, sem opção de selecionar
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = $value;
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $qtd_simulacao = 0; //iniciando variavel

        if (auth('') != false and $cep == "") {
            $cep = auth('')['dados_usuario']['cep'];
        }

        $buscarCep = buscar_cep($cep);
        if ($buscarCep['data']['status'] == false) {
            $retornar["errors"]["cep"] = $buscarCep['data']['message'];
        } else {
            if ($apiFrete == "kangu") {
                $dadosCep = $buscarCep['data']['response'];
                $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';
                $valor_total_venda = simularValoresFrete($produtoID, $codigo_nf)['valorTotalVenda'];
                $peso_mercadoria = simularValoresFrete($produtoID, $codigo_nf)['pesoMercadoria'];
                $alturaCaixa = simularValoresFrete($produtoID, $codigo_nf)['alturaCaixa'];
                $larguraCaixa = simularValoresFrete($produtoID, $codigo_nf)['larguraCaixa'];
                $comprimentoCaixa = simularValoresFrete($produtoID, $codigo_nf)['comprimentoCaixa'];

                $simular_frete =  simularFreteKangu(
                    $dadosCep,
                    $valor_total_venda,
                    $peso_mercadoria,
                    $alturaCaixa,
                    $larguraCaixa,
                    $comprimentoCaixa,
                    1
                );

                if ($simular_frete['data']['status'] == true) {
                    $simular_frete = $simular_frete['data']['response'];

                    if ($tipo == "consulta") { //cliente apenas irá consultar o frete
                        $html = "
                    <div class='list-group w-auto'>";
                        foreach ($simular_frete as $info) {
                            $idSimulacao = isset($info["idSimulacao"]) ? $info["idSimulacao"] : '';
                            $titulo =   isset($info["transp_nome"]) ? $info["transp_nome"] : '';
                            $prazoEnt = isset($info["prazoEnt"]) ? $info["prazoEnt"] : '';

                            $dtPrevEnt = isset($info["dtPrevEnt"]) ? formatDateB($info["dtPrevEnt"]) : '';
                            $dtPrevEntMin = isset($info["dtPrevEntMin"]) ? formatDateB($info["dtPrevEntMin"]) : '';

                            if (!empty($idSimulacao)) {
                                $qtd_simulacao++; //contabilizar a simulação de frete


                                $prazoEntMin = $prazoEnt - 1;
                                $valor = real_format($info["vlrFrete"]);
                                $text_success = $info["vlrFrete"] == 0 ? "text-success" : '';

                                if ($idSimulacao == "retirada") {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>Retirada na loja<br><span class='text-muted'>$enderecoRetirada</span></p>";
                                } elseif ($idSimulacao == "grtlocalizacao") {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>$titulo<br><small class='text-muted'>(Entrega em $prazoEntMin a $prazoEnt dias úteis)</small></p>";
                                } else {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>$titulo<br><small class='text-muted'>Chegará entre <strong>$dtPrevEntMin</strong> e <strong>$dtPrevEnt</strong></small></p>";
                                }

                                $borderGratis = ($idSimulacao == "grtestado" or  $idSimulacao == "grtdemaisestado" or $idSimulacao == "grtlocalizacao") ? 'border border-success ' : '';
                                $html .= "<label  href='#'  class='list-group-item list-group-item-action d-flex gap-3 ' aria-current='true'>
                        <!-- <input type='radio' name='selected_simulacao' class='selected_simulacao' value=''> -->
                        <div class='d-flex gap-2 w-100 justify-content-between' >
                            <div>
                              $p
                            </div>
                            <small class='opacity-10 text-nowrap'>$valor</small>
                        </div>
                    </label>";
                            }
                        }
                        $html .= "</div>";
                        if ($qtd_simulacao == 0) { //não foram indetificado fretes para o local ou api está inativa
                            $retornar["errors"]["option-frete"] = "Desculpe-nos pela inconveniência. Atualmente, estamos enfrentando
                    instabilidades em nosso site. Pedimos desculpas pelo transtorno e recomendamos que tente novamente mais tarde ou faça o pedido diretamente pelo nosso whatsapp.
                     Estamos trabalhando para resolver a situação o mais rápido possível. Agradecemos pela sua compreensão";
                        }
                    } elseif ($tipo == "opcao") { //cliente irá selecionar o frete
                        $html = "
                        <label  class='form-label'><i class='bi bi-truck'></i> Opção de frete</label>
                        <div class='list-group w-auto'>";
                        foreach ($simular_frete as $info) {
                            $idSimulacao = isset($info["idSimulacao"]) ? $info["idSimulacao"] : '';
                            $titulo =   isset($info["transp_nome"]) ? $info["transp_nome"] : '';
                            $prazoEnt = isset($info["prazoEnt"]) ? $info["prazoEnt"] : '';
                            // $dtPrevEnt = isset($info["dtPrevEnt"]) ? formatDateB($info["dtPrevEnt"]) : '';

                            $dtPrevEnt = isset($info["dtPrevEnt"]) ? formatDateB($info["dtPrevEnt"]) : '';
                            $dtPrevEntMin = isset($info["dtPrevEntMin"]) ? formatDateB($info["dtPrevEntMin"]) : '';


                            if (!empty($idSimulacao)) {
                                $qtd_simulacao++; //contabilizar a simulação de frete


                                $prazoEntMin = $prazoEnt - 1;
                                $valor = real_format($info["vlrFrete"]);
                                $text_success = $info["vlrFrete"] == 0 ? "text-success" : '';

                                if ($idSimulacao == "retirada") {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>Retirada na loja<br><span class='text-muted'>$enderecoRetirada</span></p>";
                                } elseif ($idSimulacao == "grtlocalizacao") {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>$titulo<br><small class='text-muted'>(Entrega em $prazoEntMin a $prazoEnt dias úteis)</small></p>";
                                } else {
                                    $p = "<p class='mb-0 fw-semibold $text_success'>$titulo<br><small class='text-muted'>Chegará entre <strong>$dtPrevEntMin</strong> e <strong>$dtPrevEnt</strong></small></p>";
                                }

                                $check = ($idSimulacao == "grtestado" || $idSimulacao == "grtdemaisestado" || $idSimulacao == "grtlocalizacao") ? 'checked' : '';
                                $borderGratis = ($idSimulacao == "grtestado" || $idSimulacao == "grtdemaisestado" || $idSimulacao == "grtlocalizacao") ? 'border border-success' : '';

                                $html .= "<label style='cursor: pointer;' class='list-group-item list-group-item-action d-flex align-items-center gap-3' aria-current='true'>
                                            <input type='radio' name='selected_simulacao' class='selected_simulacao form-check-input' value='$idSimulacao' >
                                            <div class='d-flex w-100 justify-content-between'>
                                                <div class='flex-grow-1'>
                                                    $p
                                                </div>
                                                <div class='d-flex align-items-center'>
                                                    <span class='badge bg-light text-dark px-3 py-2'>$valor</span>
                                                </div>
                                            </div>
                                        </label>";
                            }
                        }
                        $html .= "</div>";
                        if ($qtd_simulacao == 0) { //não foram indetificado fretes para o local ou api está inativa
                            $retornar["errors"]["option-frete"] = "Desculpe-nos pela inconveniência. Atualmente, estamos enfrentando
                    instabilidades em nosso site. Pedimos desculpas pelo transtorno e recomendamos que tente novamente mais tarde ou faça o pedido diretamente pelo nosso whatsapp.
                     Estamos trabalhando para resolver a situação o mais rápido possível. Agradecemos pela sua compreensão";
                        }
                    }
                } else {
                    $retornar["errors"]["option-frete"] = $simular_frete['data']['message'];
                }
            }
        }


        if (isset($retornar["errors"])) {
            $retornar["data"] = array("status" => false, "response" => $retornar["errors"]);
            echo json_encode($retornar); //retornando o array
            exit;
        }

        $retornar["data"] = array("status" => true, "cep" => $cep, "response" => $html, "simulacao" => $simular_frete, "dadosCep" => $dadosCep);

        // // Verificar se a operação foi executada com sucesso
        // if ($execute['data']['status']) { //executado som sucesso
        //     $retornar["data"] = array(
        //         "status" => true, "message" => "Produto adicionado ao carrinho com sucesso",
        //         "qtd_cart" => $qtd_cart
        //     );
        // } else {
        //     if ($execute['data']['type'] == "usuario") { //erro de usuário, validação
        //         $retornar["data"] = array(
        //             "status" => false,
        //             "message" => $execute['data']['message']
        //         );
        //     } else { //erro interno da aplicação
        //         $retornar["data"] = array(
        //             "status" => false,
        //             "message" => "Ops, o site está apresentando um mau funcionamento.
        //             Lamentamos o inconveniente, mas estamos trabalhando para resolver o 
        //             problema o mais rápido possível. Por carrinhoor, tente acessar novamente em alguns minutos"
        //         );
        //         // Registrar log do erro
        //         $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo -" . $execute['data']['message']);
        //         registrar_log($conecta, 'ecommerce', $data, $mensagem);
        //     }
        // }
    }

    if ($acao == "consultarDados") {
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = $value;
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }


        $buscarCep = buscar_cep($cep);
        if ($buscarCep['data']['status'] == false) {
            $retornar["errors"]["cep"] = $buscarCep['data']['message'];
        } else {
            $dadosCep = $buscarCep['data']['response'];
        }

        if (isset($retornar["errors"])) {
            $retornar["data"] = array("status" => false, "response" => $retornar["errors"]);
            echo json_encode($retornar); //retornando o array
            exit;
        }

        $retornar["data"] = array("status" => true,  "dadosCep" => $dadosCep);
    }

    // Encerre a conexão com o banco de dados
    mysqli_close($conecta);
    echo json_encode($retornar); //retornando o array
}
