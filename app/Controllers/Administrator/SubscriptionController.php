<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\UserSubscriptionModel;

class SubscriptionController extends BaseController
{
    protected $userSubscriptionModel;

    public function __construct()
    {
        $this->userSubscriptionModel = new UserSubscriptionModel();
    }

    public function index()
    {
        // Get all subscriptions with plan details
        $subscriptions = $this->userSubscriptionModel->select('user_subscriptions.*, users.username, users.email, subscription_plans.name as plan_name, subscription_plans.price')
            ->join('users', 'users.id = user_subscriptions.user_id')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->orderBy('user_subscriptions.created_at', 'DESC')
            ->findAll();

        // Calculate Revenue
        $totalRevenue = 0;
        $thisMonthRevenue = 0;
        $currentMonth = date('Y-m');

        foreach ($subscriptions as $sub) {
            // Only count if payment_status is paid (assuming price > 0 means paid for simplicity in this context, 
            // but ideally check payment_status 'paid' if you have it. The model has 'payment_status')
            if ($sub['payment_status'] == 'paid') {
                $totalRevenue += $sub['price'];
                
                if (date('Y-m', strtotime($sub['created_at'])) == $currentMonth) {
                    $thisMonthRevenue += $sub['price'];
                }
            }
        }

        $data = [
            'title' => 'Manajemen Langganan',
            'subscriptions' => $subscriptions,
            'totalRevenue' => $totalRevenue,
            'thisMonthRevenue' => $thisMonthRevenue
        ];

        return view('administrator/subscription/index', $data);
    }

    public function export()
    {
        $subscriptions = $this->userSubscriptionModel->select('user_subscriptions.*, users.username, users.email, subscription_plans.name as plan_name, subscription_plans.price')
            ->join('users', 'users.id = user_subscriptions.user_id')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->orderBy('user_subscriptions.created_at', 'DESC')
            ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Properties
        $spreadsheet->getProperties()->setCreator('FinanceFlow Admin')->setTitle('Data Langganan Premium');

        // Headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Plan');
        $sheet->setCellValue('E1', 'Harga (Rp)');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Mulai');
        $sheet->setCellValue('H1', 'Berakhir');
        $sheet->setCellValue('I1', 'Pembayaran');

        // Styling Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '009E60']]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $row = 2;
        foreach ($subscriptions as $key => $sub) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $sub['username']);
            $sheet->setCellValue('C' . $row, $sub['email']);
            $sheet->setCellValue('D' . $row, $sub['plan_name']);
            $sheet->setCellValue('E' . $row, $sub['price']);
            $sheet->setCellValue('F' . $row, $sub['status']);
            $sheet->setCellValue('G' . $row, $sub['start_date']);
            $sheet->setCellValue('H' . $row, $sub['end_date']);
            $sheet->setCellValue('I' . $row, $sub['payment_status']);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Langganan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if ($this->userSubscriptionModel->update($id, ['status' => $status])) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status langganan berhasil diperbarui'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal memperbarui status'
        ]);
    }
}
