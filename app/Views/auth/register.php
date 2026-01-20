<?= $this->extend('layout/auth/template'); ?>

<?= $this->section('content'); ?>

<head>
    <title>FinanceFlow - Register</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="header">
                <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Logo" class="logo">
                <h1>FINANCE FLOW</h1>
            </div>


            <div class="form-box">
                <h2>Selamat Datang !</h2>
                <p>Silahkan lengkapi data dibawah ya</p>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= url_to('register') ?>" method="post">
                    <?= csrf_field() ?>

                    <label for="username">Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Nama kamu" value="<?= old('fullname') ?>" required>

                    <label for="username">Username</label>
                    <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" id="username" placeholder="<?= lang('Auth.username') ?>" name="username" value="<?= old('username') ?>" required>

                    <label for="email"><?= lang('Auth.email') ?></label>
                    <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" id="email" name="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" id="password" name="password" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                    </div>

                    <label for="confirm_password">Ulangi Password</label>
                    <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" id="pass_confirm" name="pass_confirm" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">

                    <div class="captcha">
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">
                            Saya setuju dengan <a href="<?= base_url('pages/syarat-dan-ketentuan'); ?>">Syarat & Ketentuan</a> yang berlaku.
                        </label>
                    </div>

                    <button type="submit"><?= lang('Auth.register') ?></button>
                </form>
                <div class="register">
                    <p>Sudah memiliki akun? <a href="<?= base_url('login') ?>">Login</a></p>
                </div>
            </div>
        </div>
        <footer>
            <p>Copyright © 2025 FinanceFlow.</p>
        </footer>
    </div>
</body>

<?= $this->endSection(); ?>