<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th colspan="2">Laporan Arus Kas</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Awal -->
            <tr class="table-light">
                <td class="fw-bold">Saldo Awal</td>
                <td class="text-end fw-bold">Rp <?= number_format($openingBalance, 0, ',', '.') ?></td>
            </tr>

            <!-- Arus Kas Operasional -->
            <tr>
                <td colspan="2" class="fw-bold">Arus Kas Operasional</td>
            </tr>
            <tr>
                <td class="ps-4">Penerimaan Kas</td>
                <td class="text-end text-success">Rp <?= number_format($cashFlow['operating']['in'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="ps-4">Pengeluaran Kas</td>
                <td class="text-end text-danger">Rp <?= number_format($cashFlow['operating']['out'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Arus Kas Bersih dari Aktivitas Operasional</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($cashFlow['operating']['in'] - $cashFlow['operating']['out'], 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Arus Kas Investasi -->
            <tr>
                <td colspan="2" class="fw-bold">Arus Kas Investasi</td>
            </tr>
            <tr>
                <td class="ps-4">Penerimaan Kas</td>
                <td class="text-end text-success">Rp <?= number_format($cashFlow['investing']['in'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="ps-4">Pengeluaran Kas</td>
                <td class="text-end text-danger">Rp <?= number_format($cashFlow['investing']['out'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Arus Kas Bersih dari Aktivitas Investasi</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($cashFlow['investing']['in'] - $cashFlow['investing']['out'], 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Arus Kas Pendanaan -->
            <tr>
                <td colspan="2" class="fw-bold">Arus Kas Pendanaan</td>
            </tr>
            <tr>
                <td class="ps-4">Penerimaan Kas</td>
                <td class="text-end text-success">Rp <?= number_format($cashFlow['financing']['in'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="ps-4">Pengeluaran Kas</td>
                <td class="text-end text-danger">Rp <?= number_format($cashFlow['financing']['out'], 0, ',', '.') ?></td>
            </tr>
            <tr class="table-light">
                <td class="ps-4 fw-bold">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($cashFlow['financing']['in'] - $cashFlow['financing']['out'], 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Total Perubahan Kas -->
            <tr class="table-primary">
                <td class="fw-bold">Total Perubahan Kas</td>
                <td class="text-end fw-bold">
                    Rp <?= number_format($currentBalance - $openingBalance, 0, ',', '.') ?>
                </td>
            </tr>

            <!-- Saldo Akhir -->
            <tr class="table-info">
                <td class="fw-bold">Saldo Akhir</td>
                <td class="text-end fw-bold">Rp <?= number_format($currentBalance, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</div>