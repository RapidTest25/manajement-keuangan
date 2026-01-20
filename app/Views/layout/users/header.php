<header class="top-nav shadow-sm">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between py-3">
            <div>
                <h1 class="app-brand mb-0"><?= $settings['website_name'] ?? 'FinanceFlow' ?></h1>
                <p class="text-muted mb-0"><?= $settings['website_description'] ?? 'Catatan Keuangan Jadi Menyenangkan' ?></p>
            </div>
            <div class="text-end">
                <?php if (logged_in()): ?>
                    <img src="<?= base_url('assets/images/user/' . user()->user_image) ?>"
                        alt="Profile"
                        class="rounded-circle header-profile-pic"
                        style="width: 40px; height: 40px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>