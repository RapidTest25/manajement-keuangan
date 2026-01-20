<?php

namespace App\Models;

use CodeIgniter\Model;

class DebtNoteModel extends Model
{
    protected $table = 'debt_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'type',
        'borrowType',
        'application',
        'loan_amount',
        'payment_amount',
        'payment_period',
        'loan_duration',
        'due_date',
        'description',
        'status',
        'lender_category',
        'lender_name',
        'borrower_name',
        'amount_paid',
        'loan_date',
        'payments',
        'payments_count'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function addDebt($data)
    {
        $data['user_id'] = user_id();
        return $this->insert($data);
    }
    public function getDebts($userId)
    {
        try {
            $result = $this->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->findAll();

            // Debug log
            log_message('debug', 'Query: ' . $this->db->getLastQuery());
            log_message('debug', 'Result count: ' . count($result ?: []));
            log_message('debug', 'Result: ' . json_encode($result));

            return $result ?: []; // Return empty array instead of null if no results
        } catch (\Exception $e) {
            log_message('error', 'Error in getDebts: ' . $e->getMessage());
            return [];
        }
    }
}
