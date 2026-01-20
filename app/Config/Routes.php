<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');

$routes->get('/', 'Home::index');

/* Protected Routes */
$routes->group('', ['filter' => 'login'], function ($routes) {
    $routes->get('home', 'App\View::home');
    $routes->get('budgeting', 'App\View::budgeting');
    $routes->get('calculator', 'App\Calculator::index');
    $routes->get('setting', 'App\View::settingAccount');
    $routes->get('loan', 'App\View::loan');
    $routes->get('history', 'App\View::history');
    $routes->get('statistik', 'App\View::statistik');
    $routes->get('statistik/download', 'App\Statistics::downloadPDF');
    $routes->get('nabung', 'App\Saving::index');
    $routes->get('note-utang', 'App\View::noteUtang');
    $routes->get('cicilan', 'App\View::cicilan');
    $routes->get('biayaefektif', 'App\BiayaEfektif::index');
});

$routes->group('auth/ajax', function ($routes) {
    $routes->post('validasi', 'Anonymous\Ajax::validasi');
});

/* Untuk Ajax Methode Post user*/
$routes->group('app', ['filter' => 'login'], function ($routes) {
    $routes->group('ajax', function ($routes) {
        // Biaya Efektif routes
        $routes->post('saveBiayaEfektif', 'App\Ajax::saveBiayaEfektif');
        $routes->post('updateBiayaEfektif/(:num)', 'App\Ajax::updateBiayaEfektif/$1');
        $routes->delete('deleteBiayaEfektif/(:num)', 'App\Ajax::deleteBiayaEfektif/$1');
        $routes->post('get-biaya-efektif', 'App\Ajax::getBiayaEfektifData');
        $routes->get('getTotalBiayaEfektif', 'App\Ajax::getTotalBiayaEfektif');
        $routes->get('getBiayaEfektifStatistik', 'App\Ajax::getBiayaEfektifStatistik');

        // Existing routes
        $routes->post('saveCicilan', 'App\Ajax::saveCicilan');
        $routes->get('getCicilan', 'App\Ajax::getCicilan');
        $routes->post('payCicilan/(:num)', 'App\Ajax::payCicilan/$1');
        $routes->post('addtransaction', 'App\Ajax::addTransaction');
        $routes->get('getTransactions', 'App\Ajax::getTransactions');
        $routes->get('get_all', 'App\Ajax::get_all');
        $routes->get('getCategories', 'App\Ajax::getCategories');
        $routes->post('addCategory', 'App\Ajax::addCategory');
        $routes->delete('deleteCategory/(:segment)', 'App\Ajax::deleteCategory/$1');
        $routes->get('getFilteredTransactions', 'App\Ajax::getFilteredTransactions');
        $routes->get('testExport', 'App\Ajax::testExport');
        $routes->get('exportTransactions', 'App\Ajax::exportTransactions');
        $routes->post('importTransactions', 'App\Ajax::importTransactions');
        $routes->post('resetData', 'App\Ajax::resetData');
        $routes->post('setDebtNoteCookie', 'App\Ajax::setDebtNoteCookie');
        $routes->get('getDebtNotes', 'App\Ajax::getDebtNotes');
        $routes->post('saveBudget', 'App\Ajax::saveBudget');
        $routes->get('manageBudget', 'App\Ajax::manageBudget');
        $routes->post('saveBiayaEfektif', 'App\Ajax::saveBiayaEfektif');
        $routes->post('updateBiayaEfektif/(:num)', 'App\Ajax::updateBiayaEfektif/$1');
        $routes->post('deleteBiayaEfektif/(:num)', 'App\Ajax::deleteBiayaEfektif/$1');
        $routes->get('getSaldoBulan', 'App\Ajax::getSaldoBulan');
        $routes->get('getSummaryBulan', 'App\Ajax::getSummaryBulan');
    });

    // Biaya Efektif routes
    $routes->post('biayaefektif/add', 'App\BiayaEfektif::add');
    $routes->post('biayaefektif/update/(:num)', 'App\BiayaEfektif::update/$1');
    $routes->get('biayaefektif/delete/(:num)', 'App\BiayaEfektif::delete/$1');

    // Note Utang Routes
    $routes->get('note-utang', 'App\DebtNote::index');
    $routes->post('note-utang/save', 'App\DebtNote::save');
    $routes->get('note-utang/list', 'App\DebtNote::list');
    $routes->get('note-utang/payment/monthly/(:num)', 'App\DebtNote::showMonthlyPaymentForm/$1');
    $routes->get('note-utang/payment/update/(:num)', 'App\DebtNote::showPaymentForm/$1');
    $routes->post('note-utang/update-payment', 'App\DebtNote::updatePayment');
    $routes->post('note-utang/update-monthly-payment', 'App\DebtNote::updateMonthlyPayment');
    $routes->post('note-utang/mark-paid/(:num)', 'App\DebtNote::markPaid/$1');
    $routes->post('note-utang/delete/(:num)', 'App\DebtNote::delete/$1');
});

$routes->group('app', ['filter' => 'login'], function ($routes) {
    $routes->group('ajax', function ($routes) {
        $routes->post('saveSavingTarget', 'App\Ajax::saveSavingTarget');
        $routes->post('updateSaving', 'App\Ajax::updateSaving');
        $routes->get('getSavingTarget', 'App\Ajax::getSavingTarget');
        $routes->get('getMonthlySavings', 'App\Ajax::getMonthlySavings');
    });
    $routes->get('note-utang/list', 'App\DebtNote::list');
});

// Subscription Routes
$routes->group('app/subscription', ['filter' => 'login'], function ($routes) {
    $routes->get('plans', 'App\Subscription::plans');
    $routes->get('my-subscription', 'App\Subscription::mySubscription');
    $routes->get('subscribe/(:segment)', 'App\Subscription::subscribe/$1');
    $routes->post('process-payment', 'App\Subscription::processPayment');
    $routes->get('cancel', 'App\Subscription::cancel');
    $routes->get('check-feature-access', 'App\Subscription::checkFeatureAccess');
});

// Premium Features (Protected by 'premium' filter)
$routes->group('app/premium', ['filter' => 'premium'], function ($routes) {
    $routes->get('advanced-reports', 'App\AdvancedReports::index');
    $routes->get('export-pdf', 'App\AdvancedReports::exportPDF');
    $routes->get('export-excel', 'App\AdvancedReports::exportExcel');
    // Add more premium features here as needed
});

/* Validasi Login atau Regist */
$routes->group('auth/ajax', function ($routes) {
    $routes->post('validasi', 'Anonymous\Ajax::validasi');
});

// Auth Routes (Core)
$routes->match(['get', 'post'], 'login', 'Auth::login', ['as' => 'login']);
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);
$routes->get('register', 'Auth\View::register', ['as' => 'register']);
$routes->get('forgot', 'Auth\View::forgot', ['as' => 'forgot']);
$routes->get('reset', 'Auth\View::reset', ['as' => 'reset']);

// Auth Routes (Namespace alias for compatibility)
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('register', 'Auth\View::register');
    $routes->get('forgot', 'Auth\View::forgot');
    $routes->get('reset', 'Auth\View::reset');
});

/* Administrator Routes */
$routes->get('administrator/dashboard/stats', 'Administrator\DashboardController::stats');
$routes->get('administrator/dashboard/trends', 'Administrator\DashboardController::trends');
$routes->get('administrator/dashboard/categories', 'Administrator\DashboardController::categories');
$routes->group('administrator', ['filter' => 'role:admin'], function ($routes) {
    // Dashboard routes
    $routes->get('dashboard', 'Administrator\DashboardController::index');
    $routes->get('administrator/dashboard/stats', 'Administrator\DashboardController::stats');
    $routes->get('administrator/dashboard/trends', 'Administrator\DashboardController::trends');
    $routes->get('administrator/dashboard/categories', 'Administrator\DashboardController::categories');
    $routes->get('categories', 'Administrator\CategoryController::index');
    $routes->get('categories/list', 'Administrator\CategoryController::list');
    $routes->post('categories/add', 'Administrator\CategoryController::add');
    $routes->put('categories/update', 'Administrator\CategoryController::update');
    $routes->delete('categories/delete/(:num)', 'Administrator\CategoryController::delete/$1');
    $routes->get('categories/(:num)', 'Administrator\CategoryController::get/$1');

    // Reviews Management Routes
    $routes->get('reviews', 'Administrator\ReviewController::index');
    $routes->post('reviews/create', 'Administrator\ReviewController::create');
    $routes->post('reviews/toggle/(:num)', 'Administrator\ReviewController::toggle/$1');
    $routes->post('reviews/delete/(:num)', 'Administrator\ReviewController::delete/$1');
    $routes->post('reviews/update/(:num)', 'Administrator\ReviewController::update/$1');
    $routes->get('dashboard/stats', 'Administrator\DashboardController::getDashboardStats');
    $routes->get('dashboard/trends', 'Administrator\DashboardController::getTransactionTrends');
    $routes->get('dashboard/categories', 'Administrator\DashboardController::getCategoryDistribution');
    $routes->get('dashboard/recent', 'Administrator\DashboardController::getRecentTransactions');

    // Program routes
    $routes->get('program', 'Administrator\Program::index');

    // Settings routes
    $routes->get('settings', 'Administrator\SettingController::index');
    $routes->post('settings/update', 'Administrator\SettingController::updateSettings');
    $routes->get('settings/backup', 'Administrator\SettingController::backupDatabase');
    $routes->get('settings/download/(:segment)', 'Administrator\SettingController::downloadBackup/$1');
    $routes->get('settings/database-size', 'Administrator\SettingController::getDatabaseSize');
    $routes->get('settings/last-backup', 'Administrator\SettingController::getLastBackup');

    // Report routes
    $routes->get('laporan', 'Administrator\ReportController::index');
    $routes->get('report/monthly', 'Administrator\ReportController::getMonthlyReport');
    $routes->get('report/quarterly', 'Administrator\ReportController::getQuarterlyReport');
    $routes->get('report/yearly', 'Administrator\ReportController::getYearlyReport');
    $routes->get('report/export/pdf', 'Administrator\ReportController::generatePDF');
    $routes->get('report/export/excel', 'Administrator\ReportController::generateExcel');

    // Transaction routes - support both English and Indonesian paths
    $routes->get('transaksi', 'Administrator\TransactionController::index');
    $routes->get('transaction', 'Administrator\TransactionController::index');
    $routes->get('transaction/view/(:num)', 'Administrator\TransactionController::view/$1');
    $routes->get('transaction/get/(:num)', 'Administrator\TransactionController::get/$1');
    $routes->post('transaction/update', 'Administrator\TransactionController::update');

    // User management routes - support both English and Indonesian paths
    $routes->get('users', 'Administrator\DashboardController::users');
    $routes->get('pengguna', 'Administrator\DashboardController::users');
    $routes->get('users/list', 'Administrator\DashboardController::getUsers');
    $routes->get('pengguna/list', 'Administrator\DashboardController::getUsers');
    $routes->get('users/(:num)', 'Administrator\DashboardController::getUser/$1');
    $routes->get('pengguna/(:num)', 'Administrator\DashboardController::getUser/$1');
    $routes->post('users/create', 'Administrator\DashboardController::addUser');
    $routes->post('pengguna/create', 'Administrator\DashboardController::addUser');
    $routes->put('users/update', 'Administrator\DashboardController::updateUser');
    $routes->put('pengguna/update', 'Administrator\DashboardController::updateUser');
    $routes->delete('users/delete/(:num)', 'Administrator\DashboardController::deleteUser/$1');
    $routes->delete('pengguna/delete/(:num)', 'Administrator\DashboardController::deleteUser/$1');
    $routes->get('users/trash', 'Administrator\DashboardController::trashUsers');
    $routes->delete('users/delete_permanent/(:num)', 'Administrator\DashboardController::deletePermanentUser/$1');
    $routes->put('users/restore/(:num)', 'Administrator\DashboardController::restoreUser/$1');

    // Transaction management routes
    $routes->put('transaction/status', 'Administrator\DashboardController::updateTransactionStatus');
    $routes->get('cicilan', 'Administrator\CicilanController::index');
    $routes->get('debt-notes', 'Administrator\DebtNoteController::index');
    $routes->get('savings', 'Administrator\SavingsController::index');


    // Subscription Management
    $routes->get('subscription', 'Administrator\SubscriptionController::index');
    $routes->post('subscription/updateStatus', 'Administrator\SubscriptionController::updateStatus');
    $routes->get('subscription/export', 'Administrator\SubscriptionController::export');
});
