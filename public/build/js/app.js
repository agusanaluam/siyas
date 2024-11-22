import './component/location.js';
import './pages/profile.js';
import './pages/campaign/category.js';
import './pages/campaign/campaign.js';
import './pages/donation-app.js';
import './pages/donation/donation.js';
import './pages/donation/mutation.js';
import './pages/dashboard.js';
import './pages/volunteer/group.js';
import './pages/volunteer/volunteer.js';



$(document).ready(function() {
    $('#createDonation').on('click', function() {

        Swal.fire({
            title: "Silahkan isi nomor LIQ",
            input: "text",
            inputAttributes: {
                autocapitalize: "on"
            },
            showCancelButton: true,
            confirmButtonText: "Lanjutkan",
            showLoaderOnConfirm: true,
            preConfirm: (value) => {
                if (!value) {
                    return 'Nomor LIQ tidak boleh kosong!';
                }
                return $.ajax({
                    url: `${BASE_URL}/donation/check/${value}`,
                    type: "GET",
                    cache: false,
                    data: {
                            '_method': "GET",
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                        },
                    }).then(response => {
                        if (!response.success) {
                            // Jika nomor sudah ada, tampilkan error
                            Swal.showValidationMessage(response.message);
                            return false;
                        } else {
                            // Jika tidak ada, lanjutkan ke halaman lain
                            window.location.href = `${BASE_URL}/donation/create/${value}`;
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage('Terjadi kesalahan, silakan coba lagi');
                    });

            },
            allowOutsideClick: () => !Swal.isLoading()
        })
    })
});
