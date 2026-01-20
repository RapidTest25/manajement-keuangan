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

        if (strtolower($this->request->getMethod()) === 'post') {
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

                // --- FAILURE DIAGNOSIS START ---
                $found = $user ? "YES (ID: {$user['id']}, Email: {$user['email']})" : "NO";
                $passValid = ($user && password_verify($password, $user['password_hash'])) ? "YES" : "NO";
                die("<h1>LOGIN FAILED DIAGNOSIS</h1>
                     User Found in DB by '$login': <b>$found</b><br>
                     Password Verify Result: <b>$passValid</b><br>
                     <br>
                     If User Not Found: Check spelling or DB content.<br>
                     If Password Invalid: The password hash in DB doesn't match the input.");
                // --- FAILURE DIAGNOSIS END ---

                // return redirect()->back()->withInput()->with('error', 'Email/Username atau Password salah.');
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
