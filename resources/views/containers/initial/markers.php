<?php
$id = utf8_encode($linha['cl_id']);
$descricao = utf8_encode($linha['cl_descricao']);
?>

<div class="marker-button border pt-2  text-center  rounded mx-2">
    <a href="?products-filter&markers=<?= $id ?>&<?= $descricao; ?>" class="marker-link d-block text-decoration-none">
        <!-- Fundo transparente com o texto centralizado -->
        <span class="icon me-1">
            <i class="bi bi-search"></i>
        </span>
        <span class="mb-0 text-dark "><?= $descricao; ?></span>
    </a>
</div>