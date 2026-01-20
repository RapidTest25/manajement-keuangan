<?php

namespace App\Models;

use CodeIgniter\Model;

class SavingRecordModel extends Model
{
    protected $table = 'saving_records';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'savings_id',
        'user_id',
        'amount',
        'date',
        'status'
    ];

    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get saving records for a specific date
     * 
     * @param int $savingsId The savings ID
     * @param string $date Date in Y-m-d format
     * @return array|null Saving records for the date
     */
    public function getRecordForDate($savingsId, $date)
    {
        return $this->where('savings_id', $savingsId)
            ->where('date', $date)
            ->first();
    }

    /**
     * Get all records for a saving
     * 
     * @param int $savingsId The savings ID
     * @return array Saving records
     */
    public function getRecordsForSaving($savingsId)
    {
        return $this->where('savings_id', $savingsId)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    /**
     * Count successful days for a saving
     * 
     * @param int $savingsId The savings ID
     * @return int Number of successful days
     */
    public function countSuccessfulDays($savingsId)
    {
        return $this->where('savings_id', $savingsId)
            ->where('status', 'done')
            ->countAllResults();
    }

    /**
     * Get calendar data for a specific month
     * 
     * @param int $savingsId The savings ID
     * @param int $month Month (1-12)
     * @param int $year Year (YYYY)
     * @return array Calendar data
     */
    public function getCalendarData($savingsId, $month, $year)
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->where('savings_id', $savingsId)
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->findAll();
    }
}
