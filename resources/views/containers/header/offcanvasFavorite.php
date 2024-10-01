<?php
include "../../../../app/Http/Controllers/Favorite.php";
?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFavorite" aria-labelledby="offcanvasFavoriteLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasFavoriteLabel"><i class="bi bi-heart fs-5"></i> Favoritos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        if ($dados_usuario) {
            $produtosFav = $dados_usuario['produtos_fav'];
            $qtd_fav = $dados_usuario['qtd_fav'];
            if ($qtd_fav > 0) {
                $total = 0;
                $registro = 0;
                foreach ($produtosFav as $linha) {
                    include "../card-produto/modelo_favorite.php"; //modelo favortios
                };
            } else {
                include "../../component/favEmpty.php"; //modelo mensagem
            }
        } ?>
    </div>

</div>
