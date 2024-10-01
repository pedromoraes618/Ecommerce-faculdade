<?php
$nome_do_arquivo = __FILE__;


if (isset($_GET['page'])) {
    $containers = isset($_GET['containers']) ? $_GET['containers'] : '';
    $layouts = isset($_GET['layouts']) ? $_GET['layouts'] : '';

    /*parametros */
    $products_filter = isset($_GET['products_filter']) ? utf8_decode($_GET['products_filter']) : '';

    $subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';

    $news = isset($_GET['news']) ? $_GET['news'] : '';
    $catalog = isset($_GET['catalog']) ? $_GET['catalog'] : '';
    $discount = isset($_GET['discount']) ? $_GET['discount'] : '';
    $current_page = isset($_GET['pagination']) ? (($_GET['pagination'] == "") ? 1 : $_GET['pagination']) : '1';
    $formato = isset($_GET['formato']) ? $_GET['formato'] : '';
    $marcador = isset($_GET['markers']) ? $_GET['markers'] : '';


    if ($news == "true") { //pixel facebook + enviar o local da pagina
        $dados = ['pagina' => '?products-filter&news=true'];
    } elseif ($discount == "true") {
        $dados = ['pagina' => '?products-filter&discount=true'];
    } else {
        $dados = ['pagina' => '?products-filter&catalog=true'];
    }

    $baner_secao = '';
    if ($containers == "products") { // Produtos + products_filter / products.php
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); // Diretório raiz do sistema gerenciador

        $diferencia_dias_lancamento = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "41");

        $limite = consulta_tabela('tb_parametros', 'cl_id', 85, 'cl_valor'); //limite por pagina
        $inicio = ($current_page * $limite);
        // Consulta SQL para obter os produtos com a cláusula LIMIT e OFFSET para a paginação
        $inicio = ($current_page - 1) * $limite;

        $subcategory_desc = utf8_encode(consulta_tabela('tb_subgrupo_estoque', 'cl_id', $subcategory, 'cl_descricao'));


        /*titulo da seção */
        $titulo_secao_novidade = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '113', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_desconto = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '112', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_catalogo = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '115', 'cl_valor')); //verifica se está habiltado
        $status_baner_secao = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '122', 'cl_valor')); //verifica se está ativo

        $titulo_menu_unidade_medida = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '120', 'cl_valor'));

        $descricao_formato = $formato != '' ? utf8_encode(consulta_tabela('tb_unidade_medida', 'cl_id', $formato, 'cl_descricao')) : '';
        $descricao_marcador = $marcador != '' ? utf8_encode(consulta_tabela('tb_marcadores', 'cl_id', $marcador, 'cl_descricao')) : '';
        $opcao_subtitle = (consulta_tabela('tb_parametros', 'cl_id', 134, 'cl_valor')); //mostrar a refencia ou marca no card do produto


        /*Definindo o titulo  */
        if (!empty($subcategory)) {
            $title_session = $subcategory_desc;
        } elseif ($news == "true") {
            $title_session = $titulo_secao_novidade;
            $baner_secao = consulta_tabela('tb_baner_delivery', 'cl_secao', 'new', 'cl_arquivo');
        } elseif ($catalog == "true") {
            $title_session = $titulo_secao_catalogo;
            $baner_secao = consulta_tabela('tb_baner_delivery', 'cl_secao', 'catalogo', 'cl_arquivo');
        } elseif ($discount == "true") {
            $title_session = $titulo_secao_desconto;
            $baner_secao = consulta_tabela('tb_baner_delivery', 'cl_secao', 'discount', 'cl_arquivo');
        } elseif ($formato != "") {
            $title_session = $descricao_formato;
            $baner_secao = consulta_tabela('tb_baner_delivery', 'cl_secao', 'format', 'cl_arquivo');
        } elseif ($marcador != "") {
            $title_session = $descricao_marcador;
            $baner_secao = consulta_tabela('tb_baner_delivery', 'cl_secao', 'markers', 'cl_arquivo'); //seção de marcadores
        } else {
            $title_session = "Resultado da pesquisa";
        }

        /*Definir o titulo da seção para as subcategorias para o pixel*/
        if (!empty($subcategory)) {
            $subgrupo_id =  consulta_tabela("tb_subgrupo_estoque", 'cl_id', $subcategory, 'cl_grupo_id');
            $grupo_descricao =  utf8_encode(consulta_tabela("tb_grupo_estoque", 'cl_id', $subgrupo_id, 'cl_descricao')); //descruicao do grupo pai
            $categoria = $grupo_descricao . "-" . $title_session;
        } else {
            $categoria = $title_session;
        }


        /*filtros */
        $order = isset($_GET['order']) ? $_GET['order'] : '';
        $min_preco = isset($_GET['min_preco']) ? $_GET['min_preco'] : '';
        $max_preco = isset($_GET['max_preco']) ? $_GET['max_preco'] : '';

        $condicao_todos = isset($_GET['condicao_todos']) ? $_GET['condicao_todos'] : '';
        $condicao_novo = isset($_GET['condicao_novo']) ? $_GET['condicao_novo'] : '';
        $condicao_usado = isset($_GET['condicao_usado']) ? $_GET['condicao_usado'] : '';
        $unidade = isset($_GET['unidade']) ? $_GET['unidade'] : '';
        $promocao = isset($_GET['promocao']) ? $_GET['promocao'] : '';
        $destaque = isset($_GET['destaque']) ? $_GET['destaque'] : '';
        $marcadores = isset($_GET['marcadores']) ? $_GET['marcadores'] : ''; //array
        $unidades = isset($_GET['unidades']) ? $_GET['unidades'] : ''; //array
        $marcas = isset($_GET['marcas']) ? $_GET['marcas'] : ''; //array
        $condicao = "";


        if (isset($_GET['condicao_usado']) && isset($_GET['condicao_novo'])) {
            $condicao = '';
        } elseif (isset($_GET['condicao_usado'])) {
            $condicao = 'USADO';
        } elseif (isset($_GET['condicao_novo'])) {
            $condicao = 'NOVO';
        }
        // echo $condicao_novo;
        // Definindo a ordem
        switch ($order) {
            case "a_z":
                $order = "prd.cl_descricao ASC";
                break;
            case "z_a":
                $order = "prd.cl_descricao DESC";
                break;
            case "menor_maior_preco":
                $order = "cl_preco_venda ASC";
                break;
            case "maior_menor_preco":
                $order = "cl_preco_venda DESC";
                break;
            case "mais_vendidos":
                $order = "total_vendas DESC";
                break;
            case "menos_vendidos":
                $order = "total_vendas ASC";
                break;
            default:
                $order = "prd.cl_data_cadastro desc, prd.cl_destaque desc, cl_fixo desc, mt.cl_visualizacao desc"; //metrica de visualização
                break;
        }


        $query = "SELECT prd.*,prd.cl_id as produtoid FROM tb_produtos as prd 
         left join tb_marcadores as mrc on mrc.cl_codigo_nf = prd.cl_codigo left join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
        WHERE prd.cl_status_ativo = 'SIM' and prd.cl_estoque > 0 and prd.cl_tipo_id ='1' ";

        if (!empty($products_filter)) { // Pesquisa pela descrição, título etc.
            $products_filter = (str_replace("'", "", $products_filter));
            $query .= " AND (prd.cl_descricao LIKE '%{$products_filter}%' OR 
              cl_referencia LIKE '%{$products_filter}%' OR
              cl_descricao_extendida_delivery LIKE '%{$products_filter}%') ";
        }

        if (!empty($subcategory)) { // produtos que pertencem ao grupo 
            $query .= " AND ( cl_grupo_id ='$subcategory' ) ";
        }

        if (!empty($discount) or !empty($promocao)) { // produtos que estão com desconto
            $query .= " AND ( prd.cl_preco_promocao > 0
            and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
        }

        if (!empty($min_preco) && !empty($max_preco)) { //filtro por preço
            $query .= " AND (prd.cl_preco_venda BETWEEN $min_preco AND $max_preco or prd.cl_preco_promocao BETWEEN $min_preco AND $max_preco ) ";
        }

        if ($news == "true") { // produtos que estão em lançamento
            $query .= " AND ( prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', INTERVAL '$diferencia_dias_lancamento' DAY) ) ";
        }

        if ($condicao != "") { //condição do produto usado ou novo
            $query .= " AND ( prd.cl_condicao = '$condicao' ) ";
        }

        if ($destaque != "") { //produtos em destaque ou não
            $query .= " AND ( prd.cl_destaque = 'SIM' ) ";
        }

        if ($formato != "") {
            $query .= " AND ( prd.cl_und_id = '$formato' ) ";
        } elseif (!empty($unidades)) {
            $unidades_ids_str = implode("','", $unidades); // Transforma o array em uma string separada por vírgulas e aspas
            $query .= " AND ( prd.cl_und_id IN ('$unidades_ids_str') ) ";
        }

        if (!empty($marcadores)) { //filtro lateral
            $marcadoresString = utf8_decode(implode("%' OR mrc.cl_descricao LIKE '%", $marcadores));
            $query .= " AND (mrc.cl_descricao LIKE '%$marcadoresString%') ";
        }

        if (!empty($marcador)) { //filtro pesquisa seção
            $descricao_marcador = utf8_decode($descricao_marcador);
            $query .= " AND (mrc.cl_descricao LIKE '%$descricao_marcador%') ";
        }

        if (!empty($marcas)) {
            $marcas_ids_str = utf8_decode(implode("','", $marcas)); // Transforma o array em uma string separada por vírgulas e aspas
            $query .= " AND ( prd.cl_fabricante IN ('$marcas_ids_str') ) ";
        }

        if (!empty($order)) { //ordenar
            $query .= " GROUP BY prd.cl_id ORDER BY $order";
        }

        $query .= " LIMIT $inicio, $limite";
        $consultar_produtos = mysqli_query($conecta, $query); // Consulta
        $qtd_prd = mysqli_num_rows($consultar_produtos); // Quantidade de produtos
        if (!$consultar_produtos) {
            $erro = str_replace("'", "", mysqli_error($conecta));
            $mensagem = utf8_decode("Ecommerce - $nome_do_arquivo - containers == products / consultar produtos / erro - $erro");
            registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
        }


        // Calcular o número total de páginas
        $query_limite = "SELECT COUNT(DISTINCT prd.cl_id)  AS count FROM tb_produtos AS prd 
        left join tb_marcadores as mrc on mrc.cl_codigo_nf = prd.cl_codigo left join tb_metricas_produtos as mt on mt.cl_produto_id = prd.cl_id
        WHERE prd.cl_status_ativo = 'SIM' and prd.cl_tipo_id ='1' and prd.cl_estoque > 0 ";
        if (!empty($products_filter)) { // Pesquisa pela descrição, título etc.
            $query_limite .= " AND ( prd.cl_descricao LIKE '%{$products_filter}%' OR 
              cl_referencia LIKE '%{$products_filter}%' OR
              cl_descricao_extendida_delivery LIKE '%{$products_filter}%')";
        }
        if (!empty($subcategory)) { // produtos que pertencem ao grupo 
            $query_limite .= " AND ( cl_grupo_id ='$subcategory' ) ";
        }

        if (!empty($discount)  or !empty($promocao)) { // produtos que estão com desconto
            $query_limite .= " AND ( prd.cl_preco_promocao > 0
            and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
        }


        if (!empty($min_preco) && !empty($max_preco)) { //filtro por preço
            $query_limite .= " AND (prd.cl_preco_venda BETWEEN $min_preco AND $max_preco or prd.cl_preco_promocao BETWEEN $min_preco AND $max_preco ) ";
        }


        if ($news == "true") { // produtos que estão em lançamento
            $query_limite .= " AND ( prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', INTERVAL '$diferencia_dias_lancamento' DAY )) ";
        }


        if ($condicao != "") { //condição do produto usado ou novo
            $query_limite .= " AND  (prd.cl_condicao = '$condicao' ) ";
        }

        if ($destaque != "") { //produtos em destaque ou não
            $query_limite .= " AND ( prd.cl_destaque = 'SIM' ) ";
        }

        if ($formato != "") {
            $query_limite .= " AND ( prd.cl_und_id = '$formato' ) ";
        } elseif (!empty($unidades)) {
            $unidades_ids_str = implode("','", $unidades); // Transforma o array em uma string separada por vírgulas e aspas
            $query_limite .= " AND ( prd.cl_und_id IN ('$unidades_ids_str') ) ";
        }

        if (!empty($marcadores)) {
            $marcadoresString = utf8_decode(implode("%' OR mrc.cl_descricao LIKE '%", $marcadores));
            $query_limite .= " AND ( mrc.cl_descricao LIKE '%$marcadoresString%') ";
        }

        if (!empty($marcador)) { //filtro pesquisa seção
            $descricao_marcador = utf8_decode($descricao_marcador);
            $query_limite .= " AND (mrc.cl_descricao LIKE '%$descricao_marcador%') ";
        }

        if (!empty($marcas)) {
            $marcas_ids_str = utf8_decode(implode("','", $marcas)); // Transforma o array em uma string separada por vírgulas e aspas
            $query_limite .= " AND ( prd.cl_fabricante IN ('$marcas_ids_str') ) ";
        }

        if (!empty($order)) : //ordenar
            $query_limite .= " ORDER BY $order";
        endif;

        $consultar_produtos_limite = mysqli_query($conecta, $query_limite); // Consulta para contar o total de resultados
        $linha = mysqli_fetch_assoc($consultar_produtos_limite);
        $registros = $linha['count'];
        $total_pages = ceil($registros / $limite); // Número total de páginas



        /*pixel */
        if (!empty($products_filter)) {
            $dados = ['pagina' => '?products-filter&catalog=true', 'pesquisa' => $products_filter];
            if (auth('') !== false) {
                $dados_usuario = auth('')['dados_usuario'];
                $dados = [
                    'dados_usuario' => $dados_usuario,
                    'dados' => $dados
                ];

                pixel('Search', $dados);
            }
        } else {
            if (auth('') !== false) {
                $dados_usuario = auth('')['dados_usuario'];
                $dados = [
                    'dados_usuario' => $dados_usuario,
                    'dados' => $dados
                ];
                pixel('ViewCategory', $dados);
            }
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $client_agent = $_SERVER['HTTP_USER_AGENT'];
?>
        <script>
            $(document).ready(function() {
                // Parâmetros adicionais para o evento Search
                var searchParams = {
                    search_string: '<?php echo $products_filter ?>',
                    content_category: '<?php echo $categoria ?>',
                    client_ip_address: '<?= $ip ?>', //atualizar o campo email para array dados_pixel // pixel
                    client_user_agent: '<?= hash('sha256', $client_agent) ?>', //atualizar o campo email para array dados_pixel // pixel
                };

                // // Envia o evento Search para o Meta Pixel
                fbq('track', 'Search', searchParams);
            })
        </script>
<?php
    } elseif ($containers == "group") { //filtros products_filter / group.php
        include "../../../../db/conn.php";
        include "../../../../helps/funcao.php";
        $titulo_secao_desconto = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '112', 'cl_valor')); //verifica se está habiltado
        $titulo_secao_destaque = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '114', 'cl_valor')); //verifica se está habiltado
        $titulo_menu_unidade_medida = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', '120', 'cl_valor'));
        $titulo_menu_marcadores = utf8_encode(consulta_tabela('tb_parametros', 'cl_id', 139, 'cl_valor'));
        $diferencia_dias_lancamento = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "41");

        $empresa = consulta_tabela('tb_empresa', 'cl_id', '1', 'cl_empresa'); // Diretório raiz do sistema gerenciador

        $subgrupo_id = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
        $products_filter = isset($_GET['products-filter']) ? $_GET['products-filter'] : '';
        $marcador = isset($_GET['markers']) ? $_GET['markers'] : '';
        $formato = isset($_GET['formato']) ? $_GET['formato'] : '';
        $news = isset($_GET['news']) ? $_GET['news'] : '';
        $discount = isset($_GET['discount']) ? $_GET['discount'] : '';

        $descricao_marcador = $marcador != '' ? utf8_encode(consulta_tabela('tb_marcadores', 'cl_id', $marcador, 'cl_descricao')) : '';

        /*marcadores */
        $query = "SELECT mrc.*
        FROM tb_marcadores AS mrc
        INNER JOIN tb_produtos AS prd ON prd.cl_codigo = mrc.cl_codigo_nf
        INNER JOIN tb_subgrupo_estoque AS subg ON subg.cl_id = prd.cl_grupo_id";
        // Array de condições
        $conditions = [];



        if (!empty($discount)) { // produtos que estão com desconto
            $conditions[] = " ( prd.cl_preco_promocao > 0
            and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
        }

        if ($news == "true") { // produtos que estão em lançamento
            $conditions[] = "  ( prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', INTERVAL '$diferencia_dias_lancamento' DAY) ) ";
        }

        // Condição para o subgrupo
        if (!empty($subgrupo_id)) {
            $conditions[] = " prd.cl_grupo_id = '$subgrupo_id' ";
        }


        // Condição para o filtro de produtos
        if (!empty($products_filter)) {

            $conditions[] = " (prd.cl_descricao LIKE '%{$products_filter}%' OR 
                          prd.cl_referencia LIKE '%{$products_filter}%' OR 
                          prd.cl_descricao_extendida_delivery LIKE '%{$products_filter}%') ";
        }
        // Condição para a unidade de medida
        if (!empty($formato)) {
            $conditions[] = " prd.cl_und_id = '$formato' ";
        }

        // Condições obrigatórias
        $mandatoryConditions = "(prd.cl_estoque > 0 AND prd.cl_tipo_id = '1') AND prd.cl_status_ativo = 'SIM' ";

        // Junta as condições, se houver
        if (!empty($conditions)) {
            $query .= " WHERE $mandatoryConditions AND " . implode(" AND ", $conditions);
        } else {
            $query .= " WHERE $mandatoryConditions";
        }
        // Adiciona o group by
        $query .= " GROUP BY mrc.cl_descricao";
        // Executa a query
        $resultados_marcadores = consulta_linhas_tb_query($conecta, $query);


        /*unidade de medida */
        $query = "SELECT und.*
        FROM tb_unidade_medida AS und
        INNER JOIN tb_produtos AS prd ON prd.cl_und_id = und.cl_id
        INNER JOIN tb_subgrupo_estoque AS subg ON subg.cl_id = prd.cl_grupo_id
        left JOIN tb_marcadores AS mrc ON mrc.cl_codigo_nf = prd.cl_codigo ";
        $conditions = [];
        // Condição para o subgrupo

        if (!empty($subgrupo_id)) {
            $conditions[] = "prd.cl_grupo_id = '$subgrupo_id'";
        }


        if (!empty($discount)) { // produtos que estão com desconto
            $conditions[] = " ( prd.cl_preco_promocao > 0
            and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
        }

        if ($news == "true") { // produtos que estão em lançamento
            $conditions[] = "  ( prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', INTERVAL '$diferencia_dias_lancamento' DAY) ) ";
        }


        // Condição para o filtro de produtos
        if (!empty($products_filter)) {
            $conditions[] = "(prd.cl_descricao LIKE '%{$products_filter}%' OR 
                    prd.cl_referencia LIKE '%{$products_filter}%' OR 
                    prd.cl_descricao_extendida_delivery LIKE '%{$products_filter}%')";
        }
        // Condição para o marcador
        if (!empty($marcador)) {
            $conditions[] = "mrc.cl_descricao = '$descricao_marcador'";
        }

        // Condições obrigatórias
        $mandatoryConditions = "(prd.cl_estoque > 0 AND prd.cl_tipo_id = '1') AND prd.cl_status_ativo = 'SIM' ";

        // Junta as condições, se houver
        if (!empty($conditions)) {
            $query .= " WHERE $mandatoryConditions AND " . implode(" AND ", $conditions);
        } else {
            $query .= " WHERE $mandatoryConditions";
        }
        // Condições finais
        $query .= " GROUP BY und.cl_id";

        // Executa a query
        $resultados_unidade_medida = consulta_linhas_tb_query($conecta, $query);


        /* Fabricante */
        $query = "SELECT prd.* FROM tb_produtos AS prd   
    INNER JOIN tb_subgrupo_estoque AS subg ON subg.cl_id = prd.cl_grupo_id
    LEFT JOIN tb_marcadores AS mrc ON mrc.cl_codigo_nf = prd.cl_codigo ";

        // Inicia a cláusula WHERE se necessário
        $conditions = [];

        // Condição para o subgrupo
        if (!empty($subgrupo_id)) {
            $conditions[] = "prd.cl_grupo_id = '$subgrupo_id'";
        }

        // Produtos que estão com desconto
        if (!empty($discount)) {
            $conditions[] = "(prd.cl_preco_promocao > 0 AND prd.cl_data_valida_promocao >= '$data_lancamento')";
        }

        // Produtos que estão em lançamento
        if ($news == "true") {
            $conditions[] = "(prd.cl_data_cadastro >= DATE_SUB('$data_lancamento', INTERVAL '$diferencia_dias_lancamento' DAY))";
        }

        // Filtro de produtos
        if (!empty($products_filter)) {
            $conditions[] = "(prd.cl_descricao LIKE '%{$products_filter}%' OR 
                     prd.cl_referencia LIKE '%{$products_filter}%' OR 
                     prd.cl_descricao_extendida_delivery LIKE '%{$products_filter}%')";
        }

        // Condição para a unidade de medida
        if (!empty($formato)) {
            $conditions[] = "prd.cl_und_id = '$formato'";
        }

        // Condição para o marcador
        if (!empty($marcador)) {
            $conditions[] = "mrc.cl_descricao = '$descricao_marcador'";
        }

        // Condições obrigatórias
        $mandatoryConditions = "(prd.cl_estoque > 0 AND prd.cl_tipo_id = '1') AND prd.cl_status_ativo = 'SIM' AND COALESCE(prd.cl_fabricante, '') <> '' ";

        // Junta as condições, se houver
        if (!empty($conditions)) {
            $query .= " WHERE $mandatoryConditions AND " . implode(" AND ", $conditions);
        } else {
            $query .= " WHERE $mandatoryConditions";
        }

        // Adiciona a cláusula GROUP BY
        $query .= " GROUP BY prd.cl_fabricante";


        $consultar_fabricante = consulta_linhas_tb_query($conecta, $query);

        /*produtos visitados pelo usuário */
        if (isset($_COOKIE['product_visit'])) {
            // Decodifica o cookie
            $product_visit_cookie = json_decode($_COOKIE['product_visit'], true);

            // Verifica se a decodificação foi bem-sucedida e se há produtos no array
            if ($product_visit_cookie && is_array($product_visit_cookie)) {

                // Extrai os IDs dos produtos do array
                $product_ids_visit = array_column($product_visit_cookie, 'id');

                // Escapa os IDs dos produtos e converte para uma string separada por vírgulas
                $ids_prd_visit_cookie = implode(',', $product_ids_visit);
                $query = "SELECT prd.*,prd.cl_id as idproduto ,grup.cl_descricao as grupo,cat.cl_descricao as categoria, cat.cl_mensagem, md.cl_descricao as und FROM tb_produtos 
        as prd left join tb_unidade_medida as md on md.cl_id = prd.cl_und_id 
        left join tb_subgrupo_estoque as cat on cat.cl_id = prd.cl_grupo_id
         left join tb_grupo_estoque as grup on grup.cl_id = cat.cl_grupo_id
         where prd.cl_id IN ($ids_prd_visit_cookie) and prd.cl_tipo_id ='1' and prd.cl_estoque >0  and prd.cl_status_ativo = 'SIM' order by rand() LIMIT 3  ";
                $consultar_produtos_visit_cookie = mysqli_query($conecta, $query); // Consulta

            }
        }
    }
}

?>