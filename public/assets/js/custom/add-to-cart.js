$(document).ready(function () {
    $(".btn-add-to-cart").on("click", function () {
        swal({
            title: "Apa anda yakin?",
            text: "Anda akan menambahkan barang ini ke keranjang?",
            icon: "info",
            buttons: true,
            buttons: ["No", "Yes"],
        }).then((result) => {
            if (result === true) {
                $.ajax({
                    url: "/api/cart/add",
                    type: "POST",
                    data: {
                        _token: $("input[name=_token]").val(),
                        item_id: $(this).data("id"),
                        user_id: $(this).data("user"),
                        jumlah_item: $(this)
                            .parent()
                            .siblings()
                            .find(".quantity")
                            .val(),
                    },
                }).done(function (response) {
                    if (response.status == "success") {
                        swal({
                            title: "Berhasil!",
                            text: "Kamu telah menambahkan barang ke keranjang!",
                            icon: "success",
                            button: "OK",
                        });
                        $("#modal-detail").modal("hide");
                    } else {
                        swal({
                            title: "Gagal!",
                            text: "Kamu gagal menambahkan barang ke keranjang!",
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }
        });
    });
});
