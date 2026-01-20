<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use Myth\Auth\Password;
use App\Models\LoginModel;
use App\Models\TransactionModel;

class LoginController extends BaseController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = service('authentication');
    }

    public function attemptLogin()
    {
        // Validasi input
        $rules = [
            'login' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');

        // Tentukan apakah login menggunakan email atau username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login];
        } else {
            $credentials = ['username' => $login];
        }
        $credentials['password'] = $password;

        // Coba login
        if (!$this->auth->attempt($credentials, $remember)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
        }

        // Ambil data user
        $user = $this->auth->user();

        // Set session data
        $transactionModel = new TransactionModel();
        $totalIncome = $transactionModel->getTotalIncome($user->username);

        // Ambil group dari Myth/Auth
        $groups = service('authorization')->getGroupsForUser($user->id);
        $role = !empty($groups) ? $groups[0] : 'user';
        session()->set([
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'fullname' => $user->fullname ?? $user->username,
            'profile_picture' => $user->user_image ?? 'default.jpg',
            'created_at' => $user->created_at,
            'total_income' => $totalIncome,
            'role' => $role,
            'logged_in' => true
        ]);

        // Set welcome message
        session()->setFlashdata('welcome_back', [
            'message' => 'Selamat datang kembali, ' . ($user->fullname ?? $user->username)
        ]);

        // Redirect ke halaman home
        return redirect()->to('/home')->with('message', lang('Auth.loginSuccess'));
    }

    public function logout()
    {
        if ($this->auth->check()) {
            $this->auth->logout();
        }

        return redirect()->to('/login');
    }
}
