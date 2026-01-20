<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSubscriptionModel extends Model
{
    protected $table = 'user_subscriptions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get active subscription for a user
     */
    public function getActiveSubscription($userId)
    {
        return $this->select('user_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.slug as plan_slug, subscription_plans.features')
                    ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
                    ->where('user_subscriptions.user_id', $userId)
                    ->where('user_subscriptions.status', 'active')
                    ->where('user_subscriptions.end_date >=', date('Y-m-d H:i:s'))
                    ->orderBy('user_subscriptions.end_date', 'DESC')
                    ->first();
    }

    /**
     * Check if user has active premium subscription
     */
    public function hasPremiumSubscription($userId)
    {
        $subscription = $this->getActiveSubscription($userId);
        
        if (!$subscription) {
            return false;
        }

        // Check if subscription is not free plan
        return $subscription['plan_slug'] !== 'free';
    }

    /**
     * Check if user has access to premium feature
     */
    public function hasFeatureAccess($userId, $feature)
    {
        $subscription = $this->getActiveSubscription($userId);
        
        if (!$subscription) {
            return false;
        }

        // Premium users have access to all features
        if ($subscription['plan_slug'] !== 'free') {
            return true;
        }

        return false;
    }

    /**
     * Create new subscription for user
     */
    public function createSubscription($userId, $planId, $paymentMethod = null)
    {
        $planModel = new SubscriptionPlanModel();
        $plan = $planModel->find($planId);

        if (!$plan) {
            return false;
        }

        // Cancel any existing active subscriptions
        $this->where('user_id', $userId)
             ->where('status', 'active')
             ->set(['status' => 'cancelled'])
             ->update();

        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime("+{$plan['duration_days']} days"));

        // Free plan never expires
        if ($plan['price'] == 0) {
            $endDate = date('Y-m-d H:i:s', strtotime('+100 years'));
        }

        $data = [
            'user_id' => $userId,
            'plan_id' => $planId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'payment_method' => $paymentMethod,
            'payment_status' => $plan['price'] == 0 ? 'paid' : 'pending',
            'transaction_id' => $plan['price'] > 0 ? 'TRX-' . time() . '-' . $userId : null
        ];

        return $this->insert($data);
    }

    /**
     * Get subscription history for a user
     */
    public function getUserSubscriptionHistory($userId)
    {
        return $this->select('user_subscriptions.*, subscription_plans.name as plan_name')
                    ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
                    ->where('user_subscriptions.user_id', $userId)
                    ->orderBy('user_subscriptions.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Update expired subscriptions
     */
    public function updateExpiredSubscriptions()
    {
        return $this->where('status', 'active')
                    ->where('end_date <', date('Y-m-d H:i:s'))
                    ->set(['status' => 'expired'])
                    ->update();
    }

    /**
     * Get days remaining in subscription
     */
    public function getDaysRemaining($userId)
    {
        $subscription = $this->getActiveSubscription($userId);
        
        if (!$subscription) {
            return 0;
        }

        $endDate = strtotime($subscription['end_date']);
        $now = time();
        $diff = $endDate - $now;
        
        return max(0, ceil($diff / (60 * 60 * 24)));
    }
}
