<?= $this->extend('layout/auth/template'); ?>

<?= $this->section('content'); ?>

<head>
    <title>FinanceFlow - Forgot</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="header">
                <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Logo" class="logo">
                <h1>FINANCE FLOW</h1>
            </div>

            <div class="form-box">

                <?= view('Myth\Auth\Views\_message_block') ?>

                <div class="alert alert-success">
                    Kami akan mengirimkan email yang berisi link untuk reset password akun kamu
                </div>

                <form action="<?= url_to('forgot') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="email"><?= lang('Auth.emailAddress') ?></label>
                        <input type="email"
                            class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                            name="email"
                            aria-describedby="emailHelp"
                            placeholder="<?= lang('Auth.email') ?>"
                            value="<?= old('email') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary btn-block">
                        <?= lang('Auth.sendInstructions') ?>
                    </button>
                </form>
            </div>
        </div>
        <footer>
            <p>Copyright © 2025 FinanceFlow.</p>
        </footer>
    </div>
</body>

<?= $this->endSection(); ?>