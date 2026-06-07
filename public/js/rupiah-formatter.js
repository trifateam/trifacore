document.addEventListener('DOMContentLoaded', function () {
    // 1. Format Rupiah on input
    const rupiahInputs = document.querySelectorAll('.rupiah-input');

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    rupiahInputs.forEach(function (input) {
        // Init format on load
        if (input.value) {
            input.value = formatRupiah(input.value);
        }

        // Format on keyup
        input.addEventListener('keyup', function (e) {
            input.value = formatRupiah(this.value);
        });
    });

    // 2. Clean Rupiah format before submit
    const forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            // Find all rupiah inputs in this form
            const formRupiahInputs = form.querySelectorAll('.rupiah-input');
            formRupiahInputs.forEach(function (input) {
                // Remove all dots
                input.value = input.value.replace(/\./g, '');
            });
        });
    });
});
