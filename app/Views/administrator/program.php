<?= $this->extend('layout/administrator/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Program Monitoring</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>

    <div class="row">
        <!-- Category Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="card-title mb-1">Kategori</h6>
                            <span class="text-muted">Total Kategori</span>
                        </div>
                        <div class="avatar bg-light-primary">
                            <div class="avatar-title rounded">
                                <i class="ri-price-tag-3-line font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-2"><?= $totalCategories ?? 0 ?></h3>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-success text-success">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cicilan Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="card-title mb-1">Cicilan</h6>
                            <span class="text-muted">Total Cicilan Aktif</span>
                        </div>
                        <div class="avatar bg-light-warning">
                            <div class="avatar-title rounded">
                                <i class="ri-money-dollar-circle-line font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-2"><?= $totalCicilan ?? 0 ?></h3>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-warning text-warning">Berjalan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Utang Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="card-title mb-1">Catatan Utang</h6>
                            <span class="text-muted">Total Catatan</span>
                        </div>
                        <div class="avatar bg-light-danger">
                            <div class="avatar-title rounded">
                                <i class="ri-file-list-3-line font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-2"><?= $totalDebtNotes ?? 0 ?></h3>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-danger text-danger">Perlu Diperhatikan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Settings Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="card-title mb-1">Pengaturan User</h6>
                            <span class="text-muted">Total User Aktif</span>
                        </div>
                        <div class="avatar bg-light-info">
                            <div class="avatar-title rounded">
                                <i class="ri-user-settings-line font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-2"><?= $totalUsers ?? 0 ?></h3>
                    <div class="text-nowrap">
                        <span class="badge bg-soft-info text-info">Terdaftar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoring Tables Section -->
    <div class="row">
        <!-- Latest Categories -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Kategori Terbaru</h4>
                        <a href="<?= base_url('administrator/categories') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Tipe</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($latestCategories ?? []) as $category): ?>
                                    <tr>
                                        <td><?= esc($category['name']) ?></td>
                                        <td><span class="badge bg-soft-<?= strtolower($category['type']) === 'expense' ? 'danger' : 'success' ?> text-<?= strtolower($category['type']) === 'expense' ? 'danger' : 'success' ?>"><?= $category['type'] === 'EXPENSE' ? 'Pengeluaran' : 'Pemasukan' ?></span></td>
                                        <td><?= esc($category['username']) ?></td>
                                        <td><?= date('d M Y', strtotime($category['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Cicilan -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Cicilan Terbaru</h4>
                        <a href="<?= base_url('administrator/cicilan') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Total</th>
                                    <th>Cicilan/Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($latestCicilan ?? []) as $cicilan): ?>
                                    <tr>
                                        <td><?= esc($cicilan['username']) ?> - <?= esc($cicilan['name']) ?></td>
                                        <td>Rp <?= number_format($cicilan['total_amount'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($cicilan['monthly_amount'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge bg-soft-<?= strtolower($cicilan['status']) == 'lunas' ? 'success' : 'warning' ?> text-<?= strtolower($cicilan['status']) == 'lunas' ? 'success' : 'warning' ?>">
                                                <?= esc($cicilan['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Debt Notes -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Catatan Utang Terbaru</h4>
                        <a href="<?= base_url('administrator/debt-notes') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
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
                                <?php foreach (($latestDebtNotes ?? []) as $debtNote): ?>
                                    <tr>
                                        <td><?= esc($debtNote['username']) ?></td>
                                        <td><?= esc($debtNote['description']) ?></td>
                                        <td>Rp <?= number_format($debtNote['loan_amount'], 0, ',', '.') ?></td>
                                        <td><?= date('d M Y', strtotime($debtNote['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-soft-<?= strtolower($debtNote['status']) == 'paid' ? 'success' : 'danger' ?> text-<?= strtolower($debtNote['status']) == 'paid' ? 'success' : 'danger' ?>">
                                                <?= esc($debtNote['status']) == 'paid' ? 'Lunas' : 'Belum Lunas' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Savings Targets -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Target Menabung User</h4>
                        <a href="<?= base_url('administrator/savings') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
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
                                <?php foreach (($userSavingsTargets ?? []) as $target): ?>
                                    <tr>
                                        <td><?= esc($target['username']) ?></td>
                                        <td>Rp <?= number_format($target['target_amount'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($target['saved_amount'], 0, ',', '.') ?></td>
                                        <td><?= date('d M Y', strtotime($target['target_date'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Users -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">User Terbaru</h4>
                        <a href="<?= base_url('administrator/users') ?>" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (($latestUsers ?? []) as $user): ?>
                                    <tr>
                                        <td><?= esc($user['fullname'] ?? $user['username']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-soft-<?= $user['status'] == 'Active' ? 'success' : 'danger' ?> text-<?= $user['status'] == 'Active' ? 'success' : 'danger' ?>">
                                                <?= esc($user['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .bg-light-primary {
        background-color: rgba(85, 110, 230, 0.15) !important;
    }

    .bg-light-warning {
        background-color: rgba(255, 188, 0, 0.15) !important;
    }

    .bg-light-danger {
        background-color: rgba(241, 85, 108, 0.15) !important;
    }

    .bg-light-info {
        background-color: rgba(52, 195, 143, 0.15) !important;
    }

    .avatar-title {
        color: #556ee6;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .bg-light-warning .avatar-title {
        color: #f1b44c;
    }

    .bg-light-danger .avatar-title {
        color: #f1556c;
    }

    .bg-light-info .avatar-title {
        color: #34c38f;
    }

    .card {
        margin-bottom: 24px;
        box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
    }

    .table {
        font-size: 14px;
    }

    .badge {
        font-size: 12px;
        padding: 5px 10px;
    }

    .bg-soft-success {
        background-color: rgba(52, 195, 143, 0.15) !important;
    }

    .bg-soft-warning {
        background-color: rgba(241, 180, 76, 0.15) !important;
    }

    .bg-soft-danger {
        background-color: rgba(241, 85, 108, 0.15) !important;
    }

    .bg-soft-info {
        background-color: rgba(52, 195, 143, 0.15) !important;
    }

    .font-size-20 {
        font-size: 20px;
    }
</style>
<?= $this->endSection() ?>