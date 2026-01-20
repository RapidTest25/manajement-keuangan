<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class View extends BaseController
{
    public function login()
    {
        return view('Auth/Login');
    }

    public function register()
    {
        return view('Auth/register');
    }

    public function forgot()
    {
        return view('Auth/forgot');
    }

    public function reset()
    {
        return view('Auth/reset');
    }
}
