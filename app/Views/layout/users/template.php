<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $settings['website_name'] ?? 'FinanceFlow' ?> - <?= $settings['website_description'] ?? 'Catatan Keuangan Jadi Menyenangkan' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />



    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/history.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/loader.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/budget.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/calculator.css') ?>">
</head>

<body>
    <!-- Include Loader -->
    <?= $this->include('layout/loader') ?>

    <!-- Transaction Sound -->
    <audio id="transactionSound">
        <source src="<?= base_url('assets/sound/transaction-complate.mp3') ?>" type="audio/mp3">
    </audio>

    <?= $this->renderSection('content'); ?>

    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="transactionForm" action="<?= base_url('app/ajax/addtransaction') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <!-- Transaction Type -->
                        <div class="d-flex justify-content-center mb-3">
                            <input type="hidden" id="status" name="status" value="EXPENSE">
                            <button type="button" id="expenseBtn" class="btn btn-outline-danger me-2 active">Expense</button>
                            <button type="button" id="incomeBtn" class="btn btn-outline-success">Income</button>
                        </div>
                        <style>
                            .btn-outline-success.active,
                            .btn-outline-success:hover {
                                color: white !important;
                                background-color: #198754 !important;
                            }

                            .btn-outline-danger.active,
                            .btn-outline-danger:hover {
                                color: white !important;
                                background-color: #dc3545 !important;
                            }
                        </style>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                    class="form-control"
                                    id="amount"
                                    name="amount"
                                    placeholder="Masukkan nominal"
                                    required
                                    min="1000"
                                    oninput="validateAmount(this)"
                                    onkeydown="return event.keyCode !== 69">
                            </div>
                            <div class="invalid-feedback" id="amountFeedback">
                                Nominal tidak boleh 0 atau kosong
                            </div>
                        </div>

                        <!-- Transaction Date -->
                        <div class="mb-3">
                            <label for="transactionDate" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="transactionDate" name="transaction_date" value="<?= date('Y-m-d') ?>" required>
                            <small class="text-muted">Tanggal saat transaksi terjadi</small>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>

                        <!-- Upload File -->
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload Receipt</label>
                            <input class="form-control" type="file" id="file" name="image_receipt" accept="image/*">
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Add notes about your transaction..."></textarea>
                        </div>

                        <!-- Add Transaction Button -->
                        <div class="d-grid">
                            <button type="submit" id="submitBtn" class="btn btn-success">Add Transaction</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Add Category -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <span id="categoryCount">0/<?= get_category_limit(session()->get('user_id')) ?> kategori</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php $uri = service('uri'); ?>
    <div class="app-navbar">
        <div class="row">
            <!-- Home -->
            <div class="col text-center align-self-center">
                <a href="<?= base_url('home'); ?>"
                    class="nav-link d-flex flex-column align-items-center text-decoration-none <?= $uri->getSegment(1) === 'home' ? 'active' : '' ?>">
                    <i class="ri-home-4-line"></i>
                    <span class="text-xs mt-0">Beranda</span>
                </a>
            </div> <!-- FinPlan Dropdown -->
            <?php
            $finplanPages = ['finplan', 'interest', 'compounding', 'budgeting', 'note-utang', 'cicilan', 'biayaefektif'];
            $isFinplan = in_array($uri->getSegment(1), $finplanPages) || in_array($uri->getSegment(2), $finplanPages);
            ?>
            <div class="col text-center align-self-center">
                <div class="dropup">
                    <a type="button" id="walletDropdownMenu"
                        class="btn btn-link nav-link d-flex flex-column align-items-center text-decoration-none <?= $isFinplan ? 'active' : '' ?>"
                        data-bs-toggle="dropdown" 
                        data-bs-toggle="dropdown"
                        data-bs-display="static"
                        aria-expanded="false">
                        <i class="ri-wallet-2-line"></i>
                        <span class="text-xs mt-0">FinPlan</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="walletDropdownMenu">
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('budgeting') ?>">
                            <span><i class="ri-pie-chart-line me-2"></i> Budgeting</span>
                        </a>
                    </li>
                    <li>
                        <?php if (is_premium(session()->get('user_id'))) : ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('nabung') ?>">
                                <span><i class="ri-piggy-bank-line me-2"></i> Nabung</span>
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between text-muted" href="#" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Nabung!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                                <span><i class="ri-piggy-bank-line me-2"></i> Nabung</span>
                                <i class="ri-lock-2-fill text-warning" style="font-size: 12px;"></i>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if (is_premium(session()->get('user_id'))) : ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('note-utang') ?>">
                                <span><i class="ri-booklet-line me-2"></i> Catat Utang</span>
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between text-muted" href="#" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Catat Utang!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                                <span><i class="ri-booklet-line me-2"></i> Catat Utang</span>
                                <i class="ri-lock-2-fill text-warning" style="font-size: 12px;"></i>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if (is_premium(session()->get('user_id'))) : ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('cicilan') ?>">
                                <span><i class="ri-calendar-event-line me-2"></i> Cicilan</span>
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item d-flex align-items-center justify-content-between text-muted" href="#" onclick="Swal.fire({icon: 'info', title: 'Fitur Premium', text: 'Upgrade ke Premium untuk akses fitur Cicilan!', showCancelButton: true, confirmButtonText: 'Lihat Paket', cancelButtonText: 'Nanti'}).then((res) => { if(res.isConfirmed) window.location.href='<?= base_url('app/subscription/plans') ?>' })">
                                <span><i class="ri-calendar-event-line me-2"></i> Cicilan</span>
                                <i class="ri-lock-2-fill text-warning" style="font-size: 12px;"></i>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('biayaefektif') ?>">
                            <span><i class="ri-percent-line me-2"></i> Biaya Efektif</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('calculator') ?>">
                            <span><i class="ri-calculator-line me-2"></i> Kalkulator</span>
                        </a>
                    </li>
                </ul>
                </div>
            </div>

            <!-- Scan -->
            <div class="col text-center align-self-center">
                <a href="#" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                    <img src="<?= base_url('assets/images/scan.png') ?>" class="navbar-mid-icon" alt="">
                </a>
            </div>

            <!-- Stat -->
            <div class="col text-center align-self-center">
                <a href="<?= base_url('statistik'); ?>"
                    class="nav-link d-flex flex-column align-items-center text-decoration-none <?= $uri->getSegment(1) === 'statistik' ? 'active' : '' ?>">
                    <i class="ri-line-chart-line"></i>
                    <span class="text-xs mt-0">Stat <?php if (!is_premium_user()): ?> <i class="ri-lock-line" style="font-size: 10px;"></i> <?php endif; ?></span>
                </a>
            </div>

            <!-- Settings -->
            <div class="col text-center align-self-center">
                <a href="<?= base_url('setting'); ?>"
                    class="nav-link d-flex flex-column align-items-center text-decoration-none <?= $uri->getSegment(1) === 'setting' ? 'active' : '' ?>">
                    <i class="ri-settings-line"></i>
                    <span class="text-xs mt-0">Pengaturan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (Popper + JS) -->
    <!-- Bootstrap 5 JS Bundle (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            loadCategories();
            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                // Remove any previous error highlighting
                $('.is-invalid').removeClass('is-invalid');

                if (!validateForm()) {
                    return false;
                }

                // Get and validate transaction date
                const transactionDate = $('#transactionDate').val();
                if (!transactionDate) {
                    $('#transactionDate').addClass('is-invalid');
                    $('#dateError').text('Tanggal transaksi harus diisi');
                    return false;
                }

                const formData = new FormData(this);

                $.ajax({
                    url: '<?= base_url('app/ajax/addtransaction') ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            // Play transaction sound
                            document.getElementById('transactionSound').play();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '<?= base_url('home') ?>';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal menambah transaksi'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem. Silakan coba lagi.'
                        });
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false);
                    }
                });
            });

            function validateForm() {
                const amount = $('#amount').val();
                const category = $('#category').val();
                const transactionDate = $('#transactionDate').val();

                if (!amount || !category || !transactionDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Mohon lengkapi semua field yang diperlukan'
                    });
                    return false;
                }
                return true;
            }

            $('#editProfileForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Refresh halaman setelah 1.5 detik
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memperbarui profil'
                        });
                    }
                });
            });
        });

        function loadCategories(type = 'EXPENSE') {
            $.ajax({
                url: '<?= base_url('app/ajax/getCategories') ?>',
                method: 'GET',
                success: function(categories) {
                    // Populate category dropdown in transaction form
                    const select = document.querySelector('#transactionForm select[name="category"]');
                    if (select) {
                        select.innerHTML = '';
                        categories
                            .filter(cat => cat.type === type)
                            .forEach(cat => {
                                const option = document.createElement('option');
                                option.value = cat.name;
                                option.textContent = cat.name;
                                select.appendChild(option);
                            });
                    }

                    // Update category list in manage categories modal
                    const list = document.getElementById("categoryList");
                    if (list) {
                        list.innerHTML = "";

                        // Group categories by type
                        const expenseCategories = categories.filter(cat => cat.type === 'EXPENSE');
                        const incomeCategories = categories.filter(cat => cat.type === 'INCOME');

                        // Render expense categories
                        if (expenseCategories.length > 0) {
                            list.innerHTML += '<h6 class="mt-3">Kategori Pengeluaran</h6>';
                            expenseCategories.forEach(cat => {
                                list.innerHTML += `
                                <div class="category-item">
                                    <span>${cat.name}</span>
                                    ${cat.user_id !== '0' ? `
                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory('${cat.id}')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    ` : ''}
                                </div>`;
                            });
                        }

                        // Render income categories
                            list.innerHTML += '<h6 class="mt-3">Kategori Pemasukan</h6>';
                            incomeCategories.forEach(cat => {
                                list.innerHTML += `
                                <div class="category-item">
                                    <span>${cat.name}</span>
                                    ${cat.user_id !== '0' ? `
                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory('${cat.id}')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    ` : ''}
                                </div>`;
                            });
                        }

                        document.getElementById("categoryCount").innerText = `${categories.length}/<?= get_category_limit(session()->get('user_id')) ?> kategori`;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading categories:', error);
                }
            });
        }

        document.getElementById('expenseBtn').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('incomeBtn').classList.remove('active');
            document.getElementById('status').value = 'EXPENSE';
            loadCategories('EXPENSE');
        });

        document.getElementById('incomeBtn').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('expenseBtn').classList.remove('active');
            document.getElementById('status').value = 'INCOME';
            loadCategories('INCOME');
        });

        function loadCategoryList() {
            $.ajax({
                url: '<?= base_url('app/ajax/getCategories') ?>',
                method: 'GET',
                success: function(categories) {
                    const list = document.getElementById("categoryList");
                    list.innerHTML = "";

                    // Group categories by type
                    const expenseCategories = categories.filter(cat => cat.type === 'EXPENSE');
                    const incomeCategories = categories.filter(cat => cat.type === 'INCOME');

                    // Render expense categories
                    if (expenseCategories.length > 0) {
                        list.innerHTML += '<h6 class="mt-3">Kategori Pengeluaran</h6>';
                        expenseCategories.forEach(cat => {
                            list.innerHTML += `
                            <div class="category-item">
                                <span>${cat.name}</span>
                                ${cat.user_id !== '0' ? `
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(${cat.id})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                ` : ''}
                            </div>`;
                        });
                    }

                    // Render income categories
                    if (incomeCategories.length > 0) {
                        list.innerHTML += '<h6 class="mt-3">Kategori Pemasukan</h6>';
                        incomeCategories.forEach(cat => {
                            list.innerHTML += `
                            <div class="category-item">
                                <span>${cat.name}</span>
                                ${cat.user_id !== '0' ? `
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(${cat.id})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                ` : ''}
                            </div>`;
                        });
                    }

                document.getElementById("categoryCount").innerText = `${categories.length}/<?= get_category_limit(session()->get('user_id')) ?> kategori`;
                },
                error: function(xhr, status, error) {
                    console.error('Error loading categories:', error);
                }
            });
        }

        function addCategory() {
            const input = document.getElementById("newCategory");
            const type = document.getElementById("categoryType");
            const value = input.value.trim();

            if (value) {
                $.ajax({
                    url: '<?= base_url('app/ajax/addCategory') ?>',
                    method: 'POST',
                    data: {
                        name: value,
                        type: type.value
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            input.value = "";
                            loadCategoryList();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    }
                });
            }
        }

        function deleteCategory(id) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori yang dihapus tidak dapat dikembalikan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('app/ajax/deleteCategory/') ?>' + id,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadCategoryList();
                            }
                        }
                    });
                }
            });
        } // Load categories when modal is shown
        $('#addCategoryModal').on('shown.bs.modal', function() {
            loadCategories();
        });

        // Initialize all dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });


        });
    </script>

</body>

</html>