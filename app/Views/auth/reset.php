<?= $this->extend('layout/auth/template'); ?>

<?= $this->section('content'); ?>

<head>
    <title>FinanceFlow - Reset</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="header">
                <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Logo" class="logo">
                <h1>FINANCE FLOW</h1>
            </div>
            <div class="form-box">
                <h2>Silahkan Atur Ulang Password Kamu !</h2>

                <?= view('Myth\Auth\Views\_message_block') ?>


                <form action="<?= url_to('reset-password') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="token"><?= lang('Auth.token') ?></label>
                        <input type="text"
                            class="form-control <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>"
                            name="token"
                            placeholder="<?= lang('Auth.token') ?>"
                            value="<?= old('token', $token ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.token') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><?= lang('Auth.email') ?></label>
                        <input type="email"
                            class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                            name="email"
                            placeholder="<?= lang('Auth.email') ?>"
                            value="<?= old('email') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password"><?= lang('Auth.newPassword') ?></label>
                        <input type="password"
                            name="password"
                            class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                            placeholder="<?= lang('Auth.newPassword') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pass_confirm"><?= lang('Auth.newPasswordRepeat') ?></label>
                        <input type="password"
                            name="pass_confirm"
                            class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                            placeholder="<?= lang('Auth.newPasswordRepeat') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.pass_confirm') ?>
                        </div>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.resetPassword') ?></button>
                </form>
            </div>
        </div>
        <footer>
            <p>Copyright © 2025 FinanceFlow.</p>
        </footer>
    </div>
</body>

<?= $this->endSection(); ?>