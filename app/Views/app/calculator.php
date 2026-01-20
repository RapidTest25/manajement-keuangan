<?= $this->extend('layout/users/template'); ?>

<?= $this->section('content'); ?>
<div class="wrapper d-flex flex-column min-vh-100">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 500px;">
                <div class="card shadow mt-4 mb-5">
                    <div class="card-body">
                        <h2 class="card-title mb-4 text-center">Kalkulator Suku Bunga</h2>

                        <!-- Calculator Type Toggle -->
                        <div class="btn-group w-100 mb-4" role="group">
                            <input type="radio" class="btn-check" name="calc-type" id="calc-loan" autocomplete="off" checked>
                            <label class="btn btn-outline-success rounded-pill me-2" for="calc-loan">Pinjaman</label>

                            <input type="radio" class="btn-check" name="calc-type" id="calc-investment" autocomplete="off">
                            <label class="btn btn-outline-success rounded-pill me-2" for="calc-investment">Investasi</label>

                            <input type="radio" class="btn-check" name="calc-type" id="calc-kpr" autocomplete="off">
                            <label class="btn btn-outline-success rounded-pill" for="calc-kpr">KPR</label>
                        </div>

                        <!-- Loan Calculator Form -->
                        <div id="loanCalculator">
                            <form id="loanForm">
                                <div class="alert alert-info" role="alert">
                                    <small>
                                        <strong>Jenis Suku Bunga Pinjaman:</strong><br>
                                        • Bunga Flat: Bocok untuk pinjaman jangka pendek dan memberi cicilan tetap.<br>
                                        • Bunga Efektif: Cocok untuk pinjaman jangka panjang karena beban bunga berkurang tiap bulan.
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="loanAmount" class="form-label">Jumlah Pinjaman</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="loanAmount" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="interestType" class="form-label">Jenis Suku Bunga</label>
                                    <select class="form-select" id="interestType" required>
                                        <option value="flat">Bunga Flat</option>
                                        <option value="effective">Bunga Efektif</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="loanInterest" class="form-label">Suku Bunga</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="loanInterest" step="0.01" required>
                                        <span class="input-group-text">%/tahun</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="loanTerm" class="form-label">Jangka Waktu</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="loanTerm" required>
                                        <span class="input-group-text">bulan</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 rounded-pill">Hitung</button>
                            </form>
                            <div id="loanResults" class="mt-4" style="display: none;">
                                <h4>Hasil Perhitungan:</h4>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light">Total Pinjaman</th>
                                            <td id="totalLoan">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Angsuran per Bulan</th>
                                            <td id="monthlyPayment">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Pembayaran</th>
                                            <td id="totalPayment">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Bunga</th>
                                            <td id="totalInterest">Rp 0</td>
                                        </tr>
                                    </table>
                                </div>

                                <h5>Rincian Angsuran:</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="bg-light">Bulan</th>
                                                <th class="bg-light">Angsuran Pokok</th>
                                                <th class="bg-light">Angsuran Bunga</th>
                                                <th class="bg-light">Total Angsuran</th>
                                                <th class="bg-light">Sisa Pinjaman</th>
                                            </tr>
                                        </thead>
                                        <tbody id="installmentDetails">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- Investment Calculator Form -->
                        <div id="investmentCalculator" style="display: none;">
                            <form id="investmentForm">
                                <div class="alert alert-info" role="alert">
                                    <small>
                                        <strong>Rumus Bunga Majemuk:</strong><br>
                                        Na = Nt × (1 + i)^n<br>
                                        <strong>Keterangan:</strong><br>
                                        Na = Nilai akhir<br>
                                        Nt = Nilai tunai (modal awal)<br>
                                        i = Suku bunga (dalam desimal)<br>
                                        n = Jumlah periode (tahun)
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="principal" class="form-label">Modal Awal (Nt)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="principal" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="interestRate" class="form-label">Suku Bunga (i)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="interestRate" step="0.01" required>
                                        <span class="input-group-text">%/tahun</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="period" class="form-label">Jangka Waktu (n)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="period" required>
                                        <span class="input-group-text">tahun</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 rounded-pill">Hitung</button>
                            </form>
                            <div id="investmentResults" class="mt-4" style="display: none;">
                                <h4>Hasil Perhitungan Bunga Majemuk:</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light">Modal Awal (Nt)</th>
                                            <td id="initialAmount">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Keuntungan Bunga</th>
                                            <td id="interestProfit">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Nilai Akhir (Na)</th>
                                            <td id="finalAmount">Rp 0</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="alert alert-secondary mt-3">
                                    <small>
                                        <strong>Keterangan:</strong><br>
                                        • Modal Awal: Jumlah uang yang diinvestasikan<br>
                                        • Keuntungan Bunga: Total bunga yang didapat selama periode investasi<br>
                                        • Nilai Akhir: Total uang setelah periode investasi selesai
                                    </small>
                                </div>
                            </div>
                        </div> <!-- KPR Calculator Form -->
                        <div id="kprCalculator" style="display: none;">
                            <form id="kprForm">
                                <div class="alert alert-info" role="alert">
                                    <small>
                                        <strong>Jenis Suku Bunga KPR:</strong><br>
                                        • Fixed Rate: Bunga tetap sepanjang tenor<br>
                                        • Floating Rate: Bunga mengikuti pasar<br>
                                        • Capped Rate: Bunga mengambang dengan batas maksimal<br>
                                        • Hybrid Rate: Kombinasi dari beberapa jenis bunga
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="kprAmount" class="form-label">Jumlah Pinjaman</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="kprAmount" name="kprAmount" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="kprInterestType" class="form-label">Jenis Suku Bunga</label>
                                    <select class="form-select" id="kprInterestType" name="kprInterestType" required>
                                        <option value="fixed">Fixed Rate (Tetap)</option>
                                        <option value="floating">Floating Rate (Mengambang)</option>
                                        <option value="capped">Capped Rate (Terbatas)</option>
                                        <option value="hybrid">Hybrid Rate (Gabungan)</option>
                                    </select>
                                </div>

                                <!-- Fixed Rate Fields -->
                                <div id="fixedRateFields" style="display: block;">
                                    <div class="alert alert-info mb-3" role="alert">
                                        <small>
                                            <strong>Keterangan Input:</strong><br>
                                            • Suku Bunga Tetap: Persentase bunga yang akan tetap sama sepanjang masa pinjaman<br>
                                            • Jangka Waktu: Lama periode pinjaman dalam tahun (1-30 tahun)
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprFixedInterest" class="form-label">Suku Bunga Tetap</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprFixedInterest" name="kprFixedInterest" step="0.01" required>
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Rate Fields -->
                                <div id="floatingRateFields" style="display: none;">
                                    <div class="alert alert-info mb-3" role="alert">
                                        <small>
                                            <strong>Keterangan Input:</strong><br>
                                            • Suku Bunga Awal: Bunga tetap yang digunakan pada periode awal pinjaman<br>
                                            • Suku Bunga Mengambang: Bunga yang akan digunakan setelah periode bunga tetap berakhir<br>
                                            • Periode Bunga Tetap: Berapa lama bunga awal akan diterapkan (dalam tahun)<br>
                                            • Jangka Waktu: Total periode pinjaman dalam tahun (1-30 tahun)
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprInitialInterest" class="form-label">Suku Bunga Awal</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprInitialInterest" name="kprInitialInterest" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprFloatingInterest" class="form-label">Suku Bunga Mengambang</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprFloatingInterest" name="kprFloatingInterest" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprFixedPeriod" class="form-label">Periode Bunga Tetap</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprFixedPeriod" name="kprFixedPeriod">
                                            <span class="input-group-text">tahun</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Capped Rate Fields -->
                                <div id="cappedRateFields" style="display: none;">
                                    <div class="alert alert-info mb-3" role="alert">
                                        <small>
                                            <strong>Keterangan Input:</strong><br>
                                            • Suku Bunga Awal: Bunga dasar yang digunakan saat memulai pinjaman<br>
                                            • Batas Maksimal Suku Bunga: Batas tertinggi bunga yang dapat dikenakan<br>
                                            • Jangka Waktu: Total periode pinjaman dalam tahun (1-30 tahun)<br>
                                            <br>
                                            <em>Catatan: Bunga akan mengikuti suku bunga pasar tetapi tidak akan melebihi batas maksimal</em>
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprInitialCappedInterest" class="form-label">Suku Bunga Awal</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprInitialCappedInterest" name="kprInitialCappedInterest" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprMaxInterest" class="form-label">Batas Maksimal Suku Bunga</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprMaxInterest" name="kprMaxInterest" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hybrid Rate Fields -->
                                <div id="hybridRateFields" style="display: none;">
                                    <div class="alert alert-info mb-3" role="alert">
                                        <small>
                                            <strong>Keterangan Input:</strong><br>
                                            • Suku Bunga Fixed: Bunga tetap yang berlaku untuk 3 tahun pertama<br>
                                            • Suku Bunga Capped: Bunga dengan batas maksimal untuk tahun ke 4-5<br>
                                            • Suku Bunga Floating: Bunga mengambang yang berlaku setelah tahun ke-5<br>
                                            • Jangka Waktu: Total periode pinjaman dalam tahun (1-30 tahun)<br>
                                            <br>
                                            <em>Catatan: Hybrid menggabungkan tiga jenis bunga dalam satu pinjaman dengan periode yang berbeda</em>
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprHybridFixed" class="form-label">Suku Bunga Fixed (Tahun 1-3)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprHybridFixed" name="kprHybridFixed" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprHybridCapped" class="form-label">Suku Bunga Capped (Tahun 4-5)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprHybridCapped" name="kprHybridCapped" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kprHybridFloating" class="form-label">Suku Bunga Floating (Tahun 6+)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="kprHybridFloating" name="kprHybridFloating" step="0.01">
                                            <span class="input-group-text">%/tahun</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="kprTerm" class="form-label">Jangka Waktu</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="kprTerm" name="kprTerm" required>
                                        <span class="input-group-text">tahun</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 rounded-pill">Hitung KPR</button>
                            </form>

                            <div id="kprResults" class="mt-4" style="display: none;">
                                <h4>Hasil Perhitungan KPR:</h4>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light">Total Pinjaman</th>
                                            <td id="kprTotalLoan">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Cicilan per Bulan (Awal)</th>
                                            <td id="kprMonthlyPayment">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Pembayaran</th>
                                            <td id="kprTotalPayment">Rp 0</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Bunga</th>
                                            <td id="kprTotalInterest">Rp 0</td>
                                        </tr>
                                    </table>
                                </div>

                                <h5>Rincian Cicilan:</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="bg-light">Tahun</th>
                                                <th class="bg-light">Bulan</th>
                                                <th class="bg-light">Suku Bunga</th>
                                                <th class="bg-light">Cicilan Pokok</th>
                                                <th class="bg-light">Cicilan Bunga</th>
                                                <th class="bg-light">Total Cicilan</th>
                                                <th class="bg-light">Sisa Pinjaman</th>
                                            </tr>
                                        </thead>
                                        <tbody id="kprInstallmentDetails">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- KPR Calculator -->
                        <div id="kpr-calculator" class="calculator-section d-none">
                            <h4 class="text-center mb-4">Kalkulator KPR</h4>
                            <form id="kpr-form" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="kpr-amount" class="form-label">Jumlah Pinjaman (Rp)</label>
                                    <input type="number" class="form-control" id="kpr-amount" required min="0">
                                    <div class="invalid-feedback">Harap masukkan jumlah pinjaman</div>
                                </div>

                                <div class="mb-3">
                                    <label for="kpr-rate-type" class="form-label">Jenis Suku Bunga</label>
                                    <select class="form-select" id="kpr-rate-type" required>
                                        <option value="">Pilih jenis suku bunga</option>
                                        <option value="fixed">Fixed Rate (Bunga Tetap)</option>
                                        <option value="floating">Floating Rate (Bunga Mengambang)</option>
                                        <option value="capped">Capped Rate (Bunga Maksimal)</option>
                                        <option value="hybrid">Hybrid Rate (Bunga Campuran)</option>
                                    </select>
                                    <div class="invalid-feedback">Harap pilih jenis suku bunga</div>
                                </div>

                                <!-- Fixed Rate Fields -->
                                <div id="fixed-rate-fields" class="rate-fields d-none">
                                    <div class="mb-3">
                                        <label for="fixed-rate" class="form-label">Suku Bunga Tetap (%)</label>
                                        <input type="number" class="form-control" id="fixed-rate" step="0.01" min="0">
                                    </div>
                                </div>

                                <!-- Floating Rate Fields -->
                                <div id="floating-rate-fields" class="rate-fields d-none">
                                    <div class="mb-3">
                                        <label for="floating-base-rate" class="form-label">Suku Bunga Dasar (%)</label>
                                        <input type="number" class="form-control" id="floating-base-rate" step="0.01" min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="floating-margin" class="form-label">Margin (%)</label>
                                        <input type="number" class="form-control" id="floating-margin" step="0.01" min="0">
                                    </div>
                                </div>

                                <!-- Capped Rate Fields -->
                                <div id="capped-rate-fields" class="rate-fields d-none">
                                    <div class="mb-3">
                                        <label for="capped-base-rate" class="form-label">Suku Bunga Dasar (%)</label>
                                        <input type="number" class="form-control" id="capped-base-rate" step="0.01" min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="capped-cap-rate" class="form-label">Suku Bunga Maksimal (%)</label>
                                        <input type="number" class="form-control" id="capped-cap-rate" step="0.01" min="0">
                                    </div>
                                </div>

                                <!-- Hybrid Rate Fields -->
                                <div id="hybrid-rate-fields" class="rate-fields d-none">
                                    <div class="mb-3">
                                        <label for="hybrid-fixed-rate" class="form-label">Suku Bunga Tetap Awal (%)</label>
                                        <input type="number" class="form-control" id="hybrid-fixed-rate" step="0.01" min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hybrid-fixed-years" class="form-label">Lama Bunga Tetap (Tahun)</label>
                                        <input type="number" class="form-control" id="hybrid-fixed-years" min="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="hybrid-floating-rate" class="form-label">Suku Bunga Mengambang (%)</label>
                                        <input type="number" class="form-control" id="hybrid-floating-rate" step="0.01" min="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="kpr-tenor" class="form-label">Jangka Waktu (Tahun)</label>
                                    <input type="number" class="form-control" id="kpr-tenor" required min="1" max="30">
                                    <div class="invalid-feedback">Harap masukkan jangka waktu antara 1-30 tahun</div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 mb-4">Hitung</button>
                            </form>

                            <!-- KPR Results -->
                            <div id="kpr-results" class="d-none">
                                <h5 class="mb-3">Hasil Perhitungan KPR</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Informasi</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Jumlah Pinjaman</td>
                                                <td id="kpr-result-amount"></td>
                                            </tr>
                                            <tr>
                                                <td>Cicilan per Bulan</td>
                                                <td id="kpr-result-monthly"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Bunga</td>
                                                <td id="kpr-result-interest"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Pembayaran</td>
                                                <td id="kpr-result-total"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Payment Schedule -->
                                <h5 class="mt-4 mb-3">Jadwal Pembayaran</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Bulan</th>
                                                <th>Sisa Pinjaman</th>
                                                <th>Pokok</th>
                                                <th>Bunga</th>
                                                <th>Total Cicilan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="kpr-schedule"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle calculator type
        const loanCalc = document.getElementById('loanCalculator');
        const investCalc = document.getElementById('investmentCalculator');
        const kprCalc = document.getElementById('kprCalculator');
        const calcLoanBtn = document.getElementById('calc-loan');
        const calcInvestBtn = document.getElementById('calc-investment');
        const calcKprBtn = document.getElementById('calc-kpr');

        calcLoanBtn.addEventListener('change', function() {
            loanCalc.style.display = 'block';
            investCalc.style.display = 'none';
            kprCalc.style.display = 'none';
        });

        calcInvestBtn.addEventListener('change', function() {
            loanCalc.style.display = 'none';
            investCalc.style.display = 'block';
            kprCalc.style.display = 'none';
        });

        calcKprBtn.addEventListener('change', function() {
            loanCalc.style.display = 'none';
            investCalc.style.display = 'none';
            kprCalc.style.display = 'block';
        }); // Loan Calculator
        document.getElementById('loanForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const amount = parseFloat(document.getElementById('loanAmount').value);
            const annualInterest = parseFloat(document.getElementById('loanInterest').value) / 100; // Annual interest rate
            const term = parseInt(document.getElementById('loanTerm').value);
            const interestType = document.getElementById('interestType').value;

            let monthlyPayment, totalPayment, totalInterest;
            const installments = [];

            if (interestType === 'flat') {
                // Flat rate calculation
                const monthlyPrincipal = amount / term;
                const monthlyInterest = (amount * annualInterest * term / 12) / term;
                monthlyPayment = monthlyPrincipal + monthlyInterest;
                totalPayment = monthlyPayment * term;
                totalInterest = totalPayment - amount;

                // Generate installment details
                let remainingLoan = amount;
                for (let i = 1; i <= term; i++) {
                    remainingLoan = amount - (monthlyPrincipal * i);
                    if (remainingLoan < 0) remainingLoan = 0;

                    installments.push({
                        month: i,
                        principal: monthlyPrincipal,
                        interest: monthlyInterest,
                        payment: monthlyPayment,
                        remaining: remainingLoan
                    });
                }
            } else {
                // Effective rate calculation
                const monthlyPrincipal = amount / term;
                let remainingLoan = amount;
                totalPayment = 0;

                for (let i = 1; i <= term; i++) {
                    const monthlyInterest = remainingLoan * annualInterest * (30 / 360);
                    const installmentAmount = monthlyPrincipal + monthlyInterest;
                    totalPayment += installmentAmount;

                    installments.push({
                        month: i,
                        principal: monthlyPrincipal,
                        interest: monthlyInterest,
                        payment: installmentAmount,
                        remaining: remainingLoan - monthlyPrincipal
                    });

                    remainingLoan -= monthlyPrincipal;
                    if (remainingLoan < 0) remainingLoan = 0;
                }

                totalInterest = totalPayment - amount;
                monthlyPayment = installments[0].payment; // First month's payment
            }

            // Update summary
            document.getElementById('totalLoan').textContent = formatCurrency(amount);
            document.getElementById('monthlyPayment').textContent = formatCurrency(monthlyPayment);
            document.getElementById('totalPayment').textContent = formatCurrency(totalPayment);
            document.getElementById('totalInterest').textContent = formatCurrency(totalInterest);

            // Update installment details table
            const tableBody = document.getElementById('installmentDetails');
            tableBody.innerHTML = '';
            installments.forEach(inst => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${inst.month}</td>
                    <td>${formatCurrency(inst.principal)}</td>
                    <td>${formatCurrency(inst.interest)}</td>
                    <td>${formatCurrency(inst.payment)}</td>
                    <td>${formatCurrency(inst.remaining)}</td>
                `;
                tableBody.appendChild(row);
            });

            document.getElementById('loanResults').style.display = 'block';
        }); // Investment Calculator
        document.getElementById('investmentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil nilai input
            const principal = parseFloat(document.getElementById('principal').value); // Nt (Modal Awal)
            const rate = parseFloat(document.getElementById('interestRate').value) / 100; // i (Suku Bunga dalam desimal)
            const years = parseInt(document.getElementById('period').value); // n (Jumlah Tahun)

            // Hitung nilai akhir menggunakan rumus bunga majemuk
            // Na = Nt × (1 + i)^n
            const finalAmount = principal * Math.pow(1 + rate, years);

            // Hitung keuntungan bunga
            const interestProfit = finalAmount - principal;

            // Tampilkan hasil perhitungan
            document.getElementById('initialAmount').textContent = formatCurrency(principal);
            document.getElementById('interestProfit').textContent = formatCurrency(interestProfit);
            document.getElementById('finalAmount').textContent = formatCurrency(finalAmount);

            document.getElementById('investmentResults').style.display = 'block';
        });         // KPR Calculator
        document.getElementById('kprForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear any previous errors
            hideKprError();

            const amount = parseFloat(document.getElementById('kprAmount').value);
            const term = parseInt(document.getElementById('kprTerm').value) * 12; // Convert years to months
            const rateType = document.getElementById('kprInterestType').value;

            // Basic validation
            if (!amount || isNaN(amount) || amount <= 0) {
                showKprError('Jumlah pinjaman harus diisi dan lebih besar dari 0');
                return;
            }

            if (!term || isNaN(term) || term <= 0) {
                showKprError('Jangka waktu harus diisi dan lebih besar dari 0');
                return;
            }

            if (!rateType) {
                showKprError('Jenis suku bunga harus dipilih');
                return;
            }

            let schedule = [];

            try {
                switch (rateType) {
                    case 'fixed':
                        const fixedRateElement = document.getElementById('kprFixedInterest');
                        if (!fixedRateElement) {
                            showKprError('Field suku bunga tetap tidak ditemukan');
                            return;
                        }
                        const fixedRate = parseFloat(fixedRateElement.value) / 100;
                        
                        if (!fixedRateElement.value || isNaN(fixedRate) || fixedRate < 0) {
                            showKprError('Suku bunga tetap harus diisi dengan benar (minimal 0%)');
                            return;
                        }
                        schedule = calculateFixedRate(amount, fixedRate, term);
                        break;

                    case 'floating':
                        const initialRateElement = document.getElementById('kprInitialInterest');
                        const floatingRateElement = document.getElementById('kprFloatingInterest');
                        const fixedPeriodElement = document.getElementById('kprFixedPeriod');
                        
                        if (!initialRateElement || !floatingRateElement || !fixedPeriodElement) {
                            showKprError('Field floating rate tidak lengkap');
                            return;
                        }
                        
                        const initialRate = parseFloat(initialRateElement.value) / 100;
                        const floatingRate = parseFloat(floatingRateElement.value) / 100;
                        const fixedPeriod = parseInt(fixedPeriodElement.value) * 12;
                        
                        if (isNaN(initialRate) || isNaN(floatingRate) || isNaN(fixedPeriod) || 
                            initialRate < 0 || floatingRate < 0 || fixedPeriod <= 0) {
                            showKprError('Semua field floating rate harus diisi dengan benar');
                            return;
                        }
                        schedule = calculateFloatingRate(amount, initialRate, floatingRate, fixedPeriod, term);
                        break;

                    case 'capped':
                        const baseRateElement = document.getElementById('kprInitialCappedInterest');
                        const capRateElement = document.getElementById('kprMaxInterest');
                        
                        if (!baseRateElement || !capRateElement) {
                            showKprError('Field capped rate tidak lengkap');
                            return;
                        }
                        
                        const baseRate = parseFloat(baseRateElement.value) / 100;
                        const capRate = parseFloat(capRateElement.value) / 100;
                        
                        if (isNaN(baseRate) || isNaN(capRate) || baseRate < 0 || capRate < 0) {
                            showKprError('Semua field capped rate harus diisi dengan benar');
                            return;
                        }
                        schedule = calculateCappedRate(amount, baseRate, capRate, term);
                        break;

                    case 'hybrid':
                        const hybridFixedElement = document.getElementById('kprHybridFixed');
                        const hybridCappedElement = document.getElementById('kprHybridCapped');
                        const hybridFloatingElement = document.getElementById('kprHybridFloating');
                        
                        if (!hybridFixedElement || !hybridCappedElement || !hybridFloatingElement) {
                            showKprError('Field hybrid rate tidak lengkap');
                            return;
                        }
                        
                        const hybridFixed = parseFloat(hybridFixedElement.value) / 100;
                        const hybridCapped = parseFloat(hybridCappedElement.value) / 100;
                        const hybridFloating = parseFloat(hybridFloatingElement.value) / 100;
                        
                        if (isNaN(hybridFixed) || isNaN(hybridCapped) || isNaN(hybridFloating) || 
                            hybridFixed < 0 || hybridCapped < 0 || hybridFloating < 0) {
                            showKprError('Semua field hybrid rate harus diisi dengan benar');
                            return;
                        }
                        schedule = calculateHybridRate(amount, hybridFixed, hybridCapped, hybridFloating, term);
                        break;
                        
                    default:
                        showKprError('Jenis suku bunga tidak valid');
                        return;
                }

                if (schedule && schedule.length > 0) {
                    displayKPRResults(amount, schedule);
                } else {
                    showKprError('Gagal menghitung jadwal pembayaran.');
                }
            } catch (error) {
                showKprError('Terjadi kesalahan dalam perhitungan: ' + error.message);
            }
        });

        // KPR Calculator - New Form
        document.getElementById('kpr-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const amount = parseFloat(document.getElementById('kpr-amount').value);
            const term = parseInt(document.getElementById('kpr-tenor').value);
            const rateType = document.getElementById('kpr-rate-type').value;

            let monthlyPayment, totalPayment, totalInterest;
            const installments = [];

            if (rateType === 'fixed') {
                const fixedRate = parseFloat(document.getElementById('fixed-rate').value) / 100;
                if (isNaN(amount) || isNaN(fixedRate) || isNaN(term) || amount <= 0 || fixedRate < 0 || term <= 0) {
                    showKprError('Input tidak valid. Pastikan semua input telah diisi dengan benar.');
                    return;
                }
                monthlyPayment = (amount * fixedRate) / 12;
                totalPayment = monthlyPayment * term * 12;
                totalInterest = totalPayment - amount;

                // Generate installment details
                let remainingLoan = amount;
                for (let i = 1; i <= term * 12; i++) {
                    const monthlyInterest = remainingLoan * fixedRate / 12;
                    const principalPayment = monthlyPayment - monthlyInterest;
                    remainingLoan -= principalPayment;

                    installments.push({
                        month: i,
                        principal: principalPayment,
                        interest: monthlyInterest,
                        payment: monthlyPayment,
                        remaining: remainingLoan
                    });
                }
            } else if (rateType === 'floating') {
                const baseRate = parseFloat(document.getElementById('floating-base-rate').value) / 100;
                const margin = parseFloat(document.getElementById('floating-margin').value) / 100;
                const effectiveRate = baseRate + margin;
                if (isNaN(amount) || isNaN(effectiveRate) || isNaN(term) || amount <= 0 || effectiveRate < 0 || term <= 0) {
                    showKprError('Input tidak valid. Pastikan semua input telah diisi dengan benar.');
                    return;
                }
                monthlyPayment = (amount * effectiveRate) / 12;
                totalPayment = monthlyPayment * term * 12;
                totalInterest = totalPayment - amount;

                // Generate installment details
                let remainingLoan = amount;
                for (let i = 1; i <= term * 12; i++) {
                    const monthlyInterest = remainingLoan * effectiveRate / 12;
                    const principalPayment = monthlyPayment - monthlyInterest;
                    remainingLoan -= principalPayment;

                    installments.push({
                        month: i,
                        principal: principalPayment,
                        interest: monthlyInterest,
                        payment: monthlyPayment,
                        remaining: remainingLoan
                    });
                }
            } else if (rateType === 'capped') {
                const baseRate = parseFloat(document.getElementById('capped-base-rate').value) / 100;
                const capRate = parseFloat(document.getElementById('capped-cap-rate').value) / 100;
                const effectiveRate = baseRate > capRate ? capRate : baseRate;
                if (isNaN(amount) || isNaN(effectiveRate) || isNaN(term) || amount <= 0 || effectiveRate < 0 || term <= 0) {
                    showKprError('Input tidak valid. Pastikan semua input telah diisi dengan benar.');
                    return;
                }
                monthlyPayment = (amount * effectiveRate) / 12;
                totalPayment = monthlyPayment * term * 12;
                totalInterest = totalPayment - amount;

                // Generate installment details
                let remainingLoan = amount;
                for (let i = 1; i <= term * 12; i++) {
                    const monthlyInterest = remainingLoan * effectiveRate / 12;
                    const principalPayment = monthlyPayment - monthlyInterest;
                    remainingLoan -= principalPayment;

                    installments.push({
                        month: i,
                        principal: principalPayment,
                        interest: monthlyInterest,
                        payment: monthlyPayment,
                        remaining: remainingLoan
                    });
                }
            } else if (rateType === 'hybrid') {
                const fixedRate = parseFloat(document.getElementById('hybrid-fixed-rate').value) / 100;
                const fixedYears = parseInt(document.getElementById('hybrid-fixed-years').value);
                const floatingRate = parseFloat(document.getElementById('hybrid-floating-rate').value) / 100;

                // Calculate first fixedYears with fixed rate
                for (let i = 1; i <= fixedYears * 12; i++) {
                    const monthlyInterest = (amount * fixedRate) / 12;
                    const principalPayment = (amount / (term * 12));
                    const payment = monthlyPayment + monthlyInterest;

                    installments.push({
                        month: i,
                        principal: principalPayment,
                        interest: monthlyInterest,
                        payment: payment,
                        remaining: amount - (principalPayment * i)
                    });
                }

                // Calculate remaining period with floating rate
                const remainingTerm = term - fixedYears;
                if (remainingTerm > 0) {
                    const remainingAmount = amount - (principalPayment * fixedYears * 12);
                    const monthlyInterest = (remainingAmount * floatingRate) / 12;
                    monthlyPayment = (remainingAmount / (remainingTerm * 12)) + monthlyInterest;
                    totalPayment = monthlyPayment * remainingTerm * 12;
                    totalInterest = totalPayment - remainingAmount;

                    for (let i = 1; i <= remainingTerm * 12; i++) {
                        const interest = remainingAmount * floatingRate;
                        const principal = monthlyPayment - interest;
                        installments.push({
                            month: i + fixedYears * 12,
                            principal: principal,
                            interest: interest,
                            payment: monthlyPayment,
                            remaining: remainingAmount - principal
                        });

                        remainingAmount -= principal;
                        if (remainingAmount < 0) remainingAmount = 0;
                    }
                }
            }

            // Update summary
            document.getElementById('kpr-result-amount').textContent = formatCurrency(amount);
            document.getElementById('kpr-result-monthly').textContent = formatCurrency(installments[0].payment);
            document.getElementById('kpr-result-interest').textContent = formatCurrency(totalInterest);
            document.getElementById('kpr-result-total').textContent = formatCurrency(totalPayment);

            // Update schedule table
            const scheduleBody = document.getElementById('kpr-schedule');
            scheduleBody.innerHTML = '';
            installments.forEach((inst, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${formatCurrency(inst.remaining)}</td>
                    <td>${formatCurrency(inst.principal)}</td>
                    <td>${formatCurrency(inst.interest)}</td>
                    <td>${formatCurrency(inst.payment)}</td>
                `;
                scheduleBody.appendChild(row);
            });

            document.getElementById('kpr-results').classList.remove('d-none');
        });

        // KPR Calculator Event Handlers
        document.getElementById('kpr-rate-type').addEventListener('change', function() {
            document.querySelectorAll('.rate-fields').forEach(field => field.classList.add('d-none'));
            const selectedType = this.value;
            if (selectedType) {
                document.getElementById(`${selectedType}-rate-fields`).classList.remove('d-none');
            }
        });

        // Add event listener untuk menampilkan/menyembunyikan field sesuai jenis bunga
        document.getElementById('kprInterestType').addEventListener('change', function() {
            // Clear any existing errors
            hideKprError();
            
            // Sembunyikan semua fields
            document.getElementById('fixedRateFields').style.display = 'none';
            document.getElementById('floatingRateFields').style.display = 'none';
            document.getElementById('cappedRateFields').style.display = 'none';
            document.getElementById('hybridRateFields').style.display = 'none';
            
            // Reset required attributes
            const allInputs = document.querySelectorAll('#fixedRateFields input, #floatingRateFields input, #cappedRateFields input, #hybridRateFields input');
            allInputs.forEach(input => input.removeAttribute('required'));

            // Tampilkan field sesuai pilihan dan set required
            switch (this.value) {
                case 'fixed':
                    document.getElementById('fixedRateFields').style.display = 'block';
                    document.getElementById('kprFixedInterest').setAttribute('required', 'required');
                    break;
                case 'floating':
                    document.getElementById('floatingRateFields').style.display = 'block';
                    document.getElementById('kprInitialInterest').setAttribute('required', 'required');
                    document.getElementById('kprFloatingInterest').setAttribute('required', 'required');
                    document.getElementById('kprFixedPeriod').setAttribute('required', 'required');
                    break;
                case 'capped':
                    document.getElementById('cappedRateFields').style.display = 'block';
                    document.getElementById('kprInitialCappedInterest').setAttribute('required', 'required');
                    document.getElementById('kprMaxInterest').setAttribute('required', 'required');
                    break;
                case 'hybrid':
                    document.getElementById('hybridRateFields').style.display = 'block';
                    document.getElementById('kprHybridFixed').setAttribute('required', 'required');
                    document.getElementById('kprHybridCapped').setAttribute('required', 'required');
                    document.getElementById('kprHybridFloating').setAttribute('required', 'required');
                    break;
            }
        });

        // Trigger the initial field display for KPR
        document.getElementById('kprInterestType').dispatchEvent(new Event('change'));

        // Helper function to format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Helper function to format currency with detail
        function formatCurrencyDetail(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Show KPR error message
        function showKprError(message) {
            let errorDiv = document.getElementById('kprError');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'kprError';
                errorDiv.className = 'alert alert-danger mt-3';
                document.getElementById('kprForm').appendChild(errorDiv);
            }
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        // Hide KPR error message
        function hideKprError() {
            const errorDiv = document.getElementById('kprError');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
        }

        // KPR Results Display Function
        function displayKPRResults(principal, schedule) {
            if (!schedule || schedule.length === 0) {
                showKprError('Tidak ada data perhitungan yang dapat ditampilkan.');
                return;
            }

            const totalPayments = schedule.reduce((sum, payment) => sum + payment.payment, 0);
            const totalInterest = totalPayments - principal;

            // Update summary
            document.getElementById('kprTotalLoan').textContent = formatCurrency(principal);
            document.getElementById('kprMonthlyPayment').textContent = formatCurrency(schedule[0].payment);
            document.getElementById('kprTotalPayment').textContent = formatCurrency(totalPayments);
            document.getElementById('kprTotalInterest').textContent = formatCurrency(totalInterest);

            // Update schedule table
            const scheduleBody = document.getElementById('kprInstallmentDetails');
            scheduleBody.innerHTML = '';
            
            schedule.forEach((payment, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${payment.year}</td>
                    <td>${payment.month}</td>
                    <td>${payment.rate.toFixed(2)}%</td>
                    <td>${formatCurrency(payment.principal)}</td>
                    <td>${formatCurrency(payment.interest)}</td>
                    <td>${formatCurrency(payment.payment)}</td>
                    <td>${formatCurrency(payment.remainingPrincipal)}</td>
                `;
                scheduleBody.appendChild(row);
            });

            // Show results
            document.getElementById('kprResults').style.display = 'block';
        }

        // KPR Calculator Functions
        function calculateFixedRate(principal, annualRate, term) {
            let schedule = [];
            let remainingPrincipal = principal;
            
            // Formula anuitas: PMT = P * [r(1+r)^n] / [(1+r)^n - 1]
            const monthlyRate = annualRate / 12;
            let monthlyPayment;
            
            if (monthlyRate <= 0) {
                // If no interest, simple division
                monthlyPayment = principal / term;
            } else {
                monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, term)) / (Math.pow(1 + monthlyRate, term) - 1);
                // Check for NaN or Infinity
                if (isNaN(monthlyPayment) || !isFinite(monthlyPayment)) {
                    monthlyPayment = principal / term; // Fallback
                }
            }

            for (let month = 1; month <= term; month++) {
                const monthlyInterest = remainingPrincipal * monthlyRate;
                const monthlyPrincipal = Math.max(0, monthlyPayment - monthlyInterest);
                remainingPrincipal = Math.max(0, remainingPrincipal - monthlyPrincipal);

                schedule.push({
                    year: Math.ceil(month / 12),
                    month: month,
                    rate: annualRate * 100,
                    principal: monthlyPrincipal,
                    interest: monthlyInterest,
                    payment: monthlyPayment,
                    remainingPrincipal: remainingPrincipal
                });
            }

            return schedule;
        }

        function calculateFloatingRate(principal, initialRate, floatingRate, fixedPeriod, term) {
            let schedule = [];
            let remainingPrincipal = principal;

            for (let month = 1; month <= term; month++) {
                // Tentukan suku bunga berdasarkan periode
                const currentRate = month <= fixedPeriod ? initialRate : floatingRate;
                const monthlyRate = currentRate / 12;

                // Hitung cicilan bulanan berdasarkan sisa saldo dan suku bunga saat ini
                const remainingTerm = term - month + 1;
                let monthlyPayment;
                
                if (remainingTerm <= 0 || remainingPrincipal <= 0 || monthlyRate <= 0) {
                    monthlyPayment = 0;
                } else {
                    monthlyPayment = remainingPrincipal * (monthlyRate * Math.pow(1 + monthlyRate, remainingTerm)) / (Math.pow(1 + monthlyRate, remainingTerm) - 1);
                    // Check for NaN or Infinity
                    if (isNaN(monthlyPayment) || !isFinite(monthlyPayment)) {
                        monthlyPayment = remainingPrincipal / remainingTerm; // Fallback to simple division
                    }
                }
                
                const monthlyInterest = remainingPrincipal * monthlyRate;
                const monthlyPrincipal = Math.max(0, monthlyPayment - monthlyInterest);
                
                remainingPrincipal = Math.max(0, remainingPrincipal - monthlyPrincipal);

                schedule.push({
                    year: Math.ceil(month / 12),
                    month: month,
                    rate: currentRate * 100,
                    principal: monthlyPrincipal,
                    interest: monthlyInterest,
                    payment: monthlyPayment,
                    remainingPrincipal: remainingPrincipal
                });
            }

            return schedule;
        }

        function calculateCappedRate(principal, baseRate, capRate, term) {
            let schedule = [];
            let remainingPrincipal = principal;

            for (let month = 1; month <= term; month++) {
                // Simulasi market rate yang naik seiring waktu, tapi dibatasi oleh cap rate
                let marketRate = baseRate + (month > 12 ? 0.005 : 0); // Naik 0.5% setelah tahun pertama
                let effectiveRate = Math.min(marketRate, capRate);
                const monthlyRate = effectiveRate / 12;

                // Hitung cicilan bulanan berdasarkan sisa saldo dan suku bunga efektif
                const remainingTerm = term - month + 1;
                let monthlyPayment;
                
                if (remainingTerm <= 0 || remainingPrincipal <= 0 || monthlyRate <= 0) {
                    monthlyPayment = 0;
                } else {
                    monthlyPayment = remainingPrincipal * (monthlyRate * Math.pow(1 + monthlyRate, remainingTerm)) / (Math.pow(1 + monthlyRate, remainingTerm) - 1);
                    // Check for NaN or Infinity
                    if (isNaN(monthlyPayment) || !isFinite(monthlyPayment)) {
                        monthlyPayment = remainingPrincipal / remainingTerm; // Fallback
                    }
                }
                
                const monthlyInterest = remainingPrincipal * monthlyRate;
                const monthlyPrincipal = Math.max(0, monthlyPayment - monthlyInterest);
                
                remainingPrincipal = Math.max(0, remainingPrincipal - monthlyPrincipal);

                schedule.push({
                    year: Math.ceil(month / 12),
                    month: month,
                    rate: effectiveRate * 100,
                    principal: monthlyPrincipal,
                    interest: monthlyInterest,
                    payment: monthlyPayment,
                    remainingPrincipal: remainingPrincipal
                });
            }

            return schedule;
        }

        function calculateHybridRate(principal, fixedRate, cappedRate, floatingRate, term) {
            let schedule = [];
            let remainingPrincipal = principal;

            // Periode untuk masing-masing jenis bunga (dalam bulan)
            const fixedPeriod = 36; // 3 tahun fixed
            const cappedPeriod = 24; // 2 tahun setelah fixed (tahun 4-5)

            for (let month = 1; month <= term; month++) {
                let currentRate;

                if (month <= fixedPeriod) {
                    // 3 tahun pertama gunakan fixed rate
                    currentRate = fixedRate;
                } else if (month <= fixedPeriod + cappedPeriod) {
                    // 2 tahun berikutnya (tahun 4-5) gunakan capped rate
                    // Simulasi market rate yang naik tapi dibatasi oleh cap
                    let marketRate = floatingRate + 0.01; // Simulasi market rate lebih tinggi
                    currentRate = Math.min(marketRate, cappedRate);
                } else {
                    // Sisa periode (tahun 6+) gunakan floating rate penuh
                    currentRate = floatingRate;
                }

                const monthlyRate = currentRate / 12;
                
                // Hitung cicilan bulanan berdasarkan sisa saldo dan suku bunga saat ini
                const remainingTerm = term - month + 1;
                let monthlyPayment;
                
                if (remainingTerm <= 0 || remainingPrincipal <= 0 || monthlyRate <= 0) {
                    monthlyPayment = 0;
                } else {
                    monthlyPayment = remainingPrincipal * (monthlyRate * Math.pow(1 + monthlyRate, remainingTerm)) / (Math.pow(1 + monthlyRate, remainingTerm) - 1);
                    // Check for NaN or Infinity
                    if (isNaN(monthlyPayment) || !isFinite(monthlyPayment)) {
                        monthlyPayment = remainingPrincipal / remainingTerm; // Fallback
                    }
                }
                
                const monthlyInterest = remainingPrincipal * monthlyRate;
                const monthlyPrincipal = Math.max(0, monthlyPayment - monthlyInterest);
                
                remainingPrincipal = Math.max(0, remainingPrincipal - monthlyPrincipal);

                schedule.push({
                    year: Math.ceil(month / 12),
                    month: month,
                    rate: currentRate * 100,
                    principal: monthlyPrincipal,
                    interest: monthlyInterest,
                    payment: monthlyPayment,
                    remainingPrincipal: remainingPrincipal
                });
            }

            return schedule;
        }

        function formatCurrencyDetail(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }
    });
</script>
<?= $this->endSection(); ?>