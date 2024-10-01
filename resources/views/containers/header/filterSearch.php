<?php
include "../../../../app/Http/Controllers/Initial.php";
?>

<?php if ($qtd_filter > 0) { ?>
    <div class="filter-search-group">
        <?php
        while ($linha = mysqli_fetch_assoc($consulta_filter)) {
            include "../card-produto/modelo_search.php"; //modelo do car produto
        } ?>
    </div>
<?php } ?>