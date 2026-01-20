<?= $this->extend('layout/administrator/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <h2 class="h4 mb-4">Daftar Target Tabungan User</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Target</th>
                            <th>Tercapai</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($savings ?? []) as $row): ?>
                            <tr>
                                <td><?= esc($row['username']) ?></td>
                                <td>Rp <?= number_format($row['target_amount'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['saved_amount'], 0, ',', '.') ?></td>
                                <td><?= date('d M Y', strtotime($row['target_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>