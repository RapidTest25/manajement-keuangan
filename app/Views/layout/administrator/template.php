<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - <?= $settings['website_name'] ?? 'FinanceFlow' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/loader.css'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <?= $this->renderSection('css') ?>
    <style>
        :root {
            --header-height: 3.5rem;
            --nav-width: 250px;
            --first-color: #00bd6d;
            --first-color-light: #f0f0f0;
            --white-color: #ffffff;
            --body-font: 'Inter', sans-serif;
            --normal-font-size: 1rem;
            --z-fixed: 100;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            position: relative;
            padding: 0 1rem;
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            transition: .5s;
            background-color: #f8f9fa;
        }

        .header {
            width: 100%;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            background-color: var(--white-color);
            z-index: var(--z-fixed);
            transition: .5s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: -30%;
            width: var(--nav-width);
            height: 100vh;
            background-color: var(--white-color);
            padding: .5rem 1rem;
            transition: .5s;
            z-index: var(--z-fixed);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .nav {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            height: 100%;
        }

        .nav-logo,
        .nav-link {
            display: flex;
            align-items: center;
            column-gap: 1rem;
            padding: .5rem 0 .5rem 1.5rem;
            text-decoration: none;
        }

        .nav-logo {
            margin-bottom: 2rem;
        }

        .nav-logo-name {
            color: var(--first-color);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .nav-link {
            position: relative;
            color: #555555;
            transition: .3s;
            margin-bottom: .5rem;
            border-radius: 8px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--first-color);
            background-color: #f0f9ff;
        }

        .nav-icon {
            font-size: 1.25rem;
        }

        .show-sidebar {
            left: 0;
        }

        .body-pd {
            padding-left: calc(var(--nav-width) + 1rem);
        }

        main {
            padding-top: calc(var(--header-height) + 1rem);
            flex: 1 0 auto;
        }

        /* Main content wrapper */
        .content-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Footer Style */
        .footer {
            flex-shrink: 0;
            width: 100%;
            padding: 1rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6c757d;
            border-top: 1px solid #eee;
            margin-top: auto;
            background: var(--white-color);
        }

        /* Mobile First Approach */
        @media screen and (max-width: 767px) {
            body {
                padding-left: 0;
            }

            .header {
                padding: 0 1rem;
            }

            #sidebar {
                left: -100%;
                padding: 1rem;
                transition: .3s;
            }

            #sidebar.show-sidebar {
                left: 0;
            }

            .body-pd {
                padding-left: 0;
            }

            main {
                padding: calc(var(--header-height) + 1rem) 1rem 0 1rem;
            }

            .footer {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Desktop styles */
        @media screen and (min-width: 768px) {
            body {
                padding-left: var(--nav-width);
            }

            .header {
                padding: 0 2rem 0 calc(var(--nav-width) + 2rem);
            }

            #sidebar {
                left: 0;
                padding: 1rem;
            }

            #header-toggle {
                display: none;
            }

            main {
                padding: calc(var(--header-height) + 1rem) 2rem 0 2rem;
            }

            .footer {
                margin-left: var(--nav-width);
                width: calc(100% - var(--nav-width));
            }
        }

        /* Custom Styles for Dashboard */
        .heading-section {
            margin-bottom: 2rem;
        }

        .heading-section h4 {
            font-weight: 600;
            color: #2d3436;
        }

        .text-success {
            color: var(--first-color) !important;
        }

        .btn-success {
            background-color: var(--first-color);
            border-color: var(--first-color);
        }

        .btn-success:hover {
            background-color: #00a65d;
            border-color: #009654;
        }

        .btn-outline-success {
            color: var(--first-color);
            border-color: var(--first-color);
        }

        .btn-outline-success:hover {
            background-color: var(--first-color);
            border-color: var(--first-color);
        }

        /* Update sidebar style */
        .sidebar-section {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 1rem;
        }

        .sidebar-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #999;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
        }

        .nav-link.active {
            background-color: #e8f8f1;
            color: var(--first-color) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: #f8f9fa;
        }

        .nav-icon {
            width: 1.5rem;
            margin-right: 0.5rem;
            font-size: 1.25rem;
        }

        /* Active link indicator */
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--first-color);
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>

<body id="body-pd">
    <!-- Include Loader -->
    <?= $this->include('layout/loader') ?>

    <!-- Header -->
    <header class="header" id="header">
        <div class="header-toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <div class="header-name">
            <i class='bx bx-dollar-circle me-2'></i>FinAdmin
        </div>
    </header>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="nav">
            <div>
                <a href="#" class="nav-logo">
                    <i class='bx bx-dollar-circle nav-logo-icon text-success'></i>
                    <span class="nav-logo-name">FinAdmin</span>
                </a>

                <!-- Main Navigation -->
                <div class="sidebar-section">
                    <div class="sidebar-title">Main Menu</div>
                    <div class="nav-list">
                        <a href="<?= base_url('administrator/dashboard') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'dashboard' ? 'active' : '' ?>">
                            <i class='bx bx-home-alt nav-icon'></i>
                            <span class="nav-name">Dashboard</span>
                        </a>

                        <a href="<?= base_url('administrator/transaksi') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'transaksi' ? 'active' : '' ?>">
                            <i class='bx bx-transfer nav-icon'></i>
                            <span class="nav-name">Transaksi</span>
                        </a>
                    </div>
                </div>

                <!-- Management Section -->
                <div class="sidebar-section">
                    <div class="sidebar-title">Manajemen</div>
                    <div class="nav-list">
                        <a href="<?= base_url('administrator/pengguna') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'pengguna' ? 'active' : '' ?>">
                            <i class='bx bx-group nav-icon'></i>
                            <span class="nav-name">Pengguna</span>
                        </a>

                        <a href="<?= base_url('administrator/program') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'program' ? 'active' : '' ?>">
                            <i class='bx bx-cube nav-icon'></i>
                            <span class="nav-name">Program</span>
                        </a> <a href="<?= base_url('administrator/reviews') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'reviews' ? 'active' : '' ?>">
                            <i class='bx bx-star nav-icon'></i>
                            <span class="nav-name">Testimoni</span>
                        </a>

                        <a href="<?= base_url('administrator/settings') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'settings' ? 'active' : '' ?>">
                            <i class='bx bx-cog nav-icon'></i>
                            <span class="nav-name">Pengaturan</span>
                        </a>

                        <a href="<?= base_url('administrator/subscription') ?>"
                            class="nav-link <?= current_url(true)->getSegment(2) == 'subscription' ? 'active' : '' ?>">
                            <i class='bx bx-diamond nav-icon'></i>
                            <span class="nav-name">Langganan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logout Link -->
            <div class="sidebar-section mb-0 border-0">
                <a href="#" id="logout-link" class="nav-link text-danger">
                    <i class='bx bx-log-out nav-icon'></i>
                    <span class="nav-name">Keluar</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Main Content -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p class="mb-0">&copy; <?= date('Y') ?> FinAdmin. Hak Cipta Dilindungi.</p>
        </footer>
    </div>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle sidebar
            const toggle = document.getElementById('header-toggle');
            const sidebar = document.getElementById('sidebar');
            const bodyPd = document.getElementById('body-pd');
            const content = document.querySelector('.content-wrapper');
            const logoutLink = document.getElementById('logout-link');

            function toggleSidebar() {
                sidebar.classList.toggle('show-sidebar');
                bodyPd.classList.toggle('body-pd');
            }

            if (toggle && sidebar && bodyPd) {
                // Toggle on menu button click
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleSidebar();
                });

                // Close sidebar when clicking outside
                content.addEventListener('click', () => {
                    if (window.innerWidth < 768 && sidebar.classList.contains('show-sidebar')) {
                        toggleSidebar();
                    }
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('show-sidebar');
                        bodyPd.classList.remove('body-pd');
                    }
                });
            }

            // Active links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Handle logout confirmation
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    showConfirm(
                        'Konfirmasi Keluar',
                        'Apakah Anda yakin ingin keluar?',
                        function() {
                            window.location.href = '<?= base_url('/logout'); ?>';
                        }
                    );
                });
            }
        });

        // Helper function for showing alerts
        function showAlert(title, text, icon = 'success') {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#00bd6d'
            });
        }

        // Helper function for showing confirmations
        function showConfirm(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00bd6d',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
    </script>

    <!-- Additional Scripts -->
    <?= $this->renderSection('js') ?>
</body>

</html>