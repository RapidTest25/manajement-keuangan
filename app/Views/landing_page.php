<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $settings['website_name'] ?? 'FinanceFlow' ?> - <?= $settings['website_description'] ?? 'Catatan Keuangan Jadi Menyenangkan' ?></title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #10b981;
            --secondary-color: #d1fae5;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section Styles */
        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #ffffff 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 4rem 0;
        }

        .content-section {
            position: relative;
            z-index: 2;
        }

        .display-4 {
            font-size: 3.2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 400;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0ea271;
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .feature-box {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            background-color: var(--secondary-color);
            padding: 15px;
            border-radius: 12px;
            display: inline-flex;
            margin-bottom: 20px;
        }

        .feature-icon svg {
            width: 32px;
            height: 32px;
            color: var(--primary-color);
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0ea271;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        .decorative-shape {
            position: absolute;
            opacity: 0.5;
            z-index: 0;
        }

        .shape-1 {
            top: 10%;
            right: 5%;
        }

        .shape-2 {
            bottom: 15%;
            left: 5%;
        }

        .shape-3 {
            top: 50%;
            left: 15%;
        }

        .content-section {
            position: relative;
            z-index: 1;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
            background: var(--primary-color);
        }

        .feature-card:hover .feature-icon svg {
            color: white;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .feature-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-icon {
            animation: iconFloat 3s ease-in-out infinite;
        }

        /* Box Navigation Styles */
        .box-navigation {
            background: linear-gradient(135deg, #f8fafc 0%, var(--secondary-color) 100%);
            padding: 5rem 0;
        }

        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .nav-box {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .nav-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav-box:hover::before {
            transform: scaleX(1);
        }

        .nav-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.1);
        }

        .nav-icon {
            width: 64px;
            height: 64px;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 12px;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon svg {
            width: 32px;
            height: 32px;
            color: var(--primary-color);
        }

        .nav-box:hover .nav-icon {
            background: var(--primary-color);
        }

        .nav-box:hover .nav-icon svg {
            color: white;
        }

        .nav-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .nav-description {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Stats Section */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 1rem 0;
        }

        .stats-text {
            color: #6b7280;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .bg-primary-soft {
            background-color: var(--secondary-color);
        }

        .bg-success-soft {
            background-color: rgba(16, 185, 129, 0.1);
        }

        /* Testimonial Cards */
        .testimonial-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
        }

        .testimonial-content {
            position: relative;
        }

        .testimonial-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        .testimonial-user {
            display: flex;
            align-items: center;
        }

        /* Styles for horizontal layout container */
        .testimonial-row {
            display: flex;
            flex-wrap: nowrap;
            /* Ensure items stay on a single line */
            overflow-x: auto;
            /* Enable horizontal scrolling */
            gap: 1.5rem;
            /* Space between cards */
            padding-bottom: 1rem;
            /* Add some padding for the scrollbar area */
            /* Hide scrollbar for different browsers */
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* Internet Explorer 10+ */
        }

        /* Hide scrollbar for Chrome, Edge, and Safari */
        .testimonial-row::-webkit-scrollbar {
            display: none;
        }

        /* Adjust individual testimonial card container */
        .testimonial-item {
            flex: 0 0 320px;
            /* Prevent shrinking and set a fixed width */
            width: 320px;
            /* Explicitly set width */
            margin-bottom: 1.5rem;
            /* Add back bottom margin lost from col class */
        }

        /* Responsive adjustments - adjust item width for smaller screens if needed */
        @media (max-width: 767px) {
            .testimonial-item {
                flex: 0 0 85%;
                /* Make items take up a percentage of the viewport width on small screens */
                width: 85%;
            }
        }

        /* Gradient Backgrounds */
        .bg-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, var(--secondary-color) 100%);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0ea271 100%);
        }

        /* Footer Styles */
        .footer {
            background: var(--dark-color);
        }

        .footer a:hover {
            color: white !important;
            text-decoration: none;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991px) {
            .hero-section {
                padding: 2rem 0;
                min-height: auto;
                text-align: center;
            }

            .display-4 {
                font-size: 2.5rem;
            }

            .lead {
                font-size: 1.1rem;
            }

            .stats-card {
                margin-bottom: 1.5rem;
            }

            .nav-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .nav-box {
                padding: 1.5rem;
            }

            .testimonial-card {
                margin-bottom: 1.5rem;
            }

            .testimonial-content {
                padding: 1rem;
            }

            .testimonial-user {
                flex-direction: column;
                text-align: center;
            }

            .testimonial-avatar {
                margin: 0 auto 1rem;
            }

            .decorative-shape {
                display: none;
            }

            .bg-gradient-primary {
                text-align: center !important;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                margin-top: 1rem;
            }

            .col-lg-4,
            .col-lg-6,
            .col-lg-8 {
                margin-bottom: 2rem;
            }

            .stats-number {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .display-4 {
                font-size: 2rem;
            }

            .stats-card {
                padding: 1.5rem;
            }

            .testimonial-card {
                padding: 1.5rem;
            }

            .badge {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .nav-title {
                font-size: 1.1rem;
            }

            .nav-description {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Decorative Elements -->
        <div class="decorative-shape shape-1 floating-element">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="var(--primary-color)">
                <circle cx="12" cy="12" r="8" />
            </svg>
        </div>
        <div class="decorative-shape shape-2 floating-element">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="var(--primary-color)">
                <rect x="4" y="4" width="16" height="16" rx="2" />
            </svg>
        </div>
        <div class="decorative-shape shape-3 floating-element">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="var(--primary-color)">
                <polygon points="12 2 19 21 12 17 5 21 12 2" />
            </svg>
        </div>

        <div class="container content-section">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4"><?= $settings['website_name'] ?? 'FinanceFlow' ?>: <?= $settings['website_description'] ?? 'Catat Keuangan Jadi Menyenangkan' ?></h1>
                    <p class="lead mb-4">Males nyatet pengeluaran? Bikin gampang dengan FinanceFlow!</p>
                    <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                        <ul class="list-unstyled fs-5">
                            <li class="mb-3">💸 <strong>Uang habis</strong> tapi nggak tahu kemana?</li>
                            <li class="mb-3">📊 Bingung <strong>ngatur budget</strong> bulanan?</li>
                            <li class="mb-3">😩 Stres karena <strong>keuangan nggak terkontrol</strong>?</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('register') ?>" class="btn btn-custom btn-lg rounded-pill px-5 py-3" data-aos="fade-up" data-aos-delay="400">
                            Mulai Gratis
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="<?= base_url('assets/images/hero-illustration.png') ?>" alt="FinanceFlow Illustration" class="img-fluid animate-float">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-gradient">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="stats-card text-center">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h3 class="stats-number"><?= number_format($stats['total_users']) ?>+</h3>
                        <p class="stats-text">Pengguna Aktif</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-card text-center">
                        <i class="fas fa-chart-line fa-3x mb-3 text-success"></i>
                        <h3 class="stats-number"><?= number_format($stats['total_transactions']) ?>+</h3>
                        <p class="stats-text">Transaksi Tercatat</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-card text-center">
                        <i class="fas fa-star fa-3x mb-3 text-warning"></i>
                        <h3 class="stats-number"><?= number_format($stats['rating'], 1) ?></h3>
                        <p class="stats-text">Rating Pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-primary-soft text-primary mb-3" data-aos="fade-up">FITUR UNGGULAN</span>
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Fitur Lengkap FinanceFlow</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Semua yang Anda butuhkan untuk mengelola keuangan dalam satu aplikasi</p>
            </div>

            <div class="nav-grid">
                <div class="nav-box" data-aos="fade-up" data-aos-delay="100">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h3 class="nav-title">Perencanaan Budget</h3>
                    <p class="nav-description">Rencanakan dan kelola budget bulanan Anda dengan mudah</p>
                </div>

                <div class="nav-box" data-aos="fade-up" data-aos-delay="200">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                            <line x1="8" y1="21" x2="16" y2="21" />
                            <line x1="12" y1="17" x2="12" y2="21" />
                        </svg>
                    </div>
                    <h3 class="nav-title">Kategori Pengeluaran</h3>
                    <p class="nav-description">Lacak pengeluaran berdasarkan kategori yang Anda tentukan</p>
                </div>

                <div class="nav-box" data-aos="fade-up" data-aos-delay="300">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <h3 class="nav-title">Target Tabungan</h3>
                    <p class="nav-description">Tetapkan target dan pantau progress tabungan Anda</p>
                </div>

                <div class="nav-box" data-aos="fade-up" data-aos-delay="400">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                            <path d="M22 12A10 10 0 0 0 12 2v10z" />
                        </svg>
                    </div>
                    <h3 class="nav-title">Laporan Keuangan</h3>
                    <p class="nav-description">Lihat laporan detail dan grafik analisis keuangan Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-success-soft text-success mb-3" data-aos="fade-up">TESTIMONI</span>
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Yang Mereka Katakan</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Lihat bagaimana FinanceFlow membantu pengguna mengelola keuangan mereka</p>
            </div>

            <div class="row testimonial-row">
                <?php foreach ($reviews ?? [] as $index => $review): ?>
                    <div class="testimonial-item" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <i class="fas fa-quote-left mb-3 text-primary"></i>
                                <p class="mb-4"><?= esc($review['content']) ?></p>
                                <div class="testimonial-stars mb-3">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i class="fas fa-star <?= $i < $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="testimonial-user">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($review['name']) ?>&background=random" alt="<?= esc($review['name']) ?>" class="testimonial-avatar">
                                    <div>
                                        <h5 class="mb-0"><?= esc($review['name']) ?></h5>
                                        <small class="text-muted"><?= esc($review['role']) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($reviews)): ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada testimoni yang ditampilkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-gradient-primary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="text-white fw-bold mb-3" data-aos="fade-up">Mulai Perjalanan Finansial Anda</h2>
                    <p class="text-white-50 mb-lg-0" data-aos="fade-up" data-aos-delay="100">Daftar sekarang dan nikmati semua fitur gratis!</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end" data-aos="fade-up" data-aos-delay="200">
                    <a href="<?= base_url('register') ?>" class="btn btn-light btn-lg rounded-pill px-5">
                        Daftar Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>

    <script>
        const testimonialRow = document.querySelector('.testimonial-row');
        let isDragging = false;
        let startPos = 0;
        let scrollLeft = 0;

        if (testimonialRow) {
            testimonialRow.addEventListener('mousedown', (e) => {
                isDragging = true;
                testimonialRow.classList.add('dragging'); // Optional: add a class for styling while dragging
                startPos = e.pageX - testimonialRow.offsetLeft;
                scrollLeft = testimonialRow.scrollLeft;
                e.preventDefault(); // Prevent default drag behavior like image dragging
            });

            testimonialRow.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    testimonialRow.classList.remove('dragging');
                }
            });

            testimonialRow.addEventListener('mouseup', () => {
                isDragging = false;
                testimonialRow.classList.remove('dragging');
            });

            testimonialRow.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - testimonialRow.offsetLeft;
                const walk = (x - startPos) * 2; // Adjust multiplier for faster/slower scroll
                testimonialRow.scrollLeft = scrollLeft - walk;
            });

            // Prevent text selection while dragging
            testimonialRow.addEventListener('selectstart', (e) => {
                if (isDragging) {
                    e.preventDefault();
                }
            });
        }
    </script>
</body>

</html>