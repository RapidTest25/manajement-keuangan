<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Statistik Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.4;
        }

        .container {
            padding: 20px;
        }

        .income {
            color: #009E60;
        }

        .expense {
            color: #dc3545;
        }

        .transactions {
            font-size: 0.9em;
            margin-top: 20px;
        }

        .transactions td {
            padding: 6px 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #009E60;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }

        h1,
        h2 {
            color: #009E60;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background: #f5f5f5;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= base_url('assets/images/logo-nama-png.png') ?>" alt="FinanceFlow" style="height: 60px; margin-bottom: 20px;">
            <h1>Laporan Statistik Keuangan</h1>
            <p>Periode: <?= date('F Y', strtotime($month)) ?></p>
        </div>

        <div class="summary">
            <h2>Ringkasan</h2>
            <p>Total Pemasukan: Rp <?= number_format($summary['income'], 0, ',', '.') ?></p>
            <p>Total Pengeluaran: Rp <?= number_format($summary['expense'], 0, ',', '.') ?></p>
        </div>

        <?php if (!empty($categories)): ?>
            <h2>Detail Kategori</h2> <!-- Category Summary -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['category'] ?></td>
                            <td><?= $category['status'] ?></td>
                            <td class="<?= strtolower($category['status']) ?>">
                                <?= $category['status'] === 'INCOME' ? '+' : '-' ?> Rp <?= number_format($category['total'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Transaction Details -->
            <h2>Detail Transaksi</h2>
            <table class="table transactions">
                <thead>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <th>Tanggal Input</th>
                        <th>ID Transaksi</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($tx['transaction_date'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></td>
                            <td><?= $tx['transaction_id'] ?></td>
                            <td><?= $tx['category'] ?></td>
                            <td><?= $tx['description'] ?: '-' ?></td>
                            <td class="<?= strtolower($tx['status']) ?>">
                                <?= $tx['status'] === 'INCOME' ? '+' : '-' ?> Rp <?= number_format($tx['amount'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>