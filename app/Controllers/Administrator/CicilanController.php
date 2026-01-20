<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\CicilanModel;
use App\Models\UserModel;

class CicilanController extends BaseController
{
    public function index()
    {
        $cicilanModel = new CicilanModel();
        $userModel = new UserModel();
        $cicilan = $cicilanModel->select('cicilan.*, users.username')
            ->join('users', 'users.id = cicilan.user_id')
            ->orderBy('cicilan.created_at', 'DESC')
            ->findAll();
        return view('administrator/cicilan', [
            'title' => 'Daftar Cicilan',
            'cicilan' => $cicilan
        ]);
    }
}
