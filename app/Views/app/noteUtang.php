<?= $this->extend('layout/users/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <?php $hasStarted = !empty(get_cookie('debt_note_started')); ?>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-5" style="display: <?= $hasStarted ? 'none' : 'block' ?>">
        <img src="<?= base_url('assets/images/empty.gif') ?>" alt="Empty State" class="mb-4" style="max-width: 200px;">
        <h5 class="mb-3">Belum Ada Catatan Utang</h5>
        <p class="text-muted mb-4">Buat catatan hutang anda jika anda memiliki hutang, FinanceFlow berharap untuk tidak berhutang dan kembali lagi</p>
        <button class="btn btn-success px-4" onclick="showDebtForm()">Buat Note Utang</button>
    </div>

    <!-- Debt Form -->
    <div id="debtForm" class="card" style="display: <?= $hasStarted ? 'block' : 'none' ?>; max-width: 600px; margin: auto;">
        <div class="card-body">
            <h5 class="card-title mb-4">Buat Catatan Utang</h5>
            <!-- Main Type Selection -->
            <div class="btn-group w-100 mb-4" role="group">
                <input type="radio" class="btn-check" name="mainType" id="borrowing" autocomplete="off" value="borrowing">
                <label class="btn btn-outline-success" for="borrowing" onclick="handleMainTypeSelect('borrowing')">Pinjam</label>

                <input type="radio" class="btn-check" name="mainType" id="lending" autocomplete="off" value="lending">
                <label class="btn btn-outline-success" for="lending" onclick="handleMainTypeSelect('lending')">Meminjamkan</label>
            </div>

            <!-- Forms Container -->
            <div id="formsContainer"> <!-- Sub Type Selection for Borrowing -->
                <div id="borrowingTypeSelection" class="btn-group w-100 mb-4" style="display: none;" role="group">
                    <input type="radio" class="btn-check" name="borrowType" id="online" autocomplete="off" value="online">
                    <label class="btn btn-outline-primary" for="online" onclick="handleBorrowTypeSelect('online')">Pinjam Online</label>

                    <input type="radio" class="btn-check" name="borrowType" id="offline" autocomplete="off" value="offline">
                    <label class="btn btn-outline-primary" for="offline" onclick="handleBorrowTypeSelect('offline')">Pinjam Offline</label>
                </div>

                <!-- Online Borrowing Form -->
                <form id="onlineDebtForm" class="needs-validation" novalidate style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Aplikasi</label>
                        <select class="form-select" name="application" required>
                            <option value="">Pilih Aplikasi</option>
                            <option value="Spinjam">Spinjam</option>
                            <option value="KreditPintar">KreditPintar</option>
                            <option value="GoPay Pinjam">GoPay Pinjam</option>
                            <option value="AkuLaku">AkuLaku</option>
                            <option value="Kredivo">Kredivo</option>
                            <option value="PinjamanGo">PinjamanGo</option>
                            <option value="Tunaiku">Tunaiku</option>
                            <option value="DanaRupiah">DanaRupiah</option>
                            <option value="Indodana">Indodana</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pembayaran Perbulan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="payment_amount" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi Pinjaman (dalam bulan)</label>
                        <input type="number" class="form-control" name="loan_duration" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai Pinjam</label>
                        <input type="date" class="form-control" name="loan_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </form>

                <!-- Offline Borrowing Form -->
                <form id="offlineDebtForm" class="needs-validation" novalidate style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Kategori Pemberi Pinjaman</label>
                        <select class="form-select" name="lender_category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="teman">Teman</option>
                            <option value="orang_tua">Orang Tua</option>
                            <option value="saudara">Saudara</option>
                            <option value="kekasih">Kekasih</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pemberi Pinjaman</label>
                        <input type="text" class="form-control" name="lender_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Pinjaman</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="loan_amount" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </form>

                <!-- Lending Form -->
                <form id="lendingForm" class="needs-validation" novalidate style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" name="borrower_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Dipinjamkan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="loan_amount" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sudah Dibayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="amount_paid" value="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" name="loan_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>

            <div class="text-end mt-4">
                <button type="button" class="btn btn-secondary me-2" onclick="hideDebtForm()">Batal</button>
                <button type="button" class="btn btn-success" id="submitButton">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- History Section -->
<div id="historySection" class="container py-4" style="max-width: 650px; display: <?= $hasStarted ? 'block' : 'none' ?>;">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Utang</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="filterDebts('all')">Semua</button>
                <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterDebts('active')">Aktif</button>
                <button type="button" class="btn btn-success btn-sm" onclick="filterDebts('paid')">Lunas</button>
            </div>
        </div>
        <div class="card-body">
            <div id="debtHistoryCards" class="row g-3">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    function resetForms() {
        // Hide all forms first
        document.getElementById('borrowingTypeSelection').style.display = 'none';
        document.getElementById('onlineDebtForm').style.display = 'none';
        document.getElementById('offlineDebtForm').style.display = 'none';
        document.getElementById('lendingForm').style.display = 'none';

        // Reset all forms
        document.querySelectorAll('form').forEach(form => form.reset());

        // Remove any validation classes
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    function handleMainTypeSelect(type) {
        console.log('Main type selected:', type);

        // Set radio button checked
        const radio = document.getElementById(type);
        radio.checked = true;

        // Reset and show appropriate form
        resetForms();
        if (type === 'borrowing') {
            document.getElementById('borrowingTypeSelection').style.display = 'flex';
        } else if (type === 'lending') {
            document.getElementById('lendingForm').style.display = 'block';
        }
    }

    function handleBorrowTypeSelect(type) {
        console.log('Borrow type selected:', type);

        // Set radio button checked
        const radio = document.getElementById(type);
        radio.checked = true;

        // Show appropriate form
        document.getElementById('onlineDebtForm').style.display = 'none';
        document.getElementById('offlineDebtForm').style.display = 'none';

        if (type === 'online') {
            document.getElementById('onlineDebtForm').style.display = 'block';
        } else if (type === 'offline') {
            document.getElementById('offlineDebtForm').style.display = 'block';
        }
    }

    function calculateRemainingMonths(loanDate, duration, paymentAmount = null, totalPaid = 0) {
        if (!loanDate || !duration) {
            return `<div class="alert alert-warning mb-0">
                <small>Tanggal atau durasi tidak valid</small>
            </div>`;
        }

        try {
            const [year, month, day] = loanDate.split('-').map(num => parseInt(num));
            const startDate = new Date(year, month - 1, day);
            const currentDate = new Date();
            const endDate = new Date(startDate);
            endDate.setMonth(startDate.getMonth() + parseInt(duration));

            const totalMonths = parseInt(duration);
            const elapsedMonths = Math.floor(
                (currentDate - startDate) / (1000 * 60 * 60 * 24 * 30.44)
            );
            const remainingMonths = Math.max(0, totalMonths - elapsedMonths);

            // Calculate payment progress if payment amount is available
            let progressHtml = '';
            if (paymentAmount && totalPaid) {
                const totalAmount = paymentAmount * totalMonths;
                const progress = (totalPaid / totalAmount) * 100;
                const paidMonths = Math.floor(totalPaid / paymentAmount);

                progressHtml = `
                    <div class="progress mb-2" style="height: 5px">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: ${progress}%" 
                             aria-valuenow="${progress}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted d-block mb-2">
                        Pembayaran: ${paidMonths} dari ${totalMonths} bulan
                    </small>`;
            }

            if (remainingMonths === 0) {
                return `
                    ${progressHtml}
                    <div class="alert alert-success mb-0">
                        <small>Status: Sudah jatuh tempo</small>
                    </div>`;
            } else {
                const formatDate = date => date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                return `
                    ${progressHtml}
                    <div class="alert ${remainingMonths <= 1 ? 'alert-warning' : 'alert-info'} mb-0">
                        <small>Sisa Waktu: ${remainingMonths} bulan lagi</small>
                        <br>
                        <small>Jatuh Tempo: ${formatDate(endDate)}</small>
                    </div>`;
            }
        } catch (e) {
            console.error('Error calculating months:', e);
            return `<div class="alert alert-warning mb-0">
                <small>Tidak dapat menghitung sisa waktu</small>
            </div>`;
        }
    }

    function isLastMonth(loanDate, duration) {
        const startDate = new Date(loanDate);
        const now = new Date();
        const monthsDiff = (now.getFullYear() - startDate.getFullYear()) * 12 +
            (now.getMonth() - startDate.getMonth());
        return monthsDiff >= duration - 1;
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Setup form submission
        const submitBtn = document.getElementById('submitButton');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Check main type selection
                const mainType = document.querySelector('input[name="mainType"]:checked');
                console.log('Selected main type:', mainType ? mainType.value : 'none');

                if (!mainType) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan pilih jenis transaksi terlebih dahulu'
                    });
                    return;
                }

                let activeForm;
                const formData = {
                    status: 'active',
                    type: mainType.value
                };

                // Handle different form types
                if (mainType.value === 'borrowing') {
                    const borrowType = document.querySelector('input[name="borrowType"]:checked');
                    console.log('Selected borrow type:', borrowType ? borrowType.value : 'none');

                    if (!borrowType) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Silakan pilih jenis pinjaman terlebih dahulu'
                        });
                        return;
                    }

                    activeForm = document.getElementById(borrowType.value + 'DebtForm');
                    formData.borrowType = borrowType.value;

                    if (borrowType.value === 'online') {
                        const appSelect = activeForm.querySelector('select[name="application"]');
                        if (!appSelect.value) {
                            appSelect.classList.add('is-invalid');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian',
                                text: 'Silakan pilih aplikasi pinjaman'
                            });
                            return;
                        }
                        const payment_amount = activeForm.querySelector('input[name="payment_amount"]').value;
                        const loan_duration = activeForm.querySelector('input[name="loan_duration"]').value;
                        Object.assign(formData, {
                            application: appSelect.value,
                            loan_amount: payment_amount * loan_duration,
                            payment_amount: payment_amount,
                            loan_duration: loan_duration,
                            loan_date: activeForm.querySelector('input[name="loan_date"]').value,
                            due_date: activeForm.querySelector('input[name="due_date"]').value,
                            description: activeForm.querySelector('textarea[name="description"]').value || ''
                        });
                    } else {
                        const lenderCategory = activeForm.querySelector('select[name="lender_category"]').value;
                        const lenderName = activeForm.querySelector('input[name="lender_name"]').value;

                        if (!lenderCategory || !lenderName) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian',
                                text: 'Silakan lengkapi data pemberi pinjaman'
                            });
                            return;
                        }

                        Object.assign(formData, {
                            lender_category: lenderCategory,
                            lender_name: lenderName,
                            loan_amount: activeForm.querySelector('input[name="loan_amount"]').value,
                            description: activeForm.querySelector('textarea[name="description"]').value || ''
                        });
                    }
                } else {
                    activeForm = document.getElementById('lendingForm');
                    Object.assign(formData, {
                        borrower_name: activeForm.querySelector('input[name="borrower_name"]').value,
                        loan_amount: activeForm.querySelector('input[name="loan_amount"]').value,
                        amount_paid: activeForm.querySelector('input[name="amount_paid"]').value,
                        loan_date: activeForm.querySelector('input[name="loan_date"]').value,
                        description: activeForm.querySelector('textarea[name="description"]').value || ''
                    });
                }

                // Validate required fields
                const requiredFields = activeForm.querySelectorAll('[required]');
                let isValid = true;
                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan lengkapi semua field yang diperlukan'
                    });
                    return;
                }

                console.log('Sending data:', formData);

                // Send the data
                fetch('<?= base_url('app/note-utang/save') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 1500
                            }).then(() => {
                                resetForms();
                                document.getElementById('debtForm').style.display = 'none';
                                document.getElementById('historySection').style.display = 'block';
                                loadDebtHistory();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Terjadi kesalahan saat menyimpan data'
                        });
                    });
            });
        }

        // Only load initial debt history if history section is visible
        const historySection = document.getElementById('historySection');
        if (historySection.style.display !== 'none') {
            loadDebtHistory();
        }
    });

    function showDebtForm() {
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('debtForm').style.display = 'block';
        document.getElementById('historySection').style.display = 'block';

        // Set cookie to remember that user has started using the debt note feature
        document.cookie = 'debt_note_started=true; path=/; max-age=31536000';
    }

    function hideDebtForm() {
        resetForms();
        document.getElementById('debtForm').style.display = 'none';

        // Check if we have any debt cards
        const historyCards = document.querySelector('#debtHistoryCards');
        if (historyCards && historyCards.children.length > 0) {
            // If we have debt records, show the history section
            document.getElementById('historySection').style.display = 'block';
        } else {
            // If no debt records, hide history section and show empty state
            document.getElementById('historySection').style.display = 'none';
            document.getElementById('emptyState').style.display = 'block';
        }
    }

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function loadDebtHistory(filter = 'all') {
        console.log('Loading debt history with filter:', filter);

        // Check if the history section should be hidden (only load content if visible)
        const historySection = document.getElementById('historySection');
        if (historySection.style.display === 'none') {
            return; // Don't load history if the section is hidden
        }

        const historyContainer = document.getElementById('debtHistoryCards');
        if (!historyContainer) {
            console.error('Could not find debtHistoryCards container');
            return;
        }
        fetch('<?= base_url('app/note-utang/list') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // First try to parse as JSON
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (!data) {
                    throw new Error('No data received from server');
                }

                // Handle if data is directly an array or wrapped in an object
                const debts = Array.isArray(data) ? data :
                    (data.debts ? data.debts :
                        (data.data ? data.data : []));

                console.log('Processed debts:', debts);
                historyContainer.innerHTML = '';
                if (debts.length === 0) {
                    console.log('No debts found');
                    const debtForm = document.getElementById('debtForm');
                    // Only show the empty state if both the form and history section are hidden
                    if (debtForm.style.display === 'none') {
                        document.getElementById('emptyState').style.display = 'block';
                        document.getElementById('historySection').style.display = 'none';
                    }
                    return;
                }

                document.getElementById('emptyState').style.display = 'none';
                debts.forEach(debt => {
                    const card = document.createElement('div');
                    card.className = 'col-12 mb-3';

                    let status = debt.status === 'active' ?
                        '<span class="badge bg-warning text-dark">Aktif</span>' :
                        '<span class="badge bg-success">Lunas</span>';

                    let detailsHtml = '';
                    if (debt.type === 'borrowing') {
                        if (debt.borrowType === 'online') {
                            detailsHtml = `
                            <div class="mb-3">
                                <small class="text-muted">Aplikasi</small>
                                <h6 class="mb-0">${debt.application}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Total Pinjaman</small>
                                <h5 class="mb-0 text-success">${formatRupiah(debt.payment_amount * debt.loan_duration)}</h5>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Cicilan per Bulan</small>
                                    <h6 class="mb-0">${formatRupiah(debt.payment_amount)}</h6>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Durasi Pinjaman</small>
                                    <h6 class="mb-0">${debt.loan_duration} bulan</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Tanggal Pinjam</small>
                                    <h6 class="mb-0">${formatDate(debt.loan_date)}</h6>
                                </div>
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Jatuh Tempo Setiap</small>
                                    <h6 class="mb-0">Tanggal ${new Date(debt.due_date).getDate()}</h6>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Total Dibayar</small>
                                <h6 class="mb-0 text-primary">${formatRupiah(debt.amount_paid || 0)}</h6>
                            </div>
                            <div class="alert ${debt.payments_count >= debt.loan_duration ? 'alert-success' : 'alert-info'} mb-3">
                                <small>Status Pembayaran: ${debt.payments_count || 0} dari ${debt.loan_duration} bulan</small>
                            </div>`;
                        } else {
                            detailsHtml = `
                            <div class="mb-3">
                                <small class="text-muted">Kategori</small>
                                <h6 class="mb-0">${debt.lender_category ? debt.lender_category.charAt(0).toUpperCase() + debt.lender_category.slice(1).replace('_', ' ') : '-'}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Pemberi Pinjaman</small>
                                <h6 class="mb-0">${debt.lender_name}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Total Pinjaman</small>
                                <h5 class="mb-0 text-success">${formatRupiah(debt.loan_amount)}</h5>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Sudah Dibayar</small>
                                <h6 class="mb-0 text-primary">${formatRupiah(debt.amount_paid || 0)}</h6>
                            </div>`;
                        }
                    } else {
                        detailsHtml = `
                        <div class="mb-3">
                            <small class="text-muted">Peminjam</small>
                            <h6 class="mb-0">${debt.borrower_name}</h6>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Pinjaman</small>
                            <h5 class="mb-0 text-success">${formatRupiah(debt.loan_amount)}</h5>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <small class="text-muted">Sudah Dibayar</small>
                                <h6 class="mb-0">${formatRupiah(debt.amount_paid)}</h6>
                            </div>
                            <div class="col-6 mb-3">
                                <small class="text-muted">Tanggal Pinjam</small>
                                <h6 class="mb-0">${debt.loan_date}</h6>
                            </div>
                        </div>`;
                    }

                    card.innerHTML = `
                    <div class="card h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                ${debt.type === 'borrowing' ? 
                                    (debt.borrowType === 'online' ? 'Pinjaman Online' : 'Pinjaman Offline') : 
                                    'Meminjamkan'}
                            </h6>
                            ${status}
                        </div>
                        <div class="card-body">
                            ${detailsHtml}
                            ${debt.description ? `
                            <div class="mt-3">
                                <small class="text-muted">Deskripsi:</small>
                                <p class="mb-0">${debt.description}</p>
                            </div>` : ''}
                        </div>
                        <div class="card-footer bg-white">
                            <div class="btn-group w-100">                                ${debt.status === 'active' ? `                                    ${debt.type === 'borrowing' ?
                                        (debt.borrowType === 'online' ?                                        `<button class="btn btn-success btn-sm" onclick="handleMonthlyPayment(${debt.id}, ${debt.payments_count || 0}, ${debt.loan_duration}, ${debt.payment_amount}, ${debt.amount_paid || 0})">
                                            Sudah Bayar Bulan Ini
                                        </button>`:
                                        `<button class="btn btn-success btn-sm" onclick="handlePaymentUpdate(${debt.id}, ${debt.amount_paid || 0}, ${debt.loan_amount})">
                                            Update Pembayaran
                                        </button>`) :
                                        `<button class="btn btn-success btn-sm" onclick="handlePaymentUpdate(${debt.id}, ${debt.amount_paid || 0}, ${debt.loan_amount})">
                                            Update Pembayaran
                                        </button>`
                                    }
                                    <button class="btn btn-danger btn-sm" onclick="deleteDebt(${debt.id})">Hapus</button>
                                ` : `
                                    <button class="btn btn-danger btn-sm" onclick="deleteDebt(${debt.id})">Hapus</button>`}
                            </div>
                        </div>
                    </div>`;

                    historyContainer.appendChild(card);
                });
            })
            .catch(error => {
                console.error('Error loading debt history:', error);
                const historyContainer = document.getElementById('debtHistoryCards');
                if (historyContainer) {
                    historyContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <p>Error loading debt history: ${error.message}</p>
                            <small>Please try refreshing the page</small>
                        </div>`;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memuat riwayat utang: ' + error.message
                });
            });
    } // No longer using markAsPaid function - removed in favor of handlePaymentUpdate

    // Function to delete debt
    function deleteDebt(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data utang ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('app/note-utang/delete') ?>/' + id, {
                        method: 'POST', // Changed from DELETE to POST to match the route definition
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        console.log('Delete response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete response:', data);
                        if (data.success || data.status) { // Check for both success and status
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Data utang berhasil dihapus!'
                            }).then(() => {
                                loadDebtHistory();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to delete debt');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting debt:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus data: ' + error.message
                        });
                        // Reload debt history to ensure UI is in sync with data
                        loadDebtHistory();
                    });
            }
        });
    }

    function filterDebts(filter) {
        console.log('Filtering debts:', filter);

        // Make sure history section is visible when filtering
        document.getElementById('historySection').style.display = 'block';

        loadDebtHistory(filter);

        // Update button states
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase().includes(filter.toLowerCase())) {
                btn.classList.add('active');
            }
        });
    }

    function handlePaymentUpdate(debtId, currentAmount, totalAmount) {
        const remaining = totalAmount - currentAmount;
        Swal.fire({
            title: 'Tambah Pembayaran',
            html: `
                <div class="text-start mb-3">
                    <div class="mb-2">
                        <small class="text-muted">Total Pinjaman:</small><br>
                        <span class="text-success h5">Rp${new Intl.NumberFormat('id-ID').format(totalAmount)}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Sudah Dibayar:</small><br>
                        <span class="text-primary h5">Rp${new Intl.NumberFormat('id-ID').format(currentAmount)}</span>
                    </div>
                    <div>
                        <small class="text-muted">Sisa Pembayaran:</small><br>
                        <span class="text-danger h5">Rp${new Intl.NumberFormat('id-ID').format(remaining)}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="paymentAmount" class="form-label">Masukkan Jumlah Pembayaran</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="paymentAmount" class="form-control" placeholder="0" max="${remaining}">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            confirmButtonColor: '#198754',
            cancelButtonText: 'Batal',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            showCloseButton: true,
            focusConfirm: false,
            preConfirm: () => {
                const paymentInput = document.getElementById('paymentAmount');
                const payment = parseFloat(paymentInput.value);
                const remaining = totalAmount - currentAmount;

                if (!payment || payment <= 0) {
                    Swal.showValidationMessage('Masukkan jumlah pembayaran yang valid');
                    return false;
                }
                if (payment > remaining) {
                    Swal.showValidationMessage('Jumlah melebihi sisa pembayaran');
                    paymentInput.value = remaining; // Set to maximum allowed
                    return false;
                }
                return payment;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim data ke server
                fetch('<?= base_url('app/note-utang/update-payment') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            debt_id: debtId,
                            payment_amount: result.value
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') { // Show success message with payment details
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil Ditambahkan',
                                html: `
                                <div class="text-start">
                                    <div class="mb-2">
                                        <small class="text-muted">Pembayaran Baru:</small><br>
                                        <span class="text-success h5">+ Rp${new Intl.NumberFormat('id-ID').format(result.value)}</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Total Pembayaran:</small><br>
                                        <span class="text-primary h5">Rp${new Intl.NumberFormat('id-ID').format(data.data.amount_paid)}</span>
                                    </div>
                                    <div>
                                        <small class="text-muted">Sisa Pembayaran:</small><br>
                                        <span class="text-${data.data.remaining > 0 ? 'danger' : 'success'} h5">Rp${new Intl.NumberFormat('id-ID').format(data.data.remaining)}</span>
                                    </div>
                                </div>
                            `,
                                timer: 3000,
                                timerProgressBar: true
                            }).then(() => {
                                loadDebtHistory(); // Refresh tampilan riwayat
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memproses Pembayaran',
                            text: error.message,
                            confirmButtonColor: '#dc3545'
                        });
                    });
            }
        });
    }

    function handleMonthlyPayment(debtId, currentPayments, totalDuration, paymentAmount, currentAmountPaid) {
        const remainingPayments = totalDuration - currentPayments;
        const newAmountPaid = currentAmountPaid + paymentAmount;

        Swal.fire({
            title: 'Konfirmasi Pembayaran Bulanan',
            html: `
                <div class="text-start mb-3">
                    <div class="mb-2">
                        <small class="text-muted">Status Pembayaran:</small><br>
                        <span class="text-success h5">${currentPayments} dari ${totalDuration} bulan</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Jumlah Pembayaran:</small><br>
                        <span class="text-success h5">Rp ${formatCurrency(paymentAmount)}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Total Terbayar Setelah Update:</small><br>
                        <span class="text-primary h5">Rp ${formatCurrency(newAmountPaid)}</span>
                    </div>
                    <div>
                        <small class="text-muted">Sisa Pembayaran:</small><br>
                        <span class="text-${remainingPayments > 0 ? 'warning' : 'success'} h5">${remainingPayments} bulan lagi</span>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Sudah Bayar',
            confirmButtonColor: '#198754',
            cancelButtonText: 'Batal',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                const postData = {
                    debt_id: debtId,
                    current_payments: currentPayments
                };

                console.log('Sending payment update:', postData);

                fetch('<?= base_url('app/note-utang/update-monthly-payment') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(postData)
                    })
                    .then(async response => {
                        console.log('Response status:', response.status);
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || 'Gagal memperbarui pembayaran');
                        }
                        return data;
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.status === 'success') {
                            const newPaymentsCount = data.data.payments_count;
                            const isComplete = data.data.is_completed;
                            Swal.fire({
                                icon: 'success',
                                title: isComplete ? 'Pembayaran Selesai!' : 'Pembayaran Berhasil',
                                html: `
                                <div class="text-start">
                                    <div class="mb-2">
                                        <small class="text-muted">Status Pembayaran:</small><br>
                                        <span class="text-success h5">${newPaymentsCount} dari ${totalDuration} bulan</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Total Dibayar:</small><br>
                                        <span class="text-success h5">Rp ${formatCurrency(data.data.amount_paid)}</span>
                                    </div>
                                    ${!isComplete ? `
                                    <div>
                                        <small class="text-muted">Sisa Pembayaran:</small><br>
                                        <span class="text-warning h5">${totalDuration - newPaymentsCount} bulan lagi</span>
                                    </div>` : ''}
                                </div>
                            `,
                                timer: isComplete ? 3000 : 2000,
                                timerProgressBar: true
                            }).then(() => {
                                loadDebtHistory();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memproses Pembayaran',
                            text: error.message || 'Terjadi kesalahan saat memproses pembayaran',
                            confirmButtonColor: '#dc3545'
                        });
                    });
            }
        });
    }
    // Function to format currency values
    function formatCurrency(amount) {
        // Use the existing formatRupiah function for consistency, but remove the 'Rp' prefix as it's added in the view
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
</script>
<?= $this->endSection(); ?>