import './component/location';
import './pages/profile';
import './pages/campaign/category';
import './pages/campaign/campaign';
import './pages/donation-app';
import './pages/donation/donation';
import './pages/donation/mutation';
import './pages/dashboard';
import './pages/volunteer/group';
import './pages/volunteer/volunteer';



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
