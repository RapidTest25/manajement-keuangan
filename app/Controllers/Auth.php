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

                $passwordValid = false;
                if ($user) {
                    if (password_verify($password, $user['password_hash'])) {
                        $passwordValid = true;
                    } elseif ($password === $user['password_hash']) {
                        // Handle Plain Text Password (Legacy/Manual Insert)
                        $passwordValid = true;
                        // Optional: Re-hash and update here if desired, but for now just allow login
                    }
                }

                if ($user && $passwordValid) {
                    // Manual DB Query for Role to bypass Authorization Service issues
                    $db = \Config\Database::connect();
                    $roleQuery = $db->table('auth_groups_users')
                        ->select('auth_groups.name')
                        ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                        ->where('auth_groups_users.user_id', $user['id'])
                        ->get()
                    $role = $roleQuery ? $roleQuery->name : 'user';
                    
                    // Set user session data
                    $sessionData = [
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['fullname'] ?? $user['username'],
                        'fullname' => $user['fullname'] ?? $user['username'],
                        'username' => $user['username'] ?? $user['email'],
                        'profile_picture' => $user['user_image'] ?? 'default.jpg',
                        'role' => $role,
                        'logged_in' => TRUE
                    ];
                    $session->set($sessionData);
                    
                    // Force session write to ensure data persists before redirect
                    session_write_close();

                    // Pre-load initial data
                    try {
                        $this->transactionModel->initializeUserData($user['id']);
                    } catch (\Exception $e) {
                        log_message('error', '[Auth::login] Init Data Failed: ' . $e->getMessage());
                    }

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

    // --- EMERGENCY TOOL ---
    public function emergency_reset()
    {
        // Reset password for specific email to '123456'
        $email = 'ahmadkhadifar@gmail.com';
        $newPass = '123456';
        
        // Use default BCRYPT config if Myth/Auth config unavailable
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('email', $email);
        $builder->update(['password_hash' => $hash]);
        
        return "Password for $email has been reset to: $newPass <br> Hash: $hash <br> <a href='".base_url('login')."'>Go to Login</a>";
    }
    // ---------------------

    public function logout()
    {
        session()->destroy();
        return redirect()->to('auth/login');
    }
}
