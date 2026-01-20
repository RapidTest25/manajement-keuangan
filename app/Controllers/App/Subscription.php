<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;
use App\Models\UserSubscriptionModel;

class Subscription extends BaseController
{
    protected $subscriptionPlanModel;
    protected $userSubscriptionModel;

    public function __construct()
    {
        $this->subscriptionPlanModel = new SubscriptionPlanModel();
        $this->userSubscriptionModel = new UserSubscriptionModel();
    }

    /**
     * Display subscription plans
     */
    public function plans()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get all active plans
        $plans = $this->subscriptionPlanModel->getActivePlans();
        
        // Get user's current subscription
        $currentSubscription = $this->userSubscriptionModel->getActiveSubscription($userId);
        
        // Get days remaining
        $daysRemaining = 0;
        if ($currentSubscription) {
            $daysRemaining = $this->userSubscriptionModel->getDaysRemaining($userId);
        }

        $data = [
            'title' => 'Paket Berlangganan',
            'plans' => $plans,
            'currentSubscription' => $currentSubscription,
            'daysRemaining' => $daysRemaining
        ];

        return view('app/subscription/plans', $data);
    }

    /**
     * Display user's subscription status
     */
    public function mySubscription()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get current subscription
        $currentSubscription = $this->userSubscriptionModel->getActiveSubscription($userId);
        
        // Get subscription history
        $history = $this->userSubscriptionModel->getUserSubscriptionHistory($userId);
        
        // Get days remaining
        $daysRemaining = 0;
        if ($currentSubscription) {
            $daysRemaining = $this->userSubscriptionModel->getDaysRemaining($userId);
        }

        $data = [
            'title' => 'Langganan Saya',
            'currentSubscription' => $currentSubscription,
            'history' => $history,
            'daysRemaining' => $daysRemaining
        ];

        return view('app/subscription/my_subscription', $data);
    }

    /**
     * Subscribe to a plan
     */
    public function subscribe($planSlug = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (!$planSlug) {
            return redirect()->to('/app/subscription/plans')->with('error', 'Paket tidak ditemukan');
        }

        $userId = session()->get('user_id');
        
        // Get plan by slug
        $plan = $this->subscriptionPlanModel->getPlanBySlug($planSlug);
        
        if (!$plan) {
            return redirect()->to('/app/subscription/plans')->with('error', 'Paket tidak ditemukan');
        }

        // Check if it's a free plan
        if ($plan['price'] == 0) {
            // Directly subscribe to free plan
            $result = $this->userSubscriptionModel->createSubscription($userId, $plan['id']);
            
            if ($result) {
                return redirect()->to('/app/subscription/my-subscription')
                               ->with('success', 'Berhasil berlangganan paket ' . $plan['name']);
            } else {
                return redirect()->to('/app/subscription/plans')
                               ->with('error', 'Gagal berlangganan. Silakan coba lagi.');
            }
        }

        // For premium plans, show payment page
        $data = [
            'title' => 'Pembayaran',
            'plan' => $plan
        ];

        return view('app/subscription/payment', $data);
    }

    /**
     * Process payment and activate subscription
     */
    public function processPayment()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        $planId = $this->request->getPost('plan_id');
        $paymentMethod = $this->request->getPost('payment_method');

        if (!$planId || !$paymentMethod) {
            return redirect()->to('/app/subscription/plans')
                           ->with('error', 'Data pembayaran tidak lengkap');
        }

        // In real application, you would integrate with payment gateway here
        // For this implementation, we'll simulate successful payment

        $result = $this->userSubscriptionModel->createSubscription($userId, $planId, $paymentMethod);
        
        if ($result) {
            // Update payment status to paid (in real app, this would be done by payment callback)
            $this->userSubscriptionModel->where('id', $result)
                                       ->set(['payment_status' => 'paid'])
                                       ->update();

            return redirect()->to('/app/subscription/my-subscription')
                           ->with('success', 'Pembayaran berhasil! Akun Anda sekarang Premium.');
        } else {
            return redirect()->to('/app/subscription/plans')
                           ->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get current subscription
        $currentSubscription = $this->userSubscriptionModel->getActiveSubscription($userId);
        
        if (!$currentSubscription) {
            return redirect()->to('/app/subscription/my-subscription')
                           ->with('error', 'Anda tidak memiliki langganan aktif');
        }

        // Cancel subscription
        $this->userSubscriptionModel->where('user_id', $userId)
                                   ->where('status', 'active')
                                   ->set(['status' => 'cancelled'])
                                   ->update();

        return redirect()->to('/app/subscription/my-subscription')
                       ->with('success', 'Langganan berhasil dibatalkan');
    }

    /**
     * Check if feature requires premium (for AJAX calls)
     */
    public function checkFeatureAccess()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not logged in',
                'hasPremium' => false
            ]);
        }

        $userId = session()->get('user_id');
        $feature = $this->request->getGet('feature');

        $hasPremium = $this->userSubscriptionModel->hasPremiumSubscription($userId);
        $hasAccess = $this->userSubscriptionModel->hasFeatureAccess($userId, $feature);

        return $this->response->setJSON([
            'status' => 'success',
            'hasPremium' => $hasPremium,
            'hasAccess' => $hasAccess
        ]);
    }
}
