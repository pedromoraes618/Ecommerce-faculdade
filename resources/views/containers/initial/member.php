<?php include "../../../../app/Http/Controllers/Initial.php"; ?>
<?php if (auth('') === false and $sessao_inscreva_se == "S") { ?>
    <div class="container-group-member">
        <div class="d-flex group-member justify-content-start align-items-center">
            <div class="col-md-auto">
                <img class="persona-img" src="../../../../<?= $empresa ?>/img/ecommerce/assets/persona_login.svg" alt="Persona Login">
            </div>
            <div class="col-md-9 text-md-start content-section">
                <p class="mb-1 title">Quer ter a melhor experiência do cliente?</p>
                <p class="subtitle"><?= $span_componente_inscrever; ?></p>
                <button href="#" class="btn btn-danger register fw-semibold rounded border-0 btn-register-member">Inscreva-se</button>
            </div>
        </div>
    </div>
<?php } ?>

<script src="public/js/containers/initial/member.js"> </script>