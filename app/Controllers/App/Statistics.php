<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;

class Statistics extends BaseController
{
    protected $transactionModel;
    protected $request;

    public function __construct()
    {
        $this->transactionModel = new \App\Models\TransactionModel();
        $this->request = \Config\Services::request();
    }

    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $username = session()->get('username');
        
        // Check Premium Access
        if (!is_premium()) {
            return redirect()->to('/app/subscription/plans')->with('error', 'Fitur Statistik hanya tersedia untuk pengguna Premium. Silakan upgrade paket Anda.');
        }

        $month = $this->request->getGet('month') ?? date('Y-m');

        // Calculate previous month for comparison
        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));

        // Get category data with biaya efektif integration
        $categoryStats = $this->transactionModel->getCategoryStatsWithBiayaEfektif($username, $month);

        $data = [
            'title' => 'Statistik',
            'month' => $month,
            'summary' => [
                'income' => $this->transactionModel->getMonthlyIncome($username, $month),
                'expense' => $this->transactionModel->getMonthlyExpenseWithBiayaEfektif($username, $month),
                'regular_expense' => $this->transactionModel->getMonthlyExpense($username, $month),
                'income_trend' => 0,
                'expense_trend' => 0
            ],
            'chartData' => [
                'labels' => array_column($categoryStats, 'category'),
                'values' => array_column($categoryStats, 'total'),
                'colors' => ['#2563EB', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444', '#06B6D4', '#8B5CF6', '#EC4899', '#F97316', '#EAB308']
            ],
            'trendData' => $this->transactionModel->getMonthlyTrend($username, $month),
            'comparison' => $this->transactionModel->getComparisonStatsWithBiayaEfektif($username, $month),
            'biayaEfektifBreakdown' => $this->transactionModel->getBiayaEfektifBreakdown($username, $month)
        ];

        // Calculate trends
        $prevIncome = $this->transactionModel->getMonthlyIncome($username, $prevMonth);
        $prevExpense = $this->transactionModel->getMonthlyExpense($username, $prevMonth);

        $data['summary']['income_trend'] = $prevIncome ? (($data['summary']['income'] - $prevIncome) / $prevIncome) * 100 : 0;
        $data['summary']['expense_trend'] = $prevExpense ? (($data['summary']['expense'] - $prevExpense) / $prevExpense) * 100 : 0;

        return view('app/statistik', $data);
    }
    public function downloadPDF()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        try {
            // Check Premium Access
            if (!is_premium()) {
                return redirect()->to('/app/subscription/plans')->with('error', 'Fitur ini hanya untuk Premium user.');
            }

            $username = session()->get('username');
            $month = $this->request->getGet('month') ?? date('Y-m');

            // Ambil data dengan limit untuk mempercepat proses
            $limit = 10; // Batasi hanya 10 transaksi terakhir
            $monthlyIncome = $this->transactionModel->getMonthlyIncome($username, $month);
            $monthlyExpense = $this->transactionModel->getMonthlyExpense($username, $month);
            $data = [
                'month' => $month,
                'summary' => [
                    'income' => $monthlyIncome,
                    'expense' => $monthlyExpense,
                    'balance' => $monthlyIncome - $monthlyExpense,
                ],            // Get all transactions and categories
                'transactions' => $this->transactionModel->getMonthlyTransactions($username, $month),
                'categories' => $this->transactionModel->getMonthlyCategories($username, $month)
            ];        // Set the logo path
            $data['logo_path'] = ROOTPATH . 'public/Assets/images/logo.png';

            // Generate HTML
            $html = view('app/pdf/statistics', $data); // Initialize Dompdf class with minimal options
            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', false);
            $options->set('debugKeepTemp', false);
            $options->set('debugCss', false);
            $options->set('debugLayout', false);
            $options->set('debugLayoutLines', false);
            $options->set('debugLayoutBlocks', false);
            $options->set('debugLayoutInline', false);
            $options->set('debugLayoutPaddingBox', false);

            // Create PDF
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Output langsung ke browser untuk download
            $filename = "statistik-{$month}.pdf";
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            echo $dompdf->output();
        } catch (\Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF. Silakan coba lagi.');
        }
    }
}
