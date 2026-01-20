<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th colspan="2">Laporan Neraca</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aset -->
            <tr>
                <td colspan="2" class="fw-bold">ASET</td>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold ps-4">Aset Lancar</td>
            </tr>
            <tr>
                <td class="ps-5">Kas dan Setara Kas</td>
                <td class="text-end">Rp <?= number_format($assets['current'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Total Aset Lancar</td>
                <td class="text-end fw-bold">Rp <?= number_format($assets['current'], 0, ',', '.') ?></td>
            </tr>

            <tr>
                <td colspan="2" class="fw-bold ps-4">Aset Tetap</td>
            </tr>
            <tr>
                <td class="ps-5">Peralatan</td>
                <td class="text-end">Rp <?= number_format($assets['fixed'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Total Aset Tetap</td>
                <td class="text-end fw-bold">Rp <?= number_format($assets['fixed'], 0, ',', '.') ?></td>
            </tr>

            <tr class="table-primary">
                <td class="fw-bold">TOTAL ASET</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($assets['current'] + $assets['fixed'], 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Liabilitas -->
            <tr>
                <td colspan="2" class="fw-bold">LIABILITAS</td>
            </tr>
            <tr>
                <td colspan="2" class="fw-bold ps-4">Liabilitas Jangka Pendek</td>
            </tr>
            <tr>
                <td class="ps-5">Hutang Usaha</td>
                <td class="text-end">Rp <?= number_format($liabilities['current'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Total Liabilitas Jangka Pendek</td>
                <td class="text-end fw-bold">Rp <?= number_format($liabilities['current'], 0, ',', '.') ?></td>
            </tr>

            <tr>
                <td colspan="2" class="fw-bold ps-4">Liabilitas Jangka Panjang</td>
            </tr>
            <tr>
                <td class="ps-5">Hutang Bank</td>
                <td class="text-end">Rp <?= number_format($liabilities['longTerm'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Total Liabilitas Jangka Panjang</td>
                <td class="text-end fw-bold">Rp <?= number_format($liabilities['longTerm'], 0, ',', '.') ?></td>
            </tr>

            <tr class="table-primary">
                <td class="fw-bold">TOTAL LIABILITAS</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($liabilities['current'] + $liabilities['longTerm'], 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Ekuitas -->
            <tr>
                <td colspan="2" class="fw-bold">EKUITAS</td>
            </tr>
            <tr>
                <td class="ps-4">Modal</td>
                <td class="text-end">Rp <?= number_format($equity, 0, ',', '.') ?></td>
            </tr>
            <tr class="table-primary">
                <td class="fw-bold">TOTAL EKUITAS</td>
                <td class="text-end fw-bold">Rp <?= number_format($equity, 0, ',', '.') ?></td>
            </tr>

            <!-- Total Liabilitas dan Ekuitas -->
            <tr class="table-info">
                <td class="fw-bold">TOTAL LIABILITAS DAN EKUITAS</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($liabilities['current'] + $liabilities['longTerm'] + $equity, 0, ',', '.') ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>