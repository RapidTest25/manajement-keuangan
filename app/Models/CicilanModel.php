<?php

namespace App\Models;

use CodeIgniter\Model;

class CicilanModel extends Model
{
    protected $table = 'cicilan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'name',
        'type',
        'total_amount',
        'monthly_amount',
        'tenor',
        'paid_amount',
        'remaining_amount',
        'start_date',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUserCicilan($userId, $filter = 'all')
    {
        $builder = $this->where('user_id', $userId);

        if ($filter === 'active') {
            $builder->where('status', 'active');
        } else if ($filter === 'completed') {
            $builder->where('status', 'completed');
        }

        return $builder->orderBy('created_at', 'DESC')->find();
    }
    public function saveCicilan($data)
    {
        // Calculate total amount from monthly payment and tenor
        $data['total_amount'] = $data['monthly_amount'] * $data['tenor'];

        // Calculate remaining amount
        $data['remaining_amount'] = $data['total_amount'];

        // Set initial paid amount
        if (!isset($data['paid_amount'])) {
            $data['paid_amount'] = 0;
        }

        return $this->insert($data);
    }

    public function markPaid($cicilanId, $userId)
    {
        $cicilan = $this->where('id', $cicilanId)
            ->where('user_id', $userId)
            ->first();

        if (!$cicilan) {
            throw new \Exception('Cicilan tidak ditemukan');
        }

        // Add monthly amount to paid amount
        $newPaidAmount = $cicilan['paid_amount'] + $cicilan['monthly_amount'];
        $newRemainingAmount = $cicilan['total_amount'] - $newPaidAmount;

        // Check if cicilan is completed
        $status = $newPaidAmount >= $cicilan['total_amount'] ? 'completed' : 'active';

        // If payment would exceed total, adjust the amounts
        if ($newPaidAmount > $cicilan['total_amount']) {
            $newPaidAmount = $cicilan['total_amount'];
            $newRemainingAmount = 0;
        }

        return $this->update($cicilanId, [
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $newRemainingAmount,
            'status' => $status
        ]);
    }
}
