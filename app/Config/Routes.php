<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Home::index');

$routes->get('/', 'admin\AuthController::index');
$routes->post('auth/send-otp', 'admin\AuthController::sendOtp');
$routes->post('auth/verify-otp', 'admin\AuthController::verifyOtp');

$routes->post('/admin/auth/login', 'admin\AuthController::login');

$routes->get('/logout', 'admin\AuthController::logout');
$routes->post('forgot-password', 'admin\AuthController::forgetPassword');
$routes->get('/unauthorized', 'admin\AuthController::unauthorized');

$routes->get('chat', 'Home::chat');

$routes->group('', ['filter' => 'auth'], function ($routes) {

  // Admin Panels
  $routes->get('ai-analysis', 'admin\AiAnalysisController::index');
  $routes->get('profile', 'admin\AdminController::profile');
  $routes->get('dashboard', 'admin\AdminController::dashboard');
  $routes->get('employees', 'admin\AdminController::index', ['filter' => 'permission:34']);
  $routes->post('addEmployee', 'admin\AdminController::addEmployee');
  $routes->post('deleteEmployee', 'admin\AdminController::deleteEmployee');
  $routes->post('updateEmployee', 'admin\AdminController::updateEmployee');

  $routes->post('permission/getPermissions', 'admin\AdminController::getPermissions');
  $routes->get('permission/getAllPermissions', 'admin\AdminController::getAllPermissions');
  $routes->post('permission/updatePermissions', 'admin\AdminController::updatePermissions');

  $routes->get('breeds/(:any)', 'admin\AdminController::breeds/$1');
  $routes->get('notifications', 'admin\AdminController::notifications', ['filter' => 'permission:46']);
  $routes->post('updateProfile', 'admin\AdminController::updateProfile');
  $routes->post('updatePassword', 'admin\AdminController::updatePassword');
  $routes->get('getMonthlyRevenue', 'admin\AdminController::getMonthlyRevenue');

  $routes->get('booking/getMonthlyBookings', 'admin\AdminController::getMonthlyBookings');
  $routes->get('getProfitData/(:any)', 'admin\AdminController::getProfitData/$1');
  $routes->get('getBookingStats/(:any)', 'admin\AdminController::getBookingStats/$1');
  $routes->get('getBookingStatusData/(:any)', 'admin\AdminController::getBookingStatusData/$1');
  $routes->get('getTopServices/(:any)', 'admin\AdminController::getTopServices/$1');

  $routes->get('admin/permissions', 'admin\AdminController::permissions', ['filter' => 'permission:46']);
  $routes->post('admin/add-permission', 'admin\AdminController::addPermission');
  $routes->post('admin/assign-permissions', 'admin\AdminController::assignPermissions');
  $routes->post('deletePetType', 'admin\AdminController::deletePetType');
  $routes->post('addBreed', 'admin\AdminController::addBreed');
  $routes->post('updatePetType', 'admin\AdminController::updatePetType');
  $routes->post('addNotification', 'admin\AdminController::addNotification');
  $routes->post('deleteNotification', 'admin\AdminController::deleteNotification');
  $routes->post('updateNotification', 'admin\AdminController::updateNotification');
  $routes->post('getBreed', 'admin\AdminController::getBreed');
  $routes->post('updateBreed', 'admin\AdminController::updateBreed');
  $routes->post('deleteBreed', 'admin\AdminController::deleteBreed');
  $routes->post('bulkDeleteBreeds', 'admin\AdminController::bulkDeleteBreeds');

  $routes->get('recoinciliation', 'admin\AdminController::recoinciliation');

  $routes->get('tax', 'admin\AdminController::tax', ['filter' => 'permission:68']);
  $routes->get('client-spendings', 'admin\AdminController::clientSpendings', ['filter' => 'permission:64']);
  // $routes->get('client-wallets', 'admin\AdminController::clientWallets', ['filter' => 'permission:74']);

  $routes->get('company-earnings', 'admin\AdminController::companyEarnings', ['filter' => 'permission:65']);
  $routes->get('daily-bookings', 'admin\AdminController::dailyBookings', ['filter' => 'permission:66']);
  $routes->post('tax/add', 'admin\AdminController::addTax');
  $routes->get('permissions', 'admin\AdminController::permissions');
  $routes->get('pets', 'admin\AdminController::pets');
  $routes->post('addPetType', 'admin\AdminController::addPetType');
  $routes->post('Pet', 'admin\AdminController::Pet');


  $routes->get('/providers', 'admin\ProviderController::index', ['filter' => 'permission:53']);
  $routes->get('/clients', 'admin\UserController::index', ['filter' => 'permission:50']);
  $routes->post('/getProviderDetails', 'admin\ProviderController::getProviderDetails');
  $routes->post('getAreas', 'admin\ProviderController::getAreas');

  $routes->get('/services', 'admin\ServiceController::index');
  $routes->get('bookings', 'admin\BookingController::allBookings');
  $routes->get('bookings/walking', 'admin\BookingController::index');
  $routes->get('bookings/addons', 'admin\ServiceController::walkingAddons');
  $routes->post('updateAddon', 'admin\ServiceController::updateAddon');
  $routes->get('bookings/packages', 'admin\ServiceController::walkingPackages');
  $routes->post('updateStatus', 'admin\ServiceController::updateStatus');
  $routes->post('updateQueryStatus/(:any)', 'admin\ServiceController::updateQueryStatus/$1');

  $routes->post('deleteAddon', 'admin\ServiceController::deleteAddon');
  $routes->post('addAddon', 'admin\ServiceController::addAddon');
  $routes->post('addPackage', 'admin\ServiceController::addPackage');
  $routes->post('updatePackage', 'admin\ServiceController::updatePackage');

  $routes->post('deletePackage', 'admin\ServiceController::deletePackage');
  $routes->post('sendNotification', 'admin\ServiceController::sendNotification');
  $routes->post('getUser', 'admin\ServiceController::getUser');
  $routes->post('update', 'admin\ServiceController::updateUser');
  $routes->post('deleteUser', 'admin\ServiceController::deleteUser');


  $routes->get('bookings/boarding', 'admin\BookingController::boarding', ['filter' => 'permission:6']);
  $routes->get('bookings/grooming', 'admin\BookingController::grooming', ['filter' => 'permission:14']);
  $routes->get('bookings/training', 'admin\BookingController::training', ['filter' => 'permission:20']);
  $routes->get('bookings/walking/request', 'admin\BookingController::walkingRequest');
  $routes->get('bookings/walkingQuotes/(:any)', 'admin\BookingController::walkingQuotes/$1');
  $routes->get('bookings/(:any)', 'admin\BookingController::bookings/$1');
  $routes->get('bookingRequests', 'admin\BookingController::bookingRequests', ['filter' => 'permission:57']);
  $routes->get('premiumRequests', 'admin\BookingController::premiumRequests');
  $routes->post('assignPremiumProvider', 'admin\BookingController::assignPremiumProvider');
  $routes->get('grooming/disputes', 'admin\BookingController::groomingDisputes');
  $routes->get('admin/getGroomingData/(:any)/(:any)/(:any)', 'admin\BookingController::getGroomingData/$1/$2/$3');
  $routes->post('admin/approveOrRejectGroomingTracking', 'admin\BookingController::approveOrRejectGroomingTracking');

  $routes->get('/packages', 'admin\ServiceController::packages');

  $routes->get('walkers', 'admin\ProviderController::walkers');
  $routes->get('providers-profile', 'admin\ProviderController::spProfile');
  $routes->get('providers-payments', 'admin\ProviderController::payments', ['filter' => 'permission:63']);
  $routes->post('admin/update_status', 'admin\PaymentController::updateStatus');
  $routes->post('admin/upload_receipt', 'admin\ProviderController::uploadReceipt');
  $routes->post('update-provider-call-status', 'admin\ProviderController::updateProviderCall');
  $routes->get('sp-earnings', 'admin\ProviderController::spEarnings', ['filter' => 'permission:67']);
  $routes->post('add-followup-comment', 'admin\ProviderController::addFollowupComment');
  $routes->post('remove-followup-status', 'admin\ProviderController::removeFollowupStatus');
  $routes->get('provider/notification-sent-status', 'admin\ProviderController::notificationStatus');
  $routes->post('resend-notification', 'admin\ProviderController::resendNotification');

  $routes->get('download', 'admin\ServiceController::download');
  $routes->get('downloadQuotes', 'admin\ServiceController::downloadQuotes');
  $routes->get('downloadProviders', 'admin\ServiceController::downloadProviders');

  $routes->post('update-provider-verifications', 'admin\ProviderController::updateVerification');
  $routes->post('repostBooking', 'admin\BookingController::repostBooking');
  $routes->post('getBookingDetails', 'admin\BookingController::getBookingDetails');
  $routes->post('refund-amount', 'admin\UserController::refundAmount');
  $routes->get('userDetails/(:any)/(:num)', 'admin\AdminController::userDetails/$1/$2');

  $routes->get('payments', 'admin\BookingController::Payments', ['filter' => 'permission:75']);
  $routes->get('banners', 'admin\ServiceController::banners', ['filter' => 'permission:46']);
  $routes->get('banners', 'admin\ServiceController::banners', ['filter' => 'permission:46']);
  $routes->post('addBanner', 'admin\ServiceController::addBanner');
  $routes->post('deleteBanner', 'admin\ServiceController::deleteBanner');
  $routes->post('updateBannerStatus', 'admin\ServiceController::updateBannerStatus');
  $routes->get('events', 'admin\ServiceController::events', ['filter' => 'permission:46']);
  $routes->get('tutorials', 'admin\AdminController::tutorials', ['filter' => 'permission:79']);
  $routes->post('tutorials/saveTutorial', 'admin\AdminController::saveTutorial');


  $routes->post('deleteBooking/(:any)/(:any)', 'admin\BookingController::deleteBooking/$1/$2', ['filter' => 'permission:69']);
  $routes->post('updateCancelReason', 'admin\BookingController::updateCancelReason');
  $routes->post('updateComment', 'admin\BookingController::updateComment');
  $routes->get('supports', 'admin\AdminController::supports');
  $routes->get('trackings', 'admin\BookingController::trackings');

  $routes->get('app-stats', 'admin\AppStatsController::index');
  $routes->get('app-stats/update', 'admin\AppStatsController::updateStats');
  $routes->get('client-statistics', 'admin\BookingController::clientStatistics', ['filter' => 'permission:70']);
  $routes->get('provider-statistics', 'admin\BookingController::providerStatistics', ['filter' => 'permission:71']);
  $routes->get('missed-walks', 'admin\BookingController::missedWalksReports');
  $routes->post('updateReason', 'admin\BookingController::updateReason');
  $routes->post('updateProviderFeedback', 'admin\AdminController::updateProviderFeedback');
  $routes->post('updateVerification', 'admin\AdminController::updateVerification');
  $routes->get('tutorials/delete/(:num)', 'admin\AdminController::deleteTutorial/$1');

  $routes->get('supportticket/(:num)', 'admin\AdminController::supportChat/$1');
  $routes->get('sp-supportticket/(:num)', 'admin\AdminController::spSupportChat/$1');
  $routes->get('support', 'admin\AdminController::support', ['filter' => 'permission:72']);
  $routes->get('unupdatedPaidBookings', 'admin\AdminController::unupdatedPaidBookings');

  $routes->post('bookings/getBookingsData/(:segment)', 'admin\BookingController::getBookingsData/$1');
  $routes->get('sp-support', 'admin\AdminController::spSupport', ['filter' => 'permission:73']);


  $routes->get('enquiries', 'Insurance\InsuranceManagement::index');
  $routes->get('quotations', 'Insurance\InsuranceManagement::quotations');
  $routes->get('active', 'Insurance\InsuranceManagement::active');
  $routes->get('expiring', 'Insurance\InsuranceManagement::expiring');
  $routes->get('claims', 'Insurance\InsuranceManagement::claims');
  $routes->post('insurance/sendQuotation', 'Insurance\InsuranceManagement::sendQuotation');
  $routes->post('insurance/updateClaim', 'Insurance\InsuranceManagement::updateClaim');
  $routes->post('insurance/searchClient', 'Insurance\InsuranceManagement::searchClient');
  $routes->post('insurance/getClientPets', 'Insurance\InsuranceManagement::getClientPets');
  $routes->post('insurance/addPolicy', 'Insurance\InsuranceManagement::addPolicy');
  $routes->post('insurance/addEnquiry', 'Insurance\InsuranceManagement::addEnquiry');
  $routes->post('insurance/updateEnquiryStatus', 'Insurance\InsuranceManagement::updateEnquiryStatus');
  $routes->post('insurance/searchActivePolicies', 'Insurance\InsuranceManagement::searchActivePolicies');
  $routes->post('insurance/addClaim', 'Insurance\InsuranceManagement::addClaim');
  $routes->get('insurance/getDogBreeds', 'Insurance\InsuranceManagement::getDogBreeds');


  // Task Management Routes
  $routes->group('tasks', function ($routes) {
    $routes->get('/', 'admin\Tasks::index');
    $routes->get('list', 'admin\Tasks::listTasks');
    $routes->post('save', 'admin\Tasks::saveTask');
    $routes->post('resolve', 'admin\Tasks::resolveTask');
    $routes->get('delete/(:num)', 'admin\Tasks::deleteTask/$1');
    $routes->get('status/(:num)/(:any)', 'admin\Tasks::updateStatus/$1/$2');
    $routes->get('alerts', 'admin\Tasks::getAlerts');
    $routes->get('remindAlerts', 'admin\Tasks::getRemindAlerts');

    $routes->get('overdue', 'admin\Tasks::getOverdue');
    $routes->get('statistics', 'admin\Tasks::getStatistics');
    $routes->post('bulk-action', 'admin\Tasks::bulkAction');
    $routes->get('search', 'admin\Tasks::search');
    $routes->get('export', 'admin\Tasks::export');
  });

  // API Routes for AJAX calls
  $routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->group('tasks', function ($routes) {
      $routes->get('alerts', 'Tasks::getAlerts');
      $routes->get('overdue', 'Tasks::getOverdue');
      $routes->get('statistics', 'Tasks::getStatistics');
      $routes->get('list', 'Tasks::listTasks');
      $routes->post('update-status', 'Tasks::updateStatus');
      $routes->post('resolve', 'Tasks::resolve');
    });
  });


  $routes->get('servicestates', 'admin\ServiceStates::index');
  $routes->post('servicestates/save', 'admin\ServiceStates::save');
  $routes->get('servicestates/delete/(:num)', 'admin\ServiceStates::delete/$1');

  $routes->post('notifications/subscribe', 'admin\Notifications::subscribe');
  $routes->post('notifications/send', 'admin\Notifications::sendNotification');
  $routes->get('notifications/test', 'admin\Notifications::testPush');
  $routes->get('push-notifications.js', 'Assets::pushNotificationsJS');
  $routes->post('notifications/send-chat-notification', 'admin\Notifications::sendChatNotification');
  $routes->post('notifications/verify-subscription', 'Notifications::verifySubscription');


  // Operations
  $routes->post('refund/request', 'RefundController::requestRefund');

  // Operations
  $routes->post('refund/request', 'RefundController::requestRefund');

  $routes::post('chat/processRefund', 'RefundController::processRefund');

  // Admin & Accounts
  $routes->get('client-wallets', 'RefundController::clientWallets', ['filter' => 'permission:74']);
  $routes->post('admin/request-refund', 'RefundController::requestRefund');
  $routes->post('admin/process-refund', 'RefundController::refundProcess');
  $routes->get('reviews/(:num)', 'admin\ProviderController::reviews/$1');
  $routes->get('client-list', 'admin\UserController::clientList');
  $routes->post('save-client-comment', 'admin\UserController::saveClientComment');

  // Bank details
  $routes->get('bank-details/(:num)', 'PaymentController::getBankDetails/$1');
  $routes->post('validate/(:num)', 'PaymentController::validateBankDetails/$1');

  // Beneficiary
  $routes->post('add-beneficiary/(:num)', 'PaymentController::addBeneficiary/$1');

  // Payouts
  $routes->post('process_payout', 'admin\PaymentController::processPayout');
  $routes->post('check_status', 'admin\PaymentController::checkStatus');
  $routes->get('list/(:num)', 'PaymentController::getPayouts/$1');
  $routes->get('get_stats', 'admin\PaymentController::getStats');


  // Community Admin Routes
  $routes->get('community/channels', 'admin\CommunityAdminController::channels');
  $routes->get('community/channel/(:num)', 'admin\CommunityAdminController::viewChannel/$1');
  $routes->post('community/addChannel', 'admin\CommunityAdminController::addChannel');
  $routes->post('community/updateChannel/(:num)', 'admin\CommunityAdminController::updateChannel/$1');
  $routes->get('community/deleteChannel/(:num)', 'admin\CommunityAdminController::deleteChannel/$1');
  $routes->get('community/posts', 'admin\CommunityAdminController::posts');
  $routes->get('community/addPost', 'admin\CommunityAdminController::addPost');
  $routes->post('community/addPost', 'admin\CommunityAdminController::savePost');
  $routes->get('community/editPost/(:num)', 'admin\CommunityAdminController::editPost/$1');
  $routes->post('community/updatePost/(:num)', 'admin\CommunityAdminController::updatePost/$1');
  $routes->get('community/post/(:num)', 'admin\CommunityAdminController::viewPost/$1');
  $routes->get('community/post-reports/(:num)', 'admin\CommunityAdminController::postReports/$1');
  $routes->get('community/approvePost/(:num)', 'admin\CommunityAdminController::approvePost/$1');
  $routes->get('community/deletePost/(:num)', 'admin\CommunityAdminController::deletePost/$1');
  $routes->get('community/deleteComment/(:num)', 'admin\CommunityAdminController::deleteComment/$1');
  $routes->get('community/togglePin/(:num)', 'admin\CommunityAdminController::togglePin/$1');
  $routes->get('community/reports', 'admin\CommunityAdminController::reports');
  $routes->get('community/reportComment/(:num)', 'admin\CommunityAdminController::reportComment/$1');
  $routes->post('community/addReply/(:num)', 'admin\CommunityAdminController::addReply/$1');
  $routes->get('community/dismissReport/(:num)', 'admin\CommunityAdminController::dismissReport/$1');
  $routes->get('community/dismissAllReports/(:num)', 'admin\CommunityAdminController::dismissAllReports/$1');
  $routes->get('getSidebarCounts', 'admin\AdminController::getSidebarCounts');
  $routes->get('community/blockPost/(:num)', 'admin\CommunityAdminController::blockPost/$1');
  $routes->get('community/unblockPost/(:num)', 'admin\CommunityAdminController::unblockPost/$1');
  $routes->post('community/addDirectComment', 'admin\CommunityAdminController::addDirectComment');

  // Loyalty Admin
  $routes->get('loyalty-admin', 'admin\LoyaltyAdminController::index');
  $routes->get('loyalty-programs', 'admin\LoyaltyAdminController::programs');
  $routes->get('loyalty-transactions', 'admin\LoyaltyAdminController::transactions');
  $routes->get('loyalty-settings', 'admin\LoyaltyAdminController::settings');
  $routes->post('loyalty-admin/updateStatus', 'admin\LoyaltyAdminController::updateStatus');
  $routes->post('loyalty-admin/saveMilestone', 'admin\LoyaltyAdminController::saveMilestone');
  $routes->get('loyalty-admin/deleteMilestone/(:num)', 'admin\LoyaltyAdminController::deleteMilestone/$1');
  $routes->post('loyalty-admin/saveReward', 'admin\LoyaltyAdminController::saveReward');
  $routes->get('loyalty-admin/deleteReward/(:num)', 'admin\LoyaltyAdminController::deleteReward/$1');
  $routes->get('loyalty-admin/toggleReward/(:num)', 'admin\LoyaltyAdminController::toggleReward/$1');
  $routes->post('loyalty-admin/saveSettings', 'admin\LoyaltyAdminController::saveSettings');
  $routes->get('reward-orders', 'admin\LoyaltyAdminController::rewardOrders');
  $routes->get('delivered-orders', 'admin\LoyaltyAdminController::deliveredOrders');


  $routes->get('chat-history', 'admin\AiAnalysisController::chatHistory');
  $routes->get('chat-history/messages/(:num)', 'admin\AiAnalysisController::getMessages/$1');
  $routes->get('sp-payments', 'admin\BookingController::spPayments');
  $routes->post('service-verification', 'admin\ProviderController::submitServiceVerification');
  $routes->get('provider/verify/(:num)/(:num)', 'admin\ProviderController::verify/$1/$2');
  $routes->post('checkVerificationRequirement', 'admin\ProviderController::checkVerificationRequirement');
  $routes->get('verification-rules', 'admin\ProviderController::verificationRules');
  $routes->get('get-rule-data/(:num)', 'admin\ProviderController::getRuleData/$1');
  $routes->post('save-rule', 'admin\ProviderController::saveRule');
  $routes->get('delete-rule/(:num)', 'admin\ProviderController::deleteRule/$1');

  // Verification Questions Management
  $routes->get('verification-questions', 'admin\VerificationQuestionController::index');
  $routes->post('verification-questions/save', 'admin\VerificationQuestionController::save');
  $routes->get('verification-questions/get-data/(:num)', 'admin\VerificationQuestionController::getData/$1');
  $routes->get('verification-questions/delete/(:num)', 'admin\VerificationQuestionController::delete/$1');


  // Admin UI Route
  $routes->get('walker-leaderboard', 'admin\WalkerStatsController::leaderboard');

  // CLI/Cron Route
  // $routes->cli('cli/evaluate-walker-tiers', 'Cli\WalkerTierEvaluator::evaluate');
  // Or a web route if you want the button in the UI to work immediately:
  $routes->get('cli/evaluate-walker-tiers', 'Cli\WalkerTierEvaluator::evaluate');
});

$routes->get('firestore/getMessages/(:any)', 'FirestoreController::getAllChats/$1');

$routes->get('updateAreas', 'AddressUpdater::updateAreas');
$routes->get('bookingsStatus/(:any)', 'admin\AdminController::newBookings/$1');
$routes->get('supportTickets', 'admin\AdminController::supportTickets');
$routes->get('failedTransactions', 'admin\AdminController::failedTransactions');
$routes->get('getNearbyProviders/(:segment)', 'admin\BookingController::getNearbyProviders/$1');
$routes->get('getQuotations/(:segment)', 'admin\BookingController::getQuotations/$1');
$routes->get('providerDetailsStatistics/(:segment)', 'admin\BookingController::providerDetailsStatistics/$1');

// Admin Event Routes
$routes->group('', ['namespace' => 'App\Controllers\admin'], static function ($routes) {
  $routes->get('pet-events', 'EventController::index');
  $routes->get('pet-events/bookings', 'EventController::bookings');
  $routes->get('pet-events/bookings/(:num)', 'EventController::bookings/$1');
  $routes->get('pet-events/scannerStats', 'EventController::scannerStats');
  $routes->post('update-whatsapp-status', 'EventController::updateWhatsappStatus');
});
