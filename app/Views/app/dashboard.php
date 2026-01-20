<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    .image-box {
        position: relative;
        display: inline-block;
    }

    .badge-new {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .custom-month-input {
        background: #009e60;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 1rem;
        font-family: inherit;
        width: 120px;
        outline: none;
        transition: box-shadow 0.2s;
    }

    .custom-month-input:focus {
        box-shadow: 0 0 0 2px #fff3;
    }

    /* Kembalikan tombol mata ke emoji biasa tanpa background */
    .eye-button {
        background: none;
        color: inherit;
        border: none;
        font-size: 1.3rem;
        padding: 4px 8px;
        border-radius: 0;
        transition: none;
    }

    /* Hapus efek active/hover pada eye-button */
    .eye-button.active,
    .eye-button:focus,
    .eye-button:hover {
        background: none;
        color: inherit;
    }

    /* Style khusus untuk input[type=month] agar ikon kalender tidak hitam */
    input[type="month"].custom-month-input::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    input[type="month"].custom-month-input::-webkit-input-placeholder {
        color: #fff;
    }

    input[type="month"].custom-month-input::placeholder {
        color: #fff;
    }
    
    /* Pastikan SweetAlert tampil di atas semua modal */
    .swal-container-high-z {
        z-index: 99999 !important;
    }
    
    .swal2-container {
        z-index: 99999 !important;
    }
</style>

<body>

    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <img src="<?= base_url('assets/images/logo-nama-png.png') ?>" alt="FinanceFlow" class="logo">
        </div>

        <!-- Last Updated -->
        <div class="times" id="clock"></div>

        <!-- Premium Banner (for non-premium users) -->
        <?php if (!is_premium_user()): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 15px; border-left: 5px solid #ffc107; background: linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%);">
            <div class="d-flex align-items-center">
                <div class="me-3" style="font-size: 32px;">⭐</div>
                <div class="flex-grow-1">
                    <h6 class="mb-1" style="font-weight: bold; color: #856404;">Upgrade ke Premium!</h6>
                    <small>Dapatkan laporan lanjutan, export PDF/Excel, dan fitur eksklusif lainnya.</small>
                </div>
                <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-warning btn-sm">
                    🚀 Lihat Paket
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php else: ?>
        <div class="alert alert-success" role="alert" style="border-radius: 15px; background: linear-gradient(135deg, #d4edda 0%, #e8f5e9 100%); border: none;">
            <div class="d-flex align-items-center">
                <div class="me-2" style="font-size: 24px;">⭐</div>
                <div class="flex-grow-1">
                    <strong>Anda Pengguna Premium!</strong> 
                    <?php 
                    $subscription = get_user_subscription();
                    if ($subscription && $subscription['plan_slug'] !== 'free') {
                        $subscriptionModel = new \App\Models\UserSubscriptionModel();
                        $daysLeft = $subscriptionModel->getDaysRemaining(session()->get('user_id'));
                        echo " Berlaku " . $daysLeft . " hari lagi.";
                    }
                    ?>
                </div>
                <a href="<?= base_url('app/subscription/my-subscription') ?>" class="btn btn-outline-success btn-sm">
                    Kelola Langganan
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Balance Card -->
        <div class="balance-card position-relative">
            <div class="balance-header d-flex justify-content-between align-items-center">
                <span class="total-saldo">Total Saldo</span>
                <div class="d-flex align-items-center gap-2">
                    <input type="month" id="monthPicker" class="custom-month-input" style="max-width: 120px;">
                    <button id="toggle-visibility" class="eye-button">👁️</button>
                </div>
            </div>

            <div class="balance-amount toggle-sensitive" id="balanceAmount" data-real="Rp <?= number_format($summary['balance'], 0, ',', '.'); ?>">
                Rp ******
            </div>
            <div class="today-expense" id="todayExpense">
                Pengeluaran Hari Ini: <span class="toggle-sensitive" data-real="Rp <?= number_format($summary['today_expense'], 0, ',', '.'); ?>">Rp ******</span>
            </div>
            <hr class="divider">
            <div class="details" id="details" style="display: none;">
                <div class="detail-item">
                    <div>Pendapatan</div>
                    <div class="toggle-sensitive" id="totalIncome" data-real="Rp <?= number_format($summary['total_income'], 0, ',', '.'); ?>">Rp ******</div>
                </div>
                <div class="detail-item">
                    <div>Pengeluaran</div>
                    <div class="toggle-sensitive" id="totalExpense" data-real="Rp <?= number_format($summary['total_expense'], 0, ',', '.'); ?>">Rp ******</div>
                </div>
                <div class="detail-item">
                    <div>Rata-rata Pengeluaran</div>
                    <div class="toggle-sensitive" id="avgExpense" data-real="Rp <?= number_format($summary['average_daily_expense'], 0, ',', '.'); ?> Per Hari">Rp ******</div>
                </div>
            </div>
            <button id="toggle-details" class="show-details">Show Details ▼</button>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">
            <!-- Nabung Menu -->
            <?php if (is_premium_user()): ?>
                <div class="menu-item position-relative" role="button" onclick="window.location.href='<?= base_url('nabung') ?>'">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/wallet-income.png') ?>" alt="Nabung Icon" class="menu-icon">
                        <?php if (!$hasNabungToday): ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">Belum nabung hari ini</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="menu-text">Nabung</span>
                </div>
            <?php else: ?>
                <div class="menu-item position-relative opacity-75" role="button" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Nabung!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/wallet-income.png') ?>" alt="Nabung Icon" class="menu-icon" style="filter: grayscale(100%);">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark border border-light" style="font-size: 8px;">
                            <i class="ri-lock-fill"></i>
                        </span>
                    </div>
                    <span class="menu-text">Nabung</span>
                </div>
            <?php endif; ?>

            <!-- Utang Menu -->
            <?php if (is_premium_user()): ?>
                <div class="menu-item position-relative" role="button" onclick="window.location.href='<?= base_url('note-utang') ?>'">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/calendar-payment-loan.png') ?>" alt="Note Utang Icon" class="menu-icon">
                        <?php if ($hasActiveLoanThisMonth): ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">Ada utang aktif bulan ini</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span>Utang</span>
                </div>
            <?php else: ?>
                <div class="menu-item position-relative opacity-75" role="button" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Catat Utang!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/calendar-payment-loan.png') ?>" alt="Note Utang Icon" class="menu-icon" style="filter: grayscale(100%);">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark border border-light" style="font-size: 8px;">
                            <i class="ri-lock-fill"></i>
                        </span>
                    </div>
                    <span>Utang</span>
                </div>
            <?php endif; ?>

            <!-- Cicilan Menu -->
            <?php if (is_premium_user()): ?>
                <div class="menu-item position-relative" role="button" onclick="window.location.href='<?= base_url('cicilan') ?>'">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/checklist-task-budget.png') ?>" class="menu-icon">
                        <?php if ($hasActiveInstallmentThisMonth): ?>
                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">Ada cicilan aktif bulan ini</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span>Cicilan</span>
                </div>
            <?php else: ?>
                <div class="menu-item position-relative opacity-75" role="button" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Cicilan!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                    <div class="image-box">
                        <img src="<?= base_url('assets/images/icons/checklist-task-budget.png') ?>" class="menu-icon" style="filter: grayscale(100%);">
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark border border-light" style="font-size: 8px;">
                            <i class="ri-lock-fill"></i>
                        </span>
                    </div>
                    <span>Cicilan</span>
                </div>
            <?php endif; ?>
            <div class="menu-item" role="button" onclick="showCategoryModal()">
                <img src="<?= base_url('assets/images/icons/output-onlinepngtools.png') ?>" alt="Statistik Icon" class="menu-icon">
                <span>Category</span>
            </div>
        </div>

        <!-- Daily Budget Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <strong>Pengeluaran 7 Hari Terakhir</strong>
                    <div class="chart-target">Budget Perhari: Rp <span id="dailyTarget"><?= number_format($budget['daily_budget'] ?? 0, 0, ',', '.') ?></span></div>
                </div> <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#budgetModal">
                    <i class="ri-settings-4-line"></i>
                </button>
            </div>
            <!-- Budget Progress Bar -->
            <div class="budget-status mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small>Status Budget Hari Ini:</small>
                    <small>Rp <span id="mainDailyTarget"><?= number_format($budget['daily_budget'] ?? 0, 0, ',', '.') ?></span></small>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%" id="mainBudgetProgressBar"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small>Terpakai: <span id="mainBudgetUsed">Rp 0</span></small>
                    <small>Sisa: <span id="mainBudgetRemaining">Rp 0</span></small>
                </div>
            </div>
            <canvas id="budgetChart" height="100"></canvas>
        </div>


        <!-- Card History dan modal -->
        <div id="history-card">
            <?php if (empty($transactions)): ?>
                <div class="no-transaction text-center">
                    <img src="<?= base_url('assets/images/empty.gif'); ?>" alt="No Transaction">
                    <p>Tidak ada transaksi</p>
                </div>
            <?php else: ?>
                <?php foreach ($transactions as $tx): ?>
                    <div class="history-card" style="border-left: 4px solid <?= $tx['status'] === 'INCOME' ? '#28a745' : '#dc3545' ?>">
                        <div class="history-content" onclick="showDetailModal(this)" data-tx='<?= json_encode($tx, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                            <div class="history-info">
                                <div class="history-category"><?= esc($tx['category']) ?></div>
                                <div class="history-description"><?= esc($tx['description'] ?: '-') ?></div>
                                <div class="history-date"><?= date('d F Y', strtotime($tx['created_at'])) ?></div>
                            </div>
                            <div class="history-amount">
                                <div class="amount <?= strtolower($tx['status']) ?>">
                                    <?= $tx['status'] === 'INCOME' ? '+' : '-' ?> Rp<?= number_format($tx['amount'], 0, ',', '.') ?>
                                </div>
                                <div class="transaction-id">#<?= esc($tx['transaction_id']) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mb-4">
            <a href="<?= base_url('history') ?>" class="btn btn-link text-decoration-none see-more-link">
                <span class="see-more-text">Lihat Lebih Banyak</span>
                <i class="ri-arrow-right-line align-middle"></i>
            </a>
        </div>

        <!-- Transaction Detail Modal -->
        <div id="transactionModal" class="modal">
            <div class="modal-content transaction-modal">
                <div class="modal-header">
                    <div class="transaction-status" id="modalStatusBadge">
                        <i class="ri-arrow-up-circle-line status-icon"></i>
                        <span id="modalStatus">INCOME</span>
                    </div>
                    <button type="button" class="btn-close" onclick="closeDetailModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="transaction-amount" id="modalAmount">Rp 0</div>

                    <div class="transaction-details">
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="ri-hashtag"></i> ID Transaksi
                            </div>
                            <div class="detail-value" id="modalTransactionId">-</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="ri-price-tag-3-line"></i> Kategori
                            </div>
                            <div class="detail-value" id="modalCategory">-</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="ri-calendar-line"></i> Tanggal
                            </div>
                            <div class="detail-value" id="modalDate">-</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="ri-chat-1-line"></i> Deskripsi
                            </div>
                            <div class="detail-value" id="modalDescription">-</div>
                        </div>
                    </div>

                    <div class="receipt-section">
                        <button onclick="toggleReceipt()" class="btn-receipt" id="btnToggleReceipt">
                            <i class="ri-image-line"></i>
                            <span>Lihat Bukti Transaksi</span>
                        </button>
                        <div id="receiptContainer">
                            <img id="modalImage" src="" alt="Receipt" class="receipt-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Modal -->
        <div id="categoryModal" class="modal" style="display:none;">
            <div class="modal-content">
                <h5>Kelola Kategori</h5>
                <div class="add-category mb-3">
                    <select class="form-select mb-2" id="categoryType">
                        <option value="EXPENSE">Pengeluaran</option>
                        <option value="INCOME">Pemasukan</option>
                    </select>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newCategory" placeholder="Nama kategori baru">
                        <button class="btn btn-success" onclick="addCategory()">Tambah</button>
                    </div>
                </div>
                <div class="category-list" id="categoryList">
                    <!-- Categories will be loaded here -->
                </div>
                <div class="modal-footer">
                    <span id="categoryCount">0/<?= get_category_limit(session()->get('user_id')) ?> kategori</span>
                    <button class="btn btn-secondary" onclick="closeCategoryModal()">Tutup</button>
                </div>
            </div>
        </div> <!-- Budget Modal -->
        <div class="modal fade" id="budgetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="budgetModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="budgetModalLabel">Atur Budget Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="budget-status mb-3">
                            <h6>Status Budget Hari Ini:</h6>
                            <div class="budget-progress">
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 0%; transition: width 0.5s ease-in-out;"
                                        id="budgetProgressBar">
                                    </div>
                                </div>
                                <div class="budget-details d-flex justify-content-between mt-3">
                                    <div class="budget-info">
                                        <div class="mb-2">
                                            <small class="text-muted">Budget Harian: <span class="fw-bold text-dark" id="budgetTarget">Rp 0</span></small>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Terpakai: <span class="fw-bold text-dark" id="budgetUsed">Rp 0</span></small>
                                        </div>
                                        <div>
                                            <small class="text-muted">Sisa: <span class="fw-bold text-dark" id="budgetRemaining">Rp 0</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="saveBudget">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2"></script>
    <script src="<?= base_url('assets/js/budget-management.js') ?>"></script>
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        const chartData = <?= json_encode($chartData) ?>;
        window.dailyBudget = <?= $budget['daily_budget'] ?? 100000 ?>;

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

        function toggleReceipt() {
            const container = document.getElementById('receiptContainer');
            const isHidden = container.style.display === 'none';
            container.style.display = isHidden ? 'block' : 'none';
        }

        function updateClock() {
            const clock = document.getElementById('clock');
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');

            clock.textContent = `Jam WIB: ${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener("DOMContentLoaded", function() {
            updateClock();
            setInterval(updateClock, 1000);
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "long",
                year: "numeric"
            });
        }

        function formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        function showDetailModal(element) {
            const tx = JSON.parse(element.getAttribute('data-tx'));
            const modal = document.getElementById('transactionModal');
            const modalContent = modal.querySelector('.modal-content');
            const receiptContainer = document.getElementById('receiptContainer');

            // Reset state
            receiptContainer.style.display = 'none';
            modalContent.classList.remove('income', 'expense');
            modalContent.classList.add(tx.status.toLowerCase());

            // Update content
            document.getElementById('modalTransactionId').textContent = '#' + tx.transaction_id;
            document.getElementById('modalStatus').textContent = tx.status;
            document.getElementById('modalCategory').textContent = tx.category;
            document.getElementById('modalAmount').textContent = `${tx.status === 'INCOME' ? '+' : '-'} Rp ${formatRupiah(tx.amount)}`;
            document.getElementById('modalDescription').textContent = tx.description || '-';
            document.getElementById('modalDate').textContent = formatDate(tx.created_at);

            // Update icon based on status
            const statusIcon = document.querySelector('.status-icon');
            if (tx.status === 'INCOME') {
                statusIcon.classList.remove('ri-arrow-down-circle-line');
                statusIcon.classList.add('ri-arrow-up-circle-line');
            } else {
                statusIcon.classList.remove('ri-arrow-up-circle-line');
                statusIcon.classList.add('ri-arrow-down-circle-line');
            }

            // Handle receipt
            const btnReceipt = document.getElementById('btnToggleReceipt');
            const modalImage = document.getElementById('modalImage');
            if (tx.image_receipt) {
                btnReceipt.style.display = 'flex';
                modalImage.src = '<?= base_url('uploads/') ?>' + tx.image_receipt;
            } else {
                btnReceipt.style.display = 'none';
            }

            modal.style.display = 'block';
        }

        function closeDetailModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionModal');
            // Only handle modal close if click is directly on the modal backdrop
            if (event.target === modal) {
                closeDetailModal();
            }
        });

        // Ensure modal clicks don't propagate to window
        document.querySelectorAll('.modal-content').forEach(content => {
            content.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

        function showCategoryModal() {
            document.getElementById('categoryModal').style.display = 'block';
            loadCategories();
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').style.display = 'none';
        } // Budget modal functionality has been moved to budget-management.js

        document.addEventListener('DOMContentLoaded', function() {
            const monthPicker = document.getElementById('monthPicker');
            const eyeBtn = document.getElementById('toggle-visibility');
            let lastMonth = localStorage.getItem('dashboardMonth');
            if (monthPicker) {
                if (lastMonth) {
                    monthPicker.value = lastMonth;
                } else {
                    const today = new Date();
                    monthPicker.value = today.toISOString().slice(0, 7);
                }
                fetchSummaryBulan(monthPicker.value);
                monthPicker.addEventListener('change', function() {
                    localStorage.setItem('dashboardMonth', this.value);
                    fetchSummaryBulan(this.value);
                });
            }
            // Restore status mata dari localStorage
            let showSensitive = localStorage.getItem('dashboardShowSensitive');
            if (showSensitive === null) showSensitive = 'true';
            const isShow = (showSensitive === 'true');
            setSensitiveVisibility(isShow);
            updateEyeIcon(isShow);
            if (eyeBtn) {
                eyeBtn.addEventListener('click', function() {
                    const isShow = !(localStorage.getItem('dashboardShowSensitive') === 'true');
                    localStorage.setItem('dashboardShowSensitive', isShow);
                    setSensitiveVisibility(isShow);
                    updateEyeIcon(isShow);
                });
            }
            // Toggle details saldo
            const toggleDetailsBtn = document.getElementById('toggle-details');
            const detailsSection = document.getElementById('details');
            if (toggleDetailsBtn && detailsSection) {
                toggleDetailsBtn.addEventListener('click', function() {
                    const isHidden = detailsSection.style.display === 'none' || detailsSection.style.display === '';
                    detailsSection.style.display = isHidden ? 'block' : 'none';
                    this.textContent = isHidden ? 'Hide Details ▲' : 'Show Details ▼';
                });
            }
        });

        function loadTransactions() {
            fetch('<?= base_url('app/ajax/getTransactions') ?>')
                .then(response => response.json())
                .then(response => {
                    if (response.status && response.data) {
                        const container = document.getElementById('history-card');
                        if (!container) return;

                        if (!response.data.length) {
                            container.innerHTML = `
                                <div class="no-transaction text-center">
                                    <img src="<?= base_url('assets/images/empty.gif'); ?>" alt="No Transaction">
                                    <p>Tidak ada transaksi</p>
                                </div>`;
                            return;
                        }

                        const html = response.data.map(tx => `
                            <div class="history-card" style="border-left: 4px solid ${tx.status === 'INCOME' ? '#28a745' : '#dc3545'}">
                                <div class="history-content" onclick="showDetailModal(this)" data-tx='${JSON.stringify(tx)}'>
                                    <div class="history-info">
                                        <div class="history-category">${tx.category}</div>
                                        <div class="history-description">${tx.description || '-'}</div>
                                        <div class="history-date">${formatDate(tx.created_at)}</div>
                                    </div>
                                    <div class="history-amount">
                                        <div class="amount ${tx.status.toLowerCase()}">
                                            ${tx.status === 'INCOME' ? '+' : '-'} Rp${formatRupiah(tx.amount)}
                                        </div>
                                        <div class="transaction-id">#${tx.transaction_id}</div>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        container.innerHTML = html;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function fetchSummaryBulan(month) {
            // Tampilkan loading
            document.getElementById('balanceAmount').textContent = 'Loading...';
            document.getElementById('totalIncome').textContent = 'Loading...';
            document.getElementById('totalExpense').textContent = 'Loading...';
            document.getElementById('avgExpense').textContent = 'Loading...';
            fetch('/app/ajax/getSummaryBulan?month=' + month)
                .then(res => res.json())
                .then(data => {
                    // Update data-real attribute agar toggle-sensitive selalu benar
                    document.getElementById('balanceAmount').setAttribute('data-real', formatRupiah(data.saldo ?? 0));
                    document.getElementById('totalIncome').setAttribute('data-real', formatRupiah(data.income ?? 0));
                    document.getElementById('totalExpense').setAttribute('data-real', formatRupiah(data.expense ?? 0));
                    document.getElementById('avgExpense').setAttribute('data-real', formatRupiah(data.avg_expense ?? 0) + ' Per Hari');
                    document.getElementById('balanceAmount').textContent = formatRupiah(data.saldo ?? 0);
                    document.getElementById('totalIncome').textContent = formatRupiah(data.income ?? 0);
                    document.getElementById('totalExpense').textContent = formatRupiah(data.expense ?? 0);
                    document.getElementById('avgExpense').textContent = formatRupiah(data.avg_expense ?? 0) + ' Per Hari';
                    // Pastikan visibilitas saldo dan emoji mata konsisten setelah update data
                    let showSensitive = localStorage.getItem('dashboardShowSensitive');
                    if (showSensitive === null) showSensitive = 'true';
                    const isShow = (showSensitive === 'true');
                    setSensitiveVisibility(isShow);
                    updateEyeIcon(isShow);
                })
                .catch((err) => {
                    console.error('Fetch summary error:', err);
                    document.getElementById('balanceAmount').textContent = 'Rp 0';
                    document.getElementById('totalIncome').textContent = 'Rp 0';
                    document.getElementById('totalExpense').textContent = 'Rp 0';
                    document.getElementById('avgExpense').textContent = 'Rp 0 Per Hari';
                    // Pastikan visibilitas saldo dan emoji mata konsisten setelah update data
                    let showSensitive = localStorage.getItem('dashboardShowSensitive');
                    if (showSensitive === null) showSensitive = 'true';
                    const isShow = (showSensitive === 'true');
                    setSensitiveVisibility(isShow);
                    updateEyeIcon(isShow);
                });
        }

        function setSensitiveVisibility(show) {
            document.body.classList.toggle('show-sensitive', show);
            document.querySelectorAll('.toggle-sensitive').forEach(el => {
                if (show) {
                    el.textContent = el.getAttribute('data-real');
                } else {
                    el.textContent = 'Rp ******';
                }
            });
        }

        function updateEyeIcon(show) {
            const eyeBtn = document.getElementById('toggle-visibility');
            if (eyeBtn) {
                eyeBtn.textContent = show ? '🙈' : '👁️';
            }
        }
    </script>

    <?= $this->endSection(); ?>

</body>

</html>