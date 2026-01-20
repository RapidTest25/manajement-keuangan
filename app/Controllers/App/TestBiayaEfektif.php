<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\BiayaEfektifModel;

class TestBiayaEfektif extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        echo "<h1>Debug Biaya Efektif</h1>";
        echo "<p>User ID from session: " . ($userId ? $userId : 'NULL') . "</p>";
        
        if ($userId) {
            $biayaEfektifModel = new BiayaEfektifModel();
            $data = $biayaEfektifModel->getBiayaEfektif($userId);
            
            echo "<p>Found " . count($data) . " records</p>";
            echo "<pre>" . print_r($data, true) . "</pre>";
            
            // Test calculation
            $total = 0;
            foreach ($data as $biaya) {
                $jumlah = floatval($biaya['jumlah']);
                echo "<p>Item: " . $biaya['nama_biaya'] . " - Jumlah: " . $jumlah . " - Frekuensi: " . $biaya['frekuensi'] . "</p>";
                
                switch ($biaya['frekuensi']) {
                    case 'Harian':
                    case 'harian':
                        $monthlyAmount = $jumlah * 30;
                        break;
                    case 'Mingguan':
                    case 'mingguan':
                        $monthlyAmount = $jumlah * 4;
                        break;
                    case 'Bulanan':
                    case 'bulanan':
                        $monthlyAmount = $jumlah;
                        break;
                    default:
                        $monthlyAmount = 0;
                        break;
                }
                
                echo "<p>Monthly contribution: " . $monthlyAmount . "</p>";
                $total += $monthlyAmount;
            }
            
            echo "<h2>Total Monthly: Rp " . number_format($total, 0, ',', '.') . "</h2>";
        }
    }
}
