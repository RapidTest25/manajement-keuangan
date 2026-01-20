<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\BiayaEfektifModel;

class BiayaEfektif extends BaseController
{
    protected $biayaEfektifModel;
    protected $session;

    public function __construct()
    {
        $this->biayaEfektifModel = new BiayaEfektifModel();
        $this->session = session();
    }

    public function index()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Sesi telah berakhir');
        }

        $data = [
            'title' => 'Biaya Efektif',
            'biayaEfektif' => $this->biayaEfektifModel->getBiayaEfektif($userId)
        ];
        return view('app/biayaEfektif', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'user_id' => $this->session->get('user_id'),
                'kategori' => $this->request->getPost('kategori'),
                'nama_biaya' => $this->request->getPost('nama_biaya'),
                'jumlah' => $this->request->getPost('jumlah'),
                'frekuensi' => $this->request->getPost('frekuensi'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'is_active' => 1
            ];

            if ($this->biayaEfektifModel->addBiayaEfektif($data)) {
                return redirect()->to('/app/biayaefektif')->with('success', 'Biaya efektif berhasil ditambahkan');
            }
            return redirect()->back()->with('error', 'Gagal menambahkan biaya efektif');
        }
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'kategori' => $this->request->getPost('kategori'),
                'nama_biaya' => $this->request->getPost('nama_biaya'),
                'jumlah' => $this->request->getPost('jumlah'),
                'frekuensi' => $this->request->getPost('frekuensi'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'is_active' => $this->request->getPost('is_active')
            ];

            if ($this->biayaEfektifModel->updateBiayaEfektif($id, $data)) {
                return redirect()->to('/app/biayaefektif')->with('success', 'Biaya efektif berhasil diupdate');
            }
            return redirect()->back()->with('error', 'Gagal mengupdate biaya efektif');
        }
    }

    public function delete($id)
    {
        if ($this->biayaEfektifModel->deleteBiayaEfektif($id)) {
            return redirect()->to('/app/biayaefektif')->with('success', 'Biaya efektif berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus biaya efektif');
    }
}
