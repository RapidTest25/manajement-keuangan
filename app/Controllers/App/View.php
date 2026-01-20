<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\BudgetModel;

class View extends BaseController
{
    protected $userModel;
    protected $transactionModel;
    protected $budgetModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
        $this->budgetModel = new BudgetModel();
    }

    public function home()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $userId = user_id();
        $username = session()->get('username');

        // Debug logging
        log_message('debug', 'User ID: ' . $userId);
        log_message('debug', 'Username: ' . $username);

        if (!$username) {
            $userData = $this->userModel->find($userId);
            if ($userData) {
                $username = $userData['username'];
                // Set session data
                session()->set([
                    'user_id' => $userData['id'],
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'fullname' => $userData['fullname'],
                    'profile_picture' => $userData['user_image'] ?? 'default.jpg',
                    'created_at' => $userData['created_at']
                ]);
                log_message('debug', 'Set session data for user: ' . $username);
            } else {
                log_message('error', 'User data not found for ID: ' . $userId);
                return redirect()->to('/login');
            }
        }

        try {
            $transactions = $this->transactionModel->getUserTransactions($username, 5);
            $summary = $this->transactionModel->getFinancialSummary($username);
            $chartData = $this->transactionModel->getLast7DaysTransactions($username);
            $budget = $this->budgetModel->getUserBudget($username);

            // BADGE LOGIC START
            $hasNabungToday = true;
            $savingTarget = model('App\\Models\\SavingModel')->getSavingTarget($userId);
            if ($savingTarget && isset($savingTarget['id'])) {
                $recordModel = model('App\\Models\\SavingRecordModel');
                $todayRecord = $recordModel->where('savings_id', $savingTarget['id'])
                    ->where('date', date('Y-m-d'))
                    ->where('status', 'done')
                    ->first();
                $hasNabungToday = $todayRecord ? true : false;
            } else {
                $hasNabungToday = false;
            }

            $hasActiveLoanThisMonth = false;
            $debtNotes = model('App\\Models\\DebtNoteModel')->where('user_id', $userId)
                ->where('type', 'borrowing')
                ->where('borrowType', 'online')
                ->where('status', 'active')
                ->findAll();
            foreach ($debtNotes as $debt) {
                $dueMonth = date('Y-m', strtotime($debt['due_date'] ?? ''));
                $nowMonth = date('Y-m');
                if ($dueMonth === $nowMonth) {
                    $hasActiveLoanThisMonth = true;
                    break;
                }
            }

            $hasActiveInstallmentThisMonth = false;
            $cicilanList = model('App\\Models\\CicilanModel')->where('user_id', $userId)
                ->where('status', 'active')
                ->findAll();
            foreach ($cicilanList as $cicilan) {
                $startMonth = date('Y-m', strtotime($cicilan['start_date'] ?? ''));
                $nowMonth = date('Y-m');
                if ($startMonth <= $nowMonth) {
                    $hasActiveInstallmentThisMonth = true;
                    break;
                }
            }
            // BADGE LOGIC END

            log_message('debug', 'Successfully loaded dashboard data for user: ' . $username);

            return view('app/dashboard', [
                'title' => 'Dashboard',
                'transactions' => $transactions,
                'summary' => $summary,
                'chartData' => $chartData,
                'budget' => $budget,
                'hasNabungToday' => $hasNabungToday,
                'hasActiveLoanThisMonth' => $hasActiveLoanThisMonth,
                'hasActiveInstallmentThisMonth' => $hasActiveInstallmentThisMonth
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error loading dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function sukuBunga()
    {
        return view('App/sukuBunga');
    }

    public function compounding()
    {
        return view('App/compounding');
    }

    public function settingAccount()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $userId = user_id();
        $userModel = new UserModel();

        $userData = $userModel->find($userId);
        $totalIncome = $this->transactionModel->getTotalIncome($userData['username']);

        session()->set([
            'user_id' => $userData['id'],
            'email' => $userData['email'],
            'username' => $userData['username'],
            'fullname' => $userData['fullname'],
            'profile_picture' => $userData['user_image'] ?? 'default.jpg',
            'created_at' => $userData['created_at'],
            'total_income' => $totalIncome
        ]);

        return view('app/settingAccount', [
            'user' => $userData,
            'total_income' => $totalIncome
        ]);
    }

    public function statistik()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $statsController = new \App\Controllers\App\Statistics();
        return $statsController->index();
    }

    public function history()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        // Pass data untuk filter awal
        $data = [
            'title' => 'Riwayat Transaksi',
            'summary' => [
                'month' => date('Y-m'),
                'categories' => model('CategoryModel')->getUserCategories(user_id())
            ]
        ];

        return view('app/history', $data);
    }

    public function noteUtang()
    {
        $data['title'] = 'Note Utang';
        return view('app/noteUtang', $data);
    }

    public function downloadStatistik()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $statsController = new \App\Controllers\App\Statistics();
        return $statsController->downloadPDF();
    }

    public function budgeting()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        try {
            $username = session()->get('username');
            if (!$username) {
                throw new \Exception('Invalid session - username not found');
            }

            $budget = $this->budgetModel->getUserBudget($username);
            $chartData = $this->transactionModel->getLast7DaysTransactions($username);

            return view('app/budgeting', [
                'title' => 'Budgeting',
                'budget' => $budget,
                'chartData' => $chartData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error loading budgeting page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function cicilan()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        // Premium check
        if (!is_premium(user_id())) {
            return redirect()->to('app/subscription/plans')->with('error', 'Fitur Cicilan hanya untuk pengguna Premium');
        }

        $data = [
            'title' => 'Cicilan'
        ];
        return view('app/cicilan', $data);
    }
}
