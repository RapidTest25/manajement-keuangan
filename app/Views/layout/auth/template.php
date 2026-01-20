<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $settings['website_name'] ?? 'FinanceFlow' ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('Assets/css/auth.css'); ?>">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?= $this->renderSection('content'); ?>

    <!-- Custom JS -->
    <script src="<?= base_url('Assets/js/auth.js'); ?>"></script>

    <script>
        // Handle success messages
        <?php if (session()->has('success')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session('success') ?>',
                timer: 1500,
                showConfirmButton: false
            });
        <?php endif; ?>

        // Handle error messages
        <?php if (session()->has('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= session('error') ?>',
            });
        <?php endif; ?>

        // Handle login/register/activation success
        <?php if (session()->has('message')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session('message') ?>',
                timer: 1500,
                showConfirmButton: false
            });
        <?php endif; ?>

        // Handle validation errors
        <?php if (session()->has('errors')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '<?php foreach (session('errors') as $error) : ?><?= esc($error) ?><br><?php endforeach ?>',
            });
        <?php endif; ?>
    </script>
</body>

</html>