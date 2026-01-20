<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-4" style="max-width: 800px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Catatan Cicilan</h5>
        <button class="btn btn-success" onclick="showCicilanForm()">
            <i class="ri-add-circle-line"></i> Tambah Cicilan
        </button>
    </div>

    <!-- Entry Form - Initially Hidden -->
    <div id="cicilanForm" class="card mb-4" style="display: none;">
        <div class="card-body">
            <form id="newCicilanForm" onsubmit="saveCicilan(event)">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Cicilan</label>
                        <input type="text" class="form-control" id="cicilanName" required placeholder="Contoh: Cicilan Motor">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Cicilan</label>
                        <select class="form-select" id="cicilanType" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Properti">Properti</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="KTA">Pinjaman KTA</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cicilan per Bulan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="monthlyAmount" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tenor (Bulan)</label>
                        <input type="number" class="form-control" id="tenor" required min="1" onchange="calculateTotal()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="startDate" required>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-light me-2" onclick="hideCicilanForm()">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Cicilan List -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0">Cicilan Aktif</h6>
        </div>
        <div class="card-body" id="activeCicilanList">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Cicilan History -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Riwayat Cicilan</h6>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success active" onclick="filterCicilan('all')">Semua</button>
                <button type="button" class="btn btn-outline-success" onclick="filterCicilan('active')">Aktif</button>
                <button type="button" class="btn btn-outline-success" onclick="filterCicilan('completed')">Lunas</button>
            </div>
        </div>
        <div class="card-body" id="cicilanHistoryList">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
    .cicilan-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
    }

    .cicilan-card:last-child {
        margin-bottom: 0;
    }

    .cicilan-progress {
        height: 8px;
        margin: 10px 0;
    }

    .cicilan-badge {
        font-size: 0.8em;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .cicilan-badge.active {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .cicilan-badge.completed {
        background-color: #eceff1;
        color: #546e7a;
    }

    .cicilan-badge.overdue {
        background-color: #ffebee;
        color: #c62828;
    }

    /* Mobile-friendly adjustments */
    @media (max-width: 576px) {
        .cicilan-card {
            padding: 10px;
        }

        .cicilan-card h6 {
            font-size: 1rem;
        }

        .cicilan-card .small {
            font-size: 0.8rem;
        }

        .cicilan-card .row.g-2>div {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .cicilan-card .text-end {
            text-align: center !important;
            margin-top: 10px;
        }

        .btn-group {
            flex-wrap: wrap;
        }

        .btn-group .btn {
            flex: 1 1 auto;
            margin-bottom: 5px;
        }
    }
</style>

<script>
    function showCicilanForm() {
        document.getElementById('cicilanForm').style.display = 'block';
    }

    function hideCicilanForm() {
        document.getElementById('cicilanForm').style.display = 'none';
        document.getElementById('newCicilanForm').reset();
    }

    function calculateTotal() {
        const monthlyAmount = Number(document.getElementById('monthlyAmount').value);
        const tenor = Number(document.getElementById('tenor').value);
        return monthlyAmount * tenor;
    }

    function saveCicilan(event) {
        event.preventDefault();

        const monthlyAmount = Number(document.getElementById('monthlyAmount').value);
        const tenor = Number(document.getElementById('tenor').value);
        const totalAmount = calculateTotal();
        const formData = {
            name: document.getElementById('cicilanName').value,
            type: document.getElementById('cicilanType').value,
            totalAmount: totalAmount,
            monthlyAmount: monthlyAmount,
            tenor: tenor,
            startDate: document.getElementById('startDate').value
        };

        // TODO: Add AJAX call to save cicilan
        fetch('/app/ajax/saveCicilan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Cicilan berhasil disimpan'
                    }).then(() => {
                        hideCicilanForm();
                        loadCicilan();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat menyimpan cicilan'
                    });
                }
            });
    }

    function loadCicilan(filter = 'all') {
        // Selalu muat cicilan aktif secara terpisah
        fetch('/app/ajax/getCicilan?filter=active')
            .then(response => response.json())
            .then(activeData => {
                if (activeData.status) {
                    // Render cicilan aktif
                    renderActiveCicilan(activeData.cicilan);
                }
            });

        // Muat data riwayat sesuai filter
        fetch(`/app/ajax/getCicilan?filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Render riwayat cicilan
                    renderHistoryCicilan(data.cicilan, filter);
                }
            });
    }

    function renderActiveCicilan(activeCicilan) {
        const activeCicilanList = document.getElementById('activeCicilanList');
        let activeHtml = '';

        activeCicilan.forEach(item => {
            if (item.status === 'active') {
                const progress = (item.paid_amount / item.total_amount) * 100;
                activeHtml += `
                <div class="cicilan-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${item.name}</h6>
                            <div class="small text-muted mb-2">${item.type}</div>
                        </div>
                        <span class="cicilan-badge active">Aktif</span>
                    </div>
                    <div class="progress cicilan-progress">
                        <div class="progress-bar bg-success" style="width: ${progress}%"></div>
                    </div>
                    <div class="row g-2 text-center mt-2">
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Total</small>
                            <strong>Rp ${formatRupiah(item.total_amount)}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Per Bulan</small>
                            <strong>Rp ${formatRupiah(item.monthly_amount)}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Sisa</small>
                            <strong>Rp ${formatRupiah(item.remaining_amount)}</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Estimasi Selesai</small>
                            <strong>${calculateEndDate(item.start_date, item.tenor)}</strong>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-sm btn-outline-success w-100" onclick="markPaid('${item.id}')">
                            <i class="ri-checkbox-circle-line"></i> Bayar Cicilan
                        </button>
                    </div>
                </div>`;
            }
        });

        activeCicilanList.innerHTML = activeHtml || '<p class="text-center text-muted my-4">Tidak ada cicilan aktif</p>';
    }

    function renderHistoryCicilan(cicilan, filter) {
        const cicilanHistoryList = document.getElementById('cicilanHistoryList');
        let historyHtml = '';

        // Filter untuk riwayat
        let historyCicilan = [];
        if (filter === 'active') {
            historyCicilan = cicilan.filter(item => item.status === 'active');
        } else if (filter === 'completed') {
            historyCicilan = cicilan.filter(item => item.status === 'completed');
        } else {
            // Untuk 'all', tampilkan semuanya di riwayat
            historyCicilan = cicilan;
        }

        historyCicilan.forEach(item => {
            const progress = (item.paid_amount / item.total_amount) * 100;
            historyHtml += `
            <div class="cicilan-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">${item.name}</h6>
                        <div class="small text-muted mb-2">${item.type}</div>
                    </div>
                    <span class="cicilan-badge ${item.status}">${item.status === 'active' ? 'Aktif' : 'Lunas'}</span>
                </div>
                <div class="progress cicilan-progress">
                    <div class="progress-bar bg-success" style="width: ${progress}%"></div>
                </div>
                <div class="row g-2 text-center mt-2">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Total</small>
                        <strong>Rp ${formatRupiah(item.total_amount)}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Per Bulan</small>
                        <strong>Rp ${formatRupiah(item.monthly_amount)}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Sisa</small>
                        <strong>Rp ${formatRupiah(item.remaining_amount)}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Estimasi Selesai</small>
                        <strong>${calculateEndDate(item.start_date, item.tenor)}</strong>
                    </div>
                </div>
            </div>`;
        });

        cicilanHistoryList.innerHTML = historyHtml || '<p class="text-center text-muted my-4">Tidak ada riwayat cicilan</p>';
    }

    function markPaid(id) {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Apakah Anda yakin ingin menandai cicilan ini sebagai sudah dibayar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/app/ajax/payCicilan/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire('Berhasil', 'Cicilan berhasil dibayar', 'success');
                            loadCicilan();
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                        }
                    });
            }
        });
    }

    function filterCicilan(filter) {
        loadCicilan(filter);
        // Update button states
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    function formatRupiah(amount) {
        return Number(amount).toLocaleString('id-ID');
    }

    function calculateEndDate(startDate, tenor) {
        const start = new Date(startDate);
        const end = new Date(start);
        end.setMonth(end.getMonth() + Number(tenor));

        // Format to Indonesian date
        const options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        };
        return end.toLocaleDateString('id-ID', options);
    }

    // Load cicilan when page loads
    document.addEventListener('DOMContentLoaded', () => {
        loadCicilan();
    });
</script>

<?= $this->endSection(); ?>