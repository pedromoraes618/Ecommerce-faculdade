<div class="modal fade" id="modal_product_question" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"> Pergunte a sua dúvida</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="question_product_form" class="">

                <div class="modal-body">
                    <div class="container-sm">
                        <div class="row mb-2">
                            <div class="col-md  mb-2">
                                <textarea rows="5" type="text" class="form-control" placeholder="Escreva a sua pergunta" id="pergunta" name="pergunta"></textarea>
                                <div class="feedback-pergunta ">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enviar pergunta</button>
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="public/js/component/productQuestion.js"> </script>