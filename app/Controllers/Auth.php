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

        if ($this->request->getMethod() === 'post') {
            $validation->setRules([
                'login' => 'required',
                'password' => 'required|min_length[6]'
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $login = $this->request->getPost('login');
                $password = $this->request->getPost('password');

                // Allow login via email or username
                $user = $this->userModel->groupStart()
                    ->where('email', $login)
                    ->orWhere('username', $login)
                    ->groupEnd()
                    ->first();

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

                    log_message('error', '[Auth::login] Login Success for user: ' . $user['email']);
                    return redirect()->to('home');
                }

                log_message('error', '[Auth::login] Password mismatch or user not found. Input: ' . $login);
                $session->setFlashdata('error', 'Email/Username atau Password salah.');
            } else {
                log_message('error', '[Auth::login] Validation failed: ' . json_encode($validation->getErrors()));
                $session->setFlashdata('error', 'Validasi gagal. Mohon isi semua kolom.');
                $session->setFlashdata('errors', $validation->getErrors());
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
