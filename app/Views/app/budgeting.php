<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-4" style="max-width: 800px;">
    <!-- Budget Summary Card -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Anggaran Harian</h5>
        </div>
        <div class="card-body">
            <div class="budget-status mb-3">
                <h6>Status Budget Hari Ini:</h6>
                <div class="budget-progress">
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: 0%; transition: width 0.5s ease-in-out;"
                            id="budgetProgressBar">
                        </div>
                    </div>
                    <div class="budget-details mt-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Budget Harian:</small><br>
                                <span class="fw-bold text-dark" id="budgetTarget">Rp 0</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Terpakai:</small><br>
                                <span class="fw-bold text-dark" id="budgetUsed">Rp 0</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Sisa:</small><br>
                                <span class="fw-bold text-dark" id="budgetRemaining">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Settings -->
                <div class="mt-4">
                    <form id="budgetForm">
                        <div class="mb-3">
                            <label for="dailyBudget" class="form-label">Budget Harian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="dailyBudget" name="daily_budget"
                                    value="<?= $budget['daily_budget'] ?? 100000 ?>" required min="1000">
                            </div>
                            <div class="form-text">Masukkan budget pengeluaran harian Anda (minimal Rp 1.000). Budget default adalah Rp 100.000 per hari.</div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-success" id="saveBudget">Update Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Statistics Card -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Statistik Pengeluaran</h5>
        </div>
        <div class="card-body">
            <canvas id="budgetChart" height="250"></canvas>
        </div>
    </div>
</div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/chart-budget.css'); ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2"></script>
<script src="<?= base_url('assets/js/budget-management.js') ?>"></script>
<script>
    const ctx = document.getElementById('budgetChart').getContext('2d');
    const chartData = <?= json_encode($chartData) ?>;
    window.dailyBudget = <?= $budget['daily_budget'] ?? 100000 ?>;

    // Create chart instance and store it globally
    const budgetChart = new Chart(ctx, {
        type: 'line',
        plugins: [window['chartjs-plugin-annotation']],
        data: {
            labels: chartData.labels,
            datasets: [{
                    label: 'Pemasukan',
                    data: chartData.incomes,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.expenses,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' +
                                context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: window.dailyBudget,
                            yMax: window.dailyBudget,
                            borderColor: '#dc3545',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: 'Budget',
                                enabled: true
                            }
                        }
                    }
                }
            }
        }
    });

    // Initialize budget manager
    document.addEventListener('DOMContentLoaded', function() {
        loadBudgetStatus();
    });
</script>

<?= $this->endSection(); ?>