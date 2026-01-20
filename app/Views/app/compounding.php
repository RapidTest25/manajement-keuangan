<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<head>
    <meta charset="UTF-8">
    <title>Kalkulator Suku Bunga</title>

    <!-- My CSS STYLE -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sukubunga.css'); ?>">
</head>

<body>
    <div class="container">
        <h1>Kalkulator Compound Interest</h1>
        <form id="compoundForm">
            <label for="principal">Pokok Investasi (Rp):</label>
            <input type="number" id="principal" required>

            <label for="interest">Bunga per Tahun (%):</label>
            <input type="number" step="0.01" id="interest" required>

            <label for="years">Jumlah Tahun:</label>
            <input type="number" id="years" required>

            <button type="submit">Hitung</button>
        </form>

        <div id="result"></div>
    </div>

    <script>
        document.getElementById('compoundForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const P = parseFloat(document.getElementById('principal').value);
            const i = parseFloat(document.getElementById('interest').value) / 100;
            const n = parseInt(document.getElementById('years').value);

            let resultDiv = document.getElementById('result');
            let html = "<h2>Hasil Perhitungan:</h2>";
            html += "<table><tr><th>Tahun</th><th>Bunga (Rp)</th><th>Total Dana (Rp)</th></tr>";

            let total = P;
            for (let year = 1; year <= n; year++) {
                let interestEarned = total * i;
                total += interestEarned;
                html += `<tr>
               <td>${year}</td>
               <td>${formatRupiah(interestEarned)}</td>
               <td>${formatRupiah(total)}</td>
             </tr>`;
            }

            html += "</table>";
            resultDiv.innerHTML = html;
        });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }
    </script>
</body>

<?= $this->endSection(); ?>