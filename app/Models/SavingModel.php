<?php

namespace App\Models;

use CodeIgniter\Model;

class SavingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'savings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'target_amount',
        'daily_amount',
        'saved_amount',
        'start_date',
        'estimated_end_date',
        'is_achieved',
        'wish_target',
        'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getSavingTarget($userId)
    {
        $target = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($target) {
            // Get related saving records
            $recordModel = new SavingRecordModel();
            $records = $recordModel->where('savings_id', $target['id'])->findAll();

            // Count successful payments
            $paymentCount = 0;
            foreach ($records as $record) {
                if ($record['status'] == 'done') {
                    $paymentCount++;
                }
            }

            // Calculate additional metrics
            $target['payment_count'] = $paymentCount;
            $target['progress_percentage'] = ($target['saved_amount'] / $target['target_amount']) * 100;
            $target['total_days_needed'] = ceil($target['target_amount'] / $target['daily_amount']);
            $target['remaining_amount'] = $target['target_amount'] - $target['saved_amount'];
            $target['days_left'] = ceil($target['remaining_amount'] / $target['daily_amount']);

            return $target;
        }

        return null;
    }

    public function getMonthlySavings($userId, $month, $year)
    {
        // Get current active target
        $target = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$target) {
            return null;
        }

        $recordModel = new SavingRecordModel();

        // Get all savings records for the month
        $startDate = $year . '-' . $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $savings = $recordModel->where('savings_id', $target['id'])
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->findAll();

        // Calculate total saved in this month
        $totalSaved = 0;
        foreach ($savings as $record) {
            if ($record['status'] == 'done') {
                $totalSaved += $target['daily_amount'];
            }
        }

        // Calculate progress percentage for this month
        $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $progressPercentage = ($totalSaved / ($target['daily_amount'] * $totalDaysInMonth)) * 100;

        return [
            'target' => $target,
            'savings' => $savings,
            'totalSaved' => $totalSaved,
            'dailyTarget' => $target['daily_amount'],
            'targetAmount' => $target['target_amount'],
            'progressPercentage' => $progressPercentage
        ];
    }

    public function updateSaving($data)
    {
        $recordModel = new SavingRecordModel();
        $savingId = $data['savings_id'];
        $date = $data['date'];
        $status = $data['status'];

        // Check if record exists
        $existingRecord = $recordModel->where('savings_id', $savingId)
            ->where('date', $date)
            ->first();

        if ($existingRecord) {
            // Update existing record
            $recordModel->update($existingRecord['id'], ['status' => $status]);
        } else {
            // Create new record
            $recordModel->insert([
                'savings_id' => $savingId,
                'date' => $date,
                'status' => $status
            ]);
        }

        // Update saved amount in savings target
        $target = $this->find($savingId);
        if ($target) {
            // Count all successful savings
            $records = $recordModel->where('savings_id', $savingId)
                ->where('status', 'done')
                ->findAll();

            $savedAmount = count($records) * $target['daily_amount'];
            $isAchieved = ($savedAmount >= $target['target_amount']);

            // Update savings with new total
            $this->update($savingId, [
                'saved_amount' => $savedAmount,
                'is_achieved' => $isAchieved
            ]);

            // Return updated target with additional data
            $updatedTarget = $this->find($savingId);
            $updatedTarget['payment_count'] = count($records);
            $updatedTarget['progress_percentage'] = ($savedAmount / $target['target_amount']) * 100;
            $updatedTarget['total_days_needed'] = ceil($target['target_amount'] / $target['daily_amount']);
            $updatedTarget['remaining_amount'] = $target['target_amount'] - $savedAmount;
            $updatedTarget['days_left'] = ceil($updatedTarget['remaining_amount'] / $target['daily_amount']);
            $updatedTarget['is_achieved'] = $isAchieved;

            return $updatedTarget;
        }

        return null;
    }

    public function saveSavingTarget($data)
    {
        $userId = $data['user_id'];
        $isNew = $data['is_new'] ?? false;

        if ($isNew) {
            // Create new target
            $result = $this->insert([
                'user_id' => $userId,
                'target_amount' => $data['target_amount'],
                'daily_amount' => $data['daily_amount'],
                'saved_amount' => 0,
                'start_date' => date('Y-m-d'),
                'estimated_end_date' => date('Y-m-d', strtotime('+' . ceil($data['target_amount'] / $data['daily_amount']) . ' days')),
                'is_achieved' => 0,
                'wish_target' => $data['wish_target'],
                'description' => $data['description'] ?? ''
            ]);

            $newTarget = $this->find($this->getInsertID());
            $newTarget['payment_count'] = 0;
            $newTarget['progress_percentage'] = 0;
            $newTarget['total_days_needed'] = ceil($data['target_amount'] / $data['daily_amount']);
            $newTarget['remaining_amount'] = $data['target_amount'];
            $newTarget['days_left'] = $newTarget['total_days_needed'];
            $newTarget['is_achieved'] = false;

            return ['status' => (bool)$result, 'target' => $newTarget];
        } else {
            // Find current active target
            $current = $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();

            if (!$current) {
                return ['status' => false, 'message' => 'No active target found'];
            }

            // Update existing target
            $result = $this->update($current['id'], [
                'target_amount' => $data['target_amount'],
                'daily_amount' => $data['daily_amount'],
                'estimated_end_date' => date('Y-m-d', strtotime('+' . ceil(($data['target_amount'] - $current['saved_amount']) / $data['daily_amount']) . ' days')),
                'wish_target' => $data['wish_target'],
                'description' => $data['description'] ?? ''
            ]);

            $updatedTarget = $this->find($current['id']);

            // Calculate additional metrics
            $recordModel = new SavingRecordModel();
            $records = $recordModel->where('savings_id', $updatedTarget['id'])
                ->where('status', 'done')
                ->findAll();

            $updatedTarget['payment_count'] = count($records);
            $updatedTarget['progress_percentage'] = ($updatedTarget['saved_amount'] / $updatedTarget['target_amount']) * 100;
            $updatedTarget['total_days_needed'] = ceil($updatedTarget['target_amount'] / $updatedTarget['daily_amount']);
            $updatedTarget['remaining_amount'] = $updatedTarget['target_amount'] - $updatedTarget['saved_amount'];
            $updatedTarget['days_left'] = ceil($updatedTarget['remaining_amount'] / $updatedTarget['daily_amount']);

            return ['status' => (bool)$result, 'target' => $updatedTarget];
        }
    }
}
