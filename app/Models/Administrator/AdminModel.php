<?php

namespace App\Models\Administrator;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['username', 'email', 'password', 'role'];

    public function getUserStats()
    {
        $currentCount = $this->countAll();
        $lastMonth = date('Y-m-d', strtotime('-1 month'));
        $lastMonthCount = $this->where('created_at <', $lastMonth)->countAllResults();

        $growth = $lastMonthCount > 0 ? (($currentCount - $lastMonthCount) / $lastMonthCount) * 100 : 0;

        return [
            'count' => $currentCount,
            'growth' => round($growth, 1)
        ];
    }

    public function getTransactionStats()
    {
        $transactionModel = new \App\Models\TransactionModel();
        $month = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        return [
            'current' => [
                'count' => $transactionModel->where('MONTH(created_at)', date('m'))->countAllResults(),
                'income' => $transactionModel->getMonthlyIncome(null, $month),
                'expense' => $transactionModel->getMonthlyExpense(null, $month)
            ],
            'last' => [
                'count' => $transactionModel->where('MONTH(created_at)', date('m', strtotime('-1 month')))->countAllResults(),
                'income' => $transactionModel->getMonthlyIncome(null, $lastMonth),
                'expense' => $transactionModel->getMonthlyExpense(null, $lastMonth)
            ]
        ];
    }
}
