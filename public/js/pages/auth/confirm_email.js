const token = new URLSearchParams(window.location.search).get('code');

confirmEmail()
function confirmEmail() {
    $.ajax({
        type: "POST",
        data: "form=auth&acao=confirmEmail&token=" + token,
        url: "app/Http/Controllers/Auth/ConfirmEmail.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        var $data = $.parseJSON(data)["data"];
        if ($data.status == true) {
            window.location.href = "./"
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $data.message,
                timer: 7500,
            })
            setTimeout(function () {
                window.location.href = "./"
            }, 3000);
        }

    }

    function falha() {
        console.log("erro");
    }
}