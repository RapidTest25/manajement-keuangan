<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>

<style>
    .payment-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .plan-summary {
        background: linear-gradient(135deg, #009e60 0%, #00c975 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .payment-method {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #009e60;
        background: #f8f9fa;
    }

    .payment-method.selected {
        border-color: #009e60;
        background: #e8f5e9;
    }

    .payment-method input[type="radio"] {
        margin-right: 10px;
    }

    .payment-icon {
        font-size: 32px;
        margin-right: 15px;
    }

    .price-breakdown {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .price-total {
        font-size: 24px;
        font-weight: bold;
        color: #009e60;
        border-top: 2px solid #e0e0e0;
        padding-top: 15px;
        margin-top: 15px;
    }
</style>

<div class="main-container">
    <div class="header mb-4">
        <h1 style="font-size: 28px; font-weight: bold; color: #333; text-align: center;">💳 Pembayaran</h1>
    </div>

    <div class="payment-card">
        <!-- Plan Summary -->
        <div class="plan-summary">
            <h3 style="margin: 0 0 10px 0;">📦 Ringkasan Paket</h3>
            <h4 style="margin: 0;"><?= $plan['name'] ?></h4>
            <p style="margin: 10px 0 0 0; opacity: 0.9;"><?= $plan['description'] ?></p>
        </div>

        <!-- Price Breakdown -->
        <div class="price-breakdown">
            <div class="price-row">
                <span>Harga Paket:</span>
                <span>Rp <?= number_format($plan['price'], 0, ',', '.') ?></span>
            </div>
            <div class="price-row">
                <span>Biaya Admin:</span>
                <span>Rp 0</span>
            </div>
            <div class="price-row price-total">
                <span>Total Pembayaran:</span>
                <span>Rp <?= number_format($plan['price'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Form -->
        <form action="<?= base_url('app/subscription/process-payment') ?>" method="POST" id="paymentForm">
            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
            
            <h5 style="margin-bottom: 20px;">Pilih Metode Pembayaran:</h5>

            <label class="payment-method" onclick="selectPayment('bank_transfer')">
                <input type="radio" name="payment_method" value="bank_transfer" required>
                <span class="payment-icon">🏦</span>
                <div style="display: inline-block; vertical-align: middle;">
                    <strong>Transfer Bank</strong><br>
                    <small>BCA, Mandiri, BNI, BRI</small>
                </div>
            </label>

            <label class="payment-method" onclick="selectPayment('e_wallet')">
                <input type="radio" name="payment_method" value="e_wallet" required>
                <span class="payment-icon">📱</span>
                <div style="display: inline-block; vertical-align: middle;">
                    <strong>E-Wallet</strong><br>
                    <small>GoPay, OVO, DANA, ShopeePay</small>
                </div>
            </label>

            <label class="payment-method" onclick="selectPayment('credit_card')">
                <input type="radio" name="payment_method" value="credit_card" required>
                <span class="payment-icon">💳</span>
                <div style="display: inline-block; vertical-align: middle;">
                    <strong>Kartu Kredit/Debit</strong><br>
                    <small>Visa, Mastercard, JCB</small>
                </div>
            </label>

            <label class="payment-method" onclick="selectPayment('qris')">
                <input type="radio" name="payment_method" value="qris" required>
                <span class="payment-icon">📲</span>
                <div style="display: inline-block; vertical-align: middle;">
                    <strong>QRIS</strong><br>
                    <small>Scan dengan aplikasi apapun</small>
                </div>
            </label>

            <div class="alert alert-info mt-3">
                <strong>ℹ️ Catatan:</strong> Ini adalah demo pembayaran. Dalam aplikasi sebenarnya, Anda akan diarahkan ke payment gateway.
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #009e60 0%, #00c975 100%); color: white; border: none; border-radius: 10px; padding: 15px; font-weight: bold;">
                    ✓ Bayar Sekarang
                </button>
                <a href="<?= base_url('app/subscription/plans') ?>" class="btn btn-outline-secondary">
                    ← Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function selectPayment(method) {
    // Remove selected class from all payment methods
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked payment method
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.querySelector(`input[value="${method}"]`).checked = true;
}

// Form submission confirmation
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (confirm('Konfirmasi pembayaran sebesar Rp <?= number_format($plan['price'], 0, ',', '.') ?>?')) {
        this.submit();
    }
});
</script>

<?= $this->endSection(); ?>
