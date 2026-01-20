<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kategori</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class='bx bx-plus'></i> Tambah Kategori
        </button>
    </div>
</div>

<!-- Categories Table -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Kategori Pemasukan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="incomeCategories">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Total Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Kategori Pengeluaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="expenseCategories">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Total Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryType" class="form-label">Tipe Kategori</label>
                        <select class="form-select" id="categoryType" required>
                            <option value="INCOME">Pemasukan</option>
                            <option value="EXPENSE">Pengeluaran</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="saveCategory">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="editCategoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryType" class="form-label">Tipe Kategori</label>
                        <select class="form-select" id="editCategoryType" required>
                            <option value="INCOME">Pemasukan</option>
                            <option value="EXPENSE">Pengeluaran</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="updateCategory">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
    $(document).ready(function() {
        loadCategories();

        function loadCategories() {
            $.get('/administrator/categories/list', function(data) {
                populateCategoryTable('#incomeCategories', data.filter(c => c.type === 'INCOME'));
                populateCategoryTable('#expenseCategories', data.filter(c => c.type === 'EXPENSE'));
            });
        }

        function populateCategoryTable(tableId, categories) {
            const table = $(tableId).DataTable({
                data: categories,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'transaction_count'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                            <button class="btn btn-sm btn-primary edit-category" data-id="${data.id}">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-category" data-id="${data.id}">
                                <i class='bx bx-trash'></i>
                            </button>
                        `;
                        }
                    }
                ]
            });
        }

        // Add Category
        $('#saveCategory').click(function() {
            const data = {
                name: $('#categoryName').val(),
                type: $('#categoryType').val()
            };

            $.post('/administrator/categories/add', data, function(response) {
                if (response.success) {
                    $('#addCategoryModal').modal('hide');
                    loadCategories();
                }
            });
        });

        // Edit Category
        $(document).on('click', '.edit-category', function() {
            const id = $(this).data('id');
            $.get(`/administrator/categories/${id}`, function(data) {
                $('#editCategoryId').val(data.id);
                $('#editCategoryName').val(data.name);
                $('#editCategoryType').val(data.type);
                $('#editCategoryModal').modal('show');
            });
        });

        // Update Category
        $('#updateCategory').click(function() {
            const data = {
                id: $('#editCategoryId').val(),
                name: $('#editCategoryName').val(),
                type: $('#editCategoryType').val()
            };

            $.ajax({
                url: '/administrator/categories/update',
                type: 'PUT',
                data: data,
                success: function(response) {
                    if (response.success) {
                        $('#editCategoryModal').modal('hide');
                        loadCategories();
                    }
                }
            });
        });

        // Delete Category
        $(document).on('click', '.delete-category', function() {
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                $.ajax({
                    url: `/administrator/categories/delete/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            loadCategories();
                        }
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>