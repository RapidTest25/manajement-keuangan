<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name', 'role', 'content', 'rating', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveReviews()
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getAllReviews()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getAverageRating()
    {
        $result = $this->selectAvg('rating')->where('status', 'active')->first();
        return round($result['rating'] ?? 0, 1);
    }
}
