<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    /**
     * Default daily budget amount for new users (Rp 100,000)
     */
    const DEFAULT_DAILY_BUDGET = 100000.00;

    protected $table = 'budget_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'daily_budget', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getUserBudget($username)
    {
        $budget = $this->where('username', $username)
            ->orderBy('created_at', 'DESC')
            ->first();

        // If no budget exists, create one with the default value
        if (!$budget) {
            $this->saveUserBudget($username, self::DEFAULT_DAILY_BUDGET);
            $budget = $this->where('username', $username)
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        return $budget;
    }

    public function saveUserBudget($username, $dailyBudget)
    {
        if (!$username) {
            log_message('error', 'saveUserBudget: Username is empty');
            return false;
        }

        if (!$dailyBudget || $dailyBudget < 1000) {
            log_message('error', 'saveUserBudget: Invalid budget amount: ' . $dailyBudget);
            return false;
        }

        try {
            // Hapus budget lama untuk user ini
            $this->where('username', $username)->delete();

            // Buat budget baru
            $result = $this->insert([
                'username' => $username,
                'daily_budget' => (float)$dailyBudget
            ]);

            if ($result) {
                log_message('debug', 'Successfully saved budget for user: ' . $username);
                return true;
            } else {
                log_message('error', 'Failed to save budget for user: ' . $username);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saving budget: ' . $e->getMessage());
            return false;
        }
    }

    public function getDailyBudgetStatus($username, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        $budget = $this->getUserBudget($username);

        if (!$budget) {
            // Create default budget for new user
            $budget = [
                'daily_budget' => self::DEFAULT_DAILY_BUDGET
            ];
            $this->saveUserBudget($username, self::DEFAULT_DAILY_BUDGET);
        }

        $db = \Config\Database::connect();
        $spent = $db->table('transactions')
            ->selectSum('amount')
            ->where('users', $username)
            ->where('status', 'EXPENSE')
            ->where('DATE(created_at)', $date)
            ->get()
            ->getRow()
            ->amount ?? 0;

        $remaining = $budget['daily_budget'] - $spent;
        $percentageUsed = $budget['daily_budget'] > 0 ?
            ($spent / $budget['daily_budget']) * 100 : 100;

        return [
            'has_budget' => true,
            'daily_budget' => (float)$budget['daily_budget'],
            'spent_today' => (float)$spent,
            'remaining' => (float)$remaining,
            'percentage_used' => (float)$percentageUsed
        ];
    }
}
