<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            // DEBUG SESSION LOST
            // die('<h1>Access Denied by Filter</h1>Session Data: <pre>' . print_r(session()->get(), true) . '</pre><br>Session ID: ' . session_id() . '<br>Save Path: ' . session_save_path());

            // Store the current URL to redirect back after login
            session()->setFlashdata('redirect_url', current_url());

            // Set error message
            session()->setFlashdata('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');

            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
