<?php

namespace App\Models;

use CodeIgniter\Model;

class BiayaEfektifModel extends Model
{
    protected $table = 'biaya_efektif';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'kategori', 'nama_biaya', 'jumlah', 'frekuensi', 'tanggal_mulai', 'tanggal_selesai', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBiayaEfektif($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_active', 1)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getTotalBiayaBulanan($userId)
    {
        $biayaEfektif = $this->getBiayaEfektif($userId);
        $total = 0;

        foreach ($biayaEfektif as $biaya) {
            $jumlah = floatval($biaya['jumlah']);
            
            switch ($biaya['frekuensi']) {
                case 'Harian':
                    $total += $jumlah * 30;
                    break;
                case 'Mingguan':
                    $total += $jumlah * 4;
                    break;
                case 'Bulanan':
                    $total += $jumlah;
                    break;
            }
        }
        
        return $total;
    }

    public function getBiayaAktif($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_active', 1)
                   ->where('(tanggal_selesai IS NULL OR tanggal_selesai >= CURDATE())')
                   ->findAll();
    }

    public function getBiayaByKategori($userId, $kategori = null)
    {
        $builder = $this->where('user_id', $userId)->where('is_active', 1);
        
        if ($kategori) {
            $builder->where('kategori', $kategori);
        }
        
        return $builder->findAll();
    }

    public function getStatistikBiaya($userId)
    {
        $builder = $this->db->table($this->table)
                           ->select('kategori, COUNT(*) as jumlah_item, SUM(jumlah) as total_biaya, frekuensi')
                           ->where('user_id', $userId)
                           ->where('is_active', 1)
                           ->groupBy(['kategori', 'frekuensi']);
        
        return $builder->get()->getResultArray();
    }

    public function nonaktifkanBiaya($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }

    public function addBiayaEfektif($data)
    {
        return $this->insert($data);
    }

    public function updateBiayaEfektif($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteBiayaEfektif($id)
    {
        return $this->delete($id);
    }
}
