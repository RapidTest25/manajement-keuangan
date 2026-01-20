<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Testimoni</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReviewModal">
            <i class='bx bx-plus'></i> Tambah Testimoni
        </button>
    </div>
</div>

<!-- Reviews Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="reviewsTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Konten</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?= esc($review['name']) ?></td>
                            <td><?= esc($review['role']) ?></td>
                            <td><?= esc($review['content']) ?></td>
                            <td>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class='bx <?= $i < $review['rating'] ? 'bxs-star text-warning' : 'bx-star' ?>'></i>
                                <?php endfor; ?>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        data-id="<?= $review['id'] ?>"
                                        <?= $review['status'] === 'active' ? 'checked' : '' ?>>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-review"
                                    data-id="<?= $review['id'] ?>"
                                    data-name="<?= esc($review['name']) ?>"
                                    data-role="<?= esc($review['role']) ?>"
                                    data-content="<?= esc($review['content']) ?>"
                                    data-rating="<?= $review['rating'] ?>"
                                    data-status="<?= $review['status'] ?>">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-review" data-id="<?= $review['id'] ?>">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">Tambah Testimoni Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addReviewForm" method="POST" action="<?= base_url('administrator/reviews/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="role">Role/Jabatan</label>
                        <input type="text" class="form-control" id="role" name="role" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="content">Testimoni</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="rating">Rating</label>
                        <select class="form-control" id="rating" name="rating" required>
                            <option value="">Pilih Rating</option>
                            <option value="1">1 Bintang</option>
                            <option value="2">2 Bintang</option>
                            <option value="3">3 Bintang</option>
                            <option value="4">4 Bintang</option>
                            <option value="5">5 Bintang</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" role="dialog" aria-labelledby="editReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel">Edit Testimoni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editReviewForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_name">Nama</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_role">Role/Jabatan</label>
                        <input type="text" class="form-control" id="edit_role" name="role" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_content">Testimoni</label>
                        <textarea class="form-control" id="edit_content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_rating">Rating</label>
                        <select class="form-control" id="edit_rating" name="rating" required>
                            <option value="1">1 Bintang</option>
                            <option value="2">2 Bintang</option>
                            <option value="3">3 Bintang</option>
                            <option value="4">4 Bintang</option>
                            <option value="5">5 Bintang</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#reviewsTable').DataTable({
            "order": [
                [3, "desc"]
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "emptyTable": "Tidak ada data yang tersedia",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Handle form submission
        $('#addReviewForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        showAlert('Berhasil', 'Testimoni berhasil ditambahkan');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Error', 'Gagal menambahkan testimoni', 'error');
                    }
                }
            });
        });

        // Handle edit form submission
        $('#editReviewForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            $.ajax({
                url: `<?= base_url('administrator/reviews/update') ?>/${id}`,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        showAlert('Berhasil', 'Testimoni berhasil diperbarui');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('Error', 'Gagal memperbarui testimoni', 'error');
                    }
                }
            });
        });

        // Handle edit button click
        $('.edit-review').on('click', function() {
            const data = $(this).data();
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_role').val(data.role);
            $('#edit_content').val(data.content);
            $('#edit_rating').val(data.rating);
            $('#edit_status').val(data.status);
            $('#editReviewModal').modal('show');
        });

        // Handle status toggle
        $('.status-toggle').on('change', function() {
            const id = $(this).data('id');
            const isChecked = $(this).prop('checked');
            const status = isChecked ? 'active' : 'inactive';

            showConfirm(
                'Ubah Status Testimoni?',
                `Apakah Anda yakin ingin mengubah status testimoni menjadi ${status}?`,
                function() {
                    $.ajax({
                        url: `<?= base_url('administrator/reviews/toggle') ?>/${id}`,
                        type: 'POST',
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert('Berhasil', 'Status testimoni berhasil diubah');
                            } else {
                                showAlert('Error', 'Gagal mengubah status testimoni', 'error');
                                // Revert toggle if failed
                                $(this).prop('checked', !isChecked);
                            }
                        }
                    });
                }
            );
        });

        // Handle delete
        $('.delete-review').on('click', function() {
            const id = $(this).data('id');
            showConfirm(
                'Hapus Testimoni?',
                'Testimoni akan dihapus secara permanen',
                function() {
                    $.ajax({
                        url: `<?= base_url('administrator/reviews/delete') ?>/${id}`,
                        type: 'POST',
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert('Berhasil', 'Testimoni berhasil dihapus');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showAlert('Error', 'Gagal menghapus testimoni', 'error');
                            }
                        }
                    });
                }
            );
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection(); ?>