<?php

namespace App\Models\Administrator;

use CodeIgniter\Model;

class AdminTransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'amount', 'type', 'category_id', 'user_id', 'description'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTransactionsWithFilters($filters = [])
    {
        $builder = $this->select('transactions.*, transactions.id as transaction_id, transactions.users as username, transactions.category')
            ->orderBy('transactions.created_at', 'DESC');

        // Apply date range filter
        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $builder->where('DATE(transactions.created_at) >=', $filters['startDate'])
                ->where('DATE(transactions.created_at) <=', $filters['endDate']);
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $builder->where('transactions.status', $filters['status']);
        }

        // Apply category filter
        if (!empty($filters['category'])) {
            $builder->where('transactions.category', $filters['category']);
        }

        return $builder->findAll();
    }

    public function getTransactionDetails($id)
    {
        return $this->select('transactions.*, transactions.users as username, transactions.category as category_name')
            ->where('transactions.id', $id)
            ->first();
    }

    public function getTransactionStats()
    {
        $month = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        $currentMonthStats = $this->getMonthStats($month);
        $lastMonthStats = $this->getMonthStats($lastMonth);

        return [
            'current' => $currentMonthStats,
            'last' => $lastMonthStats,
            'growth' => $this->calculateGrowth($currentMonthStats, $lastMonthStats)
        ];
    }

    private function getMonthStats($month)
    {
        $builder = $this->builder();

        $totalIncome = $builder->select('SUM(amount) as total')
            ->where('DATE_FORMAT(transaction_date, "%Y-%m")', $month)
            ->where('status', 'INCOME')
            ->get()
            ->getRow()
            ->total ?? 0;

        $totalExpense = $builder->select('SUM(amount) as total')
            ->where('DATE_FORMAT(transaction_date, "%Y-%m")', $month)
            ->where('status', 'EXPENSE')
            ->get()
            ->getRow()
            ->total ?? 0;

        $totalCount = $builder->where('DATE_FORMAT(transaction_date, "%Y-%m")', $month)
            ->countAllResults();

        return [
            'income' => (float)$totalIncome,
            'expense' => (float)$totalExpense,
            'count' => $totalCount
        ];
    }

    private function calculateGrowth($current, $last)
    {
        return [
            'income' => $last['income'] > 0 ? (($current['income'] - $last['income']) / $last['income']) * 100 : 0,
            'expense' => $last['expense'] > 0 ? (($current['expense'] - $last['expense']) / $last['expense']) * 100 : 0,
            'count' => $last['count'] > 0 ? (($current['count'] - $last['count']) / $last['count']) * 100 : 0
        ];
    }

    public function updateTransactionStatus($id, $status, $adminNotes = '')
    {
        return $this->update($id, [
            'status' => $status,
            'admin_notes' => $adminNotes,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getTransactionTrends($startDate, $endDate)
    {
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(transaction_date, '%Y-%m') as month,
                SUM(CASE WHEN status = 'INCOME' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN status = 'EXPENSE' THEN amount ELSE 0 END) as expense
            FROM transactions 
            WHERE transaction_date BETWEEN ? AND ?
            GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
            ORDER BY month ASC
        ", [$startDate, $endDate]);

        return $query->getResultArray();
    }

    public function getCategoryDistribution()
    {
        // Asumsi: transactions.category adalah ID kategori
        $query = $this->db->query("
            SELECT 
                c.name as category,
                SUM(t.amount) as total,
                COUNT(*) as count
            FROM transactions t
            JOIN categories c ON t.category = c.id
            WHERE DATE(t.transaction_date) >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
            GROUP BY c.name
            ORDER BY total DESC
            LIMIT 10
        ");
        $result = $query->getResultArray();
        if (!$result) {
            return [
                'labels' => [],
                'data' => []
            ];
        }
        return [
            'labels' => array_column($result, 'category'),
            'data' => array_map('floatval', array_column($result, 'total'))
        ];
    }
}
