<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TransactionModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transactionModel = new TransactionModel();
    }

    public function login()
    {
        $session = session();
        $validation = \Config\Services::validation();

        if ($this->request->getMethod() === 'post') {
            $validation->setRules([
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $user = $this->userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Ambil group dari Myth/Auth
                    $groups = service('authorization')->getGroupsForUser($user['id']);
                    $role = !empty($groups) ? $groups[0] : 'user';
                    // Set user session data
                    $sessionData = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'fullname' => $user['fullname'] ?? $user['name'],
                        'username' => $user['username'] ?? $user['email'],
                        'profile_picture' => $user['user_image'] ?? 'default.jpg',
                        'role' => $role,
                        'logged_in' => TRUE
                    ];
                    $session->set($sessionData);

                    // Pre-load initial data
                    $this->transactionModel->initializeUserData($user['id']);

                    return redirect()->to('app/dashboard');
                }

                $session->setFlashdata('error', 'Invalid email or password.');
            } else {
                $session->setFlashdata('error', 'Validation failed.');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('auth/login');
    }
}
