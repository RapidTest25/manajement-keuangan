<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    .subscription-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .subscription-status {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
    }

    .subscription-status.active {
        background: #d4edda;
        color: #155724;
    }

    .subscription-status.expired {
        background: #f8d7da;
        color: #721c24;
    }

    .history-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .history-table th {
        background: #009e60;
        color: white;
        padding: 15px;
        text-align: left;
    }

    .history-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-expired {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-cancelled {
        background: #fff3cd;
        color: #856404;
    }
</style>

<div class="main-container">
    <div class="header mb-4">
        <h1 style="font-size: 28px; font-weight: bold; color: #333;">📱 Langganan Saya</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Current Subscription -->
    <div class="subscription-card">
        <h3 style="margin-bottom: 20px;">🎯 Langganan Aktif</h3>
        
        <?php if ($currentSubscription): ?>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Paket:</strong> <?= $currentSubscription['plan_name'] ?></p>
                    <p><strong>Status:</strong> 
                        <span class="subscription-status <?= $currentSubscription['status'] ?>">
                            <?= strtoupper($currentSubscription['status']) ?>
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Mulai:</strong> <?= date('d F Y', strtotime($currentSubscription['start_date'])) ?></p>
                    <?php if ($currentSubscription['plan_slug'] !== 'free'): ?>
                        <p><strong>Berakhir:</strong> <?= date('d F Y', strtotime($currentSubscription['end_date'])) ?></p>
                        <p><strong>Sisa Waktu:</strong> <span style="color: #009e60; font-weight: bold;"><?= $daysRemaining ?> hari</span></p>
                    <?php else: ?>
                        <p><strong>Berakhir:</strong> <span style="color: #009e60;">Selamanya (Gratis)</span></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($currentSubscription['plan_slug'] !== 'free'): ?>
                <div class="mt-3">
                    <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-success">
                        🚀 Upgrade/Perpanjang Paket
                    </a>
                    <button onclick="confirmCancel()" class="btn btn-danger">
                        ❌ Batalkan Langganan
                    </button>
                </div>
            <?php else: ?>
                <div class="mt-3">
                    <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-success">
                        🚀 Upgrade ke Premium
                    </a>
                </div>
            <?php endif; ?>

            <!-- Features Available -->
            <div class="mt-4">
                <h5>✨ Fitur yang Tersedia:</h5>
                <ul style="list-style: none; padding-left: 0;">
                    <?php 
                    $features = json_decode($currentSubscription['features'], true);
                    foreach ($features as $feature): 
                    ?>
                        <li style="padding: 5px 0;">✓ <?= $feature ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <p>Anda belum memiliki langganan aktif.</p>
                <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-primary">
                    Lihat Paket Berlangganan
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Subscription History -->
    <?php if ($history && count($history) > 0): ?>
        <div class="subscription-card">
            <h3 style="margin-bottom: 20px;">📋 Riwayat Langganan</h3>
            
            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $item): ?>
                            <tr>
                                <td><?= $item['plan_name'] ?></td>
                                <td><?= date('d/m/Y', strtotime($item['start_date'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['end_date'])) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= $item['status'] ?>">
                                        <?= strtoupper($item['status']) ?>
                                    </span>
                                </td>
                                <td><?= $item['payment_method'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-outline-primary">
            🔙 Kembali ke Paket Berlangganan
        </a>
    </div>
</div>

<script>
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan langganan? Anda akan kehilangan akses ke fitur premium.')) {
        window.location.href = '<?= base_url('app/subscription/cancel') ?>';
    }
}
</script>

<?= $this->endSection(); ?>
