<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;

class Calculator extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Kalkulator Keuangan'
        ];
        return view('app/calculator', $data);
    }
}
