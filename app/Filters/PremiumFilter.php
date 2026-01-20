<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserSubscriptionModel;

class PremiumFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        $subscriptionModel = new UserSubscriptionModel();

        // Update expired subscriptions first
        $subscriptionModel->updateExpiredSubscriptions();

        // Check if user has premium subscription
        if (!$subscriptionModel->hasPremiumSubscription($userId)) {
            return redirect()->to('/app/subscription/plans')
                           ->with('error', 'Fitur ini hanya tersedia untuk pengguna Premium. Upgrade sekarang!');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
