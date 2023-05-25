$(document).ready(function () {
    // get api from /api/transaction-status/{id}
    let transaction_id = $("#transaction_id").val();

    let getTransactionStatusInterval = setInterval(function () {
        getTransactionStatus();
    }, 3000);

    function getTransactionStatus() {
        $.ajax({
            url: "/api/transaction-status/" + transaction_id,
            method: "GET",
            success: function (response) {
                $("#badge_status").html(response);
                if (response !== "Pending") {
                    $("#badge_status").removeClass("badge-warning");
                    $("#badge_status").addClass("badge-success");
                    swal({
                        title: "Transaksi Berhasil!",
                        text: "Terimakasih telah melakukan pembayaran.",
                        icon: "success",
                        timer: 5000,
                    }).then((result) => {
                        clearInterval(getTransactionStatusInterval);
                        window.location.href = "/cart";
                    });
                }
            },
        });
    }
});
