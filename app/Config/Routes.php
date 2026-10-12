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
// Unprotected Notification & Worker API Routes
$routes->post('notifications/send-chat-notification', 'admin\Notifications::sendChatNotification');
$routes->get('mongo-diagnostic', 'admin\AiAnalysisController::mongoDiagnostic');

// Unprotected AI Worker Machine-to-Machine Endpoints
$routes->get('call-analysis/next-job', 'admin\CallAnalysisController::nextJob');
$routes->post('call-analysis/complete-job', 'admin\CallAnalysisController::completeJob');
$routes->post('api/upload-call-recording', 'admin\CallAnalysisController::uploadCallRecording');

// Public Analytics Event Ingestion API (for Mobile Apps and Web Frontend)
$routes->match(['POST', 'OPTIONS'], 'api/analytics/events', 'AnalyticsApiController::recordEvents');
$routes->match(['POST', 'OPTIONS'], 'analytics/events', 'AnalyticsApiController::recordEvents');

$routes->group('', ['filter' => 'auth'], function ($routes) {

  // Admin Panels
  $routes->get('ai-analysis', 'admin\AiAnalysisController::index', ['filter' => 'permission:ai_analyst.view']);
  $routes->get('ai-analyst', 'admin\AiAnalysisController::index', ['filter' => 'permission:ai_analyst.view']);
  $routes->get('ai-analyst/credits', 'admin\AiAnalysisController::getCredits', ['filter' => 'permission:ai_analyst.view']);
  $routes->post('ai-analyst/send', 'admin\AiAnalysisController::sendMessage', ['filter' => 'permission:ai_analyst.view']);
  $routes->match(['GET', 'POST'], 'ai-analyst/stream', 'admin\AiAnalysisController::streamMessage', ['filter' => 'permission:ai_analyst.view']);
  $routes->get('ai-analyst/conversations', 'admin\AiAnalysisController::getConversations', ['filter' => 'permission:ai_analyst.view']);
  $routes->get('ai-analyst/messages/(:num)', 'admin\AiAnalysisController::getMessages/$1', ['filter' => 'permission:ai_analyst.view']);
  $routes->post('ai-analyst/new-conversation', 'admin\AiAnalysisController::newConversation', ['filter' => 'permission:ai_analyst.view']);
  $routes->post('ai-analyst/branch-conversation', 'admin\AiAnalysisController::branchConversation', ['filter' => 'permission:ai_analyst.view']);
  $routes->post('ai-analyst/delete-conversation/(:num)', 'admin\AiAnalysisController::deleteConversation/$1', ['filter' => 'permission:ai_analyst.view']);

  $routes->get('profile', 'admin\AdminController::profile');
  $routes->get('dashboard', 'admin\AdminController::dashboard', ['filter' => 'permission:dashboard.view']);
  $routes->get('employees', 'admin\AdminController::index', ['filter' => 'permission:employees.view']);
  $routes->post('addEmployee', 'admin\AdminController::addEmployee');
  $routes->post('deleteEmployee', 'admin\AdminController::deleteEmployee');
  $routes->post('updateEmployee', 'admin\AdminController::updateEmployee');

  $routes->post('permission/getPermissions', 'admin\AdminController::getPermissions');
  $routes->get('permission/getAllPermissions', 'admin\AdminController::getAllPermissions');
  $routes->post('permission/updatePermissions', 'admin\AdminController::updatePermissions');

  $routes->get('breeds/(:any)', 'admin\AdminController::breeds/$1');
  $routes->get('notifications', 'admin\AdminController::notifications', ['filter' => 'permission:notifications.view']);
  $routes->post('updateProfile', 'admin\AdminController::updateProfile');
  $routes->post('updatePassword', 'admin\AdminController::updatePassword');
  $routes->get('getMonthlyRevenue', 'admin\AdminController::getMonthlyRevenue');

  $routes->get('booking/getMonthlyBookings', 'admin\AdminController::getMonthlyBookings');
  $routes->get('getProfitData/(:any)', 'admin\AdminController::getProfitData/$1');
  $routes->get('getBookingStats/(:any)', 'admin\AdminController::getBookingStats/$1');
  $routes->get('getBookingStatusData/(:any)', 'admin\AdminController::getBookingStatusData/$1');
  $routes->get('getTopServices/(:any)', 'admin\AdminController::getTopServices/$1');

  $routes->get('admin/permissions', 'admin\AdminController::permissions', ['filter' => 'permission:permissions.view']);
  $routes->post('admin/permissions/sync-permissions', 'admin\AdminController::syncPermissions');
  $routes->post('admin/add-permission', 'admin\AdminController::addPermission');
  $routes->post('admin/assign-permissions', 'admin\AdminController::assignPermissions');
  $routes->post('admin/permissions/get-role-permissions', 'admin\AdminController::getRolePermissions');
  $routes->post('admin/permissions/get-user-permissions', 'admin\AdminController::getUserPermissions');
  $routes->post('admin/permissions/save-role-permissions', 'admin\AdminController::saveRolePermissions');
  $routes->post('admin/permissions/toggle-role-permission', 'admin\AdminController::toggleRolePermission');
  $routes->post('admin/permissions/save-user-permissions', 'admin\AdminController::saveUserPermissions');
  $routes->post('admin/permissions/toggle-user-permission', 'admin\AdminController::toggleUserPermission');
  $routes->post('admin/permissions/save-role', 'admin\AdminController::saveRole');
  $routes->post('admin/permissions/delete-role', 'admin\AdminController::deleteRole');
  $routes->post('admin/permissions/delete-permission', 'admin\AdminController::deletePermission');
  $routes->post('admin/permissions/assign-user-role', 'admin\AdminController::assignUserRole');
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

  $routes->get('recoinciliation', 'admin\AdminController::recoinciliation', ['filter' => 'permission:finance.reconciliation']);

  $routes->get('tax', 'admin\AdminController::tax', ['filter' => 'permission:finance.tax']);
  $routes->get('client-spendings', 'admin\AdminController::clientSpendings', ['filter' => 'permission:finance.client_spendings']);

  $routes->get('company-earnings', 'admin\AdminController::companyEarnings', ['filter' => 'permission:finance.company_earnings']);
  $routes->get('daily-bookings', 'admin\AdminController::dailyBookings', ['filter' => 'permission:finance.daily_bookings']);
  $routes->post('tax/add', 'admin\AdminController::addTax');
  $routes->get('permissions', 'admin\AdminController::permissions', ['filter' => 'permission:permissions.view']);
  $routes->get('pets', 'admin\AdminController::pets', ['filter' => 'permission:pets.view']);
  $routes->post('addPetType', 'admin\AdminController::addPetType');
  $routes->post('Pet', 'admin\AdminController::Pet');


  $routes->get('/providers', 'admin\ProviderController::index', ['filter' => 'permission:providers.view']);
  $routes->get('/clients', 'admin\UserController::index', ['filter' => 'permission:clients.view']);
  $routes->post('/getProviderDetails', 'admin\ProviderController::getProviderDetails');
  $routes->post('getAreas', 'admin\ProviderController::getAreas');
  $routes->get('service-media-approval', 'admin\ProviderController::serviceMedia', ['filter' => 'permission:media.approval']);
  $routes->post('update-service-media-status', 'admin\ProviderController::updateServiceMediaStatus');

  $routes->get('/services', 'admin\ServiceController::index', ['filter' => 'permission:services.view']);
  $routes->get('bookings', 'admin\BookingController::allBookings', ['filter' => 'permission:bookings.all']);
  $routes->get('bookings/walking', 'admin\BookingController::index', ['filter' => 'permission:bookings.walking']);
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


  $routes->get('bookings/boarding', 'admin\BookingController::boarding', ['filter' => 'permission:bookings.boarding']);
  $routes->get('bookings/grooming', 'admin\BookingController::grooming', ['filter' => 'permission:bookings.grooming']);
  $routes->get('bookings/training', 'admin\BookingController::training', ['filter' => 'permission:bookings.training']);
  $routes->get('bookings/walking/request', 'admin\BookingController::walkingRequest');
  $routes->get('bookings/walkingQuotes/(:any)', 'admin\BookingController::walkingQuotes/$1');
  $routes->match(['GET', 'POST'], 'bookings/deleteQuotation/(:any)', 'admin\BookingController::deleteQuotation/$1');
  $routes->match(['GET', 'POST'], 'bookings/deleteQuotation', 'admin\BookingController::deleteQuotation');
  $routes->get('bookings/(:any)', 'admin\BookingController::bookings/$1');
  $routes->get('bookingRequests', 'admin\BookingController::bookingRequests', ['filter' => 'permission:bookings.view_requests']);
  $routes->post('bookings/notifyNearbyProviders', 'admin\BookingController::notifyNearbyProviders');
  $routes->get('premiumRequests', 'admin\BookingController::premiumRequests');
  $routes->post('assignPremiumProvider', 'admin\BookingController::assignPremiumProvider');
  $routes->get('grooming/disputes', 'admin\BookingController::groomingDisputes');
  $routes->get('admin/getGroomingData/(:any)/(:any)/(:any)', 'admin\BookingController::getGroomingData/$1/$2/$3');
  $routes->post('admin/approveOrRejectGroomingTracking', 'admin\BookingController::approveOrRejectGroomingTracking');

  $routes->get('/packages', 'admin\ServiceController::packages');

  $routes->get('walkers', 'admin\ProviderController::walkers');
  $routes->get('providers-profile', 'admin\ProviderController::spProfile');
  $routes->get('providers-payments', 'admin\ProviderController::payments', ['filter' => 'permission:finance.sp_payments']);
  // Call Verification Executive Payouts & Wallets
  $routes->get('call-verification-payouts', 'admin\CallVerificationPayoutController::index', ['filter' => 'permission:verification.payouts']);
  $routes->match(['GET', 'POST'], 'call-verification-payouts/auto-settle', 'admin\CallVerificationPayoutController::autoSettleMidnightPayouts');
  $routes->post('call-verification-payouts/release', 'admin\CallVerificationPayoutController::releasePayout');
  $routes->get('call-verification-payouts/details/(:num)', 'admin\CallVerificationPayoutController::getPayoutDetails/$1');
  $routes->get('call-verification-payouts/executive-details/(:num)', 'admin\CallVerificationPayoutController::getExecutivePendingDetails/$1');
  $routes->get('call-verification-payouts/wallet-ledger/(:num)', 'admin\CallVerificationPayoutController::getExecutiveWalletLedger/$1');
  $routes->get('call-verification-payouts/bank-details/(:num)', 'admin\CallVerificationPayoutController::getExecutiveBankDetails/$1');
  $routes->post('call-verification-payouts/send-bank-otp', 'admin\CallVerificationPayoutController::sendBankOtp');
  $routes->post('call-verification-payouts/verify-bank-otp', 'admin\CallVerificationPayoutController::verifyBankOtp');
  
  $routes->post('admin/update_status', 'admin\PaymentController::updateStatus');
  $routes->post('admin/upload_receipt', 'admin\ProviderController::uploadReceipt');
  $routes->post('update-provider-call-status', 'admin\ProviderController::updateProviderCall');
  $routes->get('sp-earnings', 'admin\ProviderController::spEarnings', ['filter' => 'permission:finance.sp_earnings']);
  $routes->post('add-followup-comment', 'admin\ProviderController::addFollowupComment');
  $routes->post('remove-followup-status', 'admin\ProviderController::removeFollowupStatus');
  $routes->get('provider/notification-sent-status', 'admin\ProviderController::notificationStatus');
  $routes->post('resend-notification', 'admin\ProviderController::resendNotification');

  $routes->get('download', 'admin\ServiceController::download');
  $routes->get('downloadQuotes', 'admin\ServiceController::downloadQuotes');
  $routes->match(['GET', 'POST'], 'downloadProviders', 'admin\ProviderController::downloadProviders', ['filter' => 'permission:providers.view']);

  $routes->post('update-provider-verifications', 'admin\ProviderController::updateVerification');
  $routes->post('repostBooking', 'admin\BookingController::repostBooking');
  $routes->post('getBookingDetails', 'admin\BookingController::getBookingDetails');
  $routes->post('refund-amount', 'admin\UserController::refundAmount');
  $routes->get('userDetails/(:any)/(:num)', 'admin\AdminController::userDetails/$1/$2');

  $routes->get('payments', 'admin\BookingController::Payments', ['filter' => 'permission:finance.payments']);
  $routes->get('banners', 'admin\ServiceController::banners', ['filter' => 'permission:banners.view']);
  $routes->post('addBanner', 'admin\ServiceController::addBanner');
  $routes->post('deleteBanner', 'admin\ServiceController::deleteBanner');
  $routes->post('updateBannerStatus', 'admin\ServiceController::updateBannerStatus');
  $routes->match(['get', 'post'], 'getBannerDetails', 'admin\ServiceController::getBannerDetails');
  $routes->match(['get', 'post'], 'getBannerDetails/(:num)', 'admin\ServiceController::getBannerDetails/$1');
  $routes->get('events', 'admin\ServiceController::events', ['filter' => 'permission:events.view']);
  $routes->get('tutorials', 'admin\AdminController::tutorials', ['filter' => 'permission:tutorials.view']);
  $routes->post('tutorials/saveTutorial', 'admin\AdminController::saveTutorial');


  $routes->post('deleteBooking/(:any)/(:any)', 'admin\BookingController::deleteBooking/$1/$2', ['filter' => 'permission:bookings.delete']);
  $routes->post('updateCancelReason', 'admin\BookingController::updateCancelReason');
  $routes->post('updateComment', 'admin\BookingController::updateComment');
  $routes->get('supports', 'admin\AdminController::supports');
  $routes->get('trackings', 'admin\BookingController::trackings');
  $routes->get('suspicious-visits', 'admin\AdminController::suspiciousVisits');

  // Customer Journey Funnel & Event Analytics
  $routes->get('customer-journey', 'admin\AnalyticsController::index');
  $routes->get('analytics/journey', 'admin\AnalyticsController::index');
  $routes->get('analytics/funnel-data', 'admin\AnalyticsController::getFunnelData');
  $routes->get('analytics/problem-signals', 'admin\AnalyticsController::getProblemSignals');
  $routes->get('analytics/dropoff-users', 'admin\AnalyticsController::getDropoffUsers');
  $routes->get('analytics/events-stream', 'admin\AnalyticsController::getEventsStream');
  $routes->post('analytics/funnels/save', 'admin\AnalyticsController::saveFunnel');
  $routes->post('analytics/funnels/delete/(:num)', 'admin\AnalyticsController::deleteFunnel/$1');
  $routes->post('analytics/generate-sample-data', 'admin\AnalyticsController::generateSampleData');

  $routes->get('app-stats', 'admin\AppStatsController::index');
  $routes->get('app-stats/update', 'admin\AppStatsController::updateStats');
  $routes->get('client-statistics', 'admin\BookingController::clientStatistics', ['filter' => 'permission:statistics.client']);
  $routes->get('provider-statistics', 'admin\BookingController::providerStatistics', ['filter' => 'permission:statistics.provider']);
  $routes->get('missed-walks', 'admin\BookingController::missedWalksReports');
  $routes->post('updateReason', 'admin\BookingController::updateReason');
  $routes->post('updateProviderFeedback', 'admin\AdminController::updateProviderFeedback');
  $routes->post('updateVerification', 'admin\AdminController::updateVerification');
  $routes->get('tutorials/delete/(:num)', 'admin\AdminController::deleteTutorial/$1');

  $routes->get('supportticket/(:num)', 'admin\AdminController::supportChat/$1');
  $routes->get('sp-supportticket/(:num)', 'admin\AdminController::spSupportChat/$1');
  $routes->get('support', 'admin\AdminController::support', ['filter' => 'permission:support.client']);
  $routes->get('unupdatedPaidBookings', 'admin\AdminController::unupdatedPaidBookings');

  $routes->post('bookings/getBookingsData/(:segment)', 'admin\BookingController::getBookingsData/$1');
  $routes->get('sp-support', 'admin\AdminController::spSupport', ['filter' => 'permission:support.provider']);

  // AI Mode & Human Mode Support Endpoints
  $routes->post('support-ai/generate-reply', 'admin\SupportAIController::generateReply');
  $routes->post('support-ai/suggest-chips', 'admin\SupportAIController::suggestChips');
  $routes->post('support-ai/rewrite-draft', 'admin\SupportAIController::rewriteDraft');
  $routes->post('support-ai/toggle-mode', 'admin\SupportAIController::toggleMode');
  $routes->get('support-ai/mode-status/(:num)', 'admin\SupportAIController::getModeStatus/$1');
  $routes->match(['GET', 'POST'], 'support-ai/test-kb', 'admin\SupportAIController::testKb');
  $routes->post('support-ai/send-message', 'admin\SupportAIController::sendMessage');
  $routes->post('support-ai/delete-message', 'admin\SupportAIController::deleteMessage');



  $routes->get('enquiries', 'Insurance\InsuranceManagement::index', ['filter' => 'permission:insurance.view']);
  $routes->get('quotations', 'Insurance\InsuranceManagement::quotations', ['filter' => 'permission:insurance.view']);
  $routes->get('active', 'Insurance\InsuranceManagement::active', ['filter' => 'permission:insurance.view']);
  $routes->get('expiring', 'Insurance\InsuranceManagement::expiring', ['filter' => 'permission:insurance.view']);
  $routes->get('claims', 'Insurance\InsuranceManagement::claims', ['filter' => 'permission:insurance.view']);
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
  $routes->group('tasks', ['filter' => 'permission:tasks.view'], function ($routes) {
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


  $routes->get('servicestates', 'admin\ServiceStates::index', ['filter' => 'permission:service_states.view']);
  $routes->post('servicestates/save', 'admin\ServiceStates::save');
  $routes->get('servicestates/delete/(:num)', 'admin\ServiceStates::delete/$1');

  $routes->post('notifications/subscribe', 'admin\Notifications::subscribe');
  $routes->post('notifications/send', 'admin\Notifications::sendNotification');
  $routes->get('notifications/test', 'admin\Notifications::testPush');
  $routes->get('push-notifications.js', 'Assets::pushNotificationsJS');
  $routes->post('notifications/send-chat-notification', 'admin\Notifications::sendChatNotification');
  $routes->post('notifications/verify-subscription', 'Notifications::verifySubscription');
  $routes->match(['GET', 'POST'], 'notifications/purge-old', 'admin\Notifications::purgeOld');


  // Operations
  $routes->post('refund/request', 'RefundController::requestRefund');

  // Operations
  $routes->post('refund/request', 'RefundController::requestRefund');

  $routes::post('chat/processRefund', 'RefundController::processRefund');

  // Admin & Accounts
  $routes->get('client-wallets', 'RefundController::clientWallets', ['filter' => 'permission:finance.client_wallets']);
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
  $routes->group('community', ['filter' => 'permission:community.view'], function ($routes) {
    $routes->get('channels', 'admin\CommunityAdminController::channels');
    $routes->get('channel/(:num)', 'admin\CommunityAdminController::viewChannel/$1');
    $routes->post('addChannel', 'admin\CommunityAdminController::addChannel');
    $routes->post('updateChannel/(:num)', 'admin\CommunityAdminController::updateChannel/$1');
    $routes->get('deleteChannel/(:num)', 'admin\CommunityAdminController::deleteChannel/$1');
    $routes->get('posts', 'admin\CommunityAdminController::posts');
    $routes->get('addPost', 'admin\CommunityAdminController::addPost');
    $routes->post('addPost', 'admin\CommunityAdminController::savePost');
    $routes->get('editPost/(:num)', 'admin\CommunityAdminController::editPost/$1');
    $routes->post('updatePost/(:num)', 'admin\CommunityAdminController::updatePost/$1');
    $routes->get('post/(:num)', 'admin\CommunityAdminController::viewPost/$1');
    $routes->get('post-reports/(:num)', 'admin\CommunityAdminController::postReports/$1');
    $routes->get('approvePost/(:num)', 'admin\CommunityAdminController::approvePost/$1');
    $routes->get('deletePost/(:num)', 'admin\CommunityAdminController::deletePost/$1');
    $routes->get('deleteComment/(:num)', 'admin\CommunityAdminController::deleteComment/$1');
    $routes->get('togglePin/(:num)', 'admin\CommunityAdminController::togglePin/$1');
    $routes->get('reports', 'admin\CommunityAdminController::reports');
    $routes->get('reportComment/(:num)', 'admin\CommunityAdminController::reportComment/$1');
    $routes->post('addReply/(:num)', 'admin\CommunityAdminController::addReply/$1');
    $routes->get('dismissReport/(:num)', 'admin\CommunityAdminController::dismissReport/$1');
    $routes->get('dismissAllReports/(:num)', 'admin\CommunityAdminController::dismissAllReports/$1');
    $routes->get('blockPost/(:num)', 'admin\CommunityAdminController::blockPost/$1');
    $routes->get('unblockPost/(:num)', 'admin\CommunityAdminController::unblockPost/$1');
    $routes->post('addDirectComment', 'admin\CommunityAdminController::addDirectComment');
  });
  $routes->get('getSidebarCounts', 'admin\AdminController::getSidebarCounts');

  // Loyalty Admin
  $routes->get('loyalty-admin', 'admin\LoyaltyAdminController::index', ['filter' => 'permission:loyalty.view']);
  $routes->get('loyalty-programs', 'admin\LoyaltyAdminController::programs', ['filter' => 'permission:loyalty.view']);
  $routes->get('loyalty-transactions', 'admin\LoyaltyAdminController::transactions', ['filter' => 'permission:loyalty.view']);
  $routes->get('loyalty-settings', 'admin\LoyaltyAdminController::settings', ['filter' => 'permission:loyalty.view']);
  $routes->post('loyalty-admin/updateStatus', 'admin\LoyaltyAdminController::updateStatus');
  $routes->post('loyalty-admin/saveMilestone', 'admin\LoyaltyAdminController::saveMilestone');
  $routes->get('loyalty-admin/deleteMilestone/(:num)', 'admin\LoyaltyAdminController::deleteMilestone/$1');
  $routes->post('loyalty-admin/saveReward', 'admin\LoyaltyAdminController::saveReward');
  $routes->get('loyalty-admin/deleteReward/(:num)', 'admin\LoyaltyAdminController::deleteReward/$1');
  $routes->get('loyalty-admin/toggleReward/(:num)', 'admin\LoyaltyAdminController::toggleReward/$1');
  $routes->post('loyalty-admin/saveSettings', 'admin\LoyaltyAdminController::saveSettings');
  $routes->get('reward-orders', 'admin\LoyaltyAdminController::rewardOrders', ['filter' => 'permission:loyalty.view']);
  $routes->get('delivered-orders', 'admin\LoyaltyAdminController::deliveredOrders', ['filter' => 'permission:loyalty.view']);


  $routes->get('chat-history', 'admin\AiAnalysisController::chatHistory', ['filter' => 'permission:chat_history.view']);
  $routes->get('chat-history/messages/(:any)', 'admin\AiAnalysisController::chatMessages/$1');
  $routes->get('insurance/chat-history', 'Insurance\InsuranceManagement::chatHistory', ['filter' => 'permission:chat_history.view']);
  $routes->get('insurance/chat-history/messages/(:any)', 'Insurance\InsuranceManagement::chatMessages/$1');
  $routes->get('sp-payments', 'admin\BookingController::spPayments', ['filter' => 'permission:finance.sp_payments']);
  $routes->post('service-verification', 'admin\ProviderController::submitServiceVerification');
  $routes->get('provider/verify/(:num)/(:num)', 'admin\ProviderController::verify/$1/$2');
  $routes->post('checkVerificationRequirement', 'admin\ProviderController::checkVerificationRequirement');
  $routes->get('verification-rules', 'admin\ProviderController::verificationRules', ['filter' => 'permission:verification.rules']);
  $routes->get('get-rule-data/(:num)', 'admin\ProviderController::getRuleData/$1');
  $routes->post('save-rule', 'admin\ProviderController::saveRule');
  $routes->get('delete-rule/(:num)', 'admin\ProviderController::deleteRule/$1');


    // Call Record Analysis API
  $routes->post('analyze-call-record', 'admin\CallRecordController::analyzeCallRecord');
  $routes->post('analyze-call-record-batch', 'admin\CallRecordController::analyzeCallRecordBatch');
  $routes->get('get-call-record-batch-status', 'admin\CallRecordController::getBatchStatus');

  // Call Intelligence & AI Analysis System
  $routes->get('call-analysis', 'admin\CallAnalysisController::index', ['filter' => 'permission:call_analysis.view']);
  $routes->get('call-analysis/details/(:num)', 'admin\CallAnalysisController::details/$1');
  $routes->post('call-analysis/upload', 'admin\CallAnalysisController::upload');
  $routes->get('call-analysis/analytics', 'admin\CallAnalysisController::getAnalyticsData');
  $routes->get('call-analysis/verifiers', 'admin\CallAnalysisController::getVerifierPerformance');
  $routes->post('call-analysis/save-verifier-audit', 'admin\CallAnalysisController::saveVerifierAudit');

  // Verification Questions Management
  $routes->get('verification-questions', 'admin\VerificationQuestionController::index', ['filter' => 'permission:verification.questions']);
  $routes->post('verification-questions/save', 'admin\VerificationQuestionController::save');
  $routes->get('verification-questions/get-data/(:num)', 'admin\VerificationQuestionController::getData/$1');
  $routes->get('verification-questions/delete/(:num)', 'admin\VerificationQuestionController::delete/$1');


// Admin UI Route
  $routes->get('walker-leaderboard', 'admin\WalkerStatsController::leaderboard', ['filter' => 'permission:walker.leaderboard']);
  $routes->get('walkerstats/predictions/(:num)', 'admin\WalkerStatsController::predictions/$1');
  $routes->get('walkerstats/ai-insights', 'admin\WalkerStatsController::aiInsights');
  $routes->match(['GET', 'POST'], 'walkerstats/ask-ai', 'admin\WalkerStatsController::askAi');

  // SP Management Routes
  $routes->get('sp-management', 'admin\SPManagementController::index', ['filter' => 'permission:walker.sp_management']);
  $routes->get('sp-management/stats', 'admin\SPManagementController::getDashboardStats');
  $routes->post('sp-management/simulate-match', 'admin\SPManagementController::runMatchingSimulation');
  $routes->get('sp-management/evaluate-score/(:num)', 'admin\SPManagementController::runScoringEvaluation/$1');
  $routes->get('sp-management/ai-insights', 'admin\SPManagementController::aiInsights');
  $routes->match(['GET', 'POST'], 'sp-management/ask-ai', 'admin\SPManagementController::askAi');


  // CLI/Cron Route
  // $routes->cli('cli/evaluate-walker-tiers', 'Cli\WalkerTierEvaluator::evaluate');
  // Or a web route if you want the button in the UI to work immediately:
  $routes->get('cli/evaluate-walker-tiers', 'Cli\WalkerTierEvaluator::evaluate');

  // Unprotected AI Worker Machine-to-Machine Endpoints
$routes->get('call-analysis/next-job', 'admin\CallAnalysisController::nextJob');
$routes->post('call-analysis/complete-job', 'admin\CallAnalysisController::completeJob');
$routes->get('client-call-analysis/next-job', 'admin\ClientCallAnalysisController::nextJob');
$routes->post('client-call-analysis/complete-job', 'admin\ClientCallAnalysisController::completeJob');


// Call Record Analysis API
  $routes->post('analyze-call-record', 'admin\CallRecordController::analyzeCallRecord');
  $routes->post('analyze-call-record-batch', 'admin\CallRecordController::analyzeCallRecordBatch');
  $routes->get('get-call-record-batch-status', 'admin\CallRecordController::getBatchStatus');

  // Call Intelligence & AI Analysis System (SP Verification Calls)
  $routes->get('call-analysis', 'admin\CallAnalysisController::index');
  $routes->get('call-analysis/details/(:num)', 'admin\CallAnalysisController::details/$1');
  $routes->post('call-analysis/upload', 'admin\CallAnalysisController::upload');
  $routes->get('call-analysis/analytics', 'admin\CallAnalysisController::getAnalyticsData');
  $routes->get('call-analysis/verifiers', 'admin\CallAnalysisController::getVerifierPerformance');
  $routes->post('call-analysis/save-verifier-audit', 'admin\CallAnalysisController::saveVerifierAudit');

  // Client <-> Service Provider Call Conversation Analysis System
  $routes->get('client-call-analysis', 'admin\ClientCallAnalysisController::index');
  $routes->get('client-call-analysis/details/(:num)', 'admin\ClientCallAnalysisController::details/$1');
  $routes->post('client-call-analysis/upload', 'admin\ClientCallAnalysisController::upload');
  $routes->get('client-call-analysis/analytics', 'admin\ClientCallAnalysisController::getAnalyticsData');
  $routes->post('client-call-analysis/save-admin-review', 'admin\ClientCallAnalysisController::saveAdminReview');
});

$routes->get('firestore/getMessages/(:any)/(:any)/(:any)', 'FirestoreController::getAllChats/$1/$2/$3');
$routes->get('firestore/getMessages/(:any)', 'FirestoreController::getAllChats/$1');

$routes->get('updateAreas', 'AddressUpdater::updateAreas');
$routes->get('bookingsStatus/(:any)', 'admin\AdminController::newBookings/$1');
$routes->get('supportTickets', 'admin\AdminController::supportTickets');
$routes->get('failedTransactions', 'admin\AdminController::failedTransactions');
$routes->get('getNearbyProviders/(:segment)', 'admin\BookingController::getNearbyProviders/$1');
$routes->get('getQuotations/(:segment)', 'admin\BookingController::getQuotations/$1');
$routes->get('providerDetailsStatistics/(:segment)', 'admin\BookingController::providerDetailsStatistics/$1');

// Admin Event Routes
$routes->group('', ['namespace' => 'App\Controllers\admin', 'filter' => 'permission:events.view'], static function ($routes) {
  $routes->get('pet-events', 'EventController::index');
  $routes->get('pet-events/bookings', 'EventController::bookings');
  $routes->get('pet-events/bookings/(:num)', 'EventController::bookings/$1');
  $routes->get('pet-events/scannerStats', 'EventController::scannerStats');
  $routes->post('update-whatsapp-status', 'EventController::updateWhatsappStatus');
});
// Notification Campaign Manager
  $routes->group('notification-manager', ['filter' => 'permission:notifications.campaigns'], function ($routes) {
    $routes->get('/', 'admin\NotificationCampaignController::index');
    $routes->get('templates', 'admin\NotificationCampaignController::templates');
    $routes->post('templates/save', 'admin\NotificationCampaignController::saveTemplate');
    $routes->post('templates/delete', 'admin\NotificationCampaignController::deleteTemplate');
    $routes->get('templates/variables', 'admin\NotificationCampaignController::getTemplateVariables');
    $routes->post('templates/preview', 'admin\NotificationCampaignController::previewTemplate');

    $routes->get('campaigns', 'admin\NotificationCampaignController::campaigns');
    $routes->post('campaigns/save', 'admin\NotificationCampaignController::saveCampaign');
    $routes->post('campaigns/send', 'admin\NotificationCampaignController::sendCampaign');
    $routes->post('campaigns/cancel', 'admin\NotificationCampaignController::cancelCampaign');
    $routes->post('campaigns/preview-audience', 'admin\NotificationCampaignController::previewAudience');

    $routes->get('logs', 'admin\NotificationCampaignController::logs');
    $routes->get('analytics', 'admin\NotificationCampaignController::analytics');
  });

// Release Management
$routes->group('releases', ['filter' => 'permission:release_management.view'], function ($routes) {
    $routes->get('/', 'admin\ReleaseManagementController::index');
    $routes->get('create', 'admin\ReleaseManagementController::create');
    $routes->post('store', 'admin\ReleaseManagementController::store');
    $routes->get('detail/(:num)', 'admin\ReleaseManagementController::detail/$1');
    $routes->post('update/(:num)', 'admin\ReleaseManagementController::update/$1');
    $routes->post('delete/(:num)', 'admin\ReleaseManagementController::delete/$1');
    $routes->post('transition-status/(:num)', 'admin\ReleaseManagementController::transitionStatus/$1');

    // Updates (AJAX)
    $routes->get('updates/(:num)', 'admin\ReleaseManagementController::getUpdates/$1');
    $routes->post('save-update', 'admin\ReleaseManagementController::saveUpdate');
    $routes->post('delete-update/(:num)', 'admin\ReleaseManagementController::deleteUpdate/$1');

    // Tickets (AJAX)
    $routes->post('save-ticket', 'admin\ReleaseManagementController::saveTicket');
    $routes->post('delete-ticket/(:num)', 'admin\ReleaseManagementController::deleteTicket/$1');

    // QA Checklist
    $routes->get('checklist/(:num)', 'admin\ReleaseManagementController::getChecklist/$1');
    $routes->post('init-checklist/(:num)', 'admin\ReleaseManagementController::initChecklist/$1');
    $routes->post('update-checklist-item', 'admin\ReleaseManagementController::updateChecklistItem');

    // Backups
    $routes->post('save-backup', 'admin\ReleaseManagementController::saveBackup');
    $routes->post('verify-backup/(:num)', 'admin\ReleaseManagementController::verifyBackup/$1');

    // Approvals
    $routes->post('submit-approval', 'admin\ReleaseManagementController::submitApproval');

    // Email
    $routes->get('generate-email/(:num)', 'admin\ReleaseManagementController::generateEmail/$1');
    $routes->get('preview-email/(:num)', 'admin\ReleaseManagementController::previewEmail/$1');
    $routes->post('send-email/(:num)', 'admin\ReleaseManagementController::sendEmail/$1');

    // Go-Live & Deployment
    $routes->post('go-live/(:num)', 'admin\ReleaseManagementController::goLive/$1');
    $routes->post('update-deployment', 'admin\ReleaseManagementController::updateDeploymentStep');

    // Rollback
    $routes->post('initiate-rollback', 'admin\ReleaseManagementController::initiateRollback');
    $routes->post('complete-rollback/(:num)', 'admin\ReleaseManagementController::completeRollback/$1');

    // Comments
    $routes->post('add-comment', 'admin\ReleaseManagementController::addComment');

    // AJAX Data
    $routes->get('audit-log/(:num)', 'admin\ReleaseManagementController::getAuditLog/$1');
    $routes->get('version-thread/(:num)', 'admin\ReleaseManagementController::getVersionThread/$1');
});