<?= $this->extend('layout/administrator/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pengaturan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>

    <div class="row">
        <!-- General Settings -->
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengaturan Umum</h5>
                </div>
                <div class="card-body">
                    <form id="settingsForm">
                        <div class="mb-3">
                            <label class="form-label" for="website_name">Nama Website</label>
                            <input type="text" class="form-control" id="website_name" name="website_name"
                                value="<?= esc($settings['website_name'] ?? 'Management Keuangan') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="website_description">Deskripsi Website</label>
                            <textarea class="form-control" id="website_description" name="website_description"
                                rows="3"><?= esc($settings['website_description'] ?? 'Aplikasi Management Keuangan untuk membantu mengatur keuangan Anda') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="admin_email">Email Administrator</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email"
                                value="<?= esc($settings['admin_email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="maintenance_mode">Mode Maintenance</label>
                            <select class="form-select" id="maintenance_mode" name="maintenance_mode">
                                <option value="0" <?= ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                                <option value="1" <?= ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' ?>>Aktif</option>
                            </select>
                            <small class="text-muted">Fitur mode maintenance belum tersedia.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kontak</h5>
                </div>
                <div class="card-body">
                    <form id="contactForm">
                        <div class="mb-3">
                            <label class="form-label" for="contact_phone">Nomor Telepon</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                value="<?= esc($settings['contact_phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="contact_address">Alamat</label>
                            <textarea class="form-control" id="contact_address" name="contact_address"
                                rows="2"><?= esc($settings['contact_address'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Kontak</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Backup & System Info -->
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Backup Database</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Lakukan backup database secara berkala untuk menghindari kehilangan data.</p>
                    <button type="button" id="backupButton" class="btn btn-success w-100 mb-3">
                        <i class="ri-download-cloud-line me-1"></i> Backup Database
                    </button>
                    <div id="backupStatus" class="alert alert-info d-none">
                        Memproses backup...
                    </div>
                    <!-- Riwayat Backup -->
                    <div class="mt-3">
                        <h6>Backup Terakhir</h6>
                        <small class="text-muted" id="lastBackupDate">
                            <?= $settings['last_backup_date'] ?? 'Belum ada backup' ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>PHP Version:</strong> <?= phpversion() ?>
                        </li>
                        <li class="mb-2">
                            <strong>CodeIgniter Version:</strong> <?= \CodeIgniter\CodeIgniter::CI_VERSION ?>
                        </li>
                        <li class="mb-2">
                            <strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?>
                        </li>
                        <li class="mb-2">
                            <strong>Database:</strong> <?= db_connect()->getVersion() ?>
                        </li>
                        <li>
                            <strong>Ukuran Database:</strong> <span id="dbSize">Calculating...</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        // Handle maintenance mode change
        $('#maintenance_mode').on('change', function() {
            const isMaintenance = $(this).val() === '1';
            const title = isMaintenance ? 'Aktifkan Mode Maintenance?' : 'Nonaktifkan Mode Maintenance?';
            const text = isMaintenance ?
                'Saat mode maintenance aktif, pengguna biasa tidak dapat mengakses website. Hanya admin yang dapat mengakses.' :
                'Saat mode maintenance dinonaktifkan, semua pengguna dapat mengakses website.';

            showConfirm(title, text, function() {
                const data = {
                    maintenance_mode: isMaintenance ? '1' : '0'
                };
                fetch('<?= base_url('administrator/settings/update') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(response => {
                        if (response.status === 'success') {
                            showAlert('Berhasil', 'Pengaturan maintenance mode berhasil diperbarui');
                        } else {
                            showAlert('Error', 'Gagal memperbarui pengaturan maintenance mode', 'error');
                            // Revert select if failed
                            $('#maintenance_mode').val(isMaintenance ? '0' : '1');
                        }
                    })
                    .catch(() => {
                        showAlert('Error', 'Terjadi kesalahan saat memperbarui pengaturan', 'error');
                        // Revert select if failed
                        $('#maintenance_mode').val(isMaintenance ? '0' : '1');
                    });
            });
        });

        // Handle settings form submission
        const settingsForm = document.getElementById('settingsForm');
        const contactForm = document.getElementById('contactForm');

        settingsForm.addEventListener('submit', function(e) {
            console.log('[DEBUG] settingsForm submit intercepted');
            e.preventDefault();
            saveSettings(new FormData(settingsForm), 'Pengaturan umum berhasil disimpan');
            return false;
        });

        contactForm.addEventListener('submit', function(e) {
            console.log('[DEBUG] contactForm submit intercepted');
            e.preventDefault();
            saveSettings(new FormData(contactForm), 'Informasi kontak berhasil disimpan');
            return false;
        });

        function saveSettings(formData, successMessage) {
            // Konversi FormData ke objek JS
            let formObject = {};
            for (let [key, value] of formData.entries()) {
                formObject[key] = value;
            }
            // Debug log
            console.log('[DEBUG] saveSettings formObject:', formObject);
            fetch('<?= base_url('administrator/settings/update') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formObject)
                })
                .then(response => {
                    console.log('[DEBUG] saveSettings response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('[DEBUG] saveSettings data:', data);
                    if (data.status === 'success') {
                        showAlert('Sukses', successMessage);
                    } else {
                        showAlert('Error', data.message || 'Gagal menyimpan pengaturan', 'error');
                    }
                })
                .catch(error => {
                    console.error('[DEBUG] saveSettings error:', error);
                    showAlert('Error', error.message || 'Terjadi kesalahan saat menyimpan pengaturan', 'error');
                });
        }

        // Get database size
        const dbSizeElement = document.getElementById('dbSize');
        console.log('[DEBUG] Fetching database size...');
        fetch('<?= base_url('administrator/settings/database-size') ?>')
            .then(response => {
                console.log('[DEBUG] database-size response:', response);
                return response.json();
            })
            .then(data => {
                console.log('[DEBUG] database-size data:', data);
                if (data.status === 'success') {
                    dbSizeElement.textContent = data.size;
                } else {
                    dbSizeElement.textContent = 'Error: ' + (data.message || 'Gagal mendapatkan ukuran database');
                }
            })
            .catch(error => {
                console.error('[DEBUG] database-size error:', error);
                dbSizeElement.textContent = 'Error: ' + (error.message || 'Gagal mendapatkan ukuran database');
            });

        // Tampilkan backup terakhir
        function loadLastBackup() {
            console.log('[DEBUG] Fetching last backup...');
            fetch('<?= base_url('administrator/settings/last-backup') ?>')
                .then(response => {
                    console.log('[DEBUG] last-backup response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('[DEBUG] last-backup data:', data);
                    const lastBackupDate = document.getElementById('lastBackupDate');
                    if (!lastBackupDate) {
                        console.error('[DEBUG] Elemen #lastBackupDate tidak ditemukan!');
                        alert('Elemen #lastBackupDate tidak ditemukan di halaman!');
                        return;
                    }
                    if (data.status === 'success') {
                        lastBackupDate.innerHTML = `${data.date} <a href=\"<?= base_url('administrator/settings/download') ?>/${data.file}\" class=\"alert-link\">Download</a>`;
                    } else {
                        lastBackupDate.textContent = data.message || 'Belum ada backup';
                    }
                })
                .catch(error => {
                    console.error('[DEBUG] last-backup error:', error);
                    const lastBackupDate = document.getElementById('lastBackupDate');
                    if (lastBackupDate) {
                        lastBackupDate.textContent = 'Gagal mengambil info backup';
                    } else {
                        alert('Elemen #lastBackupDate tidak ditemukan di halaman!');
                    }
                });
        }
        loadLastBackup();

        // Handle database backup
        const backupButton = document.getElementById('backupButton');
        const backupStatus = document.getElementById('backupStatus');
        const lastBackupDate = document.getElementById('lastBackupDate');

        backupButton.addEventListener('click', function() {
            console.log('[DEBUG] Backup button clicked');
            showConfirm(
                'Konfirmasi Backup Database',
                'Apakah Anda yakin ingin melakukan backup database?',
                function() {
                    console.log('[DEBUG] Confirmed backup');
                    backupButton.disabled = true;
                    backupStatus.classList.remove('d-none');
                    backupStatus.classList.add('alert-info');
                    backupStatus.classList.remove('alert-success', 'alert-danger');
                    backupStatus.textContent = 'Memproses backup...';

                    console.log('[DEBUG] Fetching backup...');
                    fetch('<?= base_url('administrator/settings/backup') ?>')
                        .then(response => {
                            console.log('[DEBUG] backup response:', response);
                            return response.json();
                        })
                        .then(data => {
                            console.log('[DEBUG] backup data:', data);
                            backupButton.disabled = false;
                            if (data.status === 'success') {
                                backupStatus.classList.remove('alert-info');
                                backupStatus.classList.add('alert-success');
                                backupStatus.innerHTML = `Backup berhasil: ${data.file} <a href=\"<?= base_url('administrator/settings/download') ?>/${data.file}\" class=\"alert-link\">Download</a>`;
                                lastBackupDate.textContent = new Date().toLocaleString();
                                loadLastBackup();
                                showAlert('Berhasil', 'Database berhasil di-backup');
                            } else {
                                backupStatus.classList.remove('alert-info');
                                backupStatus.classList.add('alert-danger');
                                backupStatus.textContent = 'Gagal melakukan backup: ' + (data.message || 'Unknown error');
                                showAlert('Error', 'Gagal melakukan backup: ' + (data.message || 'Unknown error'), 'error');
                            }
                        })
                        .catch(error => {
                            console.error('[DEBUG] backup error:', error);
                            backupButton.disabled = false;
                            backupStatus.classList.remove('alert-info');
                            backupStatus.classList.add('alert-danger');
                            backupStatus.textContent = 'Gagal melakukan backup: ' + (error.message || 'Unknown error');
                            showAlert('Error', 'Gagal melakukan backup: ' + (error.message || 'Unknown error'), 'error');
                        });
                }
            );
        });
    });
</script>
<?= $this->endSection() ?>

<style>
    .card {
        margin-bottom: 24px;
        box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
    }

    .alert {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .text-muted {
        color: #74788d !important;
    }

    .list-unstyled li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .list-unstyled li:last-child {
        border-bottom: none;
    }
</style>