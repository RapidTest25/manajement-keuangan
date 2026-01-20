<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Laporan Keuangan</h1>
        <div class="d-flex gap-2">
            <a href="<?= base_url('administrator/report/export/pdf') ?>" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="<?= base_url('administrator/report/export/excel') ?>" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Tab Navigasi Periode -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="bulanan-tab" data-period="bulanan" href="#">
                Bulanan
                <span class="small text-muted">Mei 2025</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="kuartalan-tab" data-period="kuartalan" href="#">
                Kuartalan
                <span class="small text-muted">Q2 2025</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tahunan-tab" data-period="tahunan" href="#">
                Tahunan
                <span class="small text-muted">2025</span>
            </a>
        </li>
    </ul>

    <div class="row">
        <!-- Daftar Laporan -->
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="list-group list-group-flush">
                    <!-- Laporan Laba Rugi -->
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Laporan Laba Rugi</h6>
                                <small class="text-muted">Update terakhir: 20 May 2025</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-info text-white view-report" data-type="laba-rugi">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-success download-report" data-type="laba-rugi">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Arus Kas -->
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Laporan Arus Kas</h6>
                                <small class="text-muted">Update terakhir: 20 May 2025</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-info text-white view-report" data-type="arus-kas">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-success download-report" data-type="arus-kas">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Neraca -->
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Laporan Neraca</h6>
                                <small class="text-muted">Update terakhir: 20 May 2025</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-info text-white view-report" data-type="neraca">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-success download-report" data-type="neraca">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Periode -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-filter me-1"></i>
                    Filter Periode
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Tipe Periode</label>
                            <select class="form-select" id="tipePeriode">
                                <option value="bulanan" selected>Bulanan</option>
                                <option value="kuartalan">Kuartalan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>

                        <!-- Filter Bulanan -->
                        <div id="filterBulanan">
                            <div class="mb-3">
                                <label class="form-label">Pilih Bulan</label>
                                <input type="month" class="form-control" id="pilihBulan"
                                    value="<?= date('Y-m') ?>">
                            </div>
                        </div>

                        <!-- Filter Kuartalan -->
                        <div id="filterKuartalan" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Pilih Kuartal</label>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <select class="form-select" id="pilihKuartal">
                                            <option value="Q1">Q1 (Jan-Mar)</option>
                                            <option value="Q2" selected>Q2 (Apr-Jun)</option>
                                            <option value="Q3">Q3 (Jul-Sep)</option>
                                            <option value="Q4">Q4 (Okt-Des)</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <select class="form-select" id="tahunKuartal">
                                            <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                                                <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>>
                                                    <?= $i ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Tahunan -->
                        <div id="filterTahunan" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Pilih Tahun</label>
                                <select class="form-select" id="pilihTahun">
                                    <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                                        <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sync-alt me-1"></i> Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Tren Keuangan -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-1"></i>
            Tren Keuangan
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Laporan -->
<div class="modal fade" id="viewReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reportContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="downloadReport">
                    <i class="fas fa-download me-1"></i> Unduh
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        let currentPeriod = {
            type: 'bulanan',
            value: '<?= date('Y-m') ?>'
        };
        let trendChart;

        // Inisialisasi Chart
        function initChart() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Pendapatan',
                        borderColor: '#1cc88a',
                        tension: 0.1,
                        fill: false
                    }, {
                        label: 'Pengeluaran',
                        borderColor: '#e74a3b',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' +
                                        new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Load data laporan berdasarkan periode
        function loadReportData() {
            // Tampilkan loading state
            $('.nav-link .text-muted').html('<small><i class="fas fa-spinner fa-spin"></i></small>');

            $.ajax({
                url: '<?= base_url('administrator/report/data') ?>',
                type: 'GET',
                data: currentPeriod,
                success: function(response) {
                    if (response.success) {
                        // Update tren chart
                        if (response.trend) {
                            trendChart.data.labels = response.trend.labels;
                            trendChart.data.datasets[0].data = response.trend.income;
                            trendChart.data.datasets[1].data = response.trend.expense;
                            trendChart.update();
                        }

                        // Update periode di tab yang aktif
                        $(`#${currentPeriod.type}-tab .text-muted`).text(response.period);

                        console.log('Debug info:', response.debug);
                    }
                },
                error: function(xhr, status, error) {
                    // Tampilkan error state
                    $('.nav-link .text-muted').text('-');
                    console.error('Gagal memuat data laporan:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        }

        // Update tampilan tab periode
        function updatePeriodTab() {
            let periodText = '';
            switch (currentPeriod.type) {
                case 'bulanan':
                    const date = new Date(currentPeriod.value);
                    periodText = date.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                    break;
                case 'kuartalan':
                    periodText = `${currentPeriod.value}`;
                    break;
                case 'tahunan':
                    periodText = currentPeriod.value;
                    break;
            }

            $(`#${currentPeriod.type}-tab .text-muted`).text(periodText);
        }

        // Event handler untuk tab periode
        $('.nav-link').click(function(e) {
            e.preventDefault();
            $('.nav-link').removeClass('active');
            $(this).addClass('active');

            const period = $(this).data('period');
            currentPeriod.type = period;

            // Set default value based on current date
            switch (period) {
                case 'bulanan':
                    currentPeriod.value = moment().format('YYYY-MM');
                    $('#pilihBulan').val(currentPeriod.value);
                    break;
                case 'kuartalan':
                    const quarter = Math.ceil((new Date().getMonth() + 1) / 3);
                    currentPeriod.value = `Q${quarter} ${new Date().getFullYear()}`;
                    $('#pilihKuartal').val(`Q${quarter}`);
                    $('#tahunKuartal').val(new Date().getFullYear());
                    break;
                case 'tahunan':
                    currentPeriod.value = new Date().getFullYear().toString();
                    $('#pilihTahun').val(currentPeriod.value);
                    break;
            }

            // Tampilkan filter yang sesuai
            $(`#filter${period.charAt(0).toUpperCase() + period.slice(1)}`).show()
                .siblings('div[id^="filter"]').hide();

            $('#tipePeriode').val(period);

            // Reload data dengan periode baru
            loadReportData();
        });

        // Event handler untuk form filter
        $('#filterForm').submit(function(e) {
            e.preventDefault();
            const type = $('#tipePeriode').val();

            switch (type) {
                case 'bulanan':
                    currentPeriod = {
                        type: type,
                        value: $('#pilihBulan').val()
                    };
                    break;
                case 'kuartalan':
                    currentPeriod = {
                        type: type,
                        value: `${$('#pilihKuartal').val()} ${$('#tahunKuartal').val()}`
                    };
                    break;
                case 'tahunan':
                    currentPeriod = {
                        type: type,
                        value: $('#pilihTahun').val()
                    };
                    break;
            }

            loadReportData();
        });

        // Event handler untuk view report
        $('.view-report').click(function() {
            const type = $(this).data('type');
            $.ajax({
                url: `<?= base_url('administrator/report/view') ?>/${type}`,
                type: 'GET',
                data: currentPeriod,
                success: function(response) {
                    if (response.success) {
                        $('#reportContent').html(response.html);
                        $('#viewReportModal').modal('show');
                    }
                }
            });
        });

        // Event handler untuk download report
        $('.download-report').click(function() {
            const type = $(this).data('type');
            window.location.href = `<?= base_url('administrator/report/download') ?>/${type}?period=${currentPeriod.type}&value=${currentPeriod.value}`;
        });

        // Inisialisasi
        initChart();
        loadReportData();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .nav-tabs .nav-item {
        margin-bottom: -1px;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
        position: relative;
    }

    .nav-tabs .nav-link:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: transparent;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 500;
        background: none;
        border: none;
    }

    .nav-tabs .nav-link.active:after {
        background: #0d6efd;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        border: none;
        color: #0d6efd;
    }

    .nav-tabs .nav-link:hover:not(.active):after {
        background: rgba(13, 110, 253, 0.3);
    }

    .nav-tabs .nav-link .small {
        display: block;
        text-align: center;
        margin-top: 0.25rem;
    }
</style>
<?= $this->endSection() ?>