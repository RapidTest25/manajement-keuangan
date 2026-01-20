<?= $this->extend('layout/auth/template'); ?>

<?= $this->section('content'); ?>

<head>
    <title>FinanceFlow - Login</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="header">
                <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Logo" class="logo">
                <h1>FINANCE FLOW</h1>
            </div>
            <div class="form-box">
                <h2>Selamat Datang Kembali !</h2>
                <p>Silahkan login dengan dahulu ya</p>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="login">Email atau Username</label>
                        <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                            name="login" placeholder="Masukkan email atau username" value="<?= old('login') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.login') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                            placeholder="Masukkan password">
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember" <?= old('remember') ? 'checked' : '' ?>>
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>

                    <div class="forgot">
                        <a href="<?= base_url('forgot') ?>">🔒 Lupa Password?</a>
                    </div>
                </form>

                <div class="register">
                    Belum memiliki akun? <a href="<?= base_url('register') ?>">Daftar</a>
                </div>
            </div>
        </div>
        <footer>
            <p>Copyright © 2025 FinanceFlow.</p>
        </footer>
    </div>
</body>

<?= $this->endSection(); ?>