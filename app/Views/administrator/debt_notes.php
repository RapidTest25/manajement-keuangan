<?= $this->extend('layout/administrator/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <h2 class="h4 mb-4">Daftar Catatan Utang</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($debtNotes ?? []) as $row): ?>
                            <tr>
                                <td><?= esc($row['username']) ?></td>
                                <td><?= esc($row['description']) ?></td>
                                <td>Rp <?= number_format($row['loan_amount'], 0, ',', '.') ?></td>
                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                <td><?= esc($row['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>