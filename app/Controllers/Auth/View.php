<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class View extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function forgot()
    {
        return view('auth/forgot');
    }

    public function reset()
    {
        return view('auth/reset');
    }
}
