<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CicilanModel;
use App\Models\DebtNoteModel;
use App\Models\UserModel;
use App\Models\SavingsModel;

class Program extends BaseController
{
    protected $categoryModel;
    protected $cicilanModel;
    protected $debtNoteModel;
    protected $userModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->cicilanModel = new CicilanModel();
        $this->debtNoteModel = new DebtNoteModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $savingsModel = new SavingsModel();
        $userTargets = $savingsModel->getAllUserTargets();
        $userIds = array_column($userTargets, 'user_id');
        $users = [];
        if ($userIds) {
            $users = $this->userModel->whereIn('id', $userIds)->findAll();
            $users = array_column($users, null, 'id'); // id => user array
        }

        // Gabungkan username/email ke data target
        foreach ($userTargets as &$target) {
            $user = $users[$target['user_id']] ?? null;
            $target['username'] = $user['fullname'] ?? $user['username'] ?? $target['user_id'];
        }

        $data = [
            'title' => 'Program Monitoring',
            // Get total counts
            'totalCategories' => $this->categoryModel->countAll(),
            'totalCicilan' => $this->cicilanModel->countAll(),
            'totalDebtNotes' => $this->debtNoteModel->countAll(),
            'totalUsers' => $this->userModel->countAll(),

            // Get latest entries
            'latestCategories' => $this->categoryModel
                ->select('categories.*, users.username')
                ->join('users', 'users.id = categories.user_id')
                ->orderBy('categories.created_at', 'DESC')
                ->limit(5)
                ->find(),
            'latestCicilan' => $this->cicilanModel
                ->select('cicilan.*, users.username')
                ->join('users', 'users.id = cicilan.user_id')
                ->orderBy('cicilan.created_at', 'DESC')
                ->limit(5)
                ->find(),
            'latestDebtNotes' => $this->debtNoteModel
                ->select('debt_notes.*, users.username')
                ->join('users', 'users.id = debt_notes.user_id')
                ->orderBy('debt_notes.created_at', 'DESC')
                ->limit(5)
                ->find(),
            'latestUsers' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'userSavingsTargets' => $userTargets,
        ];

        return view('administrator/program', $data);
    }
}
