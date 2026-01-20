<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class ReportController extends BaseController
{
    protected $transactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        return view('administrator/laporan');
    }

    public function getData()
    {
        try {
            $type = $this->request->getGet('type') ?? 'bulanan';
            $value = $this->request->getGet('value') ?? date('Y-m');

            // Parse period with MySQL-compatible date format
            $startDate = '';
            $endDate = '';

            switch ($type) {
                case 'bulanan':
                    $startDate = date('Y-m-d', strtotime($value . '-01'));
                    $endDate = date('Y-m-d', strtotime($startDate . ' +1 month'));
                    break;

                case 'kuartalan':
                    list($quarter, $year) = explode(' ', $value);
                    switch ($quarter) {
                        case 'Q1':
                            $startDate = $year . '-01-01';
                            $endDate = $year . '-04-01';
                            break;
                        case 'Q2':
                            $startDate = $year . '-04-01';
                            $endDate = $year . '-07-01';
                            break;
                        case 'Q3':
                            $startDate = $year . '-07-01';
                            $endDate = $year . '-10-01';
                            break;
                        case 'Q4':
                            $startDate = $year . '-10-01';
                            $endDate = (intval($year) + 1) . '-01-01';
                            break;
                    }
                    break;

                case 'tahunan':
                    $startDate = $value . '-01-01';
                    $endDate = (intval($value) + 1) . '-01-01';
                    break;

                default:
                    throw new \Exception('Invalid period type');
            }

            // Get transactions data for the period using optimized query
            $transactions = $this->transactionModel
                ->builder()
                ->where('transaction_date >=', $startDate)
                ->where('transaction_date <', $endDate)
                ->get()
                ->getResultArray();

            // Calculate trend data
            $trend = $this->calculateTrend($transactions, $type, $startDate, $endDate);

            // Format current period info for display
            $periodText = '';
            switch ($type) {
                case 'bulanan':
                    $periodText = date('F Y', strtotime($startDate));
                    break;
                case 'kuartalan':
                    $periodText = $value;
                    break;
                case 'tahunan':
                    $periodText = $value;
                    break;
            }

            return $this->response->setJSON([
                'success' => true,
                'trend' => $trend,
                'period' => $periodText,
                'debug' => [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'transactionCount' => count($transactions),
                    'periodType' => $type
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ReportController::getData] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to fetch report data. Please try again.'
            ])->setStatusCode(500);
        }
    }

    public function view($reportType)
    {
        $type = $this->request->getGet('type');
        $value = $this->request->getGet('value');

        // Get report data based on type and period
        $data = $this->getReportData($reportType, $type, $value);

        // Load appropriate view partial
        $html = view('administrator/reports/' . $reportType, $data);

        return $this->response->setJSON([
            'success' => true,
            'html' => $html
        ]);
    }

    public function download($reportType)
    {
        $type = $this->request->getGet('type');
        $value = $this->request->getGet('value');

        // Get report data
        $data = $this->getReportData($reportType, $type, $value);

        // Generate PDF
        $pdf = new \Dompdf\Dompdf();
        $html = view('administrator/reports/' . $reportType . '_pdf', $data);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        // Download
        return $this->response->download(
            $reportType . '.pdf',
            $pdf->output(),
            'application/pdf'
        );
    }
    private function calculateTrend($transactions, $type, $startDate, $endDate)
    {
        $labels = [];
        $income = [];
        $expense = [];

        switch ($type) {
            case 'bulanan':
                // Daily trend
                $days = date('t', strtotime($startDate)); // Get number of days in month

                // Initialize arrays
                for ($i = 1; $i <= $days; $i++) {
                    $labels[] = $i;
                    $income[] = 0;
                    $expense[] = 0;
                }

                // Fill in transaction data
                foreach ($transactions as $transaction) {
                    $day = (int)date('d', strtotime($transaction['transaction_date'])) - 1;
                    if (isset($income[$day])) {  // Check array bounds
                        if ($transaction['status'] === 'INCOME') {
                            $income[$day] += (float)$transaction['amount'];
                        } else if ($transaction['status'] === 'EXPENSE') {
                            $expense[$day] += (float)$transaction['amount'];
                        }
                    }
                }
                break;

            case 'kuartalan':
                // Monthly trend for quarter
                $months = ['Jan', 'Feb', 'Mar'];
                if (strpos($startDate, '-04-') !== false) $months = ['Apr', 'May', 'Jun'];
                if (strpos($startDate, '-07-') !== false) $months = ['Jul', 'Aug', 'Sep'];
                if (strpos($startDate, '-10-') !== false) $months = ['Oct', 'Nov', 'Dec'];

                // Initialize arrays
                $labels = $months;
                $income = array_fill(0, 3, 0);
                $expense = array_fill(0, 3, 0);

                // Fill in transaction data
                foreach ($transactions as $transaction) {
                    $month = (int)date('n', strtotime($transaction['transaction_date'])) % 3;
                    if ($transaction['status'] === 'INCOME') {
                        $income[$month] += (float)$transaction['amount'];
                    } else if ($transaction['status'] === 'EXPENSE') {
                        $expense[$month] += (float)$transaction['amount'];
                    }
                }
                break;

            case 'tahunan':
                // Monthly trend for year
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = date('M', mktime(0, 0, 0, $i, 1));
                    $income[] = 0;
                    $expense[] = 0;
                }

                // Fill in transaction data
                foreach ($transactions as $transaction) {
                    $month = (int)date('n', strtotime($transaction['transaction_date'])) - 1;
                    if ($transaction['status'] === 'INCOME') {
                        $income[$month] += (float)$transaction['amount'];
                    } else if ($transaction['status'] === 'EXPENSE') {
                        $expense[$month] += (float)$transaction['amount'];
                    }
                }
                break;
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expense' => $expense
        ];
    }

    private function getReportData($reportType, $periodType, $periodValue)
    {
        $startDate = '';
        $endDate = '';

        // Parse period similar to getData method
        switch ($periodType) {
            case 'bulanan':
                $startDate = $periodValue . '-01';
                $endDate = date('Y-m-t', strtotime($startDate));
                break;
            case 'kuartalan':
                list($quarter, $year) = explode(' ', $periodValue);
                switch ($quarter) {
                    case 'Q1':
                        $startDate = $year . '-01-01';
                        $endDate = $year . '-03-31';
                        break;
                    case 'Q2':
                        $startDate = $year . '-04-01';
                        $endDate = $year . '-06-30';
                        break;
                    case 'Q3':
                        $startDate = $year . '-07-01';
                        $endDate = $year . '-09-30';
                        break;
                    case 'Q4':
                        $startDate = $year . '-10-01';
                        $endDate = $year . '-12-31';
                        break;
                }
                break;
            case 'tahunan':
                $startDate = $periodValue . '-01-01';
                $endDate = $periodValue . '-12-31';
                break;
        }

        // Get transactions
        $transactions = $this->transactionModel
            ->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate)
            ->findAll();

        // Calculate report specific data
        $data = [];
        switch ($reportType) {
            case 'laba-rugi':
                $data = $this->calculateLabaRugi($transactions);
                break;
            case 'arus-kas':
                $data = $this->calculateArusKas($transactions);
                break;
            case 'neraca':
                $data = $this->calculateNeraca($transactions);
                break;
        }

        return $data;
    }

    private function calculateLabaRugi($transactions)
    {
        $totalIncome = 0;
        $totalExpense = 0;
        $categories = [
            'income' => [],
            'expense' => []
        ];

        foreach ($transactions as $transaction) {
            if ($transaction['status'] === 'INCOME') {
                $totalIncome += $transaction['amount'];
                $categories['income'][$transaction['category']] =
                    ($categories['income'][$transaction['category']] ?? 0) + $transaction['amount'];
            } else if ($transaction['status'] === 'EXPENSE') {
                $totalExpense += $transaction['amount'];
                $categories['expense'][$transaction['category']] =
                    ($categories['expense'][$transaction['category']] ?? 0) + $transaction['amount'];
            }
        }

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netIncome' => $totalIncome - $totalExpense,
            'categories' => $categories
        ];
    }

    private function calculateArusKas($transactions)
    {
        $openingBalance = 0; // Set opening balance from previous period if needed
        $currentBalance = $openingBalance;
        $cashFlow = [
            'operating' => ['in' => 0, 'out' => 0],
            'investing' => ['in' => 0, 'out' => 0],
            'financing' => ['in' => 0, 'out' => 0]
        ];

        foreach ($transactions as $transaction) {
            if ($transaction['status'] === 'INCOME') {
                $currentBalance += $transaction['amount'];
                $cashFlow['operating']['in'] += $transaction['amount'];
            } else if ($transaction['status'] === 'EXPENSE') {
                $currentBalance -= $transaction['amount'];
                $cashFlow['operating']['out'] += $transaction['amount'];
            }
        }

        return [
            'openingBalance' => $openingBalance,
            'currentBalance' => $currentBalance,
            'cashFlow' => $cashFlow
        ];
    }

    private function calculateNeraca($transactions)
    {
        // TODO: Implement proper balance sheet calculation
        return [
            'assets' => [
                'current' => 0,
                'fixed' => 0
            ],
            'liabilities' => [
                'current' => 0,
                'longTerm' => 0
            ],
            'equity' => 0
        ];
    }
}
