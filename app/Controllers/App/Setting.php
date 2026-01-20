<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Setting extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Get current user data
        $userId = user_id();
        $userData = $this->userModel->find($userId);

        if (!$userData) {
            return redirect()->to('/login');
        }

        // Set session data
        session()->set($userData);

        return view('app/settingAccount', [
            'user' => $userData
        ]);
    }
}
