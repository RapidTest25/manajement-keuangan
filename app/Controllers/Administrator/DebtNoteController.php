<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\DebtNoteModel;
use App\Models\UserModel;

class DebtNoteController extends BaseController
{
    public function index()
    {
        $debtNoteModel = new DebtNoteModel();
        $userModel = new UserModel();
        $debtNotes = $debtNoteModel->select('debt_notes.*, users.username')
            ->join('users', 'users.id = debt_notes.user_id')
            ->orderBy('debt_notes.created_at', 'DESC')
            ->findAll();
        return view('administrator/debt_notes', [
            'title' => 'Daftar Catatan Utang',
            'debtNotes' => $debtNotes
        ]);
    }
}
