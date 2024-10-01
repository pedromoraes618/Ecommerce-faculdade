<?php include "../../../app/Http/Controllers/Initial.php"; ?>
<div class="navbar categorias pt-2 pb-2">
    <nav class="container-lg">
        <ul class="ul-1 p-0 m-0">
            <li><a class="titulo-mobile" href="#"><i class="bi bi-list fs-5 "></i><span> Categorias</span></a>
                <ul class="ul-2">
                    <?php
                    $consulta = consulta_linhas_tb('tb_grupo_estoque', 'cl_grupo_venda', '1', '', '');
                    if ($consulta) {
                        foreach ($consulta as $linha) {
                            $id = $linha['cl_id'];
                            $descricao_categoria = utf8_encode($linha['cl_descricao']);

                    ?>
                            <li class="list-item me-3">
                                <a style="cursor:pointer" class="d-block d-flex justify-content-between">
                                    <span class="mx-1   description"><?= $descricao_categoria; ?></span>
                                    <span class="icon"><i class="bi bi-chevron-down "></i></span>
                                    <!-- <span><i class="bi bi-chevron-down"></i></span> -->
                                </a>
                                <?php
                                $consulta = consulta_linhas_tb('tb_subgrupo_estoque', 'cl_delivery', 'SIM', 'cl_grupo_id', $id);
                                if (count($consulta) > 0) { ?>
                                    <ul class="rounded-1 border ul-3">
                                        <?php
                                        foreach ($consulta as $linha) {
                                            $id = $linha['cl_id'];
                                            $descricao_subcategoria = utf8_encode($linha['cl_descricao']);
                                        ?>
                                            <li><a href="?products-filter&categoria=<?= $descricao_categoria ?>&<?= $descricao_subcategoria ?>&subcategory=<?= $id; ?>" class="d-block "><?= $descricao_subcategoria; ?></a></li>
                                        <?php }; ?>
                                        <!-- Adicione mais subcategorias conforme necessário -->
                                    </ul>
                                <?php }; ?>
                            </li>
                            <?php
                        };
                        if ($menu_unidade_medida_status == "S") {
                            $consulta_und = consulta_linhas_tb_query($conecta, "SELECT und.* FROM tb_unidade_medida as und
                             inner join tb_produtos as prd on prd.cl_und_id = und.cl_id  WHERE
                              prd.cl_estoque > 0 and prd.cl_status_ativo ='SIM' group by und.cl_id ");
                            if (count($consulta_und) > 0) {
                            ?>
                                <li class="list-item"><a style="cursor:pointer" class="d-block d-flex justify-content-between">
                                        <span class="mx-1  description"><?= $titulo_menu_unidade_medida; ?></span><span class="icon"><i class="bi bi-chevron-down "></i></span>
                                        <!-- <span><i class="bi bi-chevron-down"></i></span> -->
                                    </a>
                                    <ul class="rounded-1 border ul-3">
                                        <?php
                                        foreach ($consulta_und as $linha) {
                                            $id = $linha['cl_id'];
                                            $descricao = utf8_encode($linha['cl_descricao']);
                                        ?>
                                            <li><a href="?products-filter&formato=<?= $id ?>&<?= $descricao ?>" class="d-block "><?= $descricao; ?></a></li>
                                        <?php }; ?>
                                        <!-- Adicione mais subcategorias conforme necessário -->
                                    </ul>
                                </li>
                            <?php
                            }
                        }

                        if ($menu_marcadores_status == "S") {
                            $consulta_und = consulta_linhas_tb_query($conecta, "SELECT mrc.*
                             FROM tb_marcadores as mrc inner join tb_produtos as prd on prd.cl_codigo = mrc.cl_codigo_nf WHERE
                              prd.cl_estoque > 0 and prd.cl_status_ativo ='SIM'  group by mrc.cl_descricao");
                            if (count($consulta_und) > 0) {
                            ?>
                                <li class="list-item"><a style="cursor:pointer" class="d-block d-flex justify-content-between">
                                        <span class="mx-1  description"><?= $titulo_menu_marcadores; ?></span><span class="icon"><i class="bi bi-chevron-down "></i></span>
                                        <!-- <span><i class="bi bi-chevron-down"></i></span> -->
                                    </a>
                                    <ul class="rounded-1 border ul-3">
                                        <?php
                                        foreach ($consulta_und as $linha) {
                                            $id = $linha['cl_id'];
                                            $descricao = utf8_encode($linha['cl_descricao']);
                                        ?>
                                            <li><a href="?products-filter&markers=<?= $id ?>&<?= $descricao ?>" class="d-block "><?= $descricao; ?></a></li>
                                        <?php }; ?>
                                        <!-- Adicione mais subcategorias conforme necessário -->
                                    </ul>
                                </li>
                    <?php }
                        }
                    };
                    ?>

                </ul>
            </li>
        </ul>
    </nav>
</div>