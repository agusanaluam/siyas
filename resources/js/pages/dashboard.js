$(document).ready(function() {
    let table;
    $(function () {
        table = $('#volunteerLeaderboardTable').DataTable({
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
                url: `${BASE_URL}`+'/dashboard/leaderboard',
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
                {data:'group.name'},
                {
                    data: 'name',
                    render: function(data, type, row) {
                        return `<div class="productimgname">
                                    <a href="javascript:void(0);" class="product-img stock-img">
                                        <img src="${BASE_URL}/storage/`+row.profile_picture+`"
                                            alt="product">
                                    </a>
                                    <a href="javascript:void(0);">`+data+` </a>
                                </div>`
                    }
                },
                {data: 'totalProgram'},
                {data: 'totalReceipt'},
                {data: 'totalCoupon'},
                {
                    data: 'points',
                    render: function(data, type, row) {
                        return `
                            <div class="stars-points" data-rate="${data}"></div>`;
                    }
                },
            ],
            createdRow: function(row, data) {
            // Temukan elemen di kolom "rating" dan inisialisasi Rater.js
            const ratingElement = $(row).find('.stars-points')[0];
            new raterJs({
                element: ratingElement,
                rating: data.points, // Sesuaikan agar point bisa diubah ke rating (misal 0-5)
                max: 10,
                readOnly: true,
                step: 0.1
            });
        }
        });
    });

    // Event listener untuk filter range
    $('#range').on('change', function () {
        loadChartData();
    });
    $('#category').on('change', function () {
        loadChartData();
    });

    // Panggil fungsi pertama kali dengan default range
    loadChartData();
});

function loadChartData() {
    $.ajax({
        url: `${BASE_URL}/donation/chart`,
        method: 'GET',
        data: {
            '_method': "GET",
            "_token": $('[name=csrf-token]').attr('content'),
            "category": $('#category').val(),
            "range": $('#range').val(),
        },
        success: function (data) {
            const periods = data.map(item => item.period);
            const jumlahKuitansi = data.map(item => item.total_coupon);
            const totalNominal = data.map(item => item.total_amount);

            // Konfigurasi ulang chart dengan data baru
            const options = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false,
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: false
                },
                series: [
                    {
                        name: 'Total Amount',
                        data: totalNominal
                    },
                    {
                        name: 'Total Coupon',
                        data: jumlahKuitansi
                    }
                ],
                xaxis: {
                    categories: periods
                },
                yaxis: [
                    {
                        title: {
                            text: 'Total Coupon'
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Total Amount'
                        }
                    }
                ]
            };

            $("#donation-charts").empty(); // Hapus chart sebelumnya
            const chart = new ApexCharts(document.querySelector("#donation-charts"), options);
            chart.render();
        },
        error: function (error) {
            console.log("Error fetching chart data:", error);
        }
    });
}
