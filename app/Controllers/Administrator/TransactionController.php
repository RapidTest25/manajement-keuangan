<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Transaksi',
            'transactions' => $this->transactionModel
                ->select('transaction.*, transaction.users as username')
                ->orderBy('transaction.transaction_date', 'DESC')
                ->findAll(),
            'categories' => $this->categoryModel->findAll()
        ];

        return view('administrator/transaction', $data);
    }

    public function view($id)
    {
        $transaction = $this->transactionModel
            ->select('transaction.*, users.username, users.email')
            ->join('users', 'users.username = transaction.users')
            ->where('transaction.id', $id)
            ->first();

        if (!$transaction) {
            return $this->response->setJSON(['error' => 'Transaksi tidak ditemukan']);
        }

        $html = '
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td width="30%">ID Transaksi</td>
                    <td width="5%">:</td>
                    <td>' . $transaction['id'] . '</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>' . date('d M Y', strtotime($transaction['transaction_date'])) . '</td>
                </tr>
                <tr>
                    <td>User</td>
                    <td>:</td>
                    <td>' . $transaction['username'] . ' (' . $transaction['email'] . ')</td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td>' . $transaction['description'] . '</td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td>' . $transaction['category'] . '</td>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <td>:</td>
                    <td class="' . ($transaction['status'] === 'INCOME' ? 'text-success' : 'text-danger') . '">
                        ' . ($transaction['status'] === 'INCOME' ? '+' : '-') . 'Rp ' . number_format($transaction['amount'], 0, ',', '.') . '
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><span class="status-badge ' . strtolower($transaction['status']) . '">' . $transaction['status'] . '</span></td>
                </tr>
            </table>
        </div>';

        return $this->response->setJSON(['html' => $html]);
    }

    public function get($id)
    {
        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return $this->response->setJSON(['error' => 'Transaksi tidak ditemukan']);
        }

        return $this->response->setJSON($transaction);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'transaction_date' => $this->request->getPost('transaction_date'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'amount' => $this->request->getPost('amount'),
            'status' => $this->request->getPost('status')
        ];

        $success = $this->transactionModel->update($id, $data);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Transaksi berhasil diupdate' : 'Gagal mengupdate transaksi'
        ]);
    }
}
