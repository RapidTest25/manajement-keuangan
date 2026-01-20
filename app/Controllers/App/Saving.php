<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\SavingModel;

class Saving extends BaseController
{
    protected $savingModel;

    public function __construct()
    {
        $this->savingModel = new SavingModel();
    }

    public function index()
    {
        // Premium check
        if (!is_premium(user_id())) {
            return redirect()->to('app/subscription/plans')->with('error', 'Fitur Menabung hanya untuk pengguna Premium');
        }

        $data = [
            'title' => 'Nabung',
            'targetAmount' => 10000, // You can make this configurable
        ];
        return view('app/nabung', $data);
    }

    public function getSavings()
    {
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');
        $userId = user_id();

        $savings = $this->savingModel->getMonthlySavings($userId, $month, $year);
        return $this->response->setJSON($savings);
    }

    public function updateStatus()
    {
        $userId = user_id();
        $date = $this->request->getPost('date');
        $status = $this->request->getPost('status');

        $result = $this->savingModel->updateSavingStatus($userId, $date, $status);
        return $this->response->setJSON(['success' => $result]);
    }
}
