<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class TransactionModel extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id',
        'users',
        'category',
        'description',
        'amount',
        'image_receipt',
        'status',
        'transaction_date',
        'created_at'
    ];
    protected $useTimestamps = false;

    // Add validation rules
    protected $validationRules = [
        'transaction_id' => 'required',
        'users' => 'required',
        'category' => 'required',
        'amount' => 'required|numeric',
        'status' => 'required|in_list[EXPENSE,INCOME]',
        'transaction_date' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'transaction_id' => [
            'required' => 'Transaction ID harus diisi'
        ],
        'users' => [
            'required' => 'User harus diisi'
        ],
        'category' => [
            'required' => 'Kategori harus diisi'
        ],
        'amount' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus EXPENSE atau INCOME'
        ],
        'transaction_date' => [
            'required' => 'Tanggal transaksi harus diisi',
            'valid_date' => 'Format tanggal transaksi tidak valid'
        ]
    ];

    public function getTransactionsWithFilters($filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select('transaction.*, users.username');
        $builder->join('users', 'users.id = transaction.user_id', 'left');

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $builder->where('DATE(transaction.transaction_date) >=', date('Y-m-d', strtotime($filters['startDate'])))
                ->where('DATE(transaction.transaction_date) <=', date('Y-m-d', strtotime($filters['endDate'])));
        }

        if (!empty($filters['status'])) {
            $builder->where('transaction.status', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $builder->where('transaction.category', $filters['category']);
        }

        // Order by transaction_date primarily, then by created_at for same-date transactions
        $builder->orderBy('transaction.transaction_date', 'DESC')
            ->orderBy('transaction.created_at', 'DESC');

        return [
            'data' => $builder->get()->getResultArray()
        ];
    }

    public function conn()
    {
        return $this->db->table($this->table);
    }

    public function history($username)
    {
        $data = $this->conn()->where('users', $username)->get()->getResult();
        file_put_contents("history_{$username}.json", json_encode($data));

        return ['status' => true, 'data' => $data];
    }

    public function getFinancialSummary($username, $month = null)
    {
        if (!$month) {
            $month = date('Y-m');
        }

        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $today = date('Y-m-d');

        $daysWithExpenses = $this->select('DATE(transaction_date) as expense_date')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->groupBy('DATE(transaction_date)')
            ->findAll();

        $numberOfDaysWithExpenses = count($daysWithExpenses) ?: 1;

        $totalExpense = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->get()
            ->getRow()
            ->amount ?? 0;

        $averageDailyExpense = $totalExpense / $numberOfDaysWithExpenses;

        $totalIncome = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->get()
            ->getRow()
            ->amount ?? 0;

        $todayExpense = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date)', $today)
            ->get()
            ->getRow()
            ->amount ?? 0;

        return [
            'total_income' => (int)$totalIncome,
            'total_expense' => (int)$totalExpense,
            'today_expense' => (int)$todayExpense,
            'average_daily_expense' => (int)$averageDailyExpense,
            'balance' => (int)($totalIncome - $totalExpense),
            'days_with_expenses' => $numberOfDaysWithExpenses
        ];
    }

    public function getTotalIncome($username)
    {
        $result = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->get()
            ->getRow();

        return $result->amount ?? 0;
    }

    public function getLast7DaysExpenses($username)
    {
        $end = date('Y-m-d');
        $start = date('Y-m-d', strtotime('-6 days'));

        $result = $this->select('DATE(transaction_date) as date, SUM(amount) as total')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $start)
            ->where('DATE(transaction_date) <=', $end)
            ->groupBy('DATE(transaction_date)')
            ->orderBy('date', 'ASC')
            ->get()
            ->getResultArray();

        $expenses = [];
        $labels = [];

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime($date));
            $amount = 0;

            foreach ($result as $row) {
                if ($row['date'] == $date) {
                    $amount = $row['total'];
                    break;
                }
            }

            array_unshift($expenses, (int)$amount);
            array_unshift($labels, $dayName . ', ' . date('d/m', strtotime($date)));
        }

        return [
            'labels' => $labels,
            'data' => $expenses
        ];
    }

    public function getLast7DaysTransactions($username)
    {
        $end = date('Y-m-d');
        $start = date('Y-m-d', strtotime('-6 days'));

        $expenses = $this->select('DATE(transaction_date) as date, SUM(amount) as total')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $start)
            ->where('DATE(transaction_date) <=', $end)
            ->groupBy('DATE(transaction_date)')
            ->get()
            ->getResultArray();

        $incomes = $this->select('DATE(transaction_date) as date, SUM(amount) as total')
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $start)
            ->where('DATE(transaction_date) <=', $end)
            ->groupBy('DATE(transaction_date)')
            ->get()
            ->getResultArray();

        $labels = [];
        $expenseData = [];
        $incomeData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime($date));

            $expenseAmount = 0;
            $incomeAmount = 0;

            foreach ($expenses as $row) {
                if ($row['date'] == $date) {
                    $expenseAmount = $row['total'];
                    break;
                }
            }

            foreach ($incomes as $row) {
                if ($row['date'] == $date) {
                    $incomeAmount = $row['total'];
                    break;
                }
            }

            array_unshift($expenseData, (int)$expenseAmount);
            array_unshift($incomeData, (int)$incomeAmount);
            array_unshift($labels, $dayName . ', ' . date('d/m', strtotime($date)));
        }

        return [
            'labels' => $labels,
            'expenses' => $expenseData,
            'incomes' => $incomeData
        ];
    }

    public function getUserTransactions($username, $limit = null)
    {
        if (!$username) {
            log_message('error', 'getUserTransactions called with empty username');
            return [];
        }

        log_message('debug', "Getting transactions for user: {$username}, limit: {$limit}");

        try {
            $query = $this->where('users', $username)
                ->orderBy('transaction_date', 'DESC')
                ->orderBy('created_at', 'DESC');

            if ($limit) {
                $query->limit($limit);
            }

            $result = $query->find();

            log_message('debug', 'Found ' . count($result) . ' transactions');
            log_message('debug', 'SQL: ' . $this->db->getLastQuery());

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error in getUserTransactions: ' . $e->getMessage());
            log_message('error', 'SQL: ' . $this->db->getLastQuery());
            return [];
        }
    }

    public function getMonthlyIncome($username, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->get()
            ->getRow();

        return $result->amount ?? 0;
    }

    public function getMonthlyExpense($username, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $result = $this->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->get()
            ->getRow();

        return $result->amount ?? 0;
    }

    public function getMonthlyExpenseWithBiayaEfektif($username, $month)
    {
        // Get regular expenses
        $regularExpense = $this->getMonthlyExpense($username, $month);
        
        // Get biaya efektif for this user
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('username', $username)->first();
        
        if ($user) {
            $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
            $totalBiayaEfektif = $biayaEfektifModel->getTotalBiayaBulanan($user['id']);
            
            return $regularExpense + $totalBiayaEfektif;
        }
        
        return $regularExpense;
    }

    public function getMonthlyCategories($username, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->select('category, status, SUM(amount) as total')
            ->where('users', $username)
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->groupBy('category, status')
            ->get()
            ->getResultArray();
    }

    public function getMonthlyTransactions($username, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->where('users', $username)
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->orderBy('transaction_date', 'DESC')
            ->findAll();
    }

    public function getMonthlyStats($username, $month)
    {
        $currentIncome = $this->getMonthlyIncome($username, $month);
        $currentExpense = $this->getMonthlyExpense($username, $month);

        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
        $prevIncome = $this->getMonthlyIncome($username, $prevMonth);
        $prevExpense = $this->getMonthlyExpense($username, $prevMonth);

        $incomeTrend = $prevIncome ? (($currentIncome - $prevIncome) / $prevIncome) * 100 : 0;
        $expenseTrend = $prevExpense ? (($currentExpense - $prevExpense) / $prevExpense) * 100 : 0;

        return [
            'income' => $currentIncome,
            'expense' => $currentExpense,
            'balance' => $currentIncome - $currentExpense,
            'income_trend' => round($incomeTrend, 2),
            'expense_trend' => round($expenseTrend, 2)
        ];
    }

    public function getComparisonStats($username, $month)
    {
        $currentStats = $this->getMonthlyCategories($username, $month);

        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
        $prevStats = $this->getMonthlyCategories($username, $prevMonth);

        $comparison = [];

        foreach ($currentStats as $stat) {
            $category = $stat['category'];
            $comparison[$category] = [
                'name' => $category,
                'sekarang' => (int)$stat['total'],
                'sebelumnya' => 0
            ];
        }

        foreach ($prevStats as $stat) {
            $category = $stat['category'];
            if (isset($comparison[$category])) {
                $comparison[$category]['sebelumnya'] = (int)$stat['total'];
            } else {
                $comparison[$category] = [
                    'name' => $category,
                    'sekarang' => 0,
                    'sebelumnya' => (int)$stat['total']
                ];
            }
        }

        return array_values($comparison);
    }

    public function initializeUserData($userId)
    {
        $session = session();

        $transactions = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $summary = [
            'balance' => 0,
            'today_expense' => 0,
            'total_income' => 0,
            'total_expense' => 0,
            'average_daily_expense' => 0
        ];

        $today = date('Y-m-d');

        foreach ($transactions as $tx) {
            if ($tx['status'] === 'INCOME') {
                $summary['balance'] += $tx['amount'];
                $summary['total_income'] += $tx['amount'];
            } else {
                $summary['balance'] -= $tx['amount'];
                $summary['total_expense'] += $tx['amount'];

                if (date('Y-m-d', strtotime($tx['created_at'])) === $today) {
                    $summary['today_expense'] += $tx['amount'];
                }
            }
        }

        $daysInMonth = date('t');
        $summary['average_daily_expense'] = $summary['total_expense'] / $daysInMonth;

        $session->set('user_summary', $summary);
        $session->set('user_transactions', $transactions);
    }

    public function getCategoryStats($username, $month)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        return $builder->select('category, SUM(amount) as total')
            ->where('users', $username)
            ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
            ->where('status', 'EXPENSE')
            ->groupBy('category')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getCategoryStatsWithBiayaEfektif($username, $month)
    {
        // Get regular category stats
        $categoryStats = $this->getCategoryStats($username, $month);
        
        // Get user ID
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('username', $username)->first();
        
        if ($user) {
            $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
            $biayaEfektifStats = $biayaEfektifModel->getStatistikBiaya($user['id']);
            
            // Convert biaya efektif to monthly and merge with category stats
            $biayaEfektifByCategory = [];
            foreach ($biayaEfektifStats as $stat) {
                $monthlyAmount = calculate_monthly_cost($stat['total_biaya'], $stat['frekuensi']);
                
                if (isset($biayaEfektifByCategory[$stat['kategori']])) {
                    $biayaEfektifByCategory[$stat['kategori']] += $monthlyAmount;
                } else {
                    $biayaEfektifByCategory[$stat['kategori']] = $monthlyAmount;
                }
            }
            
            // Merge with existing category stats
            foreach ($biayaEfektifByCategory as $kategori => $total) {
                $found = false;
                foreach ($categoryStats as &$catStat) {
                    if ($catStat['category'] === $kategori) {
                        $catStat['total'] += $total;
                        $catStat['biaya_efektif'] = $total;
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $categoryStats[] = [
                        'category' => $kategori,
                        'total' => $total,
                        'biaya_efektif' => $total
                    ];
                }
            }
            
            // Sort by total descending
            usort($categoryStats, function($a, $b) {
                return $b['total'] <=> $a['total'];
            });
        }
        
        return $categoryStats;
    }

    public function getMonthlyTrend($username, $month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $days = (int)date('t', strtotime($startDate));

        // Ambil income per hari
        $incomes = $this->select('DATE(transaction_date) as date, SUM(amount) as total')
            ->where('users', $username)
            ->where('status', 'INCOME')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->groupBy('DATE(transaction_date)')
            ->get()->getResultArray();
        // Ambil expense per hari
        $expenses = $this->select('DATE(transaction_date) as date, SUM(amount) as total')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(transaction_date) >=', $startDate)
            ->where('DATE(transaction_date) <=', $endDate)
            ->groupBy('DATE(transaction_date)')
            ->get()->getResultArray();

        $incomeMap = [];
        foreach ($incomes as $row) {
            $incomeMap[$row['date']] = (float)$row['total'];
        }
        $expenseMap = [];
        foreach ($expenses as $row) {
            $expenseMap[$row['date']] = (float)$row['total'];
        }

        $labels = [];
        $incomeData = [];
        $expenseData = [];
        for ($i = 1; $i <= $days; $i++) {
            $date = $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $labels[] = $i;
            $incomeData[] = $incomeMap[$date] ?? 0;
            $expenseData[] = $expenseMap[$date] ?? 0;
        }
        return [
            'labels' => $labels,
            'incomes' => $incomeData,
            'expenses' => $expenseData
        ];
    }

    public function getBiayaEfektifBreakdown($username, $month)
    {
        // Get user ID
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('username', $username)->first();
        
        if (!$user) {
            return [];
        }
        
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
        $biayaEfektif = $biayaEfektifModel->getBiayaEfektif($user['id']);
        
        $breakdown = [];
        foreach ($biayaEfektif as $biaya) {
            $monthlyAmount = calculate_monthly_cost($biaya['jumlah'], $biaya['frekuensi']);
            
            $breakdown[] = [
                'nama_biaya' => $biaya['nama_biaya'],
                'kategori' => $biaya['kategori'],
                'frekuensi' => $biaya['frekuensi'],
                'jumlah_asli' => $biaya['jumlah'],
                'jumlah_bulanan' => $monthlyAmount,
                'is_active' => $biaya['is_active'],
                'tanggal_mulai' => $biaya['tanggal_mulai'],
                'tanggal_selesai' => $biaya['tanggal_selesai']
            ];
        }
        
        return $breakdown;
    }

    public function getComparisonStatsWithBiayaEfektif($username, $month)
    {
        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
        
        $currentIncome = $this->getMonthlyIncome($username, $month);
        $currentExpense = $this->getMonthlyExpenseWithBiayaEfektif($username, $month);
        
        $prevIncome = $this->getMonthlyIncome($username, $prevMonth);
        $prevExpense = $this->getMonthlyExpenseWithBiayaEfektif($username, $prevMonth);
        
        return [
            'current' => [
                'income' => $currentIncome,
                'expense' => $currentExpense,
                'balance' => $currentIncome - $currentExpense
            ],
            'previous' => [
                'income' => $prevIncome,
                'expense' => $prevExpense,
                'balance' => $prevIncome - $prevExpense
            ],
            'trends' => [
                'income' => $prevIncome > 0 ? (($currentIncome - $prevIncome) / $prevIncome) * 100 : 0,
                'expense' => $prevExpense > 0 ? (($currentExpense - $prevExpense) / $prevExpense) * 100 : 0,
                'balance' => ($currentIncome - $currentExpense) - ($prevIncome - $prevExpense)
            ]
        ];
    }
}
