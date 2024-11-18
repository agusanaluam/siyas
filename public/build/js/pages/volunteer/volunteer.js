$(document).ready(function() {

    let table;
    $(function () {
        table = $('#volunteerTable').DataTable({
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
                url: `${BASE_URL}`+'/volunteer',
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
                {data: 'group.name'},
                {data: 'profile'},
                {data: 'sex'},
                {data: 'phone_number'},
                {data: 'statusLabel'},
                {data: 'user.level'},
                {data: 'verified_at'},
                {
                    data: 'action',
                    className: 'action-table-data',
                    searchable: false,
                    sortable: false
                },
            ]
        });

    });

    $('#group').select2({
        placeholder: 'Please select',
        ajax: {
            url: `${BASE_URL}`+'/volunteer/group/data', // URL endpoint untuk pencarian produk
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
            processResults: function(response) {
                if (response.success){
                    return {
                        results: response.data.map(group => ({
                            id: group.id,
                            text: `${group.name}`,
                        }))
                    };
                }
            },
            cache: true
        }
    });

    $('#volunteer-save').on('click', function(e) {
        e.preventDefault();
        save();
    });
});

function reloadTable()
{
    $('#volunteerTable').DataTable().ajax.reload();
}

$(document).on('click', '.delete', function() {
    let id = $(this).attr('data-id');
    deleteData(id)
});

function save() {

    const formData = new FormData($('#add-volunteer-form')[0]);

        // Memanggil AJAX
        var url = `${BASE_URL}`+'/volunteer/store';

        $.ajax({
            type: 'POST',
            url: url, // Ganti dengan route yang sesuai
            data: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                $('#add-volunteer').modal('hide');
                $('#add-volunteer-form')[0].reset()
                reloadTable();

            },
            error: function(xhr) {
                const errors = xhr.responseJSON.message;
                $('#toastDanger').toast('show');
                $('#toastDanger .toast-body').text(errors);
            }
        });

}

function deleteData(id) {
    Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "ingin menghapus data ini!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then((result) => {
            if (result.isConfirmed) {

                //fetch to delete data
                $.ajax({
                    url: `${BASE_URL}`+'/volunteer/'+id,
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
                    }
                });


            }
        })

}
