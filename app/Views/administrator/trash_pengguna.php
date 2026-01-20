<?= $this->extend('layout/administrator/template'); ?>
<?= $this->section('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Trash Pengguna</h1>
    <a href="<?= base_url('administrator/users') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="trashUsersTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Posisi</th>
                        <th>Dihapus Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= esc($user['fullname'] ?? $user['username']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td><?= esc(ucfirst($user['role'] ?? 'user')) ?></td>
                            <td><?= esc($user['deleted_at']) ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deletePermanent(<?= $user['id'] ?>)">
                                    <i class='bx bx-trash'></i> Hapus Permanen
                                </button>
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="restoreUser(<?= $user['id'] ?>)">
                                    <i class='bx bx-undo'></i> Recovery
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deletePermanent(userId) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Pengguna akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('administrator/users/delete_permanent') ?>/' + userId,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil', 'Pengguna dihapus permanen', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message || 'Gagal hapus permanen', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat hapus permanen', 'error');
                    }
                });
            }
        });
    }

    function restoreUser(userId) {
        console.log('Restore userId:', userId);
        if (!userId) {
            Swal.fire('Error', 'ID user tidak valid', 'error');
            return;
        }
        Swal.fire({
            title: 'Recovery User?',
            text: 'Pengguna akan dikembalikan ke daftar aktif!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, kembalikan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('administrator/users/restore') ?>/' + userId,
                    type: 'POST',
                    data: {
                        _method: 'PUT'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil', 'Pengguna berhasil direstore', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message || 'Gagal restore pengguna', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat restore', 'error');
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection(); ?>