<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\ReviewModel;

class Home extends BaseController
{
    protected $settingModel;
    protected $userModel;
    protected $transactionModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $totalUsers = $this->userModel->countAllResults();
        $totalTransactions = $this->transactionModel->countAllResults();
        $rating = $this->reviewModel->getAverageRating();

        $data = [
            'settings' => $this->settingModel->getAllSettings(),
            'stats' => [
                'total_users' => $totalUsers,
                'total_transactions' => $totalTransactions,
                'rating' => $rating
            ],
            'reviews' => $this->reviewModel->getActiveReviews()
        ];
        return view('landing_page', $data);
    }
}
