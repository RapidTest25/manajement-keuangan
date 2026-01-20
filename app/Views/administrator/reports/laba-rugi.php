<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th colspan="2">Laporan Laba Rugi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Pendapatan -->
            <tr>
                <td colspan="2" class="fw-bold">Pendapatan</td>
            </tr>
            <?php foreach (($categories['income'] ?? []) as $category => $amount): ?>
                <tr>
                    <td class="ps-4"><?= $category ?></td>
                    <td class="text-end">Rp <?= number_format($amount, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="table-light">
                <td class="fw-bold">Total Pendapatan</td>
                <td class="text-end fw-bold">Rp <?= number_format($totalIncome, 0, ',', '.') ?></td>
            </tr>

            <!-- Pengeluaran -->
            <tr>
                <td colspan="2" class="fw-bold">Pengeluaran</td>
            </tr>
            <?php foreach (($categories['expense'] ?? []) as $category => $amount): ?>
                <tr>
                    <td class="ps-4"><?= $category ?></td>
                    <td class="text-end text-danger">Rp <?= number_format($amount, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="table-light">
                <td class="fw-bold">Total Pengeluaran</td>
                <td class="text-end fw-bold text-danger">Rp <?= number_format($totalExpense, 0, ',', '.') ?></td>
            </tr>

            <!-- Laba/Rugi Bersih -->
            <tr class="table-primary">
                <td class="fw-bold">Laba/Rugi Bersih</td>
                <td class="text-end fw-bold <?= $netIncome >= 0 ? 'text-success' : 'text-danger' ?>">
                    Rp <?= number_format($netIncome, 0, ',', '.') ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>