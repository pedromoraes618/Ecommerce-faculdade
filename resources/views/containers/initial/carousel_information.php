<?php
include "../../../../app/Http/Controllers/Initial.php";
if ($status_baner_2 == "S") {
?>
    <!-- utilização do cupom -->
    <div class="caroulse-information p-2">
        <div class="container  ">
            <div class="owl-carousel baner-2 rounded-4  p-0">
                <?php
                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_cupom
                WHERE cl_status = '1' 
                AND ((cl_data_validade_final <> '' AND cl_data_validade_inicial <= '$data_lancamento' AND cl_data_validade_final >= '$data_lancamento') OR ((cl_data_validade_final = '' or cl_data_validade_final='0000-00-00') AND cl_data_validade_inicial <= '$data_lancamento'))
                ORDER BY cl_data ASC ");
                if ($resultados) {
                    foreach ($resultados as $linha) {
                        $descricao = utf8_encode($linha['cl_descricao']);
                        $codigo = $linha['cl_codigo'];
                ?>
                        <div class="d-flex justify-content-center align-items-center">
                            <p class="text-body-highlight mb-0 mx-2"><?= $descricao; ?> </p>
                            <div class=" rounded px-2 cupom">Cupom: <?= $codigo ?></div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
<?php } ?>


<script src="public/js/containers/initial/carousel_information.js"> </script>