<?= $this->extend('layout/users/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-4" style="max-width: 480px;">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Target Nabung</h5>
                <div class="d-flex align-items-center gap-2">
                    <div style="position:relative; display:inline-flex; align-items:center; margin-left:16px;">
                        <i class="ri-calendar-line" style="font-size:20px; padding:6px 8px; border:1.5px solid #bbb; border-radius:8px;"></i>
                        <input type="month" id="monthPicker" value="<?= date('Y-m') ?>" style="opacity:0; position:absolute; left:0; top:0; width:100%; height:100%; cursor:pointer; z-index:2;">
                    </div>
                    <span id="selectedMonthText" style="font-size: 15px; font-weight:500;"></span>
                    <button id="targetActionBtn" class="btn btn-sm btn-outline-primary">
                        <i class="ri-settings-4-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Calendar Header -->
            <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                <div class="target-info">
                    <small class="text-muted d-block">Target Harian</small>
                    <strong id="dailyTargetDisplay">Rp 0</strong>
                </div>
                <div class="status-info text-end">
                    <small class="text-muted d-block">Status Keseluruhan</small>
                    <strong id="monthlyStatus" class="text-success">0% Tercapai</strong>
                </div>
            </div>

            <!-- Calendar Days Header -->
            <div class="calendar-grid">
                <div class="calendar-days-header">
                    <?php foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day): ?>
                        <div class="day-name"><?= $day ?></div>
                    <?php endforeach; ?>
                </div>

                <!-- Calendar Grid -->
                <div id="calendarGrid" class="calendar-days">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 text-center">
                <div class="btn-group">
                    <button class="btn btn-outline-success" onclick="markToday('done')">
                        <i class="ri-checkbox-circle-line me-1"></i>
                        Sudah Nabung
                    </button>
                    <button class="btn btn-outline-danger" onclick="markToday('missed')">
                        <i class="ri-close-circle-line me-1"></i>
                        Belum Nabung
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Target Info Section -->
<div class="container py-3" style="max-width: 480px;">
    <div class="card" id="targetInfoCard" style="display: none;">
        <div class="card-body">
            <h6 class="card-subtitle mb-3 text-muted">Target Menabung</h6>

            <div class="d-flex justify-content-between mb-2">
                <div>
                    <small class="text-muted d-block">Target Harian</small>
                    <strong id="showDailyAmount">Rp 0</strong>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Total Target</small>
                    <strong id="showTargetAmount">Rp 0</strong>
                </div>
            </div>

            <!-- Progress Amount -->
            <div class="mb-3">
                <small class="text-muted d-block">Progress Dana</small>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" id="progressBar" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="small" id="savedAmount">Rp 0</span>
                    <span class="small" id="targetAmount">Rp 0</span>
                </div>
            </div>

            <!-- Progress Days -->
            <div class="mb-3">
                <small class="text-muted d-block">Progress Hari</small>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" id="daysProgressBar" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="small"><span id="daysCompleted">0</span> hari</span>
                    <span class="small"><span id="totalDays">0</span> hari</span>
                </div>
            </div>

            <div class="mb-2">
                <small class="text-muted d-block">Keinginan</small>
                <strong id="showWishTarget">-</strong>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Deskripsi</small>
                <p class="mb-0" id="showDescription">-</p>
            </div>

            <!-- Show this button when target is achieved -->
            <button id="newTargetBtn" class="btn btn-success w-100" style="display: none;" onclick="openTargetModal(true)">
                <i class="ri-add-circle-line me-1"></i> Buat Target Baru
            </button>
        </div>
    </div>
</div>

<!-- Target Modal -->
<div class="modal fade" id="targetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Target Nabung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="targetForm" onsubmit="saveTarget(event)">
                    <input type="hidden" id="currentSavingsIdField" value="">
                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control currency-input" id="targetAmount" required>
                        </div>
                        <small class="text-muted">Total uang yang ingin dicapai</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Perhari</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control currency-input" id="dailyAmount" required>
                        </div>
                        <small class="text-muted">Jumlah yang ditabung per hari</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keinginan</label>
                        <input type="text" class="form-control" id="wishTarget" required>
                        <small class="text-muted">Apa yang ingin dicapai</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                        <small class="text-muted">Catatan tambahan (opsional)</small>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-grid {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        max-width: 100%;
    }

    .calendar-days-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #f8f9fa;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .day-name {
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: #666;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        padding: 4px;
        background: #fff;
        min-width: 0;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
        min-height: 30px;
        padding: 2px;
        font-size: 0.9rem;
        word-break: break-word;
        position: relative;
    }

    .calendar-day.empty {
        background: transparent;
        border: none;
        pointer-events: none;
    }

    .calendar-day .date {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .calendar-day .amount {
        font-size: 8px;
        color: #666;
    }

    .calendar-day.done {
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .calendar-day.missed {
        background: #f8d7da;
        border-color: #f5c6cb;
    }

    .calendar-day.future {
        background: #fff;
    }

    .calendar-day.past {
        background: #f8f9fa;
        color: #999;
        cursor: not-allowed;
        pointer-events: none;
    }

    .status-icon {
        font-size: 14px;
        font-weight: bold;
        margin: 2px 0;
    }

    .calendar-day.done .status-icon {
        color: #28a745;
    }

    .calendar-day.missed .status-icon {
        color: #dc3545;
    }

    .days-left-info {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 4px;
        margin-top: 10px;
        text-align: center;
    }

    .mark-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5em;
        pointer-events: none;
        z-index: 2;
    }

    .calendar-day.done .date,
    .calendar-day.missed .date {
        opacity: 0.3;
    }

    @media (max-width: 480px) {
        .calendar-grid {
            border-radius: 6px;
        }

        .calendar-days-header {
            padding: 4px 0;
        }

        .day-name {
            font-size: 10px;
        }

        .calendar-days {
            gap: 1px;
            padding: 2px;
        }

        .calendar-day {
            min-height: 18px;
            font-size: 11px;
            padding: 1px;
        }

        .calendar-day .date {
            font-size: 11px;
        }

        .calendar-day .amount {
            font-size: 7px;
        }

        .status-icon {
            font-size: 12px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Add CSRF token to all fetch requests
    const csrfToken = '<?= csrf_hash() ?>';
    let currentSavingsId = null;

    // Global functions
    function initializeCurrencyInputs() {
        document.querySelectorAll('.currency-input').forEach(input => {
            // Format on blur
            input.addEventListener('blur', function() {
                if (this.value) {
                    // Remove existing formatting
                    const num = parseInt(this.value.replace(/[.,]/g, ''));
                    if (!isNaN(num)) {
                        // Format with thousand separators
                        this.value = num.toLocaleString('id-ID');
                        this.setAttribute('data-raw', num); // Simpan angka murni
                    }
                }
            });

            // Clean up on focus
            input.addEventListener('focus', function() {
                // Remove formatting on focus
                this.value = this.value.replace(/[.,]/g, '');
            });

            // Handle input to only allow numbers
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value) {
                    this.setAttribute('data-raw', this.value);
                } else {
                    this.removeAttribute('data-raw');
                }
            });
        });
    }

    function fetchSavingData() {
        fetch('<?= base_url('app/ajax/getSavingTarget') ?>?t=' + new Date().getTime())
            .then(response => response.json())
            .then(data => {
                if (data.target && data.target.id) {
                    currentSavingsId = data.target.id;
                    updateTargetInfo(data.target);
                    document.getElementById('dailyTargetDisplay').textContent =
                        `Rp ${formatNumberShort(data.target.daily_amount)}`;

                    // Ubah label tombol sesuai status tabungan
                    const btn = document.getElementById('targetActionBtn');
                    if (data.target.is_achieved) {
                        btn.onclick = () => openTargetModal(true);
                    } else {
                        btn.onclick = () => openTargetModal(false);
                    }

                    if (data.target.is_achieved) {
                        document.getElementById('newTargetBtn').style.display = 'block';
                    } else {
                        document.getElementById('newTargetBtn').style.display = 'none';
                    }
                } else {
                    document.getElementById('targetInfoCard').style.display = 'none';
                    // Jika tidak ada tabungan aktif, tombol jadi Buat Tabungan Baru
                    const btn = document.getElementById('targetActionBtn');
                    btn.onclick = () => openTargetModal(true);
                }
                renderCalendar();
            })
            .catch(error => {
                console.error('Error fetching saving data:', error);
            });
    }

    function renderCalendar() {
        const monthPicker = document.getElementById('monthPicker');
        const [year, month] = monthPicker.value.split('-');

        // Add cache-busting timestamp
        const cacheBuster = new Date().getTime();

        // Use getMonthlySavings which now returns both target and calendar data
        fetch(`<?= base_url('app/ajax/getMonthlySavings') ?>?month=${month}&year=${year}&t=${cacheBuster}`)
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    console.error('Error fetching data:', data.message);
                    return;
                }

                const dailyTarget = data.dailyTarget || 0;
                const overallTarget = data.targetAmount || 0;
                const totalSaved = data.totalSaved || 0;
                const progressPercentage = data.progressPercentage || 0;

                // Update overall progress
                document.getElementById('monthlyStatus').textContent = `${progressPercentage}% Tercapai`;
                document.getElementById('progressBar').style.width = `${progressPercentage}%`;

                // Update daily target display
                document.getElementById('dailyTargetDisplay').textContent = `Rp ${formatNumberShort(dailyTarget)}`;

                // Render calendar grid
                const grid = document.getElementById('calendarGrid');
                const firstDay = new Date(year, month - 1, 1);
                const lastDay = new Date(year, month, 0);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                let html = '';

                // Empty cells for days before the 1st of the month
                for (let i = 0; i < firstDay.getDay(); i++) {
                    html += '<div class="calendar-day empty"></div>';
                }

                // Calendar days
                for (let day = 1; day <= lastDay.getDate(); day++) {
                    const currentDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const cellDate = new Date(year, month - 1, day);
                    cellDate.setHours(0, 0, 0, 0);
                    const saving = data.savings?.find(s => s.date === currentDate) || {};

                    let className = 'calendar-day';
                    if (cellDate < today) className += ' past';
                    if (saving.status === 'done') className += ' done';
                    if (saving.status === 'missed') className += ' missed';
                    if (cellDate.toDateString() === today.toDateString()) className += ' today';

                    const isClickable = cellDate.toDateString() === today.toDateString() && currentSavingsId;

                    // Add status icons (checkmark or x) based on status
                    let markIcon = '';
                    if (saving.status === 'done') {
                        markIcon = '<span class="mark-icon" style="color:#28a745">✓</span>';
                    } else if (saving.status === 'missed') {
                        markIcon = '<span class="mark-icon" style="color:#dc3545">✖</span>';
                    }

                    html += `
                    <div class="${className}" ${isClickable ? 'onclick="toggleSaving(this)"' : ''} data-date="${currentDate}">
                        <span class="date">${day}</span>
                        ${markIcon}
                        <div class="amount">${formatNumberShort(dailyTarget)}</div>
                    </div>
                `;
                }

                grid.innerHTML = html;
            })
            .catch(error => {
                console.error('Error rendering calendar:', error);
            });
    }

    function toggleSaving(element) {
        const today = new Date();
        const date = new Date(element.dataset.date);

        if (date.toDateString() !== today.toDateString()) {
            return; // Only allow toggling today's status
        }

        if (!currentSavingsId) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Anda perlu membuat target tabungan terlebih dahulu',
                confirmButtonText: 'Buat Target',
            }).then((result) => {
                if (result.isConfirmed) {
                    openTargetModal(true);
                }
            });
            return;
        }

        let newStatus;
        let statusIcon;

        if (element.classList.contains('done')) {
            element.classList.remove('done');
            element.classList.add('missed');
            newStatus = 'missed';
            statusIcon = '✖';
        } else {
            element.classList.remove('missed');
            element.classList.add('done');
            newStatus = 'done';
            statusIcon = '✓';
        }

        // Update the icon in the cell
        let iconSpan = element.querySelector('.status-icon');
        if (iconSpan) {
            iconSpan.textContent = statusIcon;
        } else {
            iconSpan = document.createElement('span');
            iconSpan.className = 'status-icon';
            iconSpan.textContent = statusIcon;

            // Insert after date div
            const dateDiv = element.querySelector('.date');
            dateDiv.insertAdjacentElement('afterend', iconSpan);
        }

        updateSavingStatus(date, newStatus);
    }

    function updateSavingStatus(date, status) {
        const currentStatus = {
            date: date.toISOString().split('T')[0],
            status: status,
            savings_id: currentSavingsId // We'll define this variable globally
        };

        fetch('<?= base_url('app/ajax/updateSaving') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(currentStatus)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Update progress based on response data
                    updateTargetInfo(data.target);
                    renderCalendar();

                    // Hanya tampilkan SweetAlert jika benar-benar sudah tercapai
                    if (
                        data.target.is_achieved &&
                        parseInt(data.target.saved_amount) >= parseInt(data.target.target_amount) &&
                        parseInt(data.target.payment_count) >= parseInt(data.target.total_days_needed)
                    ) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Selamat!',
                            text: 'Target tabungan Anda telah tercapai!',
                            confirmButtonText: 'Buat Target Baru'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                openTargetModal(true); // Open modal for creating new target
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status nabung berhasil diperbarui',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function markToday(status) {
        const today = new Date();

        if (!currentSavingsId) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Anda perlu membuat target tabungan terlebih dahulu',
                confirmButtonText: 'Buat Target',
            }).then((result) => {
                if (result.isConfirmed) {
                    openTargetModal(true);
                }
            });
            return;
        }

        Swal.fire({
            title: status === 'done' ? 'Sudah menabung hari ini?' : 'Belum menabung hari ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                const cells = document.querySelectorAll('.calendar-day');
                const statusIcon = status === 'done' ? '✓' : '✖';

                cells.forEach(cell => {
                    const cellDate = new Date(cell.dataset.date);
                    if (cellDate.toDateString() === today.toDateString()) {
                        // Update cell styling
                        cell.classList.remove('done', 'missed');
                        cell.classList.add(status);

                        // Update status icon
                        let iconSpan = cell.querySelector('.status-icon');
                        if (iconSpan) {
                            iconSpan.textContent = statusIcon;
                        } else {
                            iconSpan = document.createElement('span');
                            iconSpan.className = 'status-icon';
                            iconSpan.textContent = statusIcon;

                            // Insert after date div
                            const dateDiv = cell.querySelector('.date');
                            if (dateDiv) {
                                dateDiv.insertAdjacentElement('afterend', iconSpan);
                            }
                        }

                        // Update saving status in the database
                        updateSavingStatus(today, status);
                    }
                });

                // Only show success message if not showing achieved message
                if (status === 'done' && parseFloat(document.getElementById('progressBar').style.width) >= 100) {
                    // Progress will be updated by updateSavingStatus response
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: status === 'done' ? 'Selamat! Anda telah menabung hari ini' : 'Jangan lupa menabung besok ya!',
                        timer: 1500
                    });
                }
            }
        });
    }

    function openTargetModal(isNewTarget = false) {
        const modalTitle = document.querySelector('#targetModal .modal-title');
        if (isNewTarget) {
            document.getElementById('targetAmount').value = '';
            document.getElementById('dailyAmount').value = '';
            document.getElementById('wishTarget').value = '';
            document.getElementById('description').value = '';
            modalTitle.textContent = 'Buat Target Baru';
            if (document.getElementById('targetInfoCard').style.display !== 'none') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Membuat target baru akan mereset progres tabungan saat ini.',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const modal = new bootstrap.Modal(document.getElementById('targetModal'));
                        modal.show();
                    }
                });
                return;
            }
        } else {
            modalTitle.textContent = 'Edit Tabungan';
            fetch('<?= base_url('app/ajax/getSavingTarget') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.target && data.target.id) {
                        document.getElementById('targetAmount').value = parseFloat(data.target.target_amount).toLocaleString('id-ID');
                        document.getElementById('dailyAmount').value = parseFloat(data.target.daily_amount).toLocaleString('id-ID');
                        document.getElementById('wishTarget').value = data.target.wish_target;
                        document.getElementById('description').value = data.target.description || '';
                    }
                });
        }
        const modal = new bootstrap.Modal(document.getElementById('targetModal'));
        modal.show();
    }

    function saveTarget(event) {
        submitTarget(event);
    }

    function updateTargetInfo(target) {
        const card = document.getElementById('targetInfoCard');

        // Store the target ID for future reference
        currentSavingsId = target.id;

        // Get values with proper type handling
        const savedAmount = parseFloat(target.saved_amount) || 0;
        const targetAmount = parseFloat(target.target_amount) || 0;
        const successfulDays = parseInt(target.payment_count) || 0;
        const totalDaysNeeded = parseInt(target.total_days_needed) || 0;
        const daysLeft = parseInt(target.days_left) || 0;

        // Get progress percentages
        const amountProgress = parseInt(target.progress_percentage) || 0;
        const daysProgress = totalDaysNeeded > 0 ? Math.min((successfulDays / totalDaysNeeded * 100), 100) || 0 : 0;

        // Update display values
        document.getElementById('showTargetAmount').textContent = `Rp ${formatNumberShort(targetAmount)}`;
        document.getElementById('showDailyAmount').textContent = `Rp ${formatNumberShort(target.daily_amount)}`;
        document.getElementById('savedAmount').textContent = `Rp ${formatNumberShort(savedAmount)}`;
        document.getElementById('targetAmount').textContent = `Rp ${formatNumberShort(targetAmount)}`;
        document.getElementById('progressBar').style.width = `${amountProgress}%`;
        document.getElementById('daysProgressBar').style.width = `${daysProgress}%`;
        document.getElementById('monthlyStatus').textContent = `${Math.round(amountProgress)}% Tercapai`;
        document.getElementById('daysCompleted').textContent = `${successfulDays}`;
        document.getElementById('totalDays').textContent = `${totalDaysNeeded}`;

        // Show days left info
        const daysLeftElement = document.createElement('div');
        daysLeftElement.className = 'text-center mt-2';
        daysLeftElement.innerHTML = `<small class="text-muted">Sisa ${daysLeft} hari untuk mencapai target</small>`;

        // Add days left info if not already present
        const existingDaysLeft = card.querySelector('.days-left-info');
        if (!existingDaysLeft) {
            const daysLeftContainer = document.createElement('div');
            daysLeftContainer.className = 'days-left-info';
            daysLeftContainer.appendChild(daysLeftElement);

            // Add after progress bars
            const progressContainer = card.querySelector('.progress').parentNode.parentNode;
            progressContainer.appendChild(daysLeftContainer);
        } else {
            existingDaysLeft.innerHTML = daysLeftElement.outerHTML;
        }

        // Update wish target and description
        document.getElementById('showWishTarget').textContent = target.wish_target || '-';
        document.getElementById('showDescription').textContent = target.description || '-';

        // Show "New Target" button if target is achieved
        if (target.is_achieved) {
            document.getElementById('newTargetBtn').style.display = 'block';
        } else {
            document.getElementById('newTargetBtn').style.display = 'none';
        }

        // Show card
        card.style.display = 'block';
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function formatNumberShort(number) {
        number = parseInt(number);

        if (number >= 1000000) {
            const millions = number / 1000000;
            // Jika pembagian tepat atau angka desimalnya kecil (kurang dari 0.1), tampilkan bulat
            if (millions % 1 === 0 || millions % 1 < 0.1) {
                return Math.floor(millions) + 'jt';
            } else {
                // Bulatkan ke 1 desimal
                return millions.toFixed(1) + 'jt';
            }
        } else if (number >= 1000) {
            const thousands = number / 1000;
            // Jika pembagian tepat atau angka desimalnya kecil, tampilkan bulat
            if (thousands % 1 === 0 || thousands % 1 < 0.1) {
                return Math.floor(thousands) + 'k';
            } else {
                // Bulatkan ke 1 desimal
                return thousands.toFixed(1) + 'k';
            }
        } else {
            return number.toString();
        }
    }

    function validateForm() {
        let targetAmountInput = document.getElementById('targetAmount');
        let dailyAmountInput = document.getElementById('dailyAmount');
        const wishTargetInput = document.getElementById('wishTarget');

        // Debug: log semua input dengan id targetAmount
        const allTargetInputs = Array.from(document.querySelectorAll('input#targetAmount'));
        allTargetInputs.forEach((el, idx) => {
            console.log('targetAmount input[' + idx + '] value:', el.value, 'visible:', el.offsetParent !== null);
        });

        // Jika value kosong, cari input lain yang visible
        if (!targetAmountInput.value) {
            const visibleInput = allTargetInputs.find(el => el.offsetParent !== null && el.value);
            if (visibleInput) targetAmountInput = visibleInput;
        }
        if (!dailyAmountInput.value) {
            const allDailyInputs = Array.from(document.querySelectorAll('input#dailyAmount'));
            const visibleDaily = allDailyInputs.find(el => el.offsetParent !== null && el.value);
            if (visibleDaily) dailyAmountInput = visibleDaily;
        }

        // Ambil value asli dari data-raw jika ada
        const targetValue = (targetAmountInput.getAttribute('data-raw') || targetAmountInput.value || '').trim();
        const dailyValue = (dailyAmountInput.getAttribute('data-raw') || dailyAmountInput.value || '').trim();

        // Debug
        console.log('targetValue:', targetValue, 'dailyValue:', dailyValue);

        // Check if fields are empty first
        if (!targetValue) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Harap isi target tabungan'
            });
            return null;
        }

        if (!dailyValue) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Harap isi target harian'
            });
            return null;
        }

        // Clean the currency formatting and convert to numbers
        const targetAmount = parseInt(targetValue.replace(/[.,]/g, ''));
        const dailyAmount = parseInt(dailyValue.replace(/[.,]/g, ''));
        const wishTarget = wishTargetInput.value.trim();

        // Debug hasil parsing
        console.log('targetAmount:', targetAmount, 'dailyAmount:', dailyAmount, 'wishTarget:', wishTarget);

        // Validate numeric values and other requirements
        if (isNaN(targetAmount) || targetAmount <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Target tabungan harus lebih dari 0'
            });
            return null;
        }

        if (isNaN(dailyAmount) || dailyAmount <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Target harian harus lebih dari 0'
            });
            return null;
        }

        if (dailyAmount > targetAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Target harian tidak boleh lebih besar dari target tabungan'
            });
            return null;
        }

        if (!wishTarget) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Harap isi keinginan yang ingin dicapai'
            });
            return null;
        }

        return {
            targetAmount,
            dailyAmount,
            wishTarget,
            description: document.getElementById('description').value.trim()
        };
    }

    function submitTarget(event) {
        event.preventDefault();
        const formData = validateForm();
        if (!formData) {
            return false;
        }
        const isNew = document.querySelector('#targetModal .modal-title').textContent === 'Buat Target Baru';
        const submissionData = {
            target_amount: formData.targetAmount,
            daily_amount: formData.dailyAmount,
            wish_target: formData.wishTarget,
            description: formData.description,
            is_new: isNew // true jika buat baru/reset, false jika edit
        };
        Swal.fire({
            title: 'Menyimpan Target',
            text: 'Mohon tunggu...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        fetch('<?= base_url('app/ajax/saveSavingTarget') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(submissionData)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.status) {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
                bootstrap.Modal.getInstance(document.getElementById('targetModal')).hide();
                if (data.target) {
                    currentSavingsId = data.target.id;
                    updateTargetInfo(data.target);
                    renderCalendar();
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan saat menyimpan data'
                });
            });
        return false;
    }

    // Jalankan inisialisasi ketika DOM sudah siap
    document.addEventListener('DOMContentLoaded', function() {
        // Set default month picker value to current month
        const today = new Date();
        const month = today.getMonth() + 1;
        const year = today.getFullYear();
        document.getElementById('monthPicker').value = `${year}-${month.toString().padStart(2, '0')}`;

        // Initialize currency inputs
        initializeCurrencyInputs();

        // Load data and render calendar
        fetchSavingData();

        // Month picker logic (tanpa button)
        const monthPicker = document.getElementById('monthPicker');
        const selectedMonthText = document.getElementById('selectedMonthText');

        function updateMonthText() {
            if (monthPicker.value) {
                const [year, month] = monthPicker.value.split('-');
                const date = new Date(year, month - 1);
                const monthName = date.toLocaleString('default', {
                    month: 'long'
                });
                selectedMonthText.textContent = monthName.charAt(0).toUpperCase() + monthName.slice(1) + ' ' + year;
            } else {
                selectedMonthText.textContent = '';
            }
        }
        monthPicker.addEventListener('change', function() {
            updateMonthText();
            renderCalendar();
        });
        updateMonthText();
    });
</script>

<?= $this->endSection(); ?>