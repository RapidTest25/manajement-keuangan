<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'type', 'user_id'];
    protected $useTimestamps = true;

    public function getUserCategories($userId)
    {
        // Default categories that everyone should see
        $defaultCategories = [
            [
                'id' => 'default-1',
                'name' => 'Makanan',
                'type' => 'EXPENSE',
                'user_id' => 0
            ],
            [
                'id' => 'default-2',
                'name' => 'Hiburan',
                'type' => 'EXPENSE',
                'user_id' => 0
            ],
            [
                'id' => 'default-3',
                'name' => 'Transportasi',
                'type' => 'EXPENSE',
                'user_id' => 0
            ],
            [
                'id' => 'default-4',
                'name' => 'Gaji',
                'type' => 'INCOME',
                'user_id' => 0
            ],
            [
                'id' => 'default-5',
                'name' => 'Lainnya',
                'type' => 'EXPENSE',
                'user_id' => 0
            ],
            [
                'id' => 'default-6',
                'name' => 'Lainnya',
                'type' => 'INCOME',
                'user_id' => 0
            ]
        ];

        // Get user's custom categories
        $userCategories = $this->where('user_id', $userId)->findAll();

        // Merge default and user categories
        return array_merge($defaultCategories, $userCategories);
    }
}
