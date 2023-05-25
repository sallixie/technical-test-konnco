$(document).ready(function () {
    $(".btn-delete-cart").on("click", function () {
        swal({
            title: "Apa anda yakin?",
            text: "Anda akan menghapus barang ini dari keranjang?",
            icon: "warning",
            buttons: true,
            buttons: ["No", "Yes"],
        }).then((result) => {
            if (result === true) {
                $(this).parent().submit();
            }
        });
    });
});
