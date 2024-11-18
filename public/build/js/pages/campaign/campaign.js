$(document).ready(function() {

    let table;
    $(function () {
        table = $('#campaignTable').DataTable({
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
                url: `${BASE_URL}`+'/campaign',
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
                {data: 'campaign'},
                {data: 'pic'},
                {data: 'start_date'},
                {data: 'end_date'},
                {data: 'target_amount'},
                {data: 'close_type'},
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

    $('#campaign_picture').on('change', function(event) {
        let previewContainer = $('.add-choosen');
        // previewContainer.children('div.phone-img').remove();

        $.each(event.target.files, function(index, file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                // Tambahkan gambar dan ikon untuk menghapus
                let imgHtml = `
                    <div class="phone-img">
                        <img src="${e.target.result}" alt="image" style="width:100px; height:100px; object-fit:cover;">
                        <a href="javascript:void(0);" class="remove-img-campaign" data-index="${index}">
                            <i data-feather="x" class="x-square-add">x</i>
                        </a>
                    </div>
                `;
                previewContainer.append(imgHtml);
            };
            reader.readAsDataURL(file);
        });

    });

});

function reloadTable()
{
    $('#campaignTable').DataTable().ajax.reload();
}
$(document).on("click",".remove-img-campaign",function () {
	$(this).parent().remove();
});

$(document).on('click', '.delete', function() {
    let id = $(this).attr('data-id');
    deleteData(id)
});

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
                    url: `${BASE_URL}`+'/campaign/'+id,
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
