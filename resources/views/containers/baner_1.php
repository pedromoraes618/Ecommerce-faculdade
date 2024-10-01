<?php include "../../../app/Http/Controllers/Initial.php"; ?>
<?php if ($status_baner == "S") { ?>
    <div class="owl-carousel baner-1  p-0">
        <?php
        $resultados = consulta_linhas_tb_query($conecta, "SELECT * from tb_baner_delivery where cl_inicial ='1' order by cl_ordem asc");
        if ($resultados) {
            foreach ($resultados as $linha) {
                $descricao = $linha['cl_arquivo'];
                $link = $linha['cl_link'];
                $link = !empty($link) ? "href='$link'  target='_blank'" : '';
                echo "<a  $link class='item' data-merge='3'><img src='$url_init_img/$empresa/img/ecommerce/baner/$descricao' class='img-fluid'></a>";
            }
        }
        ?>
    </div>
<?php } ?>
<script src="public/js/containers/baner_1.js"></script>