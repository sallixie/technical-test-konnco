$(document).ready(function () {
    $("#btn-checkout").on("click", function () {
        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan melakukan checkout?",
            icon: "info",
            buttons: true,
            buttons: ["No", "Yes"],
        }).then((result) => {
            if (result === true) {
                $("#form-checkout").submit();
            }
        });
    });
});
