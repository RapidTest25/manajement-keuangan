<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid px-4" style="max-width: 480px;">
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Filter Transaksi</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <input type="month" class="form-control" id="filterMonth" value="<?= date('Y-m') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" id="filterCategory">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe</label>
                    <select class="form-select" id="filterType">
                        <option value="">Semua</option>
                        <option value="INCOME">Pemasukan</option>
                        <option value="EXPENSE">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100" onclick="applyFilter()">
                        <i class="ri-filter-3-line"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Riwayat Transaksi</h5>
                <?php if (is_premium_user()): ?>
                    <button class="btn btn-outline-success btn-sm" onclick="exportTransactions()">
                        <i class="ri-file-excel-line"></i> Export
                    </button>
                <?php else: ?>
                    <button class="btn btn-warning btn-sm" onclick="showPremiumModalExport()">
                        <i class="ri-lock-line"></i> Export (Premium)
                    </button>
                <?php endif; ?>
            </div>
            <div id="transactionList"></div>
        </div>
    </div>
</div>

<style>
    .transaction-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        background-color: #f9f9f9;
    }
    
    .transaction-item.income {
        border-left: 4px solid #28a745;
    }
    
    .transaction-item.expense {
        border-left: 4px solid #dc3545;
    }
    
    .amount.income {
        color: #28a745;
        font-weight: bold;
    }
    
    .amount.expense {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadCategories();
        loadTransactions();
    });

    function loadCategories() {
        $.ajax({
            url: '<?= base_url('app/ajax/getCategories') ?>',
            method: 'GET',
            success: function(response) {
                const select = document.getElementById('filterCategory');
                // Clear existing options except the first one (Semua Kategori)
                select.innerHTML = '<option value="">Semua Kategori</option>';

                if (Array.isArray(response)) {
                    response.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.name;
                        option.textContent = category.name;
                        select.appendChild(option);
                    });
                } else {
                    console.error('Invalid category data received:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading categories:', error);
            }
        });
    }

    function loadTransactions(filters = {}) {
        const defaultFilters = {
            month: document.getElementById('filterMonth').value,
            category: document.getElementById('filterCategory').value,
            type: document.getElementById('filterType').value
        };

        const finalFilters = {
            ...defaultFilters,
            ...filters
        };

        $.get('<?= base_url('app/ajax/getTransactions') ?>', finalFilters, function(data) {
            const container = document.getElementById('transactionList');
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = `
                <div class="text-center py-5">
                    <img src="<?= base_url('assets/images/empty.gif') ?>" alt="No data" style="width: 150px;">
                    <p class="text-muted mt-3">Tidak ada transaksi</p>
                </div>
            `;
                return;
            }

            data.forEach(tx => {
                container.innerHTML += `
                <div class="transaction-item ${tx.status.toLowerCase()} mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${tx.category}</h6>
                            <p class="mb-1 text-muted small">${tx.description || '-'}</p>
                            <small class="text-muted">
                                Transaksi: ${formatDate(tx.transaction_date)}<br>
                                Input: ${formatDateTime(tx.created_at)}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="amount ${tx.status.toLowerCase()}">
                                ${tx.status === 'INCOME' ? '+' : '-'} Rp${formatNumber(tx.amount)}
                            </div>
                            <small class="text-muted">#${tx.transaction_id}</small>
                        </div>
                    </div>
                </div>
            `;
            });
        });
    }

    function applyFilter() {
        loadTransactions();
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    function formatDateTime(dateString) {
        return new Date(dateString).toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function exportTransactions() {
        <?php if (is_premium_user()): ?>
            const filters = {
                month: document.getElementById('filterMonth').value,
                category: document.getElementById('filterCategory').value,
                type: document.getElementById('filterType').value
            };
            window.location.href = `<?= base_url('app/ajax/exportTransactions') ?>?${new URLSearchParams(filters)}`;
        <?php else: ?>
            showPremiumModalExport();
        <?php endif; ?>
    }

    function showPremiumModalExport() {
        Swal.fire({
            title: '🔒 Fitur Premium',
            html: '<p>Fitur Export Excel hanya tersedia untuk pengguna Premium.</p><p><strong>Upgrade sekarang dan dapatkan:</strong></p><ul style="text-align: left; margin: 15px 0;"><li>📄 Export PDF & Excel unlimited</li><li>📊 Laporan Keuangan Lanjutan</li><li>🎯 Multiple Budget Planning</li><li>🔄 Transaksi Berulang Otomatis</li><li>💰 Dan banyak lagi!</li></ul>',
            icon: 'warning',
            confirmButtonText: '🚀 Upgrade Premium',
            cancelButtonText: 'Nanti Saja',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('app/subscription/plans') ?>';
            }
        });
    }
</script>

<?= $this->endSection(); ?>