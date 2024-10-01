<?php
$nome_do_arquivo = __FILE__;

$cookie = 0;
if (isset($_GET['page'])) {
    $containers = isset($_GET['containers']) ? $_GET['containers'] : '';
    $layouts = isset($_GET['layouts']) ? $_GET['layouts'] : '';
    if ($containers == "baner_1") { //baner
        include "../../../db/conn.php";
        include "../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
        $status_baner = consulta_tabela('tb_parametros', 'cl_id', '103', 'cl_valor'); //verificar se o baner está ativo ou não
    } elseif ($containers == "products") { //produtos
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema gerenciador
        $diferencia_dias_lancamento = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "41");
        $quantidade_prd_min_mais_buscados = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "125"); //valor minimo para considerar produtos mais buscados

        /*sessao*/
        $limite_produtos_sessao = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "105"); //limite de prouto por sessão
        $secao_novidades = consulta_tabela('tb_parametros', 'cl_id', '107', 'cl_valor'); //verifica se está habiltado
        $secao_navegar_marcadores = consulta_tabela('tb_parametros', 'cl_id', 140, 'cl_valor'); //verifica se está habiltado
        $secao_desconto = consulta_tabela('tb_parametros', 'cl_id', '108', 'cl_valor'); //verifica se está habiltado
        $secao_mais_buscados = consulta_tabela('tb_parametros', 'cl_id', '123', 'cl_valor'); //verifica se está habiltado

        /*titulo seção*/
        $titulo_secao_novidade = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '113', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_desconto = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '112', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_catalogo = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '115', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_mais_buscados = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '124', 'cl_valor')); //verifica se está habiltado

        $opcao_subtitle = (consulta_tabela('tb_parametros', 'cl_id', 134, 'cl_valor')); //mostrar a refencia ou marca no card do produto


        /*query */
        /*produtos com lançamentos */
        $select = "SELECT prd.*,prd.cl_id as produtoid from tb_produtos as prd  
        left join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
        where prd.cl_status_ativo ='SIM' and ( (prd.cl_estoque > 0 and prd.cl_produto_pai = 0) or ( prd.cl_produto_pai = 1 ) )
         and prd.cl_tipo_id ='1' 
     and prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', 
     INTERVAL '$diferencia_dias_lancamento' DAY) order by  prd.cl_data_cadastro desc, prd.cl_destaque desc, mt.cl_visualizacao desc, cl_fixo  desc   LIMIT $limite_produtos_sessao ";
        $consulta_produtos_novidades = mysqli_query($conecta, $select);
        if ($consulta_produtos_novidades) {
            $qtd_prd_lancamento = mysqli_num_rows($consulta_produtos_novidades);
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / produtos lançamentos / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); //registrar log do erro
        }


        /*produtos com desconto */
        $select = "SELECT prd.*,prd.cl_id as produtoid from tb_produtos as prd
                left join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
 where prd.cl_status_ativo ='SIM' and prd.cl_tipo_id ='1'  and 
         prd.cl_preco_promocao > 0 and ( (prd.cl_estoque > 0 and prd.cl_produto_pai = 0) or ( prd.cl_produto_pai = 1 ) )
        and prd.cl_data_valida_promocao >= '$data_lancamento' order by prd.cl_destaque desc, mt.cl_visualizacao desc, prd.cl_data_cadastro desc  LIMIT $limite_produtos_sessao";
        $consulta_produtos_desconto = mysqli_query($conecta, $select);
        if ($consulta_produtos_desconto) {
            $qtd_prd_desconto = mysqli_num_rows($consulta_produtos_desconto); //qtd dos produtos que estão com desconto
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / produtos com desconto / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); //registrar log do erro
        }

        /*catalogo */
        $select = "SELECT prd.*,prd.cl_id as produtoid  FROM tb_produtos AS prd 
        left join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
        WHERE prd.cl_status_ativo = 'SIM' and prd.cl_tipo_id ='1'  and ( (prd.cl_estoque > 0 and prd.cl_produto_pai = 0) or ( prd.cl_produto_pai = 1 ) )
        order by RAND(), prd.cl_data_cadastro desc, cl_fixo desc,prd.cl_destaque desc,  mt.cl_visualizacao desc  LIMIT $limite_produtos_sessao ";
        $consulta_produtos_catalogo = mysqli_query($conecta, $select);
        if ($consulta_produtos_catalogo) {
            $qtd_prd_catologo = mysqli_num_rows($consulta_produtos_catalogo); //qtd dos produtos que estão com desconto
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / catalogo / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); //registrar log do erro
        }



        /*produtos mais buscados */
        $select = "SELECT prd.*,prd.cl_id as produtoid  FROM tb_produtos AS prd inner join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
        WHERE prd.cl_status_ativo = 'SIM' and prd.cl_tipo_id ='1'  and ( (prd.cl_estoque > 0 and prd.cl_produto_pai = 0) or ( prd.cl_produto_pai = 1 ) ) and mt.cl_visualizacao >= $quantidade_prd_min_mais_buscados
        order by rand() ";
        $consulta_produtos_mais_buscados = mysqli_query($conecta, $select);
        if ($consulta_produtos_mais_buscados) {
            $qtd_prd_mais_buscados = mysqli_num_rows($consulta_produtos_mais_buscados); //qtd dos produtos que estão com desconto
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / produtos mais buscados / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); //registrar log do erro
        }


        /*Consultar os marcadores */
        $select = "SELECT mrc.*  FROM tb_marcadores AS mrc INNER JOIN 
        tb_produtos AS prd ON prd.cl_codigo = mrc.cl_codigo_nf where ( prd.cl_estoque > 0 and prd.cl_tipo_id = '1' ) and prd.cl_status_ativo ='SIM' GROUP BY mrc.cl_descricao  ORDER BY RAND() LIMIT 7 ";
        $consulta_marcadores = mysqli_query($conecta, $select);
        if ($consulta_marcadores) {
            $qtd_marcadores = mysqli_num_rows($consulta_marcadores);
        } else {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / marcadores / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); //registrar log do erro
        }



        /*pixel */
        if (auth('') !== false) {
            $dados = ['pagina' => '?inicial'];
            $dados_usuario = auth('')['dados_usuario'];
            $dados = [
                'dados_usuario' => $dados_usuario,
                'dados' => $dados
            ];

            pixel('ViewContent', $dados);
        } else {
            $dados = ['pagina' => '?inicial'];
            $dados = [
                'dados' => $dados
            ];

            pixel('ViewContentNotLog', $dados);
        }
    } elseif ($layouts == "categories" or $layouts == "topo" or $layouts == "footer") { //categorias e subcategorias, topo e footer
        include "../../../db/conn.php";
        include "../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema gerenciador
        $diretorio_logo = "../../../$empresa/img/ecommerce/logo/logo.png";
        $texto_footer = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', 79, 'cl_valor'));
        $whatsap = (consulta_tabela('tb_parametros', 'cl_id', 44, 'cl_valor')); //numero whatsap
        $instagram = (consulta_tabela('tb_parametros', 'cl_id', 43, 'cl_valor')); //link do instagram
        $facebook = (consulta_tabela('tb_parametros', 'cl_id', 80, 'cl_valor')); //link do facebook
        $email = (consulta_tabela('tb_parametros', 'cl_id', 74, 'cl_valor')); //email para contato
        $telefone = (consulta_tabela('tb_parametros', 'cl_id', 81, 'cl_valor')); //telefone para contato
        $tiktok = (consulta_tabela('tb_parametros', 'cl_id', 101, 'cl_valor')); //tiktok
        $menu_unidade_medida_status = (consulta_tabela('tb_parametros', 'cl_id', 121, 'cl_valor')); //verificar se o menu unidade de medidas está habilitado S/N
        $menu_marcadores_status = (consulta_tabela('tb_parametros', 'cl_id', 138, 'cl_valor')); //verificar se o menu marcadores está habilitado S/N
        $titulo_menu_unidade_medida = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '120', 'cl_valor'));
        $titulo_menu_marcadores = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', 139, 'cl_valor'));
        $nome_fantasia = utf8_encode(consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_nome_fantasia'));

        if (auth('') !== false) {
            $cookie = auth('')['dados_usuario']['cookie'];
        }
    } elseif ($containers == "member" or $containers == "baner_2") { //baner e inscreva-ser
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
        $status_baner_2 = consulta_tabela('tb_parametros', 'cl_id', '106', 'cl_valor'); //verificar se o baner 2 está ativo ou não
        $span_componente_inscrever = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '111', 'cl_valor'));
        $sessao_inscreva_se = consulta_tabela('tb_parametros', 'cl_id', '110', 'cl_valor');
    } elseif ($containers == "filterSearch") {
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
        $opcao_subtitle = (consulta_tabela('tb_parametros', 'cl_id', 134, 'cl_valor')); //mostrar a refencia ou marca no card do produto

        $products_filter = isset($_GET['q']) ? $_GET['q'] : '';
        $products_filter = utf8_decode(str_replace("'", "", $products_filter));

        $qtd_filter = 0;
        $query = "SELECT prd.* FROM tb_produtos as prd 
        WHERE prd.cl_status_ativo = 'SIM' and ( (prd.cl_estoque > 0 and prd.cl_produto_pai = 0) or ( prd.cl_produto_pai = 1 ) ) and prd.cl_tipo_id ='1' 
         AND (cl_descricao LIKE '%{$products_filter}%' OR 
              cl_referencia LIKE '%{$products_filter}%' OR
              cl_descricao_extendida_delivery LIKE '%{$products_filter}%' ) ";
        $consulta_filter = mysqli_query($conecta, $query);
        if (!$consulta_filter) {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == filterSearch  / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
        } else {
            $qtd_filter = mysqli_num_rows($consulta_filter);
        }
    }
}
