$(document).ready(function() {

    $('.product-info').on('click', function(e) {
        const campaignData = $(this).data('campaign');

        if (!$(this).hasClass('active')) {
            addCart(campaignData);
        } else {
            removeCart(campaignData.id);
        }
    });

    $('#clear-cart').on('click', function() {
        $('#campaign-list').empty();
        $('#count-selected').text(0);
        calculateTotal()
        $('.product-info').removeClass('active');
    });

    $('.payment_method').on('click', function() {
        const id = $(this).attr('id');
        $('input[name="payment_method"]').val(id);
    })

    $('#btnSubmit').on('click', function(e) {
        Swal.fire({
            title: 'Apakah data sudah sesuai?',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, Simpan'
        }).then((result) => {
            if (result.isConfirmed) {
                e.preventDefault(); // Mencegah pengiriman form biasa

                const formData = new FormData($('#donationForm')[0]);
                $.ajax({
                    type: 'POST',
                    url: `${BASE_URL}/donation/store`, // Ganti dengan route yang sesuai
                    data: formData,
                    processData: false, // Untuk menghindari jQuery mengubah data
                    contentType: false, // Memungkinkan pengiriman file
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                                $('#toastSuccess').toast('show');
                                $('#toastSuccess .toast-body').text(response.message);
                                // setTimeout(function() {
                                //     $('#successToast').fadeOut();
                                // }, 3000);
                                window.location.href = `${BASE_URL}/donation`;

                            } else {
                                $('#toastDanger').toast('show');
                                $('#toastDanger .toast-body').text(response.message);
                            }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseText;
                        $('#toastDanger').toast('show');
                        $('#toastDanger .toast-body').text(errors);
                    }
                });
            }
        });
    });
});

$(document).on('blur', '.amount', function() {
    let amount = parseInt($(this).val(), 10);
    calculateTotal();
})

function addCart(campaignData) {
    let element = '<div class="product-list d-flex align-items-center justify-content-between" id="campaign-cart-'+campaignData.id+'">'+
                        '<div class="d-flex align-items-center product-info">'+
                            '<a href="javascript:void(0);" class="img-bg">'+
                                '<img src="'+`${BASE_URL}`+'/storage/campaign_pictures/resized_'+campaignData.image[0].picture_path+'" alt="Products">'+
                            '</a>'+
                            '<div class="info">'+
                                '<h6>'+campaignData.name+'</h6>'+
                            '</div>'+
                        '</div>'+
                        '<input type="hidden" class="form-control text-right" name="campaign_id[]" value="'+campaignData.id+'" >'+
                        '<input type="text" class="form-control text-align-right amount" name="amount[]" placeholder="5000" >'+
                    '</div>';
    $("#campaign-list").append(element);

    let countSelected = parseInt($('#count-selected').text(), 10)+1;
    $('#count-selected').text(countSelected);
}

function removeCart(id) {
    $('#campaign-cart-'+id).remove();

    calculateTotal();

    let countSelected = parseInt($('#count-selected').text(), 10)-1;
    $('#count-selected').text(countSelected);
}

function calculateTotal() {
    let total = 0;
    $('#campaign-list .amount').each(function() {
        total += parseInt($(this).val());
    });
    $('input[name="total_amount"]').val(total);

    let formatTotal = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(total);
    $('#total-amount').text(formatTotal);

}
