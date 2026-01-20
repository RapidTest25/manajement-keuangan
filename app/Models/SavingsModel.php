<?php

namespace App\Models;

use CodeIgniter\Model;

class SavingsModel extends Model
{
    protected $table = 'savings';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id',
        'target_amount',
        'daily_amount',
        'wish_target',
        'description',
        'saved_amount',
        'payment_count',
        'total_days_needed',
        'start_date',
        'target_date',
        'is_achieved'
    ];

    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get the active saving target for a user
     * 
     * @param int $userId User ID
     * @return array|null User's active savings target
     */
    public function getUserTarget($userId)
    {
        $target = $this->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$target) {
            return [
                'id' => null,
                'target_amount' => 0,
                'daily_amount' => 0,
                'saved_amount' => 0,
                'payment_count' => 0,
                'total_days_needed' => 0,
                'wish_target' => '',
                'description' => '',
                'start_date' => date('Y-m-d'),
                'progress_percentage' => 0,
                'days_left' => 0,
                'successful_days' => 0
            ];
        }

        // Ensure numeric values
        $target['target_amount'] = floatval($target['target_amount']);
        $target['daily_amount'] = floatval($target['daily_amount']);
        $target['saved_amount'] = floatval($target['saved_amount']);
        $target['payment_count'] = intval($target['payment_count']);
        $target['successful_days'] = intval($target['payment_count']);

        // Calculate days left to reach target
        $daysLeft = max(0, $target['total_days_needed'] - $target['payment_count']);
        $target['days_left'] = $daysLeft;

        // Calculate overall progress percentage
        $target['progress_percentage'] = $target['target_amount'] > 0
            ? min(100, round(($target['saved_amount'] / $target['target_amount']) * 100))
            : 0;

        return $target;
    }

    /**
     * Save a new or update existing saving target for a user
     * 
     * @param int $userId User ID
     * @param array $data Target data
     * @param bool $isReset Whether to reset progress
     * @return bool|int Success status or new ID
     */
    public function saveUserTarget($userId, $data, $isReset = false)
    {
        try {
            $this->db->transStart();

            // Format data properly with numeric values
            $targetData = [
                'user_id' => $userId,
                'target_amount' => floatval($data['target_amount']),
                'daily_amount' => floatval($data['daily_amount']),
                'wish_target' => $data['wish_target'],
                'description' => $data['description'] ?? '',
                'start_date' => date('Y-m-d'),
                'total_days_needed' => ceil(floatval($data['target_amount']) / floatval($data['daily_amount']))
            ];

            // Initial values for new or reset targets
            if ($isReset) {
                $targetData['saved_amount'] = 0;
                $targetData['payment_count'] = 0;
                $targetData['is_achieved'] = 0;
            }

            // Validate the data
            if ($targetData['target_amount'] <= 0) {
                throw new \Exception('Target harus lebih dari 0');
            }

            if ($targetData['daily_amount'] <= 0) {
                throw new \Exception('Target harian harus lebih dari 0');
            }

            // Debug log
            log_message('debug', 'Target data to save: ' . json_encode($targetData));

            // Get existing target
            $existing = $this->where('user_id', $userId)->orderBy('id', 'DESC')->first();

            // For reset or new target, create a new record
            if ($isReset || !$existing) {
                // Insert new record
                $success = $this->insert($targetData);
                $newId = $this->getInsertID();

                // Delete old saving records if this is a reset
                if ($isReset && $existing) {
                    $recordModel = new SavingRecordModel();
                    $recordModel->where('savings_id', $existing['id'])->delete();
                }

                $this->db->transComplete();

                return $newId;
            } else {
                // Update existing record
                // Do not update the payment_count and saved_amount in the simple update case
                $currentValues = $this->find($existing['id']);
                $targetData['payment_count'] = $currentValues['payment_count'];
                $targetData['saved_amount'] = $currentValues['saved_amount'];

                $success = $this->update($existing['id'], $targetData);

                $this->db->transComplete();

                return $success;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saving target: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update the saving progress
     * 
     * @param int $savingsId Savings ID
     * @param string $status Status (done/missed)
     * @param string $date The date for this saving
     * @return bool Success status
     */
    public function updateProgress($savingsId, $status, $date)
    {
        try {
            $this->db->transStart();

            $recordModel = new SavingRecordModel();
            $savings = $this->find($savingsId);

            if (!$savings) {
                throw new \Exception('Target tabungan tidak ditemukan');
            }

            // Check if a record already exists for this date
            $existingRecord = $recordModel->where('savings_id', $savingsId)
                ->where('date', $date)
                ->first();

            $dailyAmount = floatval($savings['daily_amount']);

            if ($existingRecord) {
                // Update existing record
                if ($existingRecord['status'] === $status) {
                    // Status unchanged, nothing to update
                    $this->db->transComplete();
                    return true;
                }

                // Update the record status
                $recordModel->update($existingRecord['id'], ['status' => $status]);
            } else {
                // Create a new record
                $recordData = [
                    'savings_id' => $savingsId,
                    'user_id' => $savings['user_id'],
                    'amount' => $dailyAmount,
                    'date' => $date,
                    'status' => $status
                ];
                $recordModel->insert($recordData);
            }

            // Hitung ulang payment_count dan saved_amount
            $doneCount = $recordModel->where('savings_id', $savingsId)
                ->where('status', 'done')
                ->countAllResults();
            $savedAmount = $doneCount * $dailyAmount;
            $totalDaysNeeded = intval($savings['total_days_needed']);
            $targetAmount = floatval($savings['target_amount']);

            // Jangan melebihi target
            if ($savedAmount > $targetAmount) {
                $savedAmount = $targetAmount;
            }
            if ($doneCount > $totalDaysNeeded) {
                $doneCount = $totalDaysNeeded;
            }

            // is_achieved hanya true jika saved_amount >= target_amount DAN payment_count >= total_days_needed
            $isAchieved = ($savedAmount >= $targetAmount && $doneCount >= $totalDaysNeeded) ? 1 : 0;

            // Log debug
            log_message('debug', 'updateProgress: saved_amount=' . $savedAmount . ', target_amount=' . $targetAmount . ', payment_count=' . $doneCount . ', total_days_needed=' . $totalDaysNeeded . ', is_achieved=' . $isAchieved);

            // Update the savings record
            $this->update($savingsId, [
                'payment_count' => $doneCount,
                'saved_amount' => $savedAmount,
                'is_achieved' => $isAchieved
            ]);

            $this->db->transComplete();
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error updating progress: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get monthly savings records
     * 
     * @param int $userId User ID
     * @param int $month Month number (1-12)
     * @param int $year Year (YYYY)
     * @return array Monthly savings data
     */
    public function getMonthlySavings($userId, $month, $year)
    {
        // Get the active savings
        $activeSavings = $this->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$activeSavings) {
            return [
                'savings' => []
            ];
        }

        // Get records for the given month
        $recordModel = new SavingRecordModel();
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $records = $recordModel->where('savings_id', $activeSavings['id'])
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->findAll();

        $result = [];
        foreach ($records as $record) {
            $result[] = [
                'date' => $record['date'],
                'status' => $record['status'],
                'amount' => $record['amount']
            ];
        }

        return [
            'savings' => $result
        ];
    }

    /**
     * Get the latest saving target for all users
     * @return array List of user saving targets
     */
    public function getAllUserTargets()
    {
        // Ambil target terbaru per user
        $builder = $this->db->table($this->table . ' s1');
        $builder->select('s1.*');
        $builder->join(
            "(SELECT user_id, MAX(id) as max_id FROM {$this->table} GROUP BY user_id) s2",
            's1.user_id = s2.user_id AND s1.id = s2.max_id',
            'inner'
        );
        $query = $builder->get();
        return $query->getResultArray();
    }
}
