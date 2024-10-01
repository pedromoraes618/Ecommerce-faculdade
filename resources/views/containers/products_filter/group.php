<?php include "../../../../app/Http/Controllers/ProductsFilter.php"; ?>
<div class="product-filter-container mt-2 mt-md-5">
    <div class="row g-md-5">
        <div class="col-md-2  ">
            <div class="productFilterColumn ">
                <div class="list-unstyled mb-3">
                    <h5 class="d-none fw-semibold d-sm-block d-md-block">Filtro</h5>
                    <button class="btn btn-sm btn-phoenix-secondary text-body-tertiary d-lg-none btn-filters-mobile" data-phoenix-toggle="offcanvas" data-phoenix-target="#productFilterColumn">
                        <i class="bi bi-funnel-fill"></i> Filtro</button>
                </div>

                <div class="filters-dropdown">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div class="row mb-3">
                                <label class="collapse-indicator-order" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapseOrder" role="button" aria-expanded="true" aria-controls="collapseOrder">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span for="" class="form-label ">Ordenar por</span>
                                        <i class="bi bi-chevron-up rotate-icon"></i>
                                    </div>
                                </label>
                                <div class="collapse show" id="collapseOrder">
                                    <div class="d-flex justify-content-between">
                                        <select onchange="product(null)" name="order" class="form-select" id="order">
                                            <option value="">Selecione..</option>
                                            <!-- <option value="mais_vendidos">Mais Vendidos</option>
                                            <option value="menos_vendidos">Menos Vendidos</option> -->
                                            <option value="menor_maior_preco">Preço: Menor ao maior</option>
                                            <option value="maior_menor_preco">Preço: Maior ao menor</option>
                                            <option value="a_z">A-Z</option>
                                            <option value="z_a">Z-A</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Adicione mais filtros conforme necessário -->
                            <div class="row  mb-3">
                                <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapsePriceRange" role="button" aria-expanded="true" aria-controls="collapsePriceRange">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span for="" class="form-label ">Preço</span>
                                        <i class="bi bi-chevron-up rotate-icon"></i>
                                    </div>
                                </label>

                                <div class="collapse show" id="collapsePriceRange">
                                    <div class="d-flex justify-content-between">
                                        <div class="input-group me-2">
                                            <input class="form-control" name="min_preco" id="min_preco" type="text" aria-label="First name" placeholder="Min">
                                            <input class="form-control" name="max_preco" id="max_preco" type="text" aria-label="Last name" placeholder="Max">
                                        </div><button class="btn btn-outline-secondary border px-3 border-1 consultar_preco" onclick="product(null)" type=" button">Ir</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapseCondition" role="button" aria-expanded="true" aria-controls="collapsePriceRange">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span for="" class="form-label ">Condição</span>
                                        <i class="bi bi-chevron-up rotate-icon"></i>
                                    </div>
                                </label>

                                <div class="collapse show" id="collapseCondition">
                                    <div class="d-flex justify-content-between ">
                                        <div class="form-check form-check-inline p-0">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="condicao_novo" onclick="product(null)" id="condicao_novo" value="NOVO">
                                                <label class="form-check-label" for="condicao_novo">
                                                    Novo
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="condicao_usado" onclick="product(null)" id="condicao_usado" value="USADO">
                                                <label class="form-check-label" for="condicao_usado">
                                                    Usado
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapsePromotion" role="button" aria-expanded="true" aria-controls="collapsePromotion">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span for="" class="form-label ">Opções</span>
                                        <i class="bi bi-chevron-up rotate-icon"></i>
                                    </div>
                                </label>

                                <div class="collapse show" id="collapsePromotion">
                                    <div class="form-switch form-check-inline p-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="promocao" id="promocao" onclick="product(null)">
                                            <label class="form-check-label" for="promocao"><?= $titulo_secao_desconto; ?></label>
                                        </div>
                                    </div>
                                    <div class="form-switch form-check-inline p-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="destaque" id="destaque" onclick="product(null)">
                                            <label class="form-check-label" for="destaque"><?= $titulo_secao_destaque; ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if ($resultados_unidade_medida and empty($formato)) {
                            ?>
                                <div class="row  mb-3">
                                    <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapseFormat" role="button" aria-expanded="true" aria-controls="collapseFormat">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="form-label"><?= $titulo_menu_unidade_medida; ?></span>
                                            <i class="bi bi-chevron-up rotate-icon"></i>
                                        </div>
                                    </label>

                                    <div class="collapse show" id="collapseFormat">
                                        <div class="form-check form-check-inline p-0">
                                            <?php
                                            foreach ($resultados_unidade_medida as $linha) {
                                                $id = $linha['cl_id'];
                                                $descricao = utf8_encode($linha['cl_descricao']);
                                            ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="unidade" onclick="product(null)" id="unidade_<?= $id; ?>" value="<?= $id; ?>">
                                                    <label class="form-check-label" for="unidade_<?= $id; ?>">
                                                        <?= $descricao; ?>
                                                    </label>
                                                </div>
                                            <?php }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            <?php }
                            if ($resultados_marcadores and empty($marcador)) { ?>
                                <div class="row  mb-3">
                                    <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapseMarkers" role="button" aria-expanded="true" aria-controls="collapseFormat">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="form-label "><?= $titulo_menu_marcadores; ?></span>
                                            <i class="bi bi-chevron-up rotate-icon"></i>
                                        </div>
                                    </label>

                                    <div class="collapse show" id="collapseMarkers">
                                        <div class="form-check form-check-inline p-0">
                                            <?php
                                            foreach ($resultados_marcadores as $linha) {
                                                $id = $linha['cl_id'];
                                                $descricao = utf8_encode($linha['cl_descricao']);
                                            ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="marcador" onclick="product(null)" id="marcador_<?= $id; ?>" value="<?= $descricao; ?>">
                                                    <label class="form-check-label" for="marcador_<?= $id; ?>">
                                                        <?= $descricao; ?>
                                                    </label>
                                                </div>
                                            <?php }
                                            ?>

                                        </div>
                                    </div>
                                </div>

                            <?php }

                            if ($consultar_fabricante) { ?>
                                <div class="row  mb-3">
                                    <label class="collapse-indicator-preco d-block" onclick="rotateIcon(this)" data-bs-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="true" aria-controls="collapseFormat">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="form-label">Marca</span>
                                            <i class="bi bi-chevron-up rotate-icon"></i>
                                        </div>
                                    </label>

                                    <div class="collapse show" id="collapseBrand">
                                        <div class="form-check form-check-inline p-0">
                                            <?php
                                            foreach ($consultar_fabricante as $linha) {
                                                $descricao = utf8_encode($linha['cl_fabricante']);
                                            ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="marca" onclick="product(null)" id="marca_<?= $descricao; ?>" value="<?= $descricao; ?>">
                                                    <label class="form-check-label" for="marca_<?= $descricao; ?>">
                                                        <?= $descricao; ?>
                                                    </label>
                                                </div>
                                            <?php }
                                            ?>

                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    if (isset($consultar_produtos_visit_cookie)) { ?>
                        <hr>
                        <div class="product-visit">
                            <div class="box-title p-1">
                                <small class=" m-0">Seus últimos cliques</small>
                            </div>
                            <div class="box-body ">
                                <?php
                                while ($linha = mysqli_fetch_assoc($consultar_produtos_visit_cookie)) {
                                    include "../card-produto/modelo_visit.php";
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md  group-products">
        </div>
    </div>
</div>


<script src="public/js/containers/products_filter/group.js"> </script>