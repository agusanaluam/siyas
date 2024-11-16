// Mengambil data kota berdasarkan provinsi
$('#provinsi').change(function () {
    var provinsiId = $(this).val();
    $.get('/location/get-kota/' + provinsiId, function (data) {
        $('#kota').empty().append('<option value="">Pilih Kota</option>');
        $.each(data, function (index, kota) {
            $('#kota').append('<option value="' + kota.code + '">' + kota.name + '</option>');
        });
    });
});

// Mengambil data kecamatan berdasarkan kota
$('#kota').change(function () {
    var kotaId = $(this).val();
    $.get('/location/get-kecamatan/' + kotaId, function (data) {
        $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
        $.each(data, function (index, kecamatan) {
            $('#kecamatan').append('<option value="' + kecamatan.code + '">' + kecamatan.name + '</option>');
        });
    });
});

// Mengambil data desa berdasarkan kecamatan
$('#kecamatan').change(function () {
    var kecamatanId = $(this).val();
    $.get('/location/get-desa/' + kecamatanId, function (data) {
        $('#desa').empty().append('<option value="">Pilih Desa</option>');
        $.each(data, function (index, desa) {
            $('#desa').append('<option value="' + desa.code + '">' + desa.name + '</option>');
        });
    });
});
