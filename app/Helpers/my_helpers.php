<?php

function datetimeFormatIndonesia($datetime) //2022-03-05 12:29:55
{
    $datetime = explode(" ", $datetime);
    $date = explode("-", $datetime[0]);
    $time = explode(":", $datetime[1]);

    $datee = array(
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    );
    return $date[2] . ' ' . $datee[number_format($date[1])] . ' ' . $date[0] . ' ' . $time[0] . ':' . $time[1];
}

function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function status($status)
{
    if ($status == "INCOME") {
        $label = "success";
    } else if ($status == "EXPENSE") {
        $label = "danger";
    } else {
        $label = "info";
    }

    return $label;
}

/**
 * Check if current user has premium subscription
 */
function is_premium_user()
{
    if (!session()->get('logged_in')) {
        return false;
    }

    $userId = session()->get('user_id');
    $subscriptionModel = new \App\Models\UserSubscriptionModel();
    
    return $subscriptionModel->hasPremiumSubscription($userId);
}

/**
 * Get current user's subscription info
 */
function get_user_subscription()
{
    if (!session()->get('logged_in')) {
        return null;
    }

    $userId = session()->get('user_id');
    $subscriptionModel = new \App\Models\UserSubscriptionModel();
    
    return $subscriptionModel->getActiveSubscription($userId);
}

/**
 * Check if user has access to a specific feature
 */
function has_feature_access($feature)
{
    if (!session()->get('logged_in')) {
        return false;
    }

    $userId = session()->get('user_id');
    $subscriptionModel = new \App\Models\UserSubscriptionModel();
    
    return $subscriptionModel->hasFeatureAccess($userId, $feature);
}

/**
 * Generate premium badge HTML
 */
function premium_badge()
{
    if (is_premium_user()) {
        return '<span class="badge bg-warning text-dark ms-2" style="font-size: 10px;">⭐ PREMIUM</span>';
    }
    return '';
}

/**
 * Show premium feature lock with upgrade link
 */
function premium_lock($featureName = 'fitur ini')
{
    if (!is_premium_user()) {
        return '
        <div class="premium-lock-overlay" style="position: relative; padding: 40px; text-align: center; background: linear-gradient(135deg, rgba(0,158,96,0.1) 0%, rgba(0,201,117,0.1) 100%); border-radius: 15px; border: 2px dashed #009e60;">
            <div style="font-size: 48px; margin-bottom: 15px;">🔒</div>
            <h4 style="color: #009e60; margin-bottom: 10px;">Fitur Premium</h4>
            <p style="color: #666; margin-bottom: 20px;">Upgrade ke Premium untuk mengakses ' . $featureName . '</p>
            <a href="' . base_url('app/subscription/plans') . '" class="btn btn-success">
                🚀 Upgrade Sekarang
            </a>
        </div>';
    }
    return '';
}

