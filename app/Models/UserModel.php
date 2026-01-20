<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'email',
        'username',
        'fullname',
        'user_image',
        'password_hash',
        'password',
        'status',
        'active'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $beforeInsert = ['setActiveStatus'];
    protected function setActiveStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'active';
        }
        if (!isset($data['data']['active'])) {
            $data['data']['active'] = 1;
        }
        return $data;
    }

    public function getUser($id)
    {
        return $this->where(['id' => $id])
            ->first();
    }

    public function updateProfile($userId, $data)
    {
        // Filter only allowed fields
        $updateData = array_intersect_key($data, array_flip($this->allowedFields));

        return $this->update($userId, $updateData);
    }

    /**
     * Check if user has active premium subscription
     */
    public function hasPremiumSubscription($userId)
    {
        $subscriptionModel = new UserSubscriptionModel();
        return $subscriptionModel->hasPremiumSubscription($userId);
    }

    /**
     * Get user's current subscription
     */
    public function getCurrentSubscription($userId)
    {
        $subscriptionModel = new UserSubscriptionModel();
        return $subscriptionModel->getActiveSubscription($userId);
    }

    /**
     * Get user with subscription info
     */
    public function getUserWithSubscription($userId)
    {
        $user = $this->getUser($userId);
        if ($user) {
            $subscriptionModel = new UserSubscriptionModel();
            $user['subscription'] = $subscriptionModel->getActiveSubscription($userId);
            $user['is_premium'] = $subscriptionModel->hasPremiumSubscription($userId);
        }
        return $user;
    }
}

