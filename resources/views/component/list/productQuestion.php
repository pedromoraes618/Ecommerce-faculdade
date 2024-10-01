<?php include "../../../../app/Http/Controllers/ProductsDetails.php"; ?>
<?php if ($qtd_duvidas > 0) { ?>
    <div class="container ">
        <?php if ($consulta_duvidas) {
            while ($linha = mysqli_fetch_assoc($consulta_duvidas)) {
                $pergunta = utf8_encode($linha['cl_mensagem']);
                $usuario_id_pergunta = ($linha['cl_usuario_id']);
                $data = ($linha['cl_data']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $span_indentificao_msg = $usuarioID == $usuario_id_pergunta ? "<small class='text-dark'> - Pergunta feita por mim - " . formatDateB($data) . "</small>" : '';
        ?>
                <div class="mb-3 ">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <div class="d-flex align-items-center">
                                <div><i class="bi bi-arrow-return-right"></i></div>
                                <div>
                                    <p class="text-muted mb-2 mx-1"><?= $pergunta . "  " . $span_indentificao_msg; ?></p>
                                    <ul class="nav">
                                        <li class="nav-item mx-3">
                                            <?php
                                            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_duvida_loja where cl_codigo_nf ='$codigo_nf' and cl_origem_mensagem = 1 ");
                                            if ($resultados) {
                                                foreach ($resultados as $linha) {
                                                    $resposta = utf8_encode($linha['cl_mensagem']);
                                                    $data = formatarTimeStamp($linha['cl_data']);
                                            ?>
                                                    <div class="mb-2">
                                                        <p class="text-muted mb-1"><?= $resposta; ?></p>
                                                        <small class="text-dark"><?= $data; ?></small>
                                                    </div>
                                            <?php }
                                            } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
        <?php }
        } ?>

    </div>
<?php } ?>