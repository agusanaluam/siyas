$(document).ready(function() {
    $('#submitUpdate').on('click', function(e) {
        e.preventDefault(); // Mencegah pengiriman form biasa

        const formData = new FormData($('#formProfile')[0]);
        $.ajax({
            type: 'POST',
            url: `${BASE_URL}`+'/user/profile/update', // Ganti dengan route yang sesuai
            data: formData,
            processData: false, // Untuk menghindari jQuery mengubah data
            contentType: false, // Memungkinkan pengiriman file
            success: function(response) {
                if (response.success) {
                            $('#toastSuccess').toast('show');
                            $('#toastSuccess .toast-body').text(response.message);
                            // setTimeout(function() {
                            //     $('#successToast').fadeOut();
                            // }, 3000);
                        } else {
                            $('#toastDanger').toast('show');
                            $('#toastDanger .toast-body').text(response.message);
                        }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.message;
                $('#toastDanger').toast('show');
                    $('#toastDanger .toast-body').text(errors);
            }
        });
    });
});
