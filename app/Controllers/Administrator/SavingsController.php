<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\SavingsModel;
use App\Models\UserModel;

class SavingsController extends BaseController
{
    public function index()
    {
        $savingsModel = new SavingsModel();
        $userModel = new UserModel();
        $savings = $savingsModel->getAllUserTargets();
        $userIds = array_column($savings, 'user_id');
        $users = [];
        if ($userIds) {
            $users = $userModel->whereIn('id', $userIds)->findAll();
            $users = array_column($users, null, 'id');
        }
        foreach ($savings as &$target) {
            $user = $users[$target['user_id']] ?? null;
            $target['username'] = $user['fullname'] ?? $user['username'] ?? $target['user_id'];
        }
        return view('administrator/savings', [
            'title' => 'Daftar Target Tabungan',
            'savings' => $savings
        ]);
    }
}
