<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanModel extends Model
{
    protected $table = 'subscription_plans';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'features',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all active subscription plans
     */
    public function getActivePlans()
    {
        return $this->where('is_active', 1)
                    ->orderBy('price', 'ASC')
                    ->findAll();
    }

    /**
     * Get plan by slug
     */
    public function getPlanBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get features as array
     */
    public function getFeaturesArray($planId)
    {
        $plan = $this->find($planId);
        if ($plan && !empty($plan['features'])) {
            return json_decode($plan['features'], true);
        }
        return [];
    }

    /**
     * Check if plan is free
     */
    public function isFreePlan($planId)
    {
        $plan = $this->find($planId);
        return $plan && $plan['price'] == 0;
    }

    /**
     * Get premium plans only
     */
    public function getPremiumPlans()
    {
        return $this->where('is_active', 1)
                    ->where('price >', 0)
                    ->orderBy('price', 'ASC')
                    ->findAll();
    }
}
