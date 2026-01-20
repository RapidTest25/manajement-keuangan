<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengguna</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class='bx bx-user-plus'></i> Tambah Pengguna
        </button>
        <a href="<?= base_url('administrator/users/trash') ?>" class="btn btn-danger ms-2">
            <i class='bx bx-trash'></i> Trash
        </a>
    </div>
</div>

<!-- Table Container -->
<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Posisi</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Sebelumnya
            </div>
            <div class="pagination-numbers">
                <span>1</span>
                <span>2</span>
                <span class="active">Selanjutnya</span>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Posisi</label>
                        <select class="form-select" id="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="saveUser">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Posisi</label>
                        <select class="form-select" id="editRole" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="updateUser">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
    .search-box .form-control {
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .table td {
        padding: 1rem 0.75rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
    }

    .status-badge.aktif {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.tidak-aktif {
        background-color: #ffebee;
        color: #c62828;
    }

    .pagination-numbers span {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        cursor: pointer;
    }

    .pagination-numbers span.active {
        background-color: #198754;
        color: white;
        border-radius: 4px;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        background-color: #198754;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menampilkan alert
    function showAlert(title, text, icon = 'info') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: '#198754',
            timer: 3000,
            timerProgressBar: true
        });
    }

    // Fungsi untuk konfirmasi dengan alert
    function confirmAlert(title, text, callback) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    $(document).ready(function() {
        // Inisialisasi DataTable dengan fitur pencarian dan pengurutan
        const table = $('#usersTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('administrator/users/list') ?>',
                type: 'GET',
                dataSrc: function(response) {
                    // If response is an array, use it directly
                    if (Array.isArray(response)) {
                        return response;
                    }
                    // If response has a data property, use that
                    else if (response.data) {
                        return response.data;
                    }
                    // If neither, return empty array
                    return [];
                },
                error: function(xhr, error, thrown) {
                    console.error('Error loading users:', error);
                    showAlert('Error', 'Gagal memuat data pengguna', 'error');
                }
            },
            columns: [{
                    data: 'fullname',
                    render: function(data, type, row) {
                        const nameToDisplay = data || row.username;
                        const initials = nameToDisplay.split(' ')
                            .map(word => word[0])
                            .join('')
                            .toUpperCase();
                        return `
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2">
                                ${initials}
                            </div>
                            <div>${nameToDisplay}</div>
                        </div>
                    `;
                    }
                },
                {
                    data: 'email'
                },
                {
                    data: 'role',
                    render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                {
                    data: 'active',
                    render: function(data, type, row) {
                        const status = data == 1 ? 'aktif' : 'tidak-aktif';
                        const text = data == 1 ? 'Aktif' : 'Tidak Aktif';
                        const btnText = data == 1 ? 'Nonaktifkan' : 'Aktifkan';
                        const btnClass = data == 1 ? 'btn-outline-danger' : 'btn-outline-success';
                        return `
                            <span class="status-badge ${status}">${text}</span><br>
                            <button class="btn btn-sm ${btnClass} mt-1" onclick="toggleStatus(${row.id}, ${data})">${btnText}</button>
                        `;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                        <button class="btn btn-sm btn-outline-success me-1" onclick="editUser(${data.id})">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${data.id})">
                            <i class='bx bx-trash'></i>
                        </button>
                    `;
                    }
                }
            ],
            order: [
                [4, 'desc']
            ], // Kolom created_at (indeks 4) urut DESC
            language: {
                processing: "Memuat data...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                emptyTable: "Tidak ada data tersedia",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Terakhir"
                },
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });

        // Event listener untuk pencarian
        $('.search-box input').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Event handler untuk form tambah user
        $('#saveUser').on('click', function() {
            if (!$('#addUserForm')[0].checkValidity()) {
                $('#addUserForm')[0].reportValidity();
                return;
            }
            const userData = {
                fullname: $('#fullname').val(),
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                role: $('#role').val()
            };

            $.ajax({
                url: '<?= base_url('administrator/users/create') ?>',
                type: 'POST',
                data: JSON.stringify(userData),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        $('#addUserModal').modal('hide');
                        $('#addUserForm')[0].reset();
                        table.ajax.reload();
                        showAlert('Berhasil!', 'Pengguna berhasil ditambahkan', 'success');
                    } else {
                        showAlert('Gagal!', response.message || 'Gagal menambahkan pengguna', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error creating user:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menambahkan pengguna';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    showAlert('Error!', errorMessage, 'error');
                }
            });
        });

        // Fungsi untuk mengedit user
        window.editUser = function(userId) {
            $.ajax({
                url: `<?= base_url('administrator/users/') ?>${userId}`,
                type: 'GET',
                success: function(data) {
                    $('#editUserId').val(data.id);
                    $('#editUsername').val(data.username);
                    $('#editEmail').val(data.email);
                    $('#editRole').val(data.role);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    showAlert('Error!', 'Gagal memuat data pengguna', 'error');
                }
            });
        }

        // Event handler untuk update user
        $('#updateUser').on('click', function() {
            if (!$('#editUserForm')[0].checkValidity()) {
                $('#editUserForm')[0].reportValidity();
                return;
            }

            const userId = $('#editUserId').val();
            const userData = {
                id: userId,
                username: $('#editUsername').val(),
                email: $('#editEmail').val(),
                role: $('#editRole').val()
            };

            $.ajax({
                url: `<?= base_url('administrator/users/update') ?>`,
                type: 'PUT',
                data: JSON.stringify(userData),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        $('#editUserModal').modal('hide');
                        table.ajax.reload();
                        showAlert('Berhasil!', 'Data pengguna berhasil diperbarui', 'success');
                    } else {
                        showAlert('Gagal!', response.message || 'Gagal memperbarui data pengguna', 'error');
                    }
                },
                error: function(xhr) {
                    showAlert('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        });

        // Fungsi untuk menghapus user
        window.deleteUser = function(userId) {
            confirmAlert('Hapus Pengguna', 'Apakah anda yakin ingin menghapus pengguna ini?', function() {
                $.ajax({
                    url: '<?= base_url('administrator/users/delete'); ?>/' + userId,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            showAlert('Berhasil', 'Pengguna berhasil dihapus', 'success');
                            $('#usersTable').DataTable().ajax.reload();
                        } else {
                            showAlert('Error', response.message || 'Gagal menghapus pengguna', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        showAlert('Error', 'Terjadi kesalahan saat menghapus pengguna', 'error');
                        console.error(error);
                    }
                });
            });
        }

        // Fungsi toggle status aktif/tidak aktif
        window.toggleStatus = function(userId, currentStatus) {
            const newStatus = currentStatus == 1 ? 0 : 1;
            $.ajax({
                url: `<?= base_url('administrator/users/update') ?>`,
                type: 'PUT',
                data: JSON.stringify({
                    id: userId,
                    active: newStatus
                }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        showAlert('Berhasil!', 'Status pengguna berhasil diubah', 'success');
                    } else {
                        showAlert('Gagal!', response.message || 'Gagal mengubah status', 'error');
                    }
                },
                error: function(xhr) {
                    showAlert('Error!', 'Terjadi kesalahan saat mengubah status', 'error');
                }
            });
        }

        // Form submission handler
        $('#userForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        showAlert('Berhasil', 'Data pengguna berhasil disimpan', 'success');
                        $('#modalUser').modal('hide');
                        $('#userTable').DataTable().ajax.reload();
                        $('#userForm')[0].reset();
                    } else {
                        showAlert('Error', response.message || 'Gagal menyimpan data pengguna', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                    console.error(error);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>