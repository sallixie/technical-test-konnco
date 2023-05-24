$(document).ready(function () {
    $(".btn-add-to-cart").on("click", function () {
        swal({
            title: "Are you sure?",
            text: "You want to add this product to cart?",
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
                            title: "Success!",
                            text: "You have added this product to cart!",
                            icon: "success",
                            button: "OK",
                        });
                        $("#modal-detail").modal("hide");
                    } else {
                        swal({
                            title: "Failed!",
                            text: "You have failed to add this product to cart!",
                            icon: "error",
                            button: "OK",
                        });
                    }
                });
            }
        });
    });
});
