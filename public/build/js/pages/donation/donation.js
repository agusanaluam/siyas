$(document).ready(function() {

    let table;
    $('#donationHistoryTable').DataTable({
        responsive: true,
        processing: true,
        autoWidth: false,
        "bFilter": true,
		"sDom": 'fBtlpi',
		"ordering": true,
		"language": {
			search: ' ',
			sLengthMenu: '_MENU_',
			searchPlaceholder: "Search...",
			info: "_START_ - _END_ of _TOTAL_ items",
               paginate: {
                       next: ' <i class=" fa fa-angle-right"></i>',
                       previous: '<i class="fa fa-angle-left"></i> '
                   },
		 },
           initComplete: (settings, json)=>{
			$('.dataTables_filter').appendTo('#tableSearch');
			$('.dataTables_filter').appendTo('.search-input');
		}
    })
    $(function () {
        table = $('#donationTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            "bFilter": true,
			"sDom": 'fBtlpi',
			"ordering": true,
			"language": {
				search: ' ',
				sLengthMenu: '_MENU_',
				searchPlaceholder: "Search...",
				info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                        next: ' <i class=" fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i> '
                    },
			 },
            initComplete: (settings, json)=>{
				$('.dataTables_filter').appendTo('#tableSearch');
				$('.dataTables_filter').appendTo('.search-input');
			},
            ajax: {
                url: `${BASE_URL}`+'/donation',
                data: {
                    '_method': "GET",
                    "_token": $('[name=csrf-token]').attr('content'),
                    "status": $('#status').val(),
                },
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    className: 'sorting_1',
                    searchable: false,
                    sortable: false,
                    render: function (data, type, row) {
                        return '<label class="checkboxs">' +
                                    '<input type="checkbox", value="'+data+'">' +
                                    '<span class="checkmarks"></span>' +
                                '</label>';
                    }
                },
                {data: 'liq_number'},
                {data: 'donatur_name'},
                {data: 'trans_date'},
                {data: 'total_amount'},
                {data: 'paymentLabel'},
                {data: 'status'},
                {
                    data: 'action',
                    className: 'text-center',
                    searchable: false,
                    sortable: false
                },
            ]
        });

    });

    $('#select-campaign').select2({
        dropdownParent: $("#edit-donation"),
        placeholder: 'Please type campaign name and select',
        minimumInputLength: 2,
        ajax: {
            url: `${BASE_URL}`+'/campaign/search', // URL endpoint untuk pencarian produk
            dataType: 'json',
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(params) {
                return {
                    query: params.term // Term yang diketik pengguna
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(campaign => ({
                        id: campaign.id,
                        text: `${campaign.name}`,
                        image: campaign.image[0].picture_path,
                    }))
                };
            },
            cache: true
        }
    });

    $('#select-campaign').on('select2:select', function(e) {
        let data = e.params.data;
        addToTable(data); // Tambah produk ke tabel
        $('#select-campaign').val(null).trigger('change'); // Reset Select2 setelah menambahkan produk
    });

    $('#editSubmit').on('click', function(e) {
        const formData = new FormData($('#formEditDonation')[0]);// Membuat FormData dari form yang disubmit
        $.ajax({
            type: 'POST',
            url: `${BASE_URL}`+'/donation/update', // Ganti dengan route yang sesuai
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
                        $('#edit-donation').modal('hide');
                        reloadTable();

                    } else {
                        $('#toastDanger').toast('show');
                        $('#toastDanger .toast-body').text(response.data.message);
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

$(document).on('click', '.donation-detail', function() {
    let id = $(this).parent().parent().attr('id');
    $.ajax({
        url: `${BASE_URL}`+'/donation/detail/'+id,
        type: "GET",
        cache: false,
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
        },
        success:function(response){
            let data = response.data
            $('#liq_number').text('LIQ Number : '+data.liq_number);
            $('#donor_info').html(data.donatur_name+'<br>'+data.donatur_address+'<br>'+data.donatur_phone);
            $('#volunteer_info').html(data.volunteer.name+'<br>'+data.volunteer.phone_number);
            $('#description').text(data.description);
            $('#liq_status').html('<span>'+data.trans_date+'</span><br/>'+data.status);
            let totalAmount = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(data.total_amount);
            $('#total_amount').text(totalAmount);
            $('#payment_method').text((data.via_transfer == 1)? 'Transfer': 'Cash');
            $('#reference_code').html(`<a href="${BASE_URL}/storage/${data.reference_picture}" target="_blank">${data.reference_code}</a>`);
            $('#donationDetailTable').DataTable().clear().destroy();
            $('#donationDetailTable').DataTable({
                responsive: true,
                processing: true,
                "bFilter": false,
                "sDom": 'fBtlpi',
                autoWidth: false,
                "language": {
                    sLengthMenu: '_MENU_',
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                            next: ' <i class=" fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> '
                        },
                },
                data: response.data.detail,
                columns: [
                    {
                        data: 'campaign',
                        render: function(data, type, row) {
                            return '<div class="productimgname">'+
                                '<a href="javascript:void(0);"'+
                                    'class="product-img stock-img">'+
                                    '<img src="'+`${BASE_URL}`+'/storage/campaign_pictures/resized_'+data.image[0].picture_path+'"'+
                                        'alt="product">'+
                                '</a>'+
                                '<a href="javascript:void(0);">'+data.name+'</a>'+
                            '</div>';
                        }
                    },
                    {
                        data: 'amount',
                        render: function(data, type, row) {
                            return new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data);
                        }
                    },
                ]
            });
        }
    });
})

$(document).on('click', '.edit-donation', function() {
    let id = $(this).parent().parent().attr('id');
    $.ajax({
        url: `${BASE_URL}`+'/donation/detail/'+id,
        type: "GET",
        cache: false,
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
        },
        success:function(response){
            let data = response.data
            $('input[name="liq_number"]').val(data.liq_number);
            $('input[name="id"]').val(data.id);
            $('input[name="donatur_name"]').val(data.donatur_name);
            $('input[name="donatur_phone"]').val(data.donatur_phone);
            $('input[name="donatur_address"]').val(data.donatur_address);
            $('textarea[name="description"]').text(data.description);
            $('input[name="trans_date"]').val(formatDate(data.trans_date));
            let totalAmount = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(data.total_amount);
            $('#edit_total_amount').text(totalAmount);
            $('input[name="total_amount"]').val(data.total_amount)
            $('select[name="payment_method"]').val((data.via_transfer == 1)? 'transfer': 'cash');
            $('input[name="reference_code"]').val(data.reference_code);
            $('#editDonationTable').DataTable().clear().destroy();
            $('#editDonationTable').DataTable({
                responsive: true,
                processing: true,
                "bFilter": false,
                "sDom": 'fBtlpi',
                autoWidth: false,
                "language": {
                    sLengthMenu: '_MENU_',
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                            next: ' <i class=" fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> '
                        },
                },
                data: response.data.detail,
                columns: [
                    {
                        data: 'campaign',
                        render: function(data, type, row) {
                            return '<div class="productimgname">'+
                                '<a href="javascript:void(0);"'+
                                    'class="product-img stock-img">'+
                                    '<img src="'+`${BASE_URL}`+'/storage/campaign_pictures/resized_'+data.image[0].picture_path+'"'+
                                        'alt="product">'+
                                '</a>'+
                                '<a href="javascript:void(0);">'+data.name+'</a>'+
                                '<input type="hidden" name="campaign_id[]" value="'+data.id+'" />'+
                                '<input type="hidden" name="detail_id[]" value="'+row.id+'" />'+
                            '</div>';
                        }
                    },
                    {
                        data: 'amount',
                        render: function(data, type, row) {
                            return '<input type="text" name="amount[]" class="form-control edited-amount" value="'+data+'" />';
                        }
                    },
                    {
                        data: 'campaign.id',
                        className: 'text-center',
                        searchable: false,
                        sortable: false,
                        render: function(data, type, row) {
                            return '<div class="edit-delete-action">'+
                            '<a class="confirm-text p-2 delete-edited" href="javascript:void(0);" data-id="' +data+ '">'+
                                '<i data-feather="trash-2" class="feather-trash-2"></i>'+
                            '</a>'+
                            '</div>';
                        },
                    }
                ]
            });
            // $('#donationDetailModal').modal('show');
        }
    });
})

$(document).on('click', '.cancel-donation', function() {
    let id = $(this).parent().parent().attr('id');
    Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin membatalkan data ini!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA!'
        }).then((result) => {
            if (result.isConfirmed) {

                //fetch to delete data
                $.ajax({
                    url: `${BASE_URL}`+'/donation/cancel/'+id,
                    type: "DELETE",
                    cache: false,
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                    },
                    success:function(response){
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
                        reloadTable();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.message;
                        $('#toastDanger').toast('show');
                        $('#toastDanger .toast-body').text(errors);
                    }
                });


            }
        })
});

$(document).on('click', '.approve-donation', function() {
    let id = $(this).parent().parent().attr('id');
    Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "akan memverifikasi donasi ini!",
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: 'TIDAK',
        confirmButtonText: 'YA!'
    }).then((result) => {
        if (result.isConfirmed) {
            //fetch to delete data
            $.ajax({
                url: `${BASE_URL}`+'/donation/approve/'+id,
                type: "PATCH",
                cache: false,
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                },
                success:function(response){
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
                    reloadTable();
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.message;
                    $('#toastDanger').toast('show');
                    $('#toastDanger .toast-body').text(errors);
                }
            });
        }
    })
});

$(document).on('blur', '.edited-amount', function() {
    calculateTotal();
});

$(document).on('click', '.delete-edited', function() {
    $(this).closest('tr').remove();
    calculateTotal();
});

function reloadTable()
{
    $('#donationTable').DataTable().ajax.reload();
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [day, month, year].join('-');
}

function addToTable(data) {
        let markup = `
            <tr>
                <td>
                <div class="productimgname">
                    <a href="javascript:void(0);"
                        class="product-img stock-img">
                        <img src="${BASE_URL}/storage/campaign_pictures/resized_${data.image}" alt="product">
                    </a>
                    <a href="javascript:void(0);">${data.text}</a>
                    <input type="hidden" name="campaign_id[]" value="${data.id}" />
                    <input type="hidden" name="detail_id[]" value="" />
                </div>
                </td>
                <td>
                    <input type="text" name="amount[]" class="form-control edited-amount" value="0" />
                </td>
                <td>
                    <div class="edit-delete-action">
                        <a class="confirm-text p-2 delete-edited" href="javascript:void(0);" data-id="${data.id}">
                            <i data-feather="trash-2" class="feather-trash-2"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
        $('#editDonationTable tbody').append(markup);
}

function calculateTotal() {
    let total = 0;
    $('#editDonationTable .edited-amount').each(function() {
        total += parseInt($(this).val());
    });
    $('input[name="total_amount"]').val(total);

    let formatTotal = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(total);
    $('#edit_total_amount').text(formatTotal);

}

