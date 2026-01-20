<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    .settings-page {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    .tab-content {
        min-height: 0 !important;
        overflow: visible !important;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Ensure content stays within viewport */
    body {
        overflow-x: hidden;
    }

    .nav-tabs .nav-link {
        color: #198754;
        /* Warna hijau Bootstrap */
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: #000;
        font-weight: 600;
    }

    /* Hover effect */
    .nav-tabs .nav-link:hover:not(.active) {
        color: #15693f;
        /* Warna hijau yang lebih gelap saat hover */
    }

    /* Category Dropdown Styles */
    .category-dropdown {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .dropdown-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-header:hover {
        opacity: 0.8;
    }

    #dropdown-icon {
        transition: transform 0.3s ease;
    }

    .dropdown-body {
        margin-top: 15px;
    }

    .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .dot.gray {
        background-color: #a0aec0;
    }

    /* CSS untuk menyembunyikan navbar saat modal muncul */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
    }

    .modal-overlay .modal-content {
        background: white;
        border-radius: 12px;
        padding: 20px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .modal-overlay.show .modal-content {
        transform: scale(1);
    }

    /* Hide navbar when modal is active */
    body.modal-active .app-navbar {
        display: none !important;
    }

    /* Prevent body scroll when modal is open */
    body.modal-active {
        overflow: hidden;
    }

    /* Profile picture preview styling */
    #profile-preview {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    #profile-preview:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Styling for category items */
    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        margin: 4px 0;
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .category-item:hover {
        background-color: #e9ecef;
    }

    .category-list h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 8px;
        padding-left: 8px;
    }
</style>

<body>

    <div class="settings-page container">

        <h2 class="mb-4">Settings</h2> <!-- Tab -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" id="settings-tab" data-bs-toggle="tab" href="#settings-content" role="tab">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="faq-tab" data-bs-toggle="tab" href="#faq-content" role="tab">FAQ & Bantuan</a>
            </li>
        </ul>
        <div class="tab-content" style="min-height: 0">
            <!-- Settings Content -->
            <div class="tab-pane fade show active" id="settings-content" role="tabpanel">

                <!-- Section 0: Subscription Management -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Status Langganan</h5>
                            <?php if (is_premium(session()->get('user_id'))) : ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="ri-vip-crown-fill me-1"></i> Premium
                                </span>
                            <?php else : ?>
                                <span class="badge bg-secondary">Free Plan</span>
                            <?php endif; ?>
                        </div>

                        <?php if (is_premium(session()->get('user_id'))) : ?>
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="ri-vip-crown-2-line fs-4 me-3"></i>
                                <div>
                                    <strong>Kamu adalah member Premium!</strong>
                                    <div class="small">Nikmati akses tanpa batas ke semua fitur FinanceFlow.</div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <a href="<?= base_url('app/subscription/my-subscription') ?>" class="btn btn-outline-warning text-dark">
                                    Kelola Langganan
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="p-3 bg-light rounded mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <div class="bg-white p-2 rounded-circle shadow-sm text-warning">
                                            <i class="ri-vip-crown-line fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 fw-bold">Upgrade ke Premium</h6>
                                        <div class="small text-muted">Buka semua fitur canggih</div>
                                    </div>
                                </div>
                                <ul class="small mb-0 ps-3 text-muted" style="list-style-type: check;">
                                    <li>Unlimited Kategori</li>
                                    <li>Fitur Menabung & Catat Utang</li>
                                    <li>Export Laporan (Excel/PDF)</li>
                                </ul>
                            </div>
                            <div class="d-grid">
                                <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-warning text-white fw-bold shadow-sm">
                                    Upgrade Sekarang 🚀
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section 1: Kategori -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pengaturan Yang Disarankan</h5>
                        <p class="text-muted">Atur kategori pengeluaran dan pemasukan sesuai kebutuhanmu!</p>

                        <!-- Collapsible Content -->
                        <div class="category-dropdown">
                            <div class="dropdown-header " onclick="toggleCategory()">
                                Kategori yang sering digunakan
                                <span id="dropdown-icon">▾</span>
                            </div>
                            <div class="dropdown-body" id="category-body">
                                <p class="fw-bold mt-3 mb-1">Top 5 Kategori Pengeluaran</p>
                                <ul class="list-unstyled">
                                    <li><span class="dot gray"></span> Makanan & Minuman</li>
                                    <li><span class="dot gray"></span> Transportasi</li>
                                    <li><span class="dot gray"></span> Belanja Online</li>
                                    <li><span class="dot gray"></span> Tagihan Bulanan</li>
                                    <li><span class="dot gray"></span> Hiburan</li>
                                </ul>

                                <p class="fw-bold mt-3 mb-1">Top 3 Kategori Pemasukan</p>
                                <ul class="list-unstyled">
                                    <li><span class="dot gray"></span> Gaji</li>
                                    <li><span class="dot gray"></span> Bisnis Sampingan</li>
                                    <li><span class="dot gray"></span> Investasi</li>
                                </ul>
                            </div>
                        </div>

                        <button class="btn btn-success w-100 mt-3" onclick="showModal()">Atur Kategori →</button>
                    </div>
                </div>


                <!-- Section 2: Kelola Data -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Kelola Data</h5>
                        <p class="text-muted">Import, export, atau reset data transaksi keuanganmu</p>
                        <div class="d-grid gap-2">
                            <?php if (is_premium_user()): ?>
                                <button class="btn btn-success" onclick="exportTransactions()">
                                    <i class="ri-file-excel-2-line"></i> Backup Data Transaksi (Excel)
                                </button>
                                <button class="btn btn-info" onclick="document.getElementById('importFile').click()">
                                    <i class="ri-upload-2-line"></i> Import Data Transaksi (Excel)
                                </button>
                                <input type="file" id="importFile" accept=".xlsx,.xls" style="display: none" onchange="importTransactions(this)">
                            <?php else: ?>
                                <button class="btn btn-secondary" onclick="showPremiumModal('Backup Data')">
                                    <i class="ri-lock-line"></i> Backup Data Transaksi (Premium)
                                </button>
                                <button class="btn btn-secondary" onclick="showPremiumModal('Import Data')">
                                    <i class="ri-lock-line"></i> Import Data Transaksi (Premium)
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted d-block"><strong>Format Export:</strong></small>
                            <small class="text-muted">• File Excel (.xlsx) dengan styling dan ringkasan</small>
                            <small class="text-muted d-block">• Kolom: No, ID, Tanggal, Tipe, Kategori, Deskripsi, Jumlah</small>
                            <small class="text-muted d-block">• Warna coding: Hijau (Pemasukan), Merah (Pengeluaran)</small>
                            <small class="text-muted d-block">• Dilengkapi ringkasan total dan saldo</small>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block"><strong>Format Import:</strong></small>
                            <small class="text-muted">• File Excel (.xlsx/.xls) dengan format yang sama</small>
                            <small class="text-muted d-block">• Download template dengan melakukan export terlebih dahulu</small>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted d-block">Alat Pemulihan Data</small>
                            <small class="text-muted">Gunakan alat ini jika transaksi Anda hilang tetapi masih ada dalam penyimpanan.</small>
                        </div>
                        <button class="btn btn-outline-danger w-100 mt-3" onclick="confirmReset()">
                            <i class="ri-delete-bin-line"></i> Reset Semua Data
                        </button>
                    </div>
                </div>

                <!-- Section 3: Akun -->
                <div class="card mb-5 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Akun</h5>
                        <p class="text-muted">Kelola pengaturan akunmu</p>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center bg-light p-3 rounded gap-2 mb-2">
                            <div class="text-wrap">
                                <strong>Email</strong><br>
                                <span class="text-muted small"><?= session()->get('email'); ?></span>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                Edit
                            </button>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center bg-light p-3 rounded gap-2 mb-2">
                            <div class="text-wrap">
                                <strong>Logout</strong><br>
                            </div>
                            <button onclick="confirmLogout()" class="btn btn-outline-danger btn-sm">Keluar</button>
                        </div>

                        <?php if (in_groups('admin')): ?>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center bg-light p-3 rounded gap-2 mb-2">
                                <div class="text-wrap">
                                    <strong>Beralih Ke Admin</strong>
                                    <span class="text-muted small d-block">Akses halaman admin panel</span>
                                </div>
                                <button onclick="confirmSwitchToAdmin()" class="btn btn-danger btn-sm">Masuk</button>
                            </div> <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Content -->
        <div class="tab-pane fade" id="faq-content" role="tabpanel">
            <!-- Umum -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pertanyaan Umum</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionUmum">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                                    Apa itu FinanceFlow?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionUmum">
                                <div class="accordion-body">
                                    FinanceFlow adalah aplikasi manajemen keuangan pribadi yang membantu Anda melacak pemasukan, pengeluaran, hutang, dan tabungan. Dengan FinanceFlow, Anda dapat dengan mudah mengelola dan memantau kondisi keuangan Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                    Apakah data saya aman?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionUmum">
                                <div class="accordion-body">
                                    Ya, FinanceFlow mengutamakan keamanan data pengguna. Kami menggunakan enkripsi untuk melindungi data Anda dan tidak membagikan informasi pribadi kepada pihak ketiga tanpa izin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fitur -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Panduan Fitur</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionFitur">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fitur1" aria-expanded="false" aria-controls="fitur1">
                                    Cara menambah transaksi
                                </button>
                            </h2>
                            <div id="fitur1" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Klik tombol "+" di halaman utama</li>
                                        <li>Pilih jenis transaksi (Pemasukan/Pengeluaran)</li>
                                        <li>Isi nominal dan kategori</li>
                                        <li>Tambahkan deskripsi jika perlu</li>
                                        <li>Klik "Simpan"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fitur2" aria-expanded="false" aria-controls="fitur2">
                                    Cara mengelola kategori
                                </button>
                            </h2>
                            <div id="fitur2" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Buka menu Settings</li>
                                        <li>Klik "Atur Kategori"</li>
                                        <li>Pilih tipe kategori (Pemasukan/Pengeluaran)</li>
                                        <li>Tambahkan kategori baru atau hapus yang ada</li>
                                        <li>Kategori default tidak dapat dihapus</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fitur3" aria-expanded="false" aria-controls="fitur3">
                                    Cara mencatat hutang
                                </button>
                            </h2>
                            <div id="fitur3" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Buka menu Note Utang</li>
                                        <li>Klik "Buat Note Utang"</li>
                                        <li>Pilih jenis: Pinjam atau Meminjamkan</li>
                                        <li>Untuk pinjaman online, pilih aplikasi dan atur cicilan</li>
                                        <li>Untuk pinjaman offline, masukkan detail pemberi pinjaman</li>
                                        <li>Isi jumlah dan tanggal</li>
                                        <li>Klik "Simpan"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fitur4" aria-expanded="false" aria-controls="fitur4">
                                    Cara mengatur target tabungan
                                </button>
                            </h2>
                            <div id="fitur4" class="accordion-collapse collapse" data-bs-parent="#accordionFitur">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Buka menu Saving Target</li>
                                        <li>Klik "Tambah Target"</li>
                                        <li>Isi nama dan jumlah target</li>
                                        <li>Tentukan tenggat waktu</li>
                                        <li>Klik "Simpan"</li>
                                        <li>Pantau progress di dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Troubleshooting</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionTrouble">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#trouble1" aria-expanded="false" aria-controls="trouble1">
                                    Data transaksi tidak muncul
                                </button>
                            </h2>
                            <div id="trouble1" class="accordion-collapse collapse" data-bs-parent="#accordionTrouble">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Refresh halaman</li>
                                        <li>Periksa koneksi internet</li>
                                        <li>Coba logout dan login kembali</li>
                                        <li>Gunakan tombol "Data Recovery Tool" di pengaturan</li>
                                        <li>Jika masalah berlanjut, hubungi support</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#trouble2" aria-expanded="false" aria-controls="trouble2">
                                    Lupa password
                                </button>
                            </h2>
                            <div id="trouble2" class="accordion-collapse collapse" data-bs-parent="#accordionTrouble">
                                <div class="accordion-body">
                                    <ol class="ps-3">
                                        <li>Klik "Lupa Password" di halaman login</li>
                                        <li>Masukkan email terdaftar</li>
                                        <li>Ikuti instruksi reset password yang dikirim ke email</li>
                                        <li>Buat password baru</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Hubungi Kami</h5>
                </div>
                <div class="card-body">
                    <p>Jika Anda memiliki pertanyaan atau masalah yang tidak tercantum di atas, silakan hubungi kami melalui:</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="mailto:<?= $settings['admin_email'] ?? 'ahmadkhadifar@gmail.com' ?>" class="btn btn-outline-primary">
                            <i class="ri-mail-line"></i> Email Support
                        </a>
                        <a href="https://wa.me/<?= str_replace(['+', '-', ' '], '', $settings['contact_phone'] ?? '089666285670') ?>" target="_blank" class="btn btn-outline-success">
                            <i class="ri-whatsapp-line"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Catagory -->
    <div id="categoryModal" class="modal-overlay" style="display: none;">
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
                <!-- Kategori akan di-render di sini -->
            </div>

            <div class="modal-footer">
                <span id="modalCategoryCount">0/<?= get_category_limit(session()->get('user_id')) ?> kategori</span>
                <button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm">
                <!-- Modal Header -->
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="editProfileLabel">Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Profil Info -->
                    <div class="text-center mb-4">
                        <img src="<?= session()->get('profile_picture') ?: base_url('assets/images/default.jpg'); ?>"
                            class="rounded-circle mb-2"
                            alt="Foto Profil"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        <h6 class="mb-1"><?= session()->get('fullname'); ?></h6>
                        <p class="text-muted small">Bergabung: <?= date('d M Y', strtotime($user['created_at'])); ?></p>
                    </div>

                    <!-- Info -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small py-1">
                            <span class="fw-semibold text-muted">Username</span>
                            <span class="text-end"><?= session()->get('username'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between small py-1">
                            <span class="fw-semibold text-muted">Fullname</span>
                            <span class="text-end"><?= session()->get('fullname'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between small py-1">
                            <span class="fw-semibold text-muted">Total Pemasukan</span>
                            <span class="text-end text-success">Rp <?= number_format($total_income, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between small py-1">
                            <span class="fw-semibold text-muted">Email</span>
                            <span class="text-end text-break"><?= session()->get('email'); ?></span>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-justified" id="editProfileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab">
                                <i class="ri-user-line"></i> Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#edit-password" type="button" role="tab">
                                <i class="ri-lock-line"></i> Password
                            </button>
                        </li>
                    </ul>

                    <!-- Form Start -->
                    <form id="editProfileForm" action="<?= base_url('app/ajax/updateProfile'); ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Tab Contents -->
                        <div class="tab-content pt-3" id="editProfileTabContent">
                            <!-- Tab: Edit Profil -->
                            <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="mb-3">
                                    <label class="form-label">Photo Profile Link</label>
                                    <div class="input-group">
                                        <input type="url" class="form-control" name="profile_picture"
                                            value="<?= session()->get('profile_picture') ?: '' ?>"
                                            placeholder="https://i.imgur.com/example.jpg"
                                            oninput="previewProfileImage(this.value)">
                                        <button class="btn btn-outline-secondary" type="button" onclick="setDefaultImage()">
                                            Default
                                        </button>
                                        <button class="btn btn-outline-info" type="button" onclick="testImgurUrl()">
                                            Test
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        <strong>Contoh URL yang didukung:</strong><br>
                                        • Link langsung: <code>https://i.imgur.com/abc123.jpg</code><br>
                                        • Imgur galeri: <code>https://imgur.com/gallery/xxx-abc123</code><br>
                                        • URL gambar lain: <code>https://example.com/photo.png</code>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Preview:</small>
                                        <div class="border rounded p-2 mt-1 text-center" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                            <img id="profile-preview" 
                                                 src="<?= session()->get('profile_picture') ?: base_url('assets/images/default.jpg'); ?>" 
                                                 alt="Preview" 
                                                 style="max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 50%;"
                                                 onerror="this.src='<?= base_url('assets/images/default.jpg'); ?>'; showImageError();">
                                        </div>
                                        <div id="image-status" class="mt-1"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fullname</label>
                                    <input type="text" class="form-control" name="fullname"
                                        value="<?= session()->get('fullname'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?= session()->get('email'); ?>" required>
                                </div>
                            </div>

                            <!-- Tab: Edit Password -->
                            <div class="tab-pane fade" id="edit-password" role="tabpanel" aria-labelledby="security-tab">
                                <div class="mb-3">
                                    <label class="form-label">Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="old_password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimal 6 karakter</div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer border-0 px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PENTING: Fungsionalitas buka/tutup (collapse) pada Accordion di halaman ini
        // sepenuhnya bergantung pada file JavaScript Bootstrap 5.
        // Jika Accordion tidak berfungsi (tidak bisa dibuka/ditutup),
        // pastikan file JS Bootstrap dimuat dengan benar di halaman ini
        // dan tidak ada error JavaScript lain yang menghambatnya.
        // Cek Console dan Network tab di browser Developer Tools (F12) untuk debugging.

        // Script untuk menangani event saat tab FAQ aktif (opsional, bisa dihapus jika tidak ada masalah spesifik dengan tab)
        var faqTabEl = document.getElementById('faq-tab');
        var faqContentEl = document.getElementById('faq-content');

        if (faqTabEl && faqContentEl) {
            faqTabEl.addEventListener('shown.bs.tab', function(event) {
                // Opsional: Coba picu ulang collapse pada semua accordion di tab FAQ
                // Ini mungkin tidak diperlukan jika Bootstrap JS sudah terload dengan benar
                var accordionsInTab = faqContentEl.querySelectorAll('.accordion-collapse');
                accordionsInTab.forEach(function(accordion) {
                    // Gunakan metode 'hide' lalu 'show' atau cukup 'toggle' jika diperlukan
                    // new bootstrap.Collapse(accordion, { toggle: false }).hide(); // Contoh: sembunyikan dulu
                    // new bootstrap.Collapse(accordion, { toggle: false }).show(); // Contoh: lalu tampilkan

                    // Atau coba toggle setiap item jika ada masalah state awal
                    var buttons = accordion.closest('.accordion-item').querySelectorAll('.accordion-button');
                    buttons.forEach(function(button) {
                        // Memaksa toggle - gunakan dengan hati-hati
                        // button.click();
                    });
                });
                console.log('Tab FAQ ditampilkan, mencoba refresh accordion state');
            });
        }

        function setDefaultImage() {
            const defaultUrl = '<?= base_url('assets/images/default.jpg'); ?>';
            document.querySelector('input[name="profile_picture"]').value = defaultUrl;
            previewProfileImage(defaultUrl);
        }

        function convertToDirectImageUrl(url) {
            // Convert Imgur gallery URLs to direct image URLs
            if (url.includes('imgur.com/gallery/')) {
                const match = url.match(/imgur\.com\/gallery\/[^\/]*-([a-zA-Z0-9]+)/);
                if (match && match[1]) {
                    return 'https://i.imgur.com/' + match[1] + '.jpg';
                }
            }
            
            // Convert regular Imgur URLs to direct URLs
            if (url.includes('imgur.com/') && !url.includes('i.imgur.com')) {
                const match = url.match(/imgur\.com\/([a-zA-Z0-9]+)/);
                if (match && match[1]) {
                    return 'https://i.imgur.com/' + match[1] + '.jpg';
                }
            }
            
            return url;
        }

        function showImageError() {
            const status = document.getElementById('image-status');
            status.innerHTML = '<small class="text-danger">❌ Gambar tidak dapat dimuat</small>';
        }

        function showImageSuccess() {
            const status = document.getElementById('image-status');
            status.innerHTML = '<small class="text-success">✅ Gambar berhasil dimuat</small>';
        }

        function previewProfileImage(url) {
            const preview = document.getElementById('profile-preview');
            const status = document.getElementById('image-status');
            
            if (url && url.trim() !== '') {
                // Convert URL if needed
                const directUrl = convertToDirectImageUrl(url);
                
                // Update input field if URL was converted
                if (directUrl !== url) {
                    document.querySelector('input[name="profile_picture"]').value = directUrl;
                    status.innerHTML = '<small class="text-info">🔄 URL dikonversi: ' + directUrl + '</small>';
                }
                
                // Check if URL is valid
                if (isValidUrl(directUrl)) {
                    preview.onload = showImageSuccess;
                    preview.onerror = showImageError;
                    preview.src = directUrl;
                } else {
                    preview.src = '<?= base_url('assets/images/default.jpg'); ?>';
                    status.innerHTML = '<small class="text-warning">⚠️ Format URL tidak valid</small>';
                }
            } else {
                preview.src = '<?= base_url('assets/images/default.jpg'); ?>';
                status.innerHTML = '';
            }
        }

        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }

        function testImgurUrl() {
            // Test dengan URL yang diberikan user
            const testUrl = 'https://imgur.com/gallery/graphic-design-is-passion-PbBlwBq';
            document.querySelector('input[name="profile_picture"]').value = testUrl;
            previewProfileImage(testUrl);
        }

        function togglePassword(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-off-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-line';
            }
        }

        // Handle form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate profile picture URL
            const profilePictureInput = document.querySelector('input[name="profile_picture"]');
            const profilePictureUrl = profilePictureInput.value.trim();
            
            if (profilePictureUrl && !isValidUrl(profilePictureUrl)) {
                Swal.fire({
                    icon: 'error',
                    title: 'URL Tidak Valid',
                    text: 'Silakan masukkan URL gambar yang valid'
                });
                return;
            }

            const formData = new FormData(this);

            // Show loading
            Swal.fire({
                title: 'Memperbarui Profil',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then((data) => {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem'
                    });
                });
        });

        function loadCategories() {
            fetch('<?= base_url('app/ajax/getCategories') ?>')
                .then(response => response.json())
                .then(categories => {
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
                                    ${cat.user_id != 0 ? `
                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory('${cat.id}')">
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
                                    ${cat.user_id != 0 ? `
                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory('${cat.id}')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    ` : ''}
                                </div>`;
                        });
                    }

                    const subLimit = <?= get_category_limit(session()->get('user_id')) ?>;
                    const countText = `${categories.length}/${subLimit} kategori`;
                    
                    // Update count in duplicate elements (modal and page)
                    const countElements = document.querySelectorAll("#categoryCount, #modalCategoryCount");
                    countElements.forEach(el => el.innerText = countText);
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
    }

    // Load categories when modal is shown
    function showModal() {
        const modal = document.getElementById("categoryModal");
        document.body.classList.add("modal-active"); // Hide navbar
        modal.style.display = "flex";
        
        // Trigger animation
        setTimeout(() => {
            modal.classList.add("show");
        }, 10);
        
        loadCategories();
    }

        function closeModal() {
            const modal = document.getElementById("categoryModal");
            modal.classList.remove("show");
            
            // Wait for animation to complete before hiding
            setTimeout(() => {
                modal.style.display = "none";
                document.body.classList.remove("modal-active"); // Show navbar again
            }, 300);
        }

        // Add new category function
        function addCategory() {
            const categoryName = document.getElementById('newCategory').value.trim();
            const categoryType = document.getElementById('categoryType').value;

            if (!categoryName) {
                alert('Nama kategori tidak boleh kosong');
                return;
            }

            $.ajax({
                url: '<?= base_url('app/ajax/addCategory') ?>',
                method: 'POST',
                data: {
                    name: categoryName,
                    type: categoryType
                },
                success: function(response) {
                    if (response.status) {
                        document.getElementById('newCategory').value = '';
                        loadCategories();
                        // Show success message
                        alert('Kategori berhasil ditambahkan');
                    } else {
                        alert(response.message || 'Gagal menambahkan kategori');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menambahkan kategori');
                }
            });
        }

        // Delete category function
        function deleteCategory(categoryId) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                $.ajax({
                    url: '<?= base_url('app/ajax/deleteCategory/') ?>' + categoryId,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status) {
                            loadCategories();
                            alert('Kategori berhasil dihapus');
                        } else {
                            alert(response.message || 'Gagal menghapus kategori');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menghapus kategori');
                    }
                });
            }
        }

        function toggleCategory() {
            const body = document.getElementById('category-body');
            const icon = document.getElementById('dropdown-icon');
            if (body.style.display === 'none' || body.style.display === '') {
                body.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                body.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Initialize category dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const categoryBody = document.getElementById('category-body');
            if (categoryBody) {
                categoryBody.style.display = 'none';
            }

            // Close modal when clicking outside of it
            const categoryModal = document.getElementById('categoryModal');
            const modalContent = categoryModal?.querySelector('.modal-content');
            
            if (categoryModal) {
                categoryModal.addEventListener('click', function(e) {
                    if (e.target === categoryModal) {
                        closeModal();
                    }
                });
            }

            // Prevent modal from closing when clicking inside modal content
            if (modalContent) {
                modalContent.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('categoryModal');
                    if (modal && modal.style.display === 'flex') {
                        closeModal();
                    }
                }
            });

            // Cleanup when page is about to unload
            window.addEventListener('beforeunload', function() {
                document.body.classList.remove("modal-active");
            });
        });
    </script>

    <script>
        function exportTransactions() {
            // Show loading indicator
            Swal.fire({
                title: 'Memproses Export...',
                text: 'Mohon tunggu, sedang memproses data transaksi',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Direct download
            window.location.href = '<?= base_url('app/ajax/exportTransactions') ?>';
            
            // Close loading after a short delay and show success message
            setTimeout(() => {
                Swal.close();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Export Berhasil!',
                    text: 'File Excel telah diunduh. Silakan cek folder Downloads Anda.',
                    showConfirmButton: true,
                    timer: 3000
                });
            }, 2000);
        }

        function importTransactions(input) {
            const file = input.files[0];
            if (!file) return;

            // Validate file type
            const allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                'application/vnd.ms-excel' // .xls
            ];
            
            if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.xlsx') && !file.name.toLowerCase().endsWith('.xls')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Salah',
                    text: 'Silakan pilih file Excel (.xlsx atau .xls)'
                });
                input.value = '';
                return;
            }

            // Validate file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 10MB'
                });
                input.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            Swal.fire({
                title: 'Mengimport Data Excel',
                html: `
                    <div class="text-start">
                        <p><strong>File:</strong> ${file.name}</p>
                        <p><strong>Ukuran:</strong> ${(file.size / 1024).toFixed(1)} KB</p>
                        <p class="text-muted">Mohon tunggu, sedang memproses...</p>
                    </div>
                `,
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('<?= base_url('app/ajax/importTransactions') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        let htmlContent = `
                            <div class="text-start">
                                <h6 class="text-success">✅ Import Berhasil!</h6>
                                <p><strong>Transaksi berhasil diimpor:</strong> ${data.imported || 0}</p>
                        `;
                        
                        if (data.skipped > 0) {
                            htmlContent += `<p><strong>Baris dilewati:</strong> ${data.skipped}</p>`;
                        }
                        
                        if (data.errors && data.errors.length > 0) {
                            htmlContent += `
                                <p><strong>Error ditemukan:</strong> ${data.errors.length}</p>
                                <div class="mt-2">
                                    <small class="text-muted">Detail error:</small>
                                    <ul class="small text-danger mt-1">
                            `;
                            data.errors.forEach(error => {
                                htmlContent += `<li>${error}</li>`;
                            });
                            htmlContent += `</ul></div>`;
                        }
                        
                        htmlContent += `</div>`;

                        Swal.fire({
                            icon: 'success',
                            title: 'Import Selesai',
                            html: htmlContent,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (data.imported > 0) {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Gagal',
                        html: `
                            <div class="text-start">
                                <p class="text-danger">${error.message || 'Terjadi kesalahan saat import data'}</p>
                                <div class="mt-3">
                                    <small class="text-muted"><strong>Tips:</strong></small>
                                    <ul class="small text-muted mt-1">
                                        <li>Pastikan file Excel tidak rusak</li>
                                        <li>Gunakan template yang didownload dari export</li>
                                        <li>Pastikan format tanggal benar (dd/mm/yyyy)</li>
                                        <li>Pastikan kolom Tipe berisi 'Pemasukan' atau 'Pengeluaran'</li>
                                    </ul>
                                </div>
                            </div>
                        `
                    });
                });

            input.value = '';
        }

        function confirmReset() {
            Swal.fire({
                title: 'Reset Semua Data?',
                text: "Semua data transaksi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetData();
                }
            });
        }

        function resetData() {
            fetch('<?= base_url('app/ajax/resetData') ?>', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500
                        }).then(() => window.location.reload());
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Terjadi kesalahan saat reset data'
                    });
                });
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Keluar dari Aplikasi?',
                text: "Anda akan keluar dari sesi saat ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('/logout'); ?>';
                }
            });
        }

        function confirmSwitchToAdmin() {
            Swal.fire({
                title: 'Beralih ke Panel Admin?',
                text: "Anda akan dialihkan ke halaman admin",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Beralih!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('/administrator/dashboard'); ?>';
                }
            });
        }
    </script>

</body>

<?= $this->endSection(); ?>