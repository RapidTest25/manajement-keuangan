<?= $this->extend('layout/administrator/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <h2 class="h4 mb-4">Daftar Cicilan</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Total</th>
                            <th>Cicilan/Bulan</th>
                            <th>Status</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($cicilan ?? []) as $row): ?>
                            <tr>
                                <td><?= esc($row['name']) ?></td>
                                <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['monthly_amount'], 0, ',', '.') ?></td>
                                <td><?= esc($row['status']) ?></td>
                                <td><?= esc($row['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>