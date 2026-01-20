const baseUrl = window.location.origin;

let transactionsTable;

// Load dashboard data and statistics
function loadDashboardData() {
    // Load dashboard stats
    fetch(baseUrl + '/administrator/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            $('#totalUsers').text(data.totalUsers);
            $('#totalTransactions').text(data.totalTransactions);
            $('#totalIncome').text(`Rp ${formatNumber(data.totalIncome)}`);
            $('#totalExpense').text(`Rp ${formatNumber(data.totalExpense)}`);
            $('#userGrowth').text(`${data.userGrowth}% dari bulan lalu`);
            $('#transactionGrowth').text(`${data.transactionGrowth}% dari bulan lalu`);
            $('#incomeGrowth').text(`${data.incomeGrowth}% dari bulan lalu`);
            $('#expenseGrowth').text(`${data.expenseGrowth}% dari bulan lalu`);
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
            showToast('Error', 'Gagal memuat statistik dashboard', 'error');
        });

    // Load chart data
    loadChartData();

    // Load recent transactions
    loadTransactions();
}

// Load and initialize charts
function loadChartData() {
    // Load transaction trends
    fetch(baseUrl + '/administrator/dashboard/trends')
        .then(response => response.json())
        .then(data => {
            updateTrendChart(data);
        })
        .catch(error => {
            console.error('Error loading transaction trends:', error);
            showToast('Error', 'Gagal memuat tren transaksi', 'error');
        });

    // Load category distribution
    fetch(baseUrl + '/administrator/dashboard/categories')
        .then(response => response.json())
        .then(data => {
            updateCategoryChart(data);
        })
        .catch(error => {
            console.error('Error loading category distribution:', error);
            showToast('Error', 'Gagal memuat distribusi kategori', 'error');
        });
}

// Update transaction trend chart
function updateTrendChart(data) {
    const canvas = document.getElementById('financialSummary');
    if (!canvas) {
        console.warn('financialSummary canvas not found');
        return;
    }
    const ctx = canvas.getContext('2d');
    if (!data || !data.labels || data.labels.length === 0) {
        ctx.font = '16px Arial';
        ctx.fillText('Tidak ada data tren transaksi', 20, 40);
        return;
    }
    if (window.trendChart) {
        window.trendChart.destroy();
    }
    window.trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: data.income,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: data.expense,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + formatNumber(value);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + formatNumber(context.raw);
                        }
                    }
                }
            }
        }
    });
}

// Update category distribution chart
function updateCategoryChart(data) {
    const canvas = document.getElementById('expenseDistribution');
    if (!canvas) {
        console.warn('expenseDistribution canvas not found');
        return;
    }
    const ctx = canvas.getContext('2d');
    if (!data || !data.labels || data.labels.length === 0) {
        ctx.font = '16px Arial';
        ctx.fillText('Tidak ada data distribusi kategori', 20, 40);
        return;
    }
    if (window.categoryChart) {
        window.categoryChart.destroy();
    }
    window.categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: generateColors(data.labels.length)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: Rp ${formatNumber(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Load transactions with filters
function loadTransactions() {
    if (transactionsTable) {
        transactionsTable.ajax.reload();
    } else {
        transactionsTable = initializeTransactionsTable();
    }
}

// Initialize transaction table
function initializeTransactionsTable() {
    return $('#transactionsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: baseUrl + '/administrator/dashboard/recent',
            dataSrc: ''
        },
        columns: [
            { data: 'transaction_date',
              render: function(data) {
                  return moment(data).format('DD/MM/YYYY HH:mm');
              }
            },
            { data: 'username' },
            { data: 'description' },
            { data: 'category' },
            { data: 'amount',
              render: function(data, type, row) {
                  const colorClass = row.type === 'income' ? 'text-success' : 'text-danger';
                  const prefix = row.type === 'income' ? '+' : '-';
                  return `<span class="${colorClass}">${prefix}Rp ${formatNumber(data)}</span>`;
              }
            },
            { data: 'type',
              render: function(data) {
                  return data === 'income' ? 'Pemasukan' : 'Pengeluaran';
              }
            },
            { data: 'status',
              render: function(data) {
                  return createStatusBadge(data);
              }
            },
            { data: null,
              orderable: false,
              render: function(data) {
                  return createActionButtons(data);
              }
            }
        ],
        order: [[0, 'desc']],
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
            }
        }
    });
}

// Handle transaction status update
function updateTransactionStatus(id, status) {
    fetch(baseUrl + '/administrator/transaction/status', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Success', 'Status transaksi berhasil diperbarui', 'success');
            transactionsTable.ajax.reload();
            loadDashboardData();
        } else {
            showToast('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating transaction status:', error);
        showToast('Error', 'Gagal memperbarui status transaksi', 'error');
    });
}

// User Management Functions
function loadUsers(searchQuery = '') {
    const tbody = document.querySelector('#usersTable tbody');
    
    // Fetch users from the server
    fetch(baseUrl + '/administrator/users/list' + (searchQuery ? `?search=${searchQuery}` : ''))
        .then(response => response.json())
        .then(users => {
            if (tbody) { // Check if tbody exists
            tbody.innerHTML = '';
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2 d-flex align-items-center justify-content-center 
                                    text-white bg-success rounded-circle" 
                                 style="width: 35px; height: 35px; font-size: 14px;">
                                ${getInitials(user.name)}
                            </div>
                            <div>${user.name}</div>
                        </div>
                    </td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>
                        <span class="status-badge ${user.status}">
                            ${user.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </td>
                    <td>${formatDate(user.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1" onclick="editUser(${user.id})">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id})">
                            <i class='bx bx-trash'></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            } else {
                console.warn('usersTable tbody not found');
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            showToast('Error', 'Gagal memuat daftar pengguna', 'error');
        });
}

// Get initials from name for avatar
function getInitials(name) {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase();
}

// Format date to Indonesian format
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Add event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search functionality if on users page
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => {
            loadUsers(searchInput.value);
        }, 300));

        // Initial load of users
        loadUsers();
    }

    // Form submission handlers
    const saveUserBtn = document.getElementById('saveUser');
    const updateUserBtn = document.getElementById('updateUser');
    
    if (saveUserBtn) {
        saveUserBtn.addEventListener('click', () => {
            const form = document.getElementById('addUserForm');
            if (form.checkValidity()) {
                saveUser();
            }
            form.classList.add('was-validated');
        });
    }

    if (updateUserBtn) {
        updateUserBtn.addEventListener('click', () => {
            const form = document.getElementById('editUserForm');
            if (form.checkValidity()) {
                updateUser();
            }
            form.classList.add('was-validated');
        });
    }

    // Event listener untuk tombol Refresh Data
    if (document.getElementById('refreshData')) {
        document.getElementById('refreshData').addEventListener('click', function() {
            location.reload();
        });
    }
});

// Utility function to debounce search input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// CRUD Operations for users
function saveUser() {
    const userData = {
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        role: document.getElementById('role').value
    };

    fetch(baseUrl + '/administrator/users/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Pengguna berhasil ditambahkan',
                timer: 1800,
                showConfirmButton: false
            });
            loadUsers();
            bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            document.getElementById('addUserForm').reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message || 'Gagal menambahkan pengguna',
            });
        }
    })
    .catch(error => {
        console.error('Error saving user:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal menambahkan pengguna',
        });
    });
}

function editUser(userId) {
    fetch(baseUrl + '/administrator/users/' + userId)
        .then(response => response.json())
        .then(userData => {
            document.getElementById('editUserId').value = userData.id;
            document.getElementById('editUsername').value = userData.name;
            document.getElementById('editEmail').value = userData.email;
            document.getElementById('editRole').value = userData.role;

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        })
        .catch(error => {
            console.error('Error fetching user:', error);
            showToast('Error', 'Gagal memuat data pengguna', 'error');
        });
}

function updateUser() {
    const userId = document.getElementById('editUserId').value;
    const userData = {
        username: document.getElementById('editUsername').value,
        email: document.getElementById('editEmail').value,
        role: document.getElementById('editRole').value
    };

    fetch(baseUrl + '/administrator/users/' + userId + '/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Success', 'Pengguna berhasil diperbarui', 'success');
            loadUsers();
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
        } else {
            showToast('Error', data.message || 'Gagal memperbarui pengguna', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating user:', error);
        showToast('Error', 'Gagal memperbarui pengguna', 'error');
    });
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        fetch(baseUrl + '/administrator/users/' + userId + '/delete', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', 'Pengguna berhasil dihapus', 'success');
                loadUsers();
            } else {
                showToast('Error', data.message || 'Gagal menghapus pengguna', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting user:', error);
            showToast('Error', 'Gagal menghapus pengguna', 'error');
        });
    }
}

// Utility functions
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function generateColors(count) {
    const baseColors = [
        '#198754', '#28a745', '#34c759', '#40d66b', 
        '#4ce37c', '#58f08e', '#64fda0', '#70ffb2',
        '#dc3545', '#fd7e14', '#ffc107', '#0dcaf0'
    ];
    
    if (count <= baseColors.length) {
        return baseColors.slice(0, count);
    }

    const colors = [...baseColors];
    for (let i = baseColors.length; i < count; i++) {
        colors.push(`hsl(${(i * 360) / count}, 70%, 50%)`);
    }
    return colors;
}

function createStatusBadge(status) {
    const statusMap = {
        'pending': ['warning', 'Menunggu'],
        'approved': ['success', 'Disetujui'],
        'rejected': ['danger', 'Ditolak']
    };
    
    const [type, label] = statusMap[status.toLowerCase()] || ['secondary', status];
    return `<span class="badge bg-${type}">${label}</span>`;
}

function createActionButtons(data) {
    if (data.status.toLowerCase() !== 'pending') {
        return '<span class="text-muted">No action needed</span>';
    }
    
    return `
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-success" onclick="updateTransactionStatus(${data.id}, 'approved')" title="Approve">
                <i class="fas fa-check"></i>
            </button>
            <button type="button" class="btn btn-danger" onclick="updateTransactionStatus(${data.id}, 'rejected')" title="Reject">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

function showToast(title, message, type) {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Event handlers
$(document).ready(function() {
    // Load initial data
    loadDashboardData();
    
    // Initialize transaction table
    transactionsTable = initializeTransactionsTable();
    
    // Refresh data button click handler
    $('#refreshData').click(function() {
        loadDashboardData();
    });

    // Update data every 5 minutes
    setInterval(loadDashboardData, 300000);

    // Initialize date range picker
    $('#dateRange').daterangepicker({
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        ranges: {
           'Hari Ini': [moment(), moment()],
           'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
           '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
           'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
           'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Apply filters
    $('#applyFilters').click(function() {
        loadTransactions();
    });
});