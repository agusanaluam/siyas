$(document).ready(function() {

    let table;
    $(function () {
        table = $('#campaignCategoryTable').DataTable({
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
                url: `${BASE_URL}`+'/campaign/category',
                data: {
                    '_method': "GET",
                    "_token": $('[name=csrf-token]').attr('content'),
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
                {data: 'name'},
                {data: 'pic'},
                {data: 'created_at'},
                {data: 'statusLabel'},
                {
                    data: 'action',
                    className: 'action-table-data',
                    searchable: false,
                    sortable: false
                },
            ]
        });

    });

    $('#btn-save').on('click', function(e) {
        e.preventDefault();
        save();
    });

    $('#btn-edit').on('click', function(e) {
        e.preventDefault();
        update();
    });

});

$(document).on('click', '.edit', function() {
    let id = $(this).attr('data-id');
    edit(id)
});

$(document).on('click', '.delete', function() {
    let id = $(this).attr('data-id');
    deleteData(id)
});

function reloadTable()
{
    $('#campaignCategoryTable').DataTable().ajax.reload();
}


function edit(id) {
        $.ajax({
            url: `${BASE_URL}`+`/campaign/category/`+id,
            type: "GET",
            cache: false,
            data: {
                    '_method': "GET",
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
            success:function(response){
                //fill data to form
                $("input[name='id']").val(response.data.id);
                $("input[name='name']").val(response.data.name);
                $("textarea[name='description']").val(response.data.description);
                $("input[name='pic']").val(response.data.pic);
                if (response.data.status == true ) {
                    $("input[name='status']").prop('checked', true);
                }
            }
        });
}

function update() {

        const formData = new FormData($('#edit-campaign-category-form')[0]);

        // Memanggil AJAX
        var url = `${BASE_URL}`+'/campaign/category/update';

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
                $('#edit-campaign-category').modal('hide');
                 reloadTable();

            },
            error: function(xhr) {
                const errors = xhr.responseJSON.message;
                $('#toastDanger').toast('show');
                $('#toastDanger .toast-body').text(errors);
            }
        });

}

function save() {

    const formData = new FormData($('#add-campaign-category-form')[0]);

        // Memanggil AJAX
        var url = `${BASE_URL}`+'/campaign/category/store';

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
                $('#add-campaign-category').modal('hide');
                $('#add-campaign-category-form')[0].reset()
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
                    url: `${BASE_URL}`+'/campaign/category/'+id,
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
