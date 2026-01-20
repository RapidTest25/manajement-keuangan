<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    body {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        min-height: 100vh;
    }

    .main-container {
        padding: 20px;
        padding-bottom: 100px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .header-section {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px 20px;
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 158, 96, 0.3);
    }

    .header-icon {
        font-size: 60px;
        margin-bottom: 15px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .page-subtitle {
        font-size: 15px;
        opacity: 0.95;
        font-weight: 400;
    }

    .pricing-card {
        background: white;
        border-radius: 20px;
        padding: 30px 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
        overflow: hidden;
        border: 2px solid transparent;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .pricing-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #009e60 0%, #00c975 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .pricing-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #009e60;
    }

    .pricing-card:hover::before {
        transform: scaleX(1);
    }

    .pricing-card.featured {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        color: white;
        border: none;
        transform: scale(1.05);
        box-shadow: 0 15px 50px rgba(0, 158, 96, 0.4);
    }

    .pricing-card.featured .plan-name,
    .pricing-card.featured .plan-description,
    .pricing-card.featured .features-list li {
        color: white;
    }

    .pricing-card.featured .plan-price {
        color: #ffed4e;
    }

    .pricing-card.featured::after {
        content: '🔥 PALING POPULER';
        position: absolute;
        top: 15px;
        right: -35px;
        background: #ffed4e;
        color: #009e60;
        padding: 5px 40px;
        font-size: 11px;
        font-weight: 800;
        transform: rotate(45deg);
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    .pricing-card.current {
        border: 3px solid #ffc107;
        background: linear-gradient(135deg, #fff3cd 0%, #fff9e6 100%);
    }

    .current-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ffc107;
        color: #333;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 3px 10px rgba(255, 193, 7, 0.4);
    }

    .plan-icon {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
    }

    .plan-name {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .plan-price {
        font-size: 42px;
        font-weight: 900;
        margin: 20px 0;
        color: #009e60;
    }

    .pricing-card.featured .plan-price {
        color: #ffed4e;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .plan-price .currency {
        font-size: 20px;
        font-weight: 700;
    }

    .plan-price .period {
        font-size: 16px;
        color: #666;
        font-weight: 500;
    }

    .pricing-card.featured .plan-price .period {
        color: rgba(255,255,255,0.9);
    }

    .plan-price.free {
        color: #6c757d;
        font-size: 36px;
    }

    .plan-description {
        color: #6c757d;
        margin-bottom: 25px;
        font-size: 14px;
        min-height: 45px;
        line-height: 1.5;
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 25px 0;
        text-align: left;
    }

    .features-list li {
        padding: 12px 0;
        color: #495057;
        font-size: 14px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .features-list li::before {
        content: '✓';
        color: #28a745;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
    }

    .pricing-card.featured .features-list li::before {
        color: #ffed4e;
    }

    .features-list li:last-child {
        border-bottom: none;
    }

    .btn-subscribe {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        transition: all 0.3s ease;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-subscribe.btn-primary {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(0, 158, 96, 0.3);
    }

    .btn-subscribe.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 158, 96, 0.4);
    }

    .pricing-card.featured .btn-subscribe.btn-primary {
        background: white;
        color: #009e60;
    }

    .pricing-card.featured .btn-subscribe.btn-primary:hover {
        background: #ffed4e;
        color: #009e60;
    }

    .btn-subscribe.btn-outline-secondary {
        border: 2px solid #dee2e6;
        color: #6c757d;
        background: white;
    }

    .btn-subscribe.btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .btn-subscribe.btn-warning {
        background: #ffc107;
        color: #333;
    }

    .benefits-section {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        color: white;
        padding: 50px 40px;
        border-radius: 20px;
        margin: 50px 0;
        box-shadow: 0 15px 40px rgba(0, 158, 96, 0.3);
    }

    .benefits-section h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 40px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 25px;
        padding: 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .benefit-item:hover {
        background: rgba(255,255,255,0.2);
        transform: translateX(10px);
    }

    .benefit-icon {
        font-size: 40px;
        flex-shrink: 0;
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));
    }

    .benefit-content h5 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .benefit-content p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .status-alert {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0, 158, 96, 0.2);
    }

    .btn-history {
        background: white;
        color: #009e60;
        border: 2px solid #009e60;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-history:hover {
        background: #009e60;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 158, 96, 0.3);
    }

    @media (max-width: 768px) {
        .pricing-card.featured {
            transform: scale(1);
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .header-icon {
            font-size: 48px;
        }
    }
</style>

<div class="main-container">
    <div class="text-center mb-5 fade-in">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 fw-bold">PREMIUM ACCESS</span>
        <h1 class="display-4 fw-bold text-dark mb-3">Pilih Paket Langganan</h1>
        <p class="text-muted lead mx-auto" style="max-width: 600px;">
            Buka potensi penuh keuangan Anda dengan fitur premium exclusive. Mulai dari Rp 49.000/bulan.
        </p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="ri-error-warning-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($currentSubscription): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-gradient-success text-white overflow-hidden position-relative">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 opacity-75">
                            <i class="ri-vip-crown-fill"></i> Status Langganan Saat Ini
                        </div>
                        <h3 class="mb-1 fw-bold"><?= $currentSubscription['plan_name'] ?></h3>
                        <?php if ($currentSubscription['plan_slug'] !== 'free'): ?>
                            <p class="mb-0 opacity-75">
                                Aktif hingga <?= date('d M Y', strtotime($currentSubscription['end_date'])) ?> 
                                (<?= $daysRemaining ?> hari lagi)
                            </p>
                        <?php else: ?>
                            <p class="mb-0 opacity-75">Upgrade sekarang untuk fitur lebih lengkap!</p>
                        <?php endif; ?>
                    </div>
                    <?php if ($currentSubscription['plan_slug'] === 'free'): ?>
                        <a href="#premium-monthly" class="btn btn-light fw-bold rounded-pill px-4">Upgrade Premium</a>
                    <?php else: ?>
                        <a href="<?= base_url('app/subscription/my-subscription') ?>" class="btn btn-outline-light rounded-pill px-4">Kelola</a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Decor -->
            <div class="position-absolute top-0 end-0 p-4 opacity-10" style="transform: translate(20%, -20%); font-size: 150px; line-height: 1;">👑</div>
        </div>
    <?php endif; ?>

    <div class="row g-4 align-items-start mb-5 justify-content-center">
        <?php foreach ($plans as $plan): ?>
            <?php 
                $isFree = $plan['slug'] === 'free';
                $isPopular = $plan['slug'] === 'premium-monthly';
                
                // Override features for display
                if ($isFree) {
                    $features = [
                        'Mencatat Transaksi (Pemasukan & Pengeluaran)',
                        'Melihat Laporan Dasar',
                        'Maksimal 5 Kategori Custom',
                        'Backup Data Manual'
                    ];
                } else {
                    $features = [
                        'Semua fitur Free',
                        'Akses Menu Nabung (Target Tabungan)',
                        'Akses Menu Cicilan (Monitor Kredit)',
                        'Akses Catat Utang Piutang',
                        'Unlimited Kategori Transaksi',
                        'Export Laporan ke Excel',
                        'Laporan Statistik Lengkap',
                        'Support Prioritas'
                    ];
                }
                
                // Card Classes
                $cardClass = $isFree ? 'border-0 shadow-sm' : ($isPopular ? 'border-2 border-warning shadow-lg transform-scale' : 'border-0 shadow');
                
                // Icon mapping
                $slug = $plan['slug'] ?? 'free';
                $icons = [
                    'free' => '<i class="ri-box-3-line text-secondary" style="font-size: 60px;"></i>',
                    'premium-monthly' => '<i class="ri-vip-crown-fill text-warning" style="font-size: 60px;"></i>',
                    'premium-yearly' => '<i class="ri-vip-diamond-line text-success" style="font-size: 60px;"></i>'
                ];
                $icon = $icons[$slug] ?? $icons['free'];
            ?>
            <div class="col-12" id="<?= $plan['slug'] ?>">
                <div class="card h-100 rounded-4 overflow-hidden pricing-card <?= $cardClass ?>" style="transition: transform 0.3s;">
                    <?php if ($isPopular): ?>
                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 rounded-bottom-start-4 fw-bold small shadow-sm z-2">
                            🔥 PALING POPULER
                        </div>
                    <?php endif; ?>

                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <?= $icon ?>
                            </div>
                            
                            <h3 class="fw-bold mb-1"><?= $plan['name'] ?></h3>
                            <p class="text-muted small mb-0"><?= $plan['description'] ?></p>
                        </div>

                        <div class="text-center mb-4">
                            <?php if ($plan['price'] == 0): ?>
                                <h2 class="fw-bold text-dark mb-0">GRATIS</h2>
                                <span class="text-muted small">Selamanya</span>
                            <?php else: ?>
                                <h2 class="fw-bold text-success mb-0" style="color: #009e60;">
                                    <span class="fs-5 text-muted fw-normal">Rp</span> <?= number_format($plan['price'], 0, ',', '.') ?>
                                </h2>
                                <span class="text-muted small">/<?= $plan['duration_days'] == 30 ? 'bulan' : 'tahun' ?></span>
                            <?php endif; ?>
                        </div>

                        <hr class="opacity-10 my-0 mb-4">

                        <ul class="list-unstyled mb-4 flex-grow-1">
                            <?php foreach ($features as $feature): ?>
                                <li class="mb-3 d-flex align-items-start small">
                                    <i class="ri-checkbox-circle-fill text-success me-2 fs-5 lh-1 flex-shrink-0"></i>
                                    <span class="<?= $isFree ? 'text-muted' : 'text-dark' ?>"><?= $feature ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ($currentSubscription && $currentSubscription['plan_id'] == $plan['id']): ?>
                            <button class="btn btn-secondary w-100 py-3 rounded-3 fw-bold disabled" disabled>
                                Paket Saat Ini
                            </button>
                        <?php else: ?>
                            <a href="<?= base_url('app/subscription/subscribe/' . $plan['slug']) ?>" 
                               class="btn w-100 py-3 rounded-3 fw-bold <?= $isPopular ? 'btn-warning text-dark shadow-sm' : ($isFree ? 'btn-outline-dark' : 'btn-success text-white') ?>">
                                <?= $isFree ? 'Pilih Paket Gratis' : 'Upgrade Sekarang 🚀' ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Benefits Section -->
    <div class="benefits-section">
        <h2 class="text-center">✨ Kenapa Upgrade ke Premium?</h2>
        <div class="row">
            <div class="col-12">
                <div class="benefit-item">
                    <div class="benefit-icon">📊</div>
                    <div class="benefit-content">
                        <h5>Analisis Mendalam</h5>
                        <p>Grafik dan laporan keuangan yang lebih detail untuk keputusan finansial lebih baik</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">📄</div>
                    <div class="benefit-content">
                        <h5>Export Profesional</h5>
                        <p>Download laporan dalam format PDF & Excel untuk kebutuhan bisnis Anda</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">💰</div>
                    <div class="benefit-content">
                        <h5>Budget Unlimited</h5>
                        <p>Buat budget tanpa batas untuk berbagai keperluan dan proyek</p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="benefit-item">
                    <div class="benefit-icon">🔄</div>
                    <div class="benefit-content">
                        <h5>Transaksi Otomatis</h5>
                        <p>Atur transaksi berulang secara otomatis dan hemat waktu</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🎯</div>
                    <div class="benefit-content">
                        <h5>Fitur Premium Lengkap</h5>
                        <p>Akses semua fitur terbaru dan update premium secara gratis</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <div class="benefit-content">
                        <h5>Support Prioritas</h5>
                        <p>Dapatkan bantuan lebih cepat dari tim support kami 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ / Why Upgrade -->
    <div class="row justify-content-center mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="row g-0">
                    <div class="col-12 bg-success text-white p-5 d-flex flex-column justify-content-center position-relative overflow-hidden">
                        <div class="position-relative z-1">
                            <h3 class="fw-bold mb-3">Kenapa harus Upgrade?</h3>
                            <p class="opacity-75 mb-4">Nikmati kebebasan finansial dengan alat bantu yang lebih canggih dan lengkap.</p>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="ri-shield-star-line fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Data Lebih Aman</h6>
                                        <small class="opacity-75">Backup otomatis ke Excel</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="ri-pie-chart-2-line fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Analisis Mendalam</h6>
                                        <small class="opacity-75">Laporan visual tanpa batas</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="ri-vip-crown-2-line fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Fitur Exclusive</h6>
                                        <small class="opacity-75">Nabung, Cicilan & Utang</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Decor -->
                        <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(20%, 20%); font-size: 200px; line-height: 1;">🚀</div>
                    </div>
                    <div class="col-12 p-5">
                        <h4 class="fw-bold mb-4">Pertanyaan Umum</h4>
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item mb-2 border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light rounded-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Apakah bisa berhenti langganan kapan saja?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Ya, Anda dapat membatalkan langganan kapan saja melalui menu pengaturan. Akses premium akan tetap aktif hingga masa berlaku habis.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-2 border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light rounded-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Apa bedanya Bulanan dan Tahunan?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Fitur yang didapatkan sama persis. Bedanya hanya di harga, paket Tahunan jauh lebih hemat (diskon ~30%) dibandingkan bayar bulanan selama setahun.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light rounded-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Metode pembayaran apa yang tersedia?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BRI), E-Wallet (GoPay, OVO, Dana), dan QRIS.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
    }
    .transform-scale {
        transform: scale(1.02);
    }
    .pricing-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
    /* Simple Animation */
    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<?= $this->endSection(); ?>
