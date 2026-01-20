<?php

if (!function_exists('get_total_biaya_efektif_bulanan')) {
    /**
     * Mendapatkan total biaya efektif bulanan untuk user
     * 
     * @param int $userId
     * @return float
     */
    function get_total_biaya_efektif_bulanan($userId)
    {
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
        return $biayaEfektifModel->getTotalBiayaBulanan($userId);
    }
}

if (!function_exists('get_biaya_efektif_by_kategori')) {
    /**
     * Mendapatkan biaya efektif berdasarkan kategori
     * 
     * @param int $userId
     * @param string|null $kategori
     * @return array
     */
    function get_biaya_efektif_by_kategori($userId, $kategori = null)
    {
        $biayaEfektifModel = new \App\Models\BiayaEfektifModel();
        return $biayaEfektifModel->getBiayaByKategori($userId, $kategori);
    }
}

if (!function_exists('calculate_monthly_cost')) {
    /**
     * Menghitung biaya bulanan berdasarkan jumlah dan frekuensi
     * 
     * @param float $amount
     * @param string $frequency
     * @return float
     */
    function calculate_monthly_cost($amount, $frequency)
    {
        $amount = floatval($amount);
        
        switch (strtolower($frequency)) {
            case 'harian':
                return $amount * 30;
            case 'mingguan':
                return $amount * 4;
            case 'bulanan':
                return $amount;
            default:
                return $amount;
        }
    }
}

if (!function_exists('format_biaya_efektif_date')) {
    /**
     * Format tanggal untuk biaya efektif
     * 
     * @param string $dateString
     * @return string
     */
    function format_biaya_efektif_date($dateString)
    {
        if (!$dateString || $dateString === '1970-01-01' || $dateString === '0000-00-00' || $dateString === 'null') {
            return 'Tidak ada batas';
        }
        
        $date = date_create($dateString);
        if (!$date) {
            return 'Tidak ada batas';
        }
        
        return date_format($date, 'd/m/Y');
    }
}

if (!function_exists('is_biaya_efektif_active')) {
    /**
     * Cek apakah biaya efektif masih aktif
     * 
     * @param array $biayaEfektif
     * @return bool
     */
    function is_biaya_efektif_active($biayaEfektif)
    {
        if (!$biayaEfektif['is_active']) {
            return false;
        }
        
        if ($biayaEfektif['tanggal_selesai'] && $biayaEfektif['tanggal_selesai'] !== '0000-00-00') {
            $endDate = date_create($biayaEfektif['tanggal_selesai']);
            $today = date_create('today');
            
            return $endDate >= $today;
        }
        
        return true;
    }
}

if (!function_exists('get_biaya_efektif_kategori_list')) {
    /**
     * Mendapatkan daftar kategori biaya efektif
     * 
     * @return array
     */
    function get_biaya_efektif_kategori_list()
    {
        return [
            'Rumah',
            'Kendaraan', 
            'Listrik',
            'Air',
            'Internet',
            'Pendidikan',
            'Kesehatan',
            'Asuransi',
            'Hiburan',
            'Lainnya'
        ];
    }
}

if (!function_exists('get_biaya_efektif_frekuensi_list')) {
    /**
     * Mendapatkan daftar frekuensi biaya efektif
     * 
     * @return array
     */
    function get_biaya_efektif_frekuensi_list()
    {
        return [
            'Harian',
            'Mingguan',
            'Bulanan'
        ];
    }
}
