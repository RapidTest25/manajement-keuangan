<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\UserSubscriptionModel;

class AdvancedReports extends BaseController
{
    protected $transactionModel;
    protected $subscriptionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->subscriptionModel = new UserSubscriptionModel();
    }

    /**
     * Advanced reports - Premium Feature
     * This page is protected by the 'premium' filter
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');

        // Check if user has premium
        if (!$this->subscriptionModel->hasPremiumSubscription($userId)) {
            return redirect()->to('/app/subscription/plans')
                           ->with('error', 'Fitur Laporan Lanjutan hanya tersedia untuk pengguna Premium');
        }

        // Get advanced statistics
        $data = [
            'title' => '📊 Laporan Keuangan Lanjutan',
            'monthly_trend' => $this->getMonthlyTrend($userId),
            'category_analysis' => $this->getCategoryAnalysis($userId),
            'spending_forecast' => $this->getSpendingForecast($userId),
            'savings_rate' => $this->getSavingsRate($userId)
        ];

        return view('app/premium/advanced_reports', $data);
    }

    /**
     * Export to PDF - Premium Feature
     */
    public function exportPDF()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Not logged in']);
        }

        $userId = session()->get('user_id');

        if (!$this->subscriptionModel->hasPremiumSubscription($userId)) {
            return $this->response->setJSON([
                'error' => 'Premium feature only',
                'message' => 'Fitur export PDF hanya untuk pengguna Premium'
            ]);
        }

        // Generate PDF (implementation here)
        return $this->response->download('laporan.pdf', null);
    }

    /**
     * Export to Excel - Premium Feature
     */
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Not logged in']);
        }

        $userId = session()->get('user_id');

        if (!$this->subscriptionModel->hasPremiumSubscription($userId)) {
            return $this->response->setJSON([
                'error' => 'Premium feature only',
                'message' => 'Fitur export Excel hanya untuk pengguna Premium'
            ]);
        }

        // Generate Excel (implementation here)
        return $this->response->download('laporan.xlsx', null);
    }

    // Private helper methods
    private function getMonthlyTrend($userId)
    {
        // Implementation for monthly trend analysis
        return [];
    }

    private function getCategoryAnalysis($userId)
    {
        // Implementation for category analysis
        return [];
    }

    private function getSpendingForecast($userId)
    {
        // Implementation for spending forecast
        return [];
    }

    private function getSavingsRate($userId)
    {
        // Implementation for savings rate calculation
        return 0;
    }
}
