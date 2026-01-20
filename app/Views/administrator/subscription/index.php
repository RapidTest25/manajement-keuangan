<?= $this->extend('layout/administrator/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-0 text-gray-800">Manajemen Langganan</h4>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan (Premium)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-dollar-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pendapatan Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($thisMonthRevenue, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Langganan User</h6>
            
            <a href="<?= base_url('administrator/subscription/export') ?>" class="btn btn-sm btn-success">
                <i class="bx bx-export"></i> Export Excel
            </a>
        </div>
        <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-subscriptions">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Paket</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptions as $key => $sub) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <div><strong><?= esc($sub['username']) ?></strong></div>
                                        <small class="text-muted"><?= esc($sub['email']) ?></small>
                                    </td>
                                    <td><?= esc($sub['plan_name']) ?></td>
                                    <td>Rp <?= number_format($sub['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($sub['status'] == 'active') : ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif ($sub['status'] == 'expired') : ?>
                                            <span class="badge bg-secondary">Expired</span>
                                        <?php elseif ($sub['status'] == 'cancelled') : ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning"><?= esc($sub['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($sub['start_date'])) ?></td>
                                    <td><?= date('d M Y', strtotime($sub['end_date'])) ?></td>
                                    <td>
                                        <?php if ($sub['status'] == 'active') : ?>
                                            <button class="btn btn-sm btn-danger btn-update-status" data-id="<?= $sub['id'] ?>" data-status="cancelled">
                                                Cancel
                                            </button>
                                        <?php elseif ($sub['status'] == 'cancelled' || $sub['status'] == 'expired') : ?>
                                            <!-- Optional: Reactivate -->
                                        <?php endif; ?>
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

<script>
    document.querySelectorAll('.btn-update-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            
            if(confirm('Apakah Anda yakin ingin mengubah status langganan ini?')) {
                fetch('<?= base_url('administrator/subscription/updateStatus') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `id=${id}&status=${status}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
<?= $this->endSection() ?>
