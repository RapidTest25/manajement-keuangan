<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\Administrator\AdminUserModel;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class DashboardController extends BaseController
{
    protected $adminUserModel;
    protected $transactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = $this->getDashboardData();
        return view('administrator/dashboard', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'Manajemen Pengguna'
        ];
        return view('administrator/pengguna', $data);
    }

    public function getUsers()
    {
        try {
            log_message('info', 'Getting users list');

            $users = $this->adminUserModel->getAllUsers();

            if (!$users) {
                log_message('warning', 'No users found in database');
                return $this->response->setJSON([]);
            }

            // Format data untuk DataTables
            $formattedUsers = array_map(function ($user) {
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user',
                    'active' => isset($user['active']) ? (int)$user['active'] : 0,
                    'created_at' => $user['created_at']
                ];
            }, $users);

            log_message('info', 'Successfully retrieved ' . count($formattedUsers) . ' users');
            return $this->response->setJSON($formattedUsers);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching users: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = $this->adminUserModel->find($id);
            if (!$user) {
                throw new \Exception('User not found');
            }
            return $this->response->setJSON($user);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => $e->getMessage()
            ])->setStatusCode(404);
        }
    }

    public function addUser()
    {
        try {
            $json = $this->request->getJSON();

            if (!isset($json->username) || !isset($json->email) || !isset($json->password)) {
                throw new \Exception('Username, email, and password are required');
            }

            $data = [
                'fullname' => $json->fullname ?? null,
                'username' => $json->username,
                'email' => $json->email,
                'password' => $json->password, // kirim plain password, model yang hash
                'role' => $json->role ?? 'user',
                'status' => 'active',
                'active' => 1
            ];

            $this->adminUserModel->createUser($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menambahkan user: ' . $e->getMessage(),
                'error_detail' => $e->getTraceAsString()
            ])->setStatusCode(500);
        }
    }

    public function updateUser()
    {
        try {
            $json = $this->request->getJSON();

            if (!isset($json->id)) {
                throw new \Exception('User ID is required');
            }

            $data = [];
            if (isset($json->active)) {
                $data['active'] = (int)$json->active;
            }
            if (isset($json->username)) {
                $data['username'] = $json->username;
            }
            if (isset($json->email)) {
                $data['email'] = $json->email;
            }
            if (isset($json->role)) {
                $data['role'] = $json->role;
            }
            if (isset($json->password) && !empty($json->password)) {
                $data['password'] = $json->password;
            }

            $this->adminUserModel->updateUser($json->id, $data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->adminUserModel->deleteUser($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage(),
                'error_detail' => $e->getTraceAsString()
            ])->setStatusCode(500);
        }
    }

    public function deletePermanentUser($id)
    {
        try {
            $db = \Config\Database::connect();
            // Hapus relasi grup
            $db->table('auth_groups_users')->where('user_id', $id)->delete();
            // Hapus token login
            $db->table('auth_tokens')->where('user_id', $id)->delete();
            // Hapus log login
            $db->table('auth_logins')->where('user_id', $id)->delete();
            // Hapus user permissions
            $db->table('auth_users_permissions')->where('user_id', $id)->delete();
            // Hard delete user
            $this->adminUserModel->where('id', $id)->purgeDeleted();
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus permanen'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal hapus permanen: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function restoreUser($id)
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->table('users')->where('id', $id)->update([
                'deleted_at' => null,
                'status' => 'active',
                'active' => 1
            ]);
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User berhasil direstore'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal restore user (update gagal)'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal restore: ' . $e->getMessage(),
                'error_detail' => $e->getTraceAsString()
            ])->setStatusCode(500);
        }
    }

    public function trashUsers()
    {
        $users = $this->adminUserModel->onlyDeleted()->findAll();
        return view('administrator/trash_pengguna', [
            'title' => 'Trash Pengguna',
            'users' => $users
        ]);
    }

    private function getDashboardData()
    {
        // Get total users and growth stats
        $userStats = $this->adminUserModel->getUserStats();

        // Get total income and growth
        $totalIncome = $this->transactionModel->where('status', 'INCOME')->selectSum('amount')->get()->getRow()->amount ?? 0;
        $lastMonthIncome = $this->transactionModel
            ->where(['status' => 'INCOME', 'created_at >=' => date('Y-m-d', strtotime('-1 month'))])
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;
        $prevMonthIncome = $this->transactionModel
            ->where(['status' => 'INCOME', 'created_at >=' => date('Y-m-d', strtotime('-2 month')), 'created_at <' => date('Y-m-d', strtotime('-1 month'))])
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;
        $incomeGrowth = $prevMonthIncome > 0 ? (($lastMonthIncome - $prevMonthIncome) / $prevMonthIncome) * 100 : 0;

        // Get total expense and growth
        $totalExpense = $this->transactionModel->where('status', 'EXPENSE')->selectSum('amount')->get()->getRow()->amount ?? 0;
        $lastMonthExpense = $this->transactionModel
            ->where(['status' => 'EXPENSE', 'created_at >=' => date('Y-m-d', strtotime('-1 month'))])
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;
        $prevMonthExpense = $this->transactionModel
            ->where(['status' => 'EXPENSE', 'created_at >=' => date('Y-m-d', strtotime('-2 month')), 'created_at <' => date('Y-m-d', strtotime('-1 month'))])
            ->selectSum('amount')
            ->get()
            ->getRow()
            ->amount ?? 0;
        $expenseGrowth = $prevMonthExpense > 0 ? (($lastMonthExpense - $prevMonthExpense) / $prevMonthExpense) * 100 : 0;

        // Get recent transactions
        $recentTransactions = $this->transactionModel
            ->select('transaction.*, transaction.users as username, transaction.category as category_name')
            ->orderBy('transaction.created_at', 'DESC')
            ->limit(10)
            ->find();

        // Get trend data for charts
        $trendData = $this->getTransactionTrends();
        $categoryDistribution = $this->getCategoryDistribution();

        return [
            'dashboardStats' => [
                'totalUsers' => $userStats['total'],
                'userGrowth' => $userStats['growth'],
                'totalIncome' => $totalIncome,
                'incomeGrowth' => round($incomeGrowth, 2),
                'totalExpense' => $totalExpense,
                'expenseGrowth' => round($expenseGrowth, 2),
                'netBalance' => $totalIncome - $totalExpense
            ],
            'recentTransactions' => $recentTransactions,
            'trendData' => $trendData,
            'categoryDistribution' => $categoryDistribution
        ];
    }

    private function getTransactionTrends()
    {
        // Get last 6 months of data
        $startDate = date('Y-m-d', strtotime('-5 months'));
        $endDate = date('Y-m-d');

        $trends = $this->transactionModel
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, status, SUM(amount) as total")
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->groupBy(['month', 'status'])
            ->orderBy('month', 'ASC')
            ->find();

        // Format data for chart
        $months = [];
        $income = [];
        $expense = [];

        // Initialize arrays with 0
        for ($i = 0; $i < 6; $i++) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime($monthKey));
            $monthData[$monthKey] = ['INCOME' => 0, 'EXPENSE' => 0];
        }

        // Fill in actual data
        foreach ($trends as $trend) {
            $monthData[$trend['month']][$trend['status']] = (float)$trend['total'];
        }

        // Convert to arrays for chart
        foreach ($monthData as $data) {
            $income[] = $data['INCOME'];
            $expense[] = $data['EXPENSE'];
        }

        return [
            'labels' => array_reverse($months),
            'income' => array_reverse($income),
            'expense' => array_reverse($expense)
        ];
    }

    private function getCategoryDistribution()
    {
        // Get expense distribution by category for the last month
        $categoryData = $this->transactionModel
            ->select('transaction.category as category_name, SUM(amount) as total')
            ->where('transaction.status', 'EXPENSE')
            ->where('transaction.transaction_date >=', date('Y-m-d', strtotime('-1 month')))
            ->groupBy('transaction.category')
            ->find();

        // Format data for chart
        $chartData = [
            'labels' => array_column($categoryData, 'category_name'),
            'data' => array_map('floatval', array_column($categoryData, 'total'))
        ];

        return $chartData;
    }

    public function updateTransactionStatus()
    {
        $transactionId = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $success = $this->transactionModel->update($transactionId, ['status' => $status]);

        return $this->response->setJSON([
            'success' => $success
        ]);
    }

    // Endpoint dummy agar dashboard admin tidak error
    public function stats()
    {
        return $this->response->setJSON([
            'labels' => [],
            'income' => [],
            'expense' => []
        ]);
    }
    public function trends()
    {
        return $this->response->setJSON([
            'labels' => [],
            'income' => [],
            'expense' => []
        ]);
    }
    public function categories()
    {
        return $this->response->setJSON([
            'labels' => [],
            'data' => []
        ]);
    }
}
