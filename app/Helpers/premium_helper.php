<?php

if (!function_exists('is_premium')) {
    /**
     * Check if user is premium
     * 
     * @param int|null $userId
     * @return bool
     */
    function is_premium($userId = null)
    {
        if (!$userId) {
            // Try to get from session if not provided
            $userId = session()->get('user_id');
            // Or try auth helper if available
            if (!$userId && function_exists('user_id')) {
                $userId = user_id();
            }
        }

        if (!$userId) {
            return false;
        }

        $model = new \App\Models\UserSubscriptionModel();
        return $model->hasPremiumSubscription($userId);
    }
}

if (!function_exists('get_category_limit')) {
    /**
     * Get Max Category Limit for user
     * 
     * @param int|null $userId
     * @return int
     */
    function get_category_limit($userId = null)
    {
        return is_premium($userId) ? 20 : 5;
    }
}
