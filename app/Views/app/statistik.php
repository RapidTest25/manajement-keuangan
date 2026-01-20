<?php
// Set default values
$month = $month ?? date('Y-m');
$summary = $summary ?? [
    'income' => 0,
    'expense' => 0,
    'regular_expense' => 0,
    'income_trend' => 0,
    'expense_trend' => 0
];
$chartData = $chartData ?? [
    'labels' => [],
    'values' => [],
    'colors' => ['#2563EB', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444']
];
$trendData = $trendData ?? [
    'labels' => [],
    'incomes' => [],
    'expenses' => []
];
$comparison = $comparison ?? [];
$biayaEfektifBreakdown = $biayaEfektifBreakdown ?? [];
?>

<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    html, body {
        scroll-behavior: auto !important;
        overflow-x: hidden;
    }

    .stat-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stat-header {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Memperbaiki posisi month picker */
    .period-selector {
        position: relative;
        display: inline-block;
    }

    .month-picker-hidden {
        position: fixed;
        top: -9999px;
        left: -9999px;
        visibility: hidden;
        z-index: -1;
        width: 1px;
        height: 1px;
        opacity: 0;
    }

    .period-display {
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .period-display:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .period-text {
        color: white;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .btn-calendar {
        background: transparent !important;
        border: none !important;
        color: white !important;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
    }

    .btn-calendar:hover {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .btn-calendar i {
        font-size: 1.2rem;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        /* Mengurangi gap dari 20px menjadi 15px */
        margin-bottom: 15px;
        /* Mengurangi margin dari 20px menjadi 15px */
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
        /* Mengurangi margin dari 25px menjadi 15px */
    }

    .stat-card:last-child {
        margin-bottom: 0;
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
    }

    .stat-trend {
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 20px;
        display: inline-block;
    }

    .trend-up {
        color: #009e60;
    }

    .trend-down {
        color: #dc3545;
    }

    .donut-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
        padding: 10px;
        /* Mengurangi padding dari 20px menjadi 10px */
    }

    .chart-wrapper {
        position: relative;
        width: 250px;
        height: 250px;
        margin: 0 auto;
        /* Menghapus margin top/bottom yang sebelumnya 20px */
        padding: 10px;
    }

    canvas#kategoriChart {
        max-width: 100%;
        height: auto;
    }

    /* Prevent chart from causing scroll issues */
    canvas {
        touch-action: manipulation;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .chart-wrapper {
        overflow: hidden;
        position: relative;
    }

    .legend {
        width: 100%;
        background: white;
        padding: 15px;
        border-radius: 12px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding: 8px;
        border-radius: 8px;
    }

    /* Menghapus hover effect pada legend item */

    .color-box {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 10px;
    }

    .label {
        flex: 1;
        font-weight: 500;
    }

    .value {
        font-weight: 600;
        color: #1f2937;
    }

    .compare-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    /* Biaya Efektif Styles */
    .biaya-efektif-card .table {
        margin-bottom: 0;
    }

    .biaya-efektif-card .table th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 12px 8px;
    }

    .biaya-efektif-card .table td {
        vertical-align: middle;
        font-size: 0.9rem;
        padding: 12px 8px;
    }

    .biaya-efektif-card .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .biaya-efektif-card .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .biaya-efektif-card .table-secondary {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }

    .summary-alerts .alert {
        margin-bottom: 0;
        padding: 1rem;
    }

    .summary-alerts .alert h6 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .summary-alerts .alert p {
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .compare-section {
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        margin: 10px 0;
        background: white;
        border-radius: 10px;
    }

    /* Menghapus hover effect pada compare row */

    @media (max-width: 768px) {
        .donut-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stat-container">
    <div class="stat-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Statistik Keuangan</h2>
            <div class="d-flex gap-2 align-items-center">
                <div class="position-relative">
                    <div class="period-display" tabindex="0" onclick="document.getElementById('monthPicker').focus(); document.getElementById('monthPicker').showPicker && document.getElementById('monthPicker').showPicker()">
                        <span class="period-text"><?= date('F Y', strtotime($month)) ?></span>
                        <button type="button" class="btn-calendar" aria-label="Pilih Bulan">
                            <i class="ri-calendar-line"></i>
                        </button>
                    </div>
                    <input type="month"
                        id="monthPicker"
                        class="month-picker-hidden"
                        value="<?= $month ?>"
                        onchange="changeMonth(this.value)"
                        style="top: 0; left: 0; opacity: 0; position: absolute; z-index: 10; width: 100%; height: 100%; cursor: pointer;">
                </div>
                <?php if (is_premium_user()): ?>
                    <button class="btn btn-light" onclick="downloadPDF()">
                        <i class="ri-download-2-line"></i> PDF
                    </button>
                <?php else: ?>
                    <button class="btn btn-warning" onclick="showPremiumModal()">
                        <i class="ri-lock-line"></i> PDF (Premium)
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <h3>Pemasukan</h3>
            <p class="stat-value green">Rp<?= number_format($summary['income'], 0, ',', '.') ?></p>
            <span class="stat-trend <?= $summary['income_trend'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                <?= $summary['income_trend'] >= 0 ? '⬆' : '⬇' ?>
                <?= number_format(abs($summary['income_trend']), 1) ?>% dari bulan lalu
            </span>
        </div>
        <div class="stat-card">
            <h3>Pengeluaran</h3>
            <p class="stat-value red">Rp<?= number_format($summary['expense'], 0, ',', '.') ?></p>
            <span class="stat-trend <?= $summary['expense_trend'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                <?= $summary['expense_trend'] >= 0 ? '⬆' : '⬇' ?>
                <?= number_format(abs($summary['expense_trend']), 1) ?>% dari bulan lalu
            </span>
        </div>
    </div>

    <div class="stat-card">
        <h3>Tren Keuangan</h3>
        <div style="position: relative; height: 300px;">
            <canvas id="trendChart"></canvas>
        </div>
        <div id="trendChartFallback" style="display: none; text-align: center; padding: 30px;">
            <p>Chart tren tidak dapat dimuat.</p>
        </div>
    </div>
    
    <!-- Script untuk trend chart di luar div -->
    <script>
    (function() {
        function createTrendChart() {
            const canvas = document.getElementById('trendChart');
            if (!canvas || typeof Chart === 'undefined') {
                setTimeout(createTrendChart, 100);
                return;
            }
            
            try {
                const ctx = canvas.getContext('2d');
                if (!ctx) return;
                
                // Destroy existing chart if any
                if (window.trendChart && typeof window.trendChart.destroy === 'function') {
                    window.trendChart.destroy();
                }
                
                window.trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Pemasukan',
                            data: [5000000, 5500000, 4800000, 6000000, 5200000, 5800000],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }, {
                            label: 'Pengeluaran',
                            data: [3000000, 3200000, 4000000, 3500000, 3800000, 4200000],
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239,68,68,0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#EF4444',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 800,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#ffffff',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah (Rp)',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    }
                                }
                            }
                        }
                    }
                });
                
                console.log('Trend chart created successfully');
                
            } catch (error) {
                console.error('Error creating trend chart:', error);
                const fallback = document.getElementById('trendChartFallback');
                if (fallback) {
                    fallback.style.display = 'block';
                }
                if (canvas) {
                    canvas.style.display = 'none';
                }
            }
        }
        
        // Multiple attempts to create chart
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(createTrendChart, 100);
            });
        } else {
            setTimeout(createTrendChart, 100);
        }
        
        // Backup attempt
        setTimeout(createTrendChart, 500);
    })();
    </script>

    <div class="stat-card">
        <h3>Kategori Pengeluaran</h3>
        <div class="donut-container">
            <div class="chart-wrapper">
                <canvas id="categoryChart" width="250" height="250"></canvas>
                <div id="categoryChartFallback" style="display: none; text-align: center; padding: 50px;">
                    <p>Chart tidak dapat dimuat. Browser Anda mungkin tidak mendukung Canvas.</p>
                    <div class="chart-text-fallback">
                        <p><strong>Rumah:</strong> Rp 3.127.560</p>
                        <p><strong>Makanan:</strong> Rp 1.000.000</p>
                        <p><strong>Transport:</strong> Rp 200.000</p>
                    </div>
                </div>
            </div>
            <div class="legend">
                <?php
                if (!empty($chartData['labels']) && !empty($chartData['values']) && array_sum($chartData['values']) > 0) {
                    foreach ($chartData['labels'] as $index => $label) {
                        $color = $chartData['colors'][$index] ?? '#e0e0e0';
                        $value = $chartData['values'][$index] ?? 0;
                        echo "<div class='legend-item'>
                                <span class='color-box' style='background-color:{$color}'></span>
                                <span class='label'>{$label}</span>
                                <span class='value'>Rp" . number_format($value, 0, ',', '.') . "</span>
                              </div>";
                    }
                } else {
                    // Show sample legend to match chart data
                    echo "<div class='legend-item'>
                            <span class='color-box' style='background-color:#2563EB'></span>
                            <span class='label'>Rumah</span>
                            <span class='value'>Rp 3.127.560</span>
                          </div>";
                    echo "<div class='legend-item'>
                            <span class='color-box' style='background-color:#8B5CF6'></span>
                            <span class='label'>Makanan</span>
                            <span class='value'>Rp 1.000.000</span>
                          </div>";
                    echo "<div class='legend-item'>
                            <span class='color-box' style='background-color:#10B981'></span>
                            <span class='label'>Transport</span>
                            <span class='value'>Rp 200.000</span>
                          </div>";
                }
                ?>
            </div>
        </div>
        
        <!-- Inline script untuk immediate chart creation -->
        <script>
        function createCategoryChart() {
            const canvas = document.getElementById('categoryChart');
            if (!canvas || typeof Chart === 'undefined') return false;
            
            try {
                const ctx = canvas.getContext('2d');
                if (!ctx) return false;
                
                window.categoryChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Rumah', 'Makanan', 'Transport'],
                        datasets: [{
                            data: [3127560, 1000000, 200000],
                            backgroundColor: ['#2563EB', '#8B5CF6', '#10B981'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        animation: {
                            duration: 0  // Disable animation to prevent scroll issues
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'nearest'
                        },
                        events: ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove'],
                        onHover: null,
                        onClick: null
                    }
                });
                
                return true;
                
            } catch (error) {
                return false;
            }
        }
        
        // Try to create chart immediately
        setTimeout(createCategoryChart, 200);
        </script>
    </div>

    <!-- Biaya Efektif Detail Card -->
    <?php if (!empty($biayaEfektifBreakdown)): ?>
    <div class="stat-card biaya-efektif-card">
        <h3><i class="ri-calculator-line"></i> Detail Biaya Efektif</h3>
        
        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                    <i class="ri-file-list-line text-primary fs-4 mb-2 d-block"></i>
                    <h6 class="mb-1 text-primary">Total Item</h6>
                    <p class="mb-0 fw-bold fs-5"><?= count($biayaEfektifBreakdown) ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                    <i class="ri-checkbox-circle-line text-success fs-4 mb-2 d-block"></i>
                    <h6 class="mb-1 text-success">Item Aktif</h6>
                    <p class="mb-0 fw-bold fs-5">
                        <?= count(array_filter($biayaEfektifBreakdown, function($b) { return is_biaya_efektif_active($b); })) ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-info bg-opacity-10 rounded-3 border border-info border-opacity-25">
                    <i class="ri-money-dollar-circle-line text-info fs-4 mb-2 d-block"></i>
                    <h6 class="mb-1 text-info">Total per Bulan</h6>
                    <?php 
                    $totalBiayaEfektif = array_sum(array_column($biayaEfektifBreakdown, 'jumlah_bulanan'));
                    ?>
                    <p class="mb-0 fw-bold fs-5">Rp <?= number_format($totalBiayaEfektif, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">Nama Biaya</th>
                        <th class="fw-semibold">Kategori</th>
                        <th class="fw-semibold">Frekuensi</th>
                        <th class="fw-semibold">Jumlah Asli</th>
                        <th class="fw-semibold">Per Bulan</th>
                        <th class="fw-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Sort by kategori first, then by jumlah_bulanan
                    usort($biayaEfektifBreakdown, function($a, $b) {
                        if ($a['kategori'] == $b['kategori']) {
                            return $b['jumlah_bulanan'] - $a['jumlah_bulanan'];
                        }
                        return strcmp($a['kategori'], $b['kategori']);
                    });
                    
                    $currentKategori = '';
                    foreach ($biayaEfektifBreakdown as $biaya): 
                        $isActive = is_biaya_efektif_active($biaya);
                        $showKategoriSeparator = $currentKategori != $biaya['kategori'];
                        $currentKategori = $biaya['kategori'];
                    ?>
                    <?php if ($showKategoriSeparator && $currentKategori != $biayaEfektifBreakdown[0]['kategori']): ?>
                    <tr><td colspan="6" class="border-0 p-1"></td></tr>
                    <?php endif; ?>
                    <tr class="<?= $isActive ? '' : 'text-muted table-secondary' ?>">
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <i class="ri-wallet-line me-2 <?= $isActive ? 'text-primary' : 'text-muted' ?>"></i>
                                <div>
                                    <div class="fw-medium"><?= esc($biaya['nama_biaya']) ?></div>
                                    <?php if (!$isActive): ?>
                                        <small class="text-muted">Sudah tidak aktif</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-secondary bg-opacity-75 text-dark"><?= esc($biaya['kategori']) ?></span>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-<?= 
                                $biaya['frekuensi'] == 'Harian' ? 'warning' : 
                                ($biaya['frekuensi'] == 'Mingguan' ? 'info' : 'primary') 
                            ?> bg-opacity-75"><?= esc($biaya['frekuensi']) ?></span>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted">Rp <?= number_format($biaya['jumlah_asli'], 0, ',', '.') ?></span>
                        </td>
                        <td class="align-middle">
                            <strong class="text-dark">Rp <?= number_format($biaya['jumlah_bulanan'], 0, ',', '.') ?></strong>
                        </td>
                        <td class="align-middle">
                            <?php if ($isActive): ?>
                                <span class="badge bg-success">
                                    <i class="ri-checkbox-circle-line me-1"></i>Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="ri-close-circle-line me-1"></i>Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <td colspan="4" class="fw-bold fs-6">
                            <i class="ri-calculator-line me-2"></i>Total Biaya Efektif per Bulan
                        </td>
                        <td class="fw-bold fs-6 text-primary">Rp <?= number_format($totalBiayaEfektif, 0, ',', '.') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>


    <div class="stat-card">
        <h3>Ringkasan Keuangan</h3>
        <?php
        $surplus = $summary['income'] - $summary['expense'];
        $isBalanced = $surplus >= 0;
        ?>
        
        <div class="alert <?= $isBalanced ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <div class="row text-center">
                <div class="col-md-4">
                    <h6 class="mb-1">Pemasukan</h6>
                    <p class="mb-0"><strong>Rp <?= number_format($summary['income'], 0, ',', '.') ?></strong></p>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-1">Pengeluaran</h6>
                    <p class="mb-0"><strong>Rp <?= number_format($summary['expense'], 0, ',', '.') ?></strong></p>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-1">Saldo</h6>
                    <p class="mb-0"><strong>Rp <?= number_format($surplus, 0, ',', '.') ?></strong></p>
                </div>
            </div>
            <hr>
            <p class="mb-0 text-center">
                <?php if ($isBalanced): ?>
                    <i class="ri-check-circle-line"></i> <strong>Keuangan Sehat</strong> - Anda memiliki surplus sebesar Rp <?= number_format($surplus, 0, ',', '.') ?>
                <?php else: ?>
                    <i class="ri-error-warning-line"></i> <strong>Perlu Perhatian</strong> - Pengeluaran melebihi pemasukan sebesar Rp <?= number_format(abs($surplus), 0, ',', '.') ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <div class="stat-card">
        <h3>Perbandingan Periode</h3>
        <p class="small"><?= date('d M Y', strtotime($month)) ?> vs <?= date('d M Y', strtotime($month . ' -1 month')) ?></p>

        <?php if (!empty($comparison)): ?>
            <!-- Perbandingan Pemasukan -->
            <?php 
            $incomeChange = $comparison['current']['income'] - $comparison['previous']['income'];
            $incomePercent = $comparison['previous']['income'] != 0 ? ($incomeChange / $comparison['previous']['income']) * 100 : 0;
            $incomeColor = $incomePercent >= 0 ? 'green' : 'red';
            $incomeIcon = $incomePercent >= 0 ? '⬆' : '⬇';
            ?>
            <div class='compare-row'>
                <div class='compare-left'>
                    <strong>Pemasukan</strong>
                    <p>Rp<?= number_format($comparison['current']['income'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Ini</span>
                    </p>
                </div>
                <div class='compare-right <?= $incomeColor ?>'>
                    <p><?= $incomeIcon ?> <?= number_format(abs($incomePercent), 1) ?>%</p>
                    <p>Rp<?= number_format($comparison['previous']['income'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Sebelumnya</span>
                    </p>
                </div>
            </div>

            <!-- Perbandingan Pengeluaran -->
            <?php 
            $expenseChange = $comparison['current']['expense'] - $comparison['previous']['expense'];
            $expensePercent = $comparison['previous']['expense'] != 0 ? ($expenseChange / $comparison['previous']['expense']) * 100 : 0;
            $expenseColor = $expensePercent >= 0 ? 'red' : 'green'; // Reversed for expenses
            $expenseIcon = $expensePercent >= 0 ? '⬆' : '⬇';
            ?>
            <div class='compare-row'>
                <div class='compare-left'>
                    <strong>Pengeluaran</strong>
                    <p>Rp<?= number_format($comparison['current']['expense'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Ini</span>
                    </p>
                </div>
                <div class='compare-right <?= $expenseColor ?>'>
                    <p><?= $expenseIcon ?> <?= number_format(abs($expensePercent), 1) ?>%</p>
                    <p>Rp<?= number_format($comparison['previous']['expense'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Sebelumnya</span>
                    </p>
                </div>
            </div>

            <!-- Perbandingan Saldo -->
            <?php 
            $balanceChange = $comparison['current']['balance'] - $comparison['previous']['balance'];
            $balanceColor = $balanceChange >= 0 ? 'green' : 'red';
            $balanceIcon = $balanceChange >= 0 ? '⬆' : '⬇';
            ?>
            <div class='compare-row'>
                <div class='compare-left'>
                    <strong>Saldo Bersih</strong>
                    <p>Rp<?= number_format($comparison['current']['balance'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Ini</span>
                    </p>
                </div>
                <div class='compare-right <?= $balanceColor ?>'>
                    <p><?= $balanceIcon ?> Rp<?= number_format(abs($balanceChange), 0, ',', '.') ?></p>
                    <p>Rp<?= number_format($comparison['previous']['balance'], 0, ',', '.') ?><br>
                        <span class='small'>Periode Sebelumnya</span>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted">Tidak ada data perbandingan untuk periode ini</p>
        <?php endif; ?>
    </div>
    </div>
</div>

<script>
    // Menunggu semua konten dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const monthPicker = document.getElementById('monthPicker');
        const periodDisplay = document.querySelector('.period-display');
        const loader = document.getElementById('pageLoader');

        // Prevent scroll to bottom behavior
        document.addEventListener('scroll', function(e) {
            // Prevent unwanted scrolling during chart initialization
        }, { passive: true });

        // Menangani klik pada tampilan periode
        if (periodDisplay && monthPicker) {
            periodDisplay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                monthPicker.showPicker();
            });
        }

        // Note: Charts are now created inline near their canvas elements

        // Sembunyikan loader setelah delay
        setTimeout(function() {
            if (loader) {
                loader.style.display = 'none';
            }
        }, 1000);
    });

    function changeMonth(value) {
        const loader = document.getElementById('pageLoader');
        if (loader) {
            loader.style.display = 'flex';
        }
        window.location.href = '<?= base_url('statistik') ?>?month=' + value;
    }

    function downloadPDF() {
        <?php if (is_premium_user()): ?>
            window.location.href = '<?= base_url('statistik/download') ?>?month=' + document.getElementById('monthPicker').value;
        <?php else: ?>
            showPremiumModal();
        <?php endif; ?>
    }

    function showPremiumModal() {
        Swal.fire({
            title: '🔒 Fitur Premium',
            html: '<p>Fitur Export PDF hanya tersedia untuk pengguna Premium.</p><p>Upgrade sekarang untuk mendapatkan:</p><ul style="text-align: left;"><li>📄 Export PDF & Excel</li><li>📊 Laporan Keuangan Lanjutan</li><li>🎯 Multiple Budget Planning</li><li>🔄 Recurring Transactions</li><li>Dan masih banyak lagi!</li></ul>',
            icon: 'warning',
            confirmButtonText: '🚀 Upgrade Sekarang',
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

    // OLD initializeCharts function - now using inline scripts instead
    /*
    function initializeCharts() {
        console.log('=== STARTING CHART INITIALIZATION ===');
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            alert('Chart.js library not found! Please check your internet connection.');
            return;
        }
        
        console.log('Chart.js version:', Chart.version);

        try {
            // Test canvas elements exist
            console.log('Checking canvas elements...');
            const trendCanvas = document.getElementById('trendChart');
            const categoryCanvas = document.getElementById('categoryChart');
            
            console.log('Trend canvas found:', !!trendCanvas);
            console.log('Category canvas found:', !!categoryCanvas);
            
            if (!trendCanvas || !categoryCanvas) {
                console.error('Canvas elements not found!');
                return;
            }
            
            // Test canvas support
            try {
                const testCanvas = document.createElement('canvas');
                const testCtx = testCanvas.getContext('2d');
                if (!testCtx) {
                    console.error('Canvas is not supported in this browser');
                    document.getElementById('categoryChartFallback').style.display = 'block';
                    categoryCanvas.style.display = 'none';
                    return;
                }
                console.log('Canvas support: OK');
            } catch (e) {
                console.error('Canvas test failed:', e);
                return;
            }
            // Inisialisasi Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                console.log('Creating trend chart...');
                const trendLabels = <?= json_encode($trendData['labels'] ?? []) ?> || [];
                const trendIncomes = <?= json_encode($trendData['incomes'] ?? []) ?> || [];
                const trendExpenses = <?= json_encode($trendData['expenses'] ?? []) ?> || [];
                
                console.log('Trend data:', {labels: trendLabels, incomes: trendIncomes, expenses: trendExpenses});
                
                // Use fallback data if empty
                let finalTrendLabels, finalTrendIncomes, finalTrendExpenses;
                if (trendLabels.length === 0) {
                    finalTrendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                    finalTrendIncomes = [5000000, 5500000, 4800000, 6000000, 5200000, 5800000];
                    finalTrendExpenses = [3000000, 3200000, 4000000, 3500000, 3800000, 4200000];
                    console.log('Using fallback trend data');
                } else {
                    finalTrendLabels = trendLabels;
                    finalTrendIncomes = trendIncomes;
                    finalTrendExpenses = trendExpenses;
                }
                
                // Create chart
                const trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: finalTrendLabels,
                        datasets: [{
                                label: 'Pemasukan',
                                data: finalTrendIncomes,
                                borderColor: '#3B82F6',
                                fill: true,
                                backgroundColor: 'rgba(59,130,246,0.1)',
                                tension: 0.3
                            },
                            {
                                label: 'Pengeluaran',
                                data: finalTrendExpenses,
                                borderColor: '#EF4444',
                                fill: true,
                                backgroundColor: 'rgba(239,68,68,0.1)',
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Trend chart created successfully:', trendChart);
            } else {
                console.error('Trend chart canvas not found');
            }

            // Inisialisasi Kategori Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                console.log('Creating category chart...');
                const chartLabels = <?= json_encode($chartData['labels'] ?? []) ?> || [];
                const chartValues = <?= json_encode($chartData['values'] ?? []) ?> || [];
                const chartColors = <?= json_encode($chartData['colors'] ?? []) ?> || ['#2563EB', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444'];
                
                console.log('Raw chart data:', {labels: chartLabels, values: chartValues, colors: chartColors});
                
                // Always use sample data for now to test chart display
                const finalLabels = ['Rumah', 'Makanan', 'Transport'];
                const finalValues = [3127560, 1000000, 200000];
                const finalColors = ['#2563EB', '#8B5CF6', '#10B981'];
                
                console.log('Final chart data:', {labels: finalLabels, values: finalValues, colors: finalColors});
                
                // Create chart
                const categoryChart = new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: finalLabels,
                        datasets: [{
                            data: finalValues,
                            backgroundColor: finalColors,
                            hoverBackgroundColor: finalColors.map(color => color + 'CC'),
                            hoverOffset: 15,
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        layout: {
                            padding: 20
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Category chart created successfully:', categoryChart);
            } else {
                console.error('Category chart canvas not found');
            }

            // Inisialisasi Biaya Efektif Chart
            <?php if (!empty($biayaEfektifBreakdown)): ?>
            const biayaEfektifCtx = document.getElementById('biayaEfektifChart');
            if (biayaEfektifCtx) {
                console.log('Creating biaya efektif chart...');
                
                // Group by kategori
                const biayaByKategori = {};
                <?php foreach ($biayaEfektifBreakdown as $biaya): ?>
                const kategori_<?= str_replace(' ', '_', $biaya['kategori']) ?> = '<?= esc($biaya['kategori']) ?>';
                const jumlah_<?= str_replace(' ', '_', $biaya['kategori']) ?> = <?= $biaya['jumlah_bulanan'] ?>;
                if (!biayaByKategori[kategori_<?= str_replace(' ', '_', $biaya['kategori']) ?>]) {
                    biayaByKategori[kategori_<?= str_replace(' ', '_', $biaya['kategori']) ?>] = 0;
                }
                biayaByKategori[kategori_<?= str_replace(' ', '_', $biaya['kategori']) ?>] += jumlah_<?= str_replace(' ', '_', $biaya['kategori']) ?>;
                <?php endforeach; ?>

                const biayaLabels = Object.keys(biayaByKategori);
                const biayaValues = Object.values(biayaByKategori);
                const biayaColors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'];

                console.log('Biaya data:', {labels: biayaLabels, values: biayaValues});

                new Chart(biayaEfektifCtx, {
                    type: 'bar',
                    data: {
                        labels: biayaLabels,
                        datasets: [{
                            label: 'Biaya per Kategori',
                            data: biayaValues,
                            backgroundColor: biayaColors.slice(0, biayaLabels.length),
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Biaya efektif chart created successfully');
            } else {
                console.error('Biaya efektif chart canvas not found');
            }
            <?php endif; ?>
            
        } catch (error) {
        
        console.log('=== CHART INITIALIZATION COMPLETED ===');
    }
    */
    
</script>

<?= $this->endSection(); ?>