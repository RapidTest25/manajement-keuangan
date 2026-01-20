<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\Administrator\AdminUserModel;
use App\Models\Administrator\AdminTransactionModel;
use App\Models\CategoryModel;

class AdminController extends BaseController
{
    protected $adminUserModel;
    protected $adminTransactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->adminUserModel = new AdminUserModel();
        $this->adminTransactionModel = new AdminTransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Admin'
        ];
        return view('administrator/dashboard', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'Manajemen User'
        ];
        return view('administrator/users', $data);
    }

    public function getUserCount()
    {
        $stats = $this->adminUserModel->getUserStats();
        return $this->response->setJSON($stats);
    }

    public function getUsers()
    {
        $users = $this->adminUserModel->getUsersWithTransactionCount();
        return $this->response->setJSON($users);
    }

    public function getTransactions()
    {
        try {
            $filters = [
                'startDate' => $this->request->getGet('startDate'),
                'endDate' => $this->request->getGet('endDate'),
                'status' => $this->request->getGet('status'),
                'category' => $this->request->getGet('category')
            ];

            $transactions = $this->adminTransactionModel->getTransactionsWithFilters($filters);

            // Format data for DataTables
            return $this->response->setJSON([
                'data' => $transactions,
                'recordsTotal' => count($transactions),
                'recordsFiltered' => count($transactions)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error fetching transactions',
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getTransactionStats()
    {
        $stats = $this->adminTransactionModel->getTransactionStats();

        // Get trend data for charts
        $trend = $this->getTransactionTrend();
        $categoryDistribution = $this->getCategoryDistribution();

        $stats['chartData'] = [
            'trend' => $trend,
            'categories' => $categoryDistribution
        ];

        return $this->response->setJSON($stats);
    }

    public function getDashboardStats()
    {
        $transactionModel = new \App\Models\Administrator\AdminTransactionModel();

        // Get date range (last 12 months by default)
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-12 months'));

        $response = [
            'trends' => $transactionModel->getTransactionTrends($startDate, $endDate),
            'categories' => $transactionModel->getCategoryDistribution()
        ];

        return $this->response->setJSON($response);
    }

    private function getTransactionTrend()
    {
        $days = 30;
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-$days days"));

        $transactions = $this->adminTransactionModel->select('DATE(created_at) as date, status, SUM(amount) as total')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->groupBy('date, status')
            ->orderBy('date', 'ASC')
            ->findAll();

        $dates = [];
        $income = array_fill(0, $days, 0);
        $expense = array_fill(0, $days, 0);

        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            array_unshift($dates, date('d/m', strtotime($date)));
        }

        foreach ($transactions as $transaction) {
            $dayIndex = $days - 1 - ((strtotime($endDate) - strtotime($transaction['date'])) / 86400);
            if ($transaction['status'] === 'INCOME') {
                $income[$dayIndex] = (float)$transaction['total'];
            } else {
                $expense[$dayIndex] = (float)$transaction['total'];
            }
        }

        return [
            'labels' => $dates,
            'income' => $income,
            'expense' => $expense
        ];
    }

    private function getCategoryDistribution()
    {
        $categories = $this->categoryModel->select('categories.name, COUNT(transaction.id) as count')
            ->join('transaction', 'transaction.category = categories.id', 'left')
            ->where('transaction.created_at >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('categories.id')
            ->having('count >', 0)
            ->orderBy('count', 'DESC')
            ->findAll();

        return [
            'labels' => array_column($categories, 'name'),
            'values' => array_column($categories, 'count')
        ];
    }

    public function getTransaction($id)
    {
        $transaction = $this->adminTransactionModel->getTransactionDetails($id);
        return $this->response->setJSON($transaction);
    }

    public function updateTransactionStatus()
    {
        $data = $this->request->getJSON(true);

        try {
            if ($this->adminTransactionModel->updateTransactionStatus(
                $data['id'],
                $data['status'],
                $data['admin_notes'] ?? ''
            )) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Transaction status updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update transaction status'
            ])->setStatusCode(400);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error updating transaction status: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function createUser()
    {
        $data = $this->request->getJSON(true);

        try {
            if ($this->adminUserModel->createUser($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User created successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create user',
                'errors' => $this->adminUserModel->errors()
            ])->setStatusCode(400);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error creating user: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateUser($id)
    {
        $data = $this->request->getJSON(true);

        try {
            if ($this->adminUserModel->updateUser($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update user',
                'errors' => $this->adminUserModel->errors()
            ])->setStatusCode(400);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error updating user: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function deleteUser($id)
    {
        try {
            if ($this->adminUserModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete user'
            ])->setStatusCode(400);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error deleting user: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
