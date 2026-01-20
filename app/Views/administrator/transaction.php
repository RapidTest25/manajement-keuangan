<?= $this->extend('layout/administrator/template'); ?>

<?= $this->section('css') ?>
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
    .positive-amount {
        color: var(--bs-success);
        font-weight: 500;
    }

    .negative-amount {
        color: var(--bs-danger);
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.selesai {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--bs-success);
    }

    .status-badge.pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .status-badge.batal {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--bs-danger);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Transaksi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Transaksi Terdaftar
            </div>
            <!-- Tombol Filter dan Export dihapus karena tidak berfungsi -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="transactionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= $transaction['id'] ?></td>
                                <td><?= date('d M Y', strtotime($transaction['transaction_date'])) ?></td>
                                <td><?= $transaction['username'] ?></td>
                                <td><?= $transaction['description'] ?></td>
                                <td><?= $transaction['category'] ?></td>
                                <td class="<?= $transaction['status'] === 'INCOME' ? 'positive-amount' : 'negative-amount' ?>">
                                    <?= $transaction['status'] === 'INCOME' ? '+' : '-' ?>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                </td>
                                <td><span class="status-badge <?= strtolower($transaction['status']) ?>"><?= $transaction['status'] ?></span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info view-transaction" data-id="<?= $transaction['id'] ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary edit-transaction" data-id="<?= $transaction['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Transaction Modal -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="transaction-details">
                    <!-- Will be populated dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Transaction Modal -->
<div class="modal fade" id="editTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTransactionForm">
                    <input type="hidden" id="transactionId">
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="editDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="editDescription" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategory" class="form-label">Kategori</label>
                        <select class="form-select" id="editCategory" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['name'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="editAmount" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" required>
                            <option value="INCOME">Pendapatan</option>
                            <option value="EXPENSE">Pengeluaran</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveTransaction">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#transactionsTable').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            order: [
                [1, 'desc']
            ], // Sort by date descending
            pageLength: 10,
            responsive: true
        });

        // View Transaction
        $(document).on('click', '.view-transaction', function() {
            const id = $(this).data('id');
            $.get(`<?= base_url('administrator/transaction/view') ?>/${id}`, function(data) {
                $('.transaction-details').html(data.html);
                $('#viewTransactionModal').modal('show');
            });
        });

        // Edit Transaction
        $(document).on('click', '.edit-transaction', function() {
            const id = $(this).data('id');
            $('#transactionId').val(id);

            $.get(`<?= base_url('administrator/transaction/get') ?>/${id}`, function(data) {
                $('#editDate').val(data.transaction_date);
                $('#editDescription').val(data.description);
                $('#editCategory').val(data.category);
                $('#editAmount').val(data.amount);
                $('#editStatus').val(data.status);
                $('#editTransactionModal').modal('show');
            });
        });

        // Save Transaction
        $('#saveTransaction').click(function() {
            const formData = {
                id: $('#transactionId').val(),
                transaction_date: $('#editDate').val(),
                description: $('#editDescription').val(),
                category: $('#editCategory').val(),
                amount: $('#editAmount').val(),
                status: $('#editStatus').val()
            };

            $.ajax({
                url: '<?= base_url('administrator/transaction/update') ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#editTransactionModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Gagal mengupdate transaksi');
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>