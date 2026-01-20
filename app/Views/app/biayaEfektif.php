<?= $this->extend('layout/users/template') ?>

<?php
function calculateMonthlyTotal($biayaEfektif)
{
    $total = 0;
    
    foreach ($biayaEfektif as $biaya) {
        // Cast jumlah to float to ensure proper numeric handling
        $jumlah = floatval($biaya['jumlah']);

        switch (strtolower($biaya['frekuensi'])) {
            case 'harian':
                $total += $jumlah * 30; // Approximate days in a month
                break;
            case 'mingguan':
                $total += $jumlah * 4; // Approximate weeks in a month
                break;
            case 'bulanan':
                $total += $jumlah;
                break;
        }
    }
    
    return $total;
}

function calculateYearlyTotal($biayaEfektif)
{
    $total = 0;
    foreach ($biayaEfektif as $biaya) {
        if ($biaya['frekuensi'] === 'Tahunan') {
            $total += $biaya['jumlah'];
        }
    }
    return $total;
}

function formatDate($dateString)
{
    if (!$dateString || $dateString === '1970-01-01' || $dateString === '0000-00-00' || $dateString === 'null') {
        return 'Tidak ada batas';
    }
    $date = date_create($dateString);
    if (!$date) {
        return 'Tidak ada batas';
    }
    return date_format($date, 'd/m/Y');
}
?>

<?= $this->section('styles') ?>
<style>
    .container-fluid {
        padding: 20px !important;
    }

    .card {
        border-radius: 8px;
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, 0.125);
        margin-bottom: 16px;
    }

    h2 {
        font-size: 20px;
        font-weight: 500;
    }

    /* Total Card */
    .total-card {
        margin-bottom: 16px;
        padding: 16px;
    }

    .total-label {
        color: #198754;
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .total-amount {
        color: #198754;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .total-description {
        color: #6c757d;
        font-size: 14px;
    }

    /* Table Card */
    .card-body {
        padding: 16px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 16px;
    }

    /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table th {
        background: white;
        color: #000;
        font-weight: 500;
        font-size: 14px;
        padding: 12px 16px;
        border-bottom: 1px solid #dee2e6;
    }

    .table td {
        padding: 12px 16px;
        font-size: 14px;
        color: #000;
        border-bottom: 1px solid #dee2e6;
        background: white;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    /* Action Buttons */
    .btn {
        padding: 4px 8px;
        font-size: 14px;
    }

    .btn-warning {
        background: #ffc107;
        border: none;
        color: #000;
        border-radius: 4px;
        margin-right: 4px;
    }

    .btn-danger {
        background: #dc3545;
        border: none;
        color: white;
        border-radius: 4px;
    }

    /* Badge Styling */
    .badge-frekuensi {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        display: inline-block;
        /* Ensure proper display */
        text-align: center;
        color: #fff !important;
        /* Ensure text is visible */
        background-color: #000 !important;
        /* Temporary black background for debugging */
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: #6c757d;
        display: none;
    }

    .empty-state i {
        font-size: 32px;
        color: #adb5bd;
        margin-bottom: 12px;
    }

    .empty-state h4 {
        font-size: 16px;
        color: #000;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 16px;
        font-size: 14px;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(25, 135, 84, 0.10);
        border: none;
    }

    .modal-header {
        padding: 24px 28px 12px 28px;
        border-bottom: 1.5px solid #e9ecef;
        background: linear-gradient(90deg, #f8fafc 60%, #e9f7ef 100%);
        border-radius: 18px 18px 0 0;
    }

    .modal-title {
        font-size: 1.22rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #198754;
        letter-spacing: 0.2px;
    }

    .modal-title .ri {
        font-size: 1.4rem;
        color: #198754;
    }

    .modal-body {
        padding: 24px 28px 12px 28px;
        background: #fff;
    }

    .modal-body ul.list-unstyled {
        margin-bottom: 0;
    }

    .modal-body li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        font-size: 1.08rem;
        font-weight: 500;
        color: #222;
    }

    .modal-body li .ri {
        color: #43e97b;
        font-size: 1.18rem;
        min-width: 22px;
        text-align: center;
    }

    .modal-body li strong {
        font-weight: 700;
        color: #198754;
    }

    .modal-footer {
        padding: 18px 28px 18px 28px;
        border-top: 1.5px solid #e9ecef;
        border-radius: 0 0 18px 18px;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .modal-footer .btn {
        min-width: 100px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 0;
    }

    .modal-footer .btn-outline-danger {
        border-width: 2px;
    }

    .modal-footer .btn-outline-danger i {
        margin-right: 4px;
    }

    @media (max-width: 576px) {

        .modal-header,
        .modal-body,
        .modal-footer {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #000;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 6px 12px;
        font-size: 14px;
        height: auto;
    }

    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }

    .input-group-text {
        border-radius: 4px 0 0 4px;
        border: 1px solid #ced4da;
        background: #f8f9fa;
        padding: 6px 12px;
        font-size: 14px;
    }

    /* Loading Spinner */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #198754;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }

    /* Responsive Table Styling */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table th,
    .table td {
        white-space: nowrap;
        /* Prevent text wrapping */
        text-overflow: ellipsis;
        /* Add ellipsis for overflowed text */
        overflow: hidden;
        max-width: 200px;
        /* Adjust as needed */
    }

    /* Adjust Badge and Button Sizes */
    .badge-frekuensi {
        padding: 2px 6px;
        /* Reduce padding */
        font-size: 10px;
        /* Smaller font size */
    }

    .btn {
        padding: 2px 6px;
        /* Reduce button padding */
        font-size: 12px;
        /* Smaller font size */
    }

    /* Ensure Table Alignment */
    .table td {
        vertical-align: middle;
        /* Center align content vertically */
    }

    /* Tambahkan custom CSS untuk card */
    .custom-card {
        border-radius: 16px;
        border: 1px solid #e0e0e0;
        background: #fff;
        transition: box-shadow 0.2s;
        min-height: 170px;
        position: relative;
        box-shadow: 0 2px 12px rgba(25, 135, 84, 0.06);
        padding: 24px 20px 20px 20px;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }

    .custom-card:hover {
        box-shadow: 0 6px 32px rgba(25, 135, 84, 0.12);
    }

    .custom-card .card-body {
        padding: 0;
    }

    .card-actions {
        position: absolute;
        top: 16px;
        right: 16px;
        display: flex;
        gap: 10px;
        z-index: 2;
    }

    .card-actions .btn {
        box-shadow: none;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        padding: 0;
        background: #f8f9fa;
        border: 1.5px solid #e0e0e0;
        transition: border-color 0.2s, background 0.2s;
    }

    .card-actions .btn:active,
    .card-actions .btn:focus {
        outline: none;
        box-shadow: 0 0 0 2px #19875433;
    }

    .card-actions .btn-light {
        color: #198754;
    }

    .card-actions .btn-outline-danger {
        color: #dc3545;
        border-color: #ffeaea;
        background: #fff;
    }

    .card-actions .btn-outline-danger:hover {
        background: #fff0f0;
        border-color: #dc3545;
        color: #dc3545;
    }

    @media (max-width: 576px) {
        .custom-card {
            padding: 18px 8px 16px 8px;
        }

        .card-actions {
            top: 10px;
            right: 10px;
            gap: 6px;
        }

        .card-actions .btn {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }
    }

    .badge-frekuensi {
        display: inline-block;
        padding: 6px 14px;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 16px;
        background: linear-gradient(90deg, #198754 60%, #43e97b 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(25, 135, 84, 0.08);
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">Biaya Efektif</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="ri-add-circle-line"></i> Tambah Biaya
        </button>
    </div>

    <!-- Total Biaya Bulanan -->
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <i class="ri-calculator-line text-success" style="font-size: 2.5rem;"></i>
            </div>
            <div>
                <h5 class="mb-1">Total Biaya Bulanan</h5>
                <h2 class="text-success mb-0" id="totalMonthlyCost">
                    Rp <?= number_format(calculateMonthlyTotal($biayaEfektif), 0, ',', '.') ?>
                </h2>
                <small class="text-muted">Total biaya efektif yang harus dibayar setiap bulan</small>
            </div>
        </div>
    </div>

    <!-- Tabel Biaya Efektif -->
    <div class="row" id="biayaEfektifCards">
        <?php foreach ($biayaEfektif as $biaya) : ?>
            <div class="col-12 mb-3">
                <div class="custom-card shadow-sm position-relative">
                    <div class="card-actions">
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?= $biaya['id'] ?>" title="Lihat Detail">
                            <i class="ri-eye-line"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?= $biaya['id'] ?>)" title="Hapus">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="fw-bold fs-5 mb-1" style="word-break: break-word;"><?= esc($biaya['nama_biaya']) ?></div>
                        <div class="text-muted small mb-2" style="word-break: break-word;"><?= esc($biaya['kategori']) ?></div>
                        <div class="text-success fw-bold fs-6 mb-1">
                            Rp <?= number_format($biaya['jumlah'], 0, ',', '.') ?>
                        </div>
                        <div class="mb-1">
                            <span class="badge-frekuensi">
                                <i class="ri-time-line me-1"></i><?= esc($biaya['frekuensi']) ?>
                            </span>
                        </div>
                        <div class="text-muted small">
                            <?= formatDate($biaya['tanggal_mulai']) ?> - <?= formatDate($biaya['tanggal_selesai']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Detail -->
            <div class="modal fade" id="detailModal<?= $biaya['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $biaya['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel<?= $biaya['id'] ?>">
                                <i class="ri-information-line"></i> Detail Biaya Efektif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-unstyled mb-0">
                                <li><i class="ri-file-list-2-line"></i> <strong>Nama Biaya:</strong> <?= esc($biaya['nama_biaya']) ?></li>
                                <li><i class="ri-price-tag-3-line"></i> <strong>Kategori:</strong> <?= esc($biaya['kategori']) ?></li>
                                <li><i class="ri-money-dollar-circle-line"></i> <strong>Jumlah:</strong> Rp <?= number_format($biaya['jumlah'], 0, ',', '.') ?></li>
                                <li><i class="ri-time-line"></i> <strong>Frekuensi:</strong> <?= esc($biaya['frekuensi']) ?></li>
                                <li><i class="ri-calendar-line"></i> <strong>Tanggal Mulai:</strong> <?= formatDate($biaya['tanggal_mulai']) ?></li>
                                <li><i class="ri-calendar-check-line"></i> <strong>Tanggal Selesai:</strong> <?= formatDate($biaya['tanggal_selesai']) ?></li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" onclick="confirmDelete(<?= $biaya['id'] ?>)">
                                <i class="ri-delete-bin-line"></i> Hapus
                            </button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="loading-spinner"></div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addForm" onsubmit="saveBiayaEfektif(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Biaya Efektif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select class="form-control" name="kategori" required>
                            <option value="">Pilih kategori</option>
                            <option value="Rumah">Rumah</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Listrik">Listrik</option>
                            <option value="Air">Air</option>
                            <option value="Internet">Internet</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Asuransi">Asuransi</option>
                            <option value="Hiburan">Hiburan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group" id="kategoriLainnya" style="display: none;">
                        <label class="form-label">Kategori Lainnya</label>
                        <input type="text" class="form-control" name="kategori_lainnya" placeholder="Masukkan kategori lainnya">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Biaya</label>
                        <input type="text" class="form-control" name="nama_biaya" required placeholder="Contoh: Sewa Rumah">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="jumlah" required placeholder="Masukkan jumlah biaya">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Frekuensi</label>
                        <select class="form-control" name="frekuensi" required>
                            <option value="">Pilih frekuensi pembayaran</option>
                            <option value="Harian">Harian</option>
                            <option value="Mingguan">Mingguan</option>
                            <option value="Bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" required>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2">Tanggal Selesai</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="noEndDate">
                                <label class="form-check-label" for="noEndDate">
                                    Tidak ada batas waktu
                                </label>
                            </div>
                        </div>
                        <input type="date" class="form-control" name="tanggal_selesai" id="tanggalSelesai">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-save-line me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    function formatDate(dateString) {
        if (!dateString || dateString === '1970-01-01' || dateString === '0000-00-00' || dateString === 'null') {
            return 'Tidak ada batas';
        }
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Tidak ada batas';
        }
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.style.display = 'flex';
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.opacity = '1', 10);
    }

    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.display = 'none', 300);
    }

    function showEmptyState() {
        const table = document.getElementById('biayaEfektifTable');
        const emptyState = document.getElementById('emptyState');

        table.style.display = 'none';
        emptyState.style.display = 'block';
        emptyState.style.opacity = '0';
        setTimeout(() => emptyState.style.opacity = '1', 10);
    }

    function hideEmptyState() {
        const table = document.getElementById('biayaEfektifTable');
        const emptyState = document.getElementById('emptyState');

        emptyState.style.opacity = '0';
        setTimeout(() => {
            emptyState.style.display = 'none';
            table.style.display = 'table';
        }, 300);
    }

    function updateStats(data) {
        // Function untuk update statistik (tidak digunakan untuk sementara)
    }

    function animateNumber(element, start, end, duration) {
        // Function untuk animasi number (tidak digunakan untuk sementara) 
    }

    function easeOutQuad(t) {
        return t * (2 - t);
    }

    function updateTable(data) {
        // Function untuk update table (tidak digunakan karena menggunakan server-side rendering)
    }

    function loadBiayaEfektifData() {
        showLoading();

        // Reload the page to refresh data
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    function updateTable(data) {
        // Function untuk update table (tidak digunakan karena menggunakan server-side rendering)
    }

    function calculateMonthlyAmount(amount, frequency) {
        amount = parseFloat(amount) || 0;

        switch (String(frequency).toLowerCase()) {
            case 'harian':
                return amount * 30;
            case 'mingguan': 
                return amount * 4;
            case 'bulanan':
                return amount;
            default:
                return amount;
        }
    }

    function animateValue(element, start, end, duration) {
        const formatter = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        let startTime = null;
        
        function animate(currentTime) {
            if (!startTime) startTime = currentTime;
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (end - start) * progress);
            element.innerHTML = `Rp ${formatter.format(current)}`;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
        
        requestAnimationFrame(animate);
    }

    function handleFormError(error, form) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan sistem. Silakan coba lagi.'
        });
        if (form) {
            form.find('button[type="submit"]').prop('disabled', false);
        }
    }

    function showSuccessAlert(message, callback) {
        const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
        if (modal) {
            modal.dispose();
        }

        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');

        return Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    }

    function saveBiayaEfektif(e) {
        e.preventDefault();
        const form = $('#addForm');
        const formData = new FormData(form[0]);

        // Validasi form
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        // Handle kategori lainnya
        if (formData.get('kategori') === 'Lainnya') {
            const kategoriLainnya = formData.get('kategori_lainnya');
            if (!kategoriLainnya || kategoriLainnya.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan isi kategori lainnya'
                });
                return;
            }
            formData.set('kategori', kategoriLainnya.trim());
        }
        formData.delete('kategori_lainnya');

        // Handle tanggal selesai
        if ($('#noEndDate').is(':checked')) {
            formData.delete('tanggal_selesai');
        }

        showLoading();
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('app/ajax/saveBiayaEfektif') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    showSuccessAlert('Biaya efektif berhasil ditambahkan!');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menyimpan data. Silakan coba lagi.'
                });
            },
            complete: function() {
                hideLoading();
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });
    } // Removed updateBiayaEfektif function as it's no longer needed

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus biaya efektif ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteBiayaEfektif(id);
            }
        });
    }

    function deleteBiayaEfektif(id) {
        showLoading();
        
        $.ajax({
            url: `<?= base_url('app/ajax/deleteBiayaEfektif') ?>/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    showSuccessAlert('Biaya efektif berhasil dihapus!');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Terjadi kesalahan saat menghapus data'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menghapus data. Silakan coba lagi.'
                });
            },
            complete: function() {
                hideLoading();
            }
        });
    }

    // Event Handlers dan Initialization
    $(document).ready(function() {
        // Add CSS keyframes for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        // Handle kategori dropdown
        $('select[name="kategori"]').on('change', function() {
            const kategoriLainnya = $('#kategoriLainnya');
            const kategoriLainnyaInput = $('input[name="kategori_lainnya"]');
            
            if ($(this).val() === 'Lainnya') {
                kategoriLainnya.slideDown(300);
                kategoriLainnyaInput.prop('required', true);
            } else {
                kategoriLainnya.slideUp(300);
                kategoriLainnyaInput.prop('required', false).val('');
            }
        });

        // Handle checkbox tanggal selesai
        $('#noEndDate').on('change', function() {
            const tanggalSelesai = $('#tanggalSelesai');
            
            if ($(this).is(':checked')) {
                tanggalSelesai.prop('required', false).prop('disabled', true).val('');
            } else {
                tanggalSelesai.prop('required', true).prop('disabled', false);
            }
        });

        // Reset form when modal is hidden
        $('#addModal').on('hidden.bs.modal', function() {
            const form = $('#addForm')[0];
            form.reset();
            
            // Reset kategori lainnya
            $('#kategoriLainnya').hide();
            $('input[name="kategori_lainnya"]').prop('required', false);
            
            // Reset tanggal selesai
            $('#tanggalSelesai').prop('required', true).prop('disabled', false);
            $('#noEndDate').prop('checked', false);
            
            // Remove validation classes
            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.invalid-feedback').remove();
        });

        // Prevent double submission
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true);
        });

        // Initialize tooltip if Bootstrap tooltip is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        console.log('Biaya Efektif page initialized successfully');
    });
</script>
<?= $this->endSection() ?>