<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('css') ?>
<link href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
<style>
    :root {
        --white: #ffffff;
        --primary-bg: #f8f9fa;
        --card-bg: #ffffff;
        --text-primary: #2c2c2c;
        --text-secondary: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --border-color: #e9ecef;
        --income-color: #28a745;
        --expense-color: #dc3545;
        --balance-color: #17a2b8;
        --total-color: #6f42c1;
    }

    body {
        background-color: var(--primary-bg);
    }

    .card {
        background: var(--card-bg);
        border: none;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .stat-card {
        padding: 1.5rem;
        background: var(--white);
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.income {
        border-left: 4px solid var(--income-color);
    }

    .stat-card.expense {
        border-left: 4px solid var(--expense-color);
    }

    .stat-card.balance {
        border-left: 4px solid var(--balance-color);
    }

    .stat-card.total {
        border-left: 4px solid var(--total-color);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .stat-icon {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 2rem;
        opacity: 0.2;
    }

    .chart-container {
        padding: 1.5rem;
        background: var(--white);
        border-radius: 10px;
        position: relative;
        min-height: 300px;
        margin-top: 1rem;
    }

    .chart-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .table-section {
        margin-top: 2rem;
    }

    .table-section .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .table {
        font-size: 0.9rem;
    }

    .table thead th {
        background: var(--white);
        color: var(--text-secondary);
        font-weight: 500;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        color: var(--text-primary);
        vertical-align: middle;
    }

    .positive-amount {
        color: var(--income-color);
        font-weight: 500;
    }

    .negative-amount {
        color: var(--expense-color);
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.selesai {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--income-color);
    }

    .status-badge.pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status-badge.batal {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--expense-color);
    }

    .btn-toolbar {
        gap: 0.5rem;
    }

    .filter-section {
        background: var(--white);
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .form-label {
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }

        .table-responsive {
            border: none;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-2 mb-4">
    <h2 class="h4 text-secondary">Dashboard Overview</h2>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-success" id="refreshData">
                <i class='bx bx-refresh me-1'></i> Refresh Data
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class='bx bx-user-plus me-1'></i> Tambah User
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Pendapatan -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card income">
            <div class="stat-value">Rp <?= number_format($dashboardStats['totalIncome'], 0, ',', '.') ?></div>
            <div class="stat-label">Total Pendapatan</div>
            <small class="text-muted"><?= ($dashboardStats['incomeGrowth'] >= 0 ? '+' : '') ?><?= number_format($dashboardStats['incomeGrowth'], 1) ?>% dari bulan lalu</small>
            <i class='bx bx-trending-<?= $dashboardStats['incomeGrowth'] >= 0 ? 'up' : 'down' ?> stat-icon'></i>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card expense">
            <div class="stat-value">Rp <?= number_format($dashboardStats['totalExpense'], 0, ',', '.') ?></div>
            <div class="stat-label">Total Pengeluaran</div>
            <small class="text-muted"><?= ($dashboardStats['expenseGrowth'] >= 0 ? '+' : '') ?><?= number_format($dashboardStats['expenseGrowth'], 1) ?>% dari bulan lalu</small>
            <i class='bx bx-trending-<?= $dashboardStats['expenseGrowth'] >= 0 ? 'up' : 'down' ?> stat-icon'></i>
        </div>
    </div>

    <!-- Saldo Bersih -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card balance">
            <div class="stat-value">Rp <?= number_format($dashboardStats['netBalance'], 0, ',', '.') ?></div>
            <div class="stat-label">Saldo Bersih</div>
            <small class="text-muted">Kalkulasi Pendapatan - Pengeluaran</small>
            <i class='bx bx-wallet stat-icon'></i>
        </div>
    </div>

    <!-- Total Pengguna -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card total">
            <div class="stat-value"><?= number_format($dashboardStats['totalUsers']) ?></div>
            <div class="stat-label">Pengguna Aktif</div>
            <small class="text-muted"><?= ($dashboardStats['userGrowth'] >= 0 ? '+' : '') ?><?= number_format($dashboardStats['userGrowth'], 1) ?>% pertumbuhan</small>
            <i class='bx bx-group stat-icon'></i>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <!-- Financial Summary Chart -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="section-title">Ringkasan Keuangan</h5>
                <p class="text-muted">Perbandingan Pendapatan vs Pengeluaran 6 Bulan Terakhir</p>
                <div class="chart-container">
                    <canvas id="financialSummary"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Distribution Chart -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="section-title">Distribusi Pengeluaran</h5>
                <p class="text-muted">Berdasarkan kategori bulan ini</p>
                <div class="chart-container">
                    <canvas id="expenseDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="section-title mb-0">Transaksi Terbaru</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class='bx bx-filter-alt'></i> Filter
                </button>
                <button class="btn btn-sm btn-outline-secondary" id="exportTransactions">
                    <i class='bx bx-export'></i> Export
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTransactions as $transaction): ?>
                        <tr>
                            <td><?= $transaction['id'] ?></td>
                            <td><?= date('d M Y', strtotime($transaction['created_at'])) ?></td>
                            <td><?= $transaction['username'] ?></td>
                            <td><?= $transaction['description'] ?></td>
                            <td><?= $transaction['category_name'] ?></td>
                            <td class="<?= $transaction['status'] === 'INCOME' ? 'positive-amount' : 'negative-amount' ?>">
                                <?= $transaction['status'] === 'INCOME' ? '+' : '-' ?>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                            </td>
                            <td><span class="status-badge <?= strtolower($transaction['status']) ?>"><?= $transaction['status'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-success">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="saveUser">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- View Transaction Modal -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-success">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="transaction-details">
                    <!-- Details will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success approve-btn" style="display: none;">Setujui</button>
                <button type="button" class="btn btn-danger reject-btn" style="display: none;">Tolak</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Financial Summary Chart
        const financialCanvas = document.getElementById('financialSummary');
        const financialContainer = financialCanvas.parentElement;
        const trendLabels = <?= json_encode($trendData['labels']) ?>;
        const trendIncome = <?= json_encode($trendData['income']) ?>;
        const trendExpense = <?= json_encode($trendData['expense']) ?>;
        if (financialCanvas) {
            if (!trendLabels || trendLabels.length === 0) {
                financialCanvas.style.display = 'none';
                const msg = document.createElement('div');
                msg.className = 'text-center text-muted';
                msg.innerText = 'Tidak ada data tren transaksi';
                financialContainer.appendChild(msg);
            } else {
                financialCanvas.style.display = '';
                new Chart(financialCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: trendIncome,
                            borderColor: 'rgb(40, 167, 69)',
                            tension: 0.4,
                            fill: false
                        }, {
                            label: 'Pengeluaran',
                            data: trendExpense,
                            borderColor: 'rgb(220, 53, 69)',
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
        // Expense Distribution Chart
        const expenseCanvas = document.getElementById('expenseDistribution');
        const expenseContainer = expenseCanvas.parentElement;
        const categoryLabels = <?= json_encode($categoryDistribution['labels']) ?>;
        const categoryData = <?= json_encode($categoryDistribution['data']) ?>;
        if (expenseCanvas) {
            if (!categoryLabels || categoryLabels.length === 0) {
                expenseCanvas.style.display = 'none';
                const msg = document.createElement('div');
                msg.className = 'text-center text-muted';
                msg.innerText = 'Tidak ada data distribusi kategori';
                expenseContainer.appendChild(msg);
            } else {
                expenseCanvas.style.display = '';
                new Chart(expenseCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryData,
                            backgroundColor: [
                                'rgb(40, 167, 69)',
                                'rgb(220, 53, 69)',
                                'rgb(23, 162, 184)',
                                'rgb(111, 66, 193)',
                                'rgb(255, 193, 7)',
                                'rgb(23, 162, 184)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }
    });
</script>
<?= $this->endSection() ?>