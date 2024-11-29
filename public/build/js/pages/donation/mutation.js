$(document).ready(function() {
    let table;
    $(function () {
        table = $('#mutationTable').DataTable({
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
                url: `${BASE_URL}`+'/donation/mutations',
                data: {
                    '_method': "GET",
                    "_token": $('[name=csrf-token]').attr('content')
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
                {data: 'requester.name'},
                {data: 'invoice_number'},
                {data: 'trans_date'},
                {data: 'total_liq'},
                {data: 'total_amount'},
                {data: 'statusLabel'},
                {data: 'approver.name'},
                {
                    data: 'action',
                    className: 'text-center',
                    searchable: false,
                    sortable: false
                },
            ]
        });

    });

    $('#btnAddMutation').on('click', function(e) {
        e.preventDefault();
        $('input[name="invoice_number"]').val(`INV-${Date.now()}`);

        $.ajax({
            url: `${BASE_URL}/donation/search`,
            type: "GET",
            cache: false,
            data: {
                "_token": $('[name=csrf-token]').attr('content'),
                'status': 'Approved',
                'via_transfer': false,
            },
            success:function(response){
                $('#addMutationTable').DataTable().clear().destroy();
                $('#addMutationTable').DataTable({
                    responsive: true,
                    processing: true,
                    autoWidth: false,
                    "bFilter": false,
                    "bPaginate": false,
                    "ordering": true,
                    data: response.data,
                    columns: [
                        {
                            data: 'id',
                            className: 'sorting_1',
                            searchable: false,
                            sortable: false,
                            render: function (data, type, row) {
                                return '<label class="checkboxs">' +
                                            '<input type="checkbox" class="option-donation" name="donation_id[]" value="'+data+'">' +
                                            '<span class="checkmarks"></span>' +
                                        '</label>';
                            }
                        },
                        {
                            data: 'liq_number',
                            render: function (data, type, row) {
                                return '<input type="hidden" name="liq_number[]" value="'+data+'">' + data;
                            }
                        },
                        {data: 'donatur_name'},
                        {
                            data: 'trans_date',
                            render: function(data, type, row) {
                                return formatDate(data);
                            }
                        },
                        {
                            data: 'total_amount',
                            render: function(data, type, row) {
                            return '<input type="hidden" class="liq-amount" name="liq_amount[]" value="'+data+'">' +
                            new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data);
                        }
                        },
                    ]
                })
            }

        });
    });

    var selectAllItems = "#select-donation";
	var checkboxItem = ":checkbox";
	$(selectAllItems).click(function() {

		if (this.checked) {
		$(checkboxItem).each(function() {
			this.checked = true;
		});
		} else {
		$(checkboxItem).each(function() {
			this.checked = false;
		});
		}
        calculateTotal();
	});

    $('#mutationSubmit').on('click', function(e) {
        const formData = new FormData($('#formAddMutation')[0]);// Membuat FormData dari form yang disubmit
        $.ajax({
            type: 'POST',
            url: `${BASE_URL}`+'/donation/mutations/store', // Ganti dengan route yang sesuai
            data: formData,
            processData: false, // Untuk menghindari jQuery mengubah data
            contentType: false, // Memungkinkan pengiriman file
            success: function(response) {
                if (response.success) {
                        $('#toastSuccess').toast('show');
                        $('#toastSuccess .toast-body').text(response.message);
                        $('#formAddMutation')[0].reset();
                        $('#add-mutation').modal('hide');
                        reloadTable();

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

$(document).on('change', '.option-donation', function() {
    calculateTotal();
});


$(document).on('click', '.cancel-mutation', function() {
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
                    url: `${BASE_URL}`+'/donation/mutations/cancel/'+id,
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

$(document).on('click', '.approve-mutation', function() {
    let id = $(this).parent().parent().attr('id');
    Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "akan memverifikasi mutasi ini!",
        icon: 'info',
        showCancelButton: true,
        cancelButtonText: 'TIDAK',
        confirmButtonText: 'YA!'
    }).then((result) => {
        if (result.isConfirmed) {
            //fetch to delete data
            $.ajax({
                url: `${BASE_URL}`+'/donation/mutations/approve/'+id,
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

function reloadTable()
{
    $('#mutationTable').DataTable().ajax.reload();
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

function calculateTotal() {
    let total = 0;
    $('#addMutationTable .option-donation:checked').each(function() {
        // Mengambil harga di kolom harga yang bersangkutan
        let price = parseFloat($(this).closest('tr').find('.liq-amount').val());
        total += price;
    });
    // Menampilkan total harga
    $('input[name="total_amount"]').val(total);
    let totalAmount = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    }).format(total);
    $('#total_amount').val(totalAmount);
}

$(document).on('click', '.mutation-detail', function() {
    let id = $(this).parent().parent().attr('id');
    $.ajax({
        url: `${BASE_URL}`+'/donation/mutations/detail/'+id,
        type: "GET",
        cache: false,
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
        },
        success:function(response){
            let data = response.data
            $('#invoice_number').val(data.invoice_number);
            $('#trans_date').val(data.trans_date);
            let totalAmount = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(data.total_amount);
            $('#total_amount').val(totalAmount);
            $('#description').text(data.description);
            $('#viewMutationTable').DataTable().clear().destroy();
            $('#viewMutationTable').DataTable({
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
                    {data: 'liq_number'},
                    {data: 'donation.donatur_name'},
                    {data: 'donation.trans_date'},
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
