<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Libraries\PushNotify;
use App\Models\Booking;
use App\Models\Provider;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeZone;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $model;
    protected $premiumModel;
    protected $walletModel;
    protected $quotationModel;
    protected $db;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->model = new Provider();
        $this->premiumModel = new \App\Models\PremiumRequestModel();
        $this->walletModel = new \App\Models\Wallet();
        $this->quotationModel = new \App\Models\Quotation();
        $this->db = \Config\Database::connect();

        helper(['Common_helper', 'Common']);
    }

    public function index()
    {
        $data['title'] = 'Walking Bookings';
        $data['page'] = 'booking';
        $data['bookings'] = $this->bookingModel->getWalkingBookings('Confirmed', 'Accepted');
        return $this->loadView('admin/bookings/walkingbookings', $data);
    }
    public function premiumRequests()
    {
        $data['title'] = 'Premium Service Requests';
        $data['page'] = 'premium_requests';
        $requests = $this->premiumModel->getRequests();

        foreach ($requests as $request) {
            $addressParts = array_filter([
                $request->houseno_floor ?? '',
                $request->building_blockno ?? '',
                $request->landmark_areaname ?? $request->area ?? '',
                $request->address ?? '',
            ]);
            $request->formatted_address = trim(implode(", ", $addressParts));

            $today = date('Y-m-d');
            $startDate = new DateTime($today);
            if (!empty($request->service_start_date)) {
                $endDate = new DateTime($request->service_start_date);
                $interval = $startDate->diff($endDate);
                $totalDays = $interval->days;
                if (property_exists($request, 'service_days') && $request->service_days == 'weekdays') {
                    $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
                    $totalDays = 0;
                    foreach ($period as $date) {
                        if ($date->format('w') != 0) {
                            $totalDays++;
                        }
                    }
                }
                $request->total_days = $totalDays;
            } else {
                $request->total_days = 0;
            }
        }

        $data['requests'] = $requests;

        return $this->loadView('admin/bookings/premium_requests', $data);
    }

    public function assignPremiumProvider()
    {
        $rules = [
            'request_id' => 'required|numeric',
            'provider_id' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }

        $requestId = $this->request->getPost('request_id');
        $providerId = $this->request->getPost('provider_id');
        $notes = $this->request->getPost('admin_notes');

        $db = \Config\Database::connect();

        $request = $db->table('premium_requests')->where('id', $requestId)->get()->getRow();
        if (!$request) {
            return $this->response->setJSON(['status' => false, 'message' => 'Request not found']);
        }

        $db->transStart();

        $db->table('premium_requests')->where('id', $requestId)->update([
            'assigned_provider_id' => $providerId,
            'admin_notes' => $notes,
            'status' => 'Assigned',
            'assigned_at' => date('Y-m-d H:i:s')
        ]);

        $tableName = null;
        $bookingId = $request->booking_id;
        $service_id = 0;

        $price = $this->db->table('walking_service_bookings')->where('id', $bookingId)->get()->getRow()->total_price;

        if (strpos($bookingId, 'WLK') !== false) {
            $tableName = 'walking_service_bookings';
            $service_id = 4;
        } elseif (strpos($bookingId, 'GRM') !== false) {
            $tableName = 'grooming_service_bookings';
            $service_id = 2;
        } elseif (strpos($bookingId, 'TRN') !== false) {
            $tableName = 'training_service_bookings';
            $service_id = 3;
        } elseif (strpos($bookingId, 'BOA') !== false) {
            $tableName = 'boarding_service_bookings';
            $service_id = 1;
        } elseif (strpos($bookingId, 'STT') !== false) {
            $tableName = 'sitting_service_bookings';
            $service_id = 6;
        }

        if ($tableName) {
            $db->table('quotations')
                ->where('booking_id', $bookingId)
                ->where('provider_id !=', $providerId)
                ->update(['status' => 'Cancelled']);

            $bookingUpdate = [
                'status' => 'Confirmed'
            ];
            if ($service_id == 4 || $service_id == 3 || $service_id == 2) {
                $bookingUpdate['total_price'] = $price;
            } else {
                $bookingUpdate['bid_amount'] = $price;
            }

            $db->table($tableName)->where('id', $bookingId)->update($bookingUpdate);

            // Calculate Receivable Amount
            $platform_charges = floatval(getenv('PLATFORM_PERCENTAGE') ?: 20);
            $receivable_amount = $price - ($price * $platform_charges / 100);

            $existingQuotation = $db->table('quotations')
                ->where('booking_id', $bookingId)
                ->where('provider_id', $providerId)
                ->get()
                ->getRow();

            if ($existingQuotation) {
                $db->table('quotations')->where('id', $existingQuotation->id)->update([
                    'bid_amount' => $price,
                    'total_amount' => $price,
                    'receivable_amount' => $receivable_amount,
                    'status' => 'Accepted',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $db->table('quotations')->insert([
                    'booking_id' => $bookingId,
                    'provider_id' => $providerId,
                    'user_id' => $request->user_id,
                    'service_id' => $service_id,
                    'bid_amount' => $price,
                    'total_amount' => $price,
                    'receivable_amount' => $receivable_amount,
                    'status' => 'Accepted',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Wallet Logic (Replicating processPetWallet)
            $serviceTypes = [4 => 'walking', 3 => 'training', 2 => 'grooming', 1 => 'boarding', '6' => 'sitting'];
            $serviceType = $serviceTypes[$service_id] ?? 'walking';
            $petIds = $db->table('booking_pets')->where('booking_id', $bookingId)->get()->getResult();
            $petCount = count($petIds);

            if ($petCount > 0) {
                $txnId = 'TXN_' . strtoupper(bin2hex(random_bytes(8)));
                $userAmountPerPet = $price / $petCount;
                $providerAmountPerPet = $receivable_amount / $petCount;

                foreach ($petIds as $pet) {
                    // Update User Wallet
                    // $userWalletUpdate = [
                    //     'user_id' => $request->user_id,
                    //     'pet_id' => $pet->pet_id,
                    //     $serviceType . '_amount' => $userAmountPerPet
                    // ];
                    // $this->walletModel->updateWalletAmount($userWalletUpdate, $serviceType, 'user_service_wallet');

                    // Log User Transaction
                    // $userBalanceAfter = $this->walletModel->getServiceWalletSum($request->user_id)['service_amount'];
                    // $userRefundAmount = $this->walletModel->getWithdrawalWalletSum($request->user_id);
                    // $idemKeyUser = "{$serviceType}_premium_assign_{$bookingId}_{$request->user_id}_{$pet->pet_id}";

                    // $this->walletModel->logTransaction(
                    //     $request->user_id,
                    //     $service_id,
                    //     $bookingId,
                    //     'credit',
                    //     $userAmountPerPet,
                    //     $userBalanceAfter,
                    //     $userRefundAmount,
                    //     'booking_amount',
                    //     ucfirst($serviceType) . ' premium service',
                    //     'user_wallet_histories',
                    //     $idemKeyUser,
                    //     $txnId
                    // );

                    // Update Provider Wallet
                    $providerWalletUpdate = [
                        'provider_id' => $providerId,
                        'pet_id' => $pet->pet_id,
                        $serviceType . '_amount' => $providerAmountPerPet
                    ];
                    $this->walletModel->updateWalletAmount($providerWalletUpdate, $serviceType, 'sp_service_wallet');

                    // Log Provider Transaction
                    $spBalanceAfter = $this->walletModel->getProviderServiceWalletSum($providerId)['service_amount'];
                    $spEarnAmount = $this->walletModel->getProviderWithdrawalWalletSum($providerId);
                    $idemKeyProvider = "{$serviceType}_premium_payout_{$bookingId}_{$providerId}_{$pet->pet_id}";

                    $this->walletModel->logTransaction(
                        $providerId,
                        $service_id,
                        $bookingId,
                        'credit',
                        $providerAmountPerPet,
                        $spBalanceAfter,
                        $spEarnAmount,
                        'booking_amount',
                        ucfirst($serviceType) . ' premium service',
                        'sp_wallet_histories',
                        $idemKeyProvider,
                        $txnId
                    );
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to assign provider.']);
        }

        return $this->response->setJSON(['status' => true, 'message' => 'Premium provider assigned and price set successfully.']);
    }

    public function getQuotations($bookingId)
    {
        $db = \Config\Database::connect();
        $quotations = $db->table('quotations')
            ->select('quotations.*, service_providers.name as provider_name, service_providers.phone as provider_phone')
            ->join('service_providers', 'service_providers.id = quotations.provider_id')
            ->where('booking_id', $bookingId)
            ->get()
            ->getResult();

        return $this->response->setJSON(['status' => true, 'quotations' => $quotations]);
    }
    public function boarding()
    {
        $data['title'] = 'Booking';
        $data['page'] = 'boarding';
        $data['bookings'] = [];
        return $this->loadView('admin/bookings/boardingbookings', $data);
    }
    public function grooming()
    {
        $data['title'] = 'Booking';
        $data['page'] = 'grooming';
        $data['bookings'] = [];

        return $this->loadView('admin/bookings/groomingbookings', $data);
    }
    public function training()
    {
        $data['title'] = 'Booking';
        $data['page'] = 'training';
        $data['bookings'] = [];

        return $this->loadView('admin/bookings/trainingbookings', $data);
    }
    public function allBookings()
    {
        $data['title'] = 'Bookings';

        $data['bookings'] = $this->bookingModel->getWalkingBooking();
        return $this->loadView('admin/bookings/client-bookings', $data);
    }
    public function walkingRequest()
    {
        $data['title'] = 'Walking Request';
        $data['page'] = 'walkingrequest';

        $data['bookings'] = $this->bookingModel->getWalkingBookings('New');
        return $this->loadView('admin/bookings/walkingrequest', $data);
    }
    public function walkingQuotes($booking_id)
    {
        $data['title'] = '';
        $data['page'] = 'walkingquotes';
        $data['booking_id'] = $booking_id;
        $data['quotations'] = $this->bookingModel->getWalkingQuotes($booking_id);
        foreach ($data['quotations'] as &$quotation) {

            $distance = getGoogleDistance(
                $quotation->booking_latitude,
                $quotation->booking_longitude,
                $quotation->service_latitude,
                $quotation->service_longitude
            );

            $quotation->distance = isset($distance) ? $distance : null;
        }


        return $this->loadView('admin/bookings/walking-quotes', $data);
    }
    public function bookingRequests()
    {
        $data['title'] = '';
        $data['page'] = 'bookingrequest';
        $data['bookings'] = $this->getBookingRequests();

        return $this->loadView('admin/bookings/booking-requests', $data);
    }
    public function getBookingRequests()
    {
        // Get bookings (assuming 'New' status)
        $groomingBookings = $this->bookingModel->getGroomingBookings('New');
        $walkingBookings = $this->bookingModel->getWalkingBookings('New');
        $trainingBookings = $this->bookingModel->getTrainingBookings('New');
        $sittingBookings = $this->bookingModel->getSittingBookings('New');

        $allBookings = array_merge(
            $groomingBookings ?? [],
            $walkingBookings ?? [],
            $trainingBookings ?? [],
            $sittingBookings ?? []
        );

        // Sort by created_at in descending order
        usort($allBookings, function ($a, $b) {
            $dateA = strtotime($a->created_at ?? '1970-01-01');
            $dateB = strtotime($b->created_at ?? '1970-01-01');
            return $dateB - $dateA; // For descending order
        });

        foreach ($allBookings as $booking) {

            if ($booking->service_id == '3' && isset($booking->package_id)) {
                $booking->includedAddons = $this->bookingModel->getPackageIncludedAddons($booking->package_id);
            }

            $addressParts = array_filter([
                $booking->houseno_floor,
                $booking->building_blockno,
                $booking->landmark_areaname,
                $booking->address,
            ]);

            $booking->address = trim(implode(", ", $addressParts));

            $booking->totalQuotes = $this->bookingModel->getTotalNewQuotes($booking->id, $booking->service_id);
            $booking->addons = $this->bookingModel->getBookingAddons($booking->id, $booking->service_id);

            $today = date('Y-m-d');
            $startDate = new DateTime($today);

            // Check if 'service_start_date' exists and is valid
            if (!empty($booking->service_start_date)) {
                $endDate = new DateTime($booking->service_start_date);
                $interval = $startDate->diff($endDate);
                $totalDays = $interval->days + 1;

                // Check if 'service_days' exists before accessing it
                if (property_exists($booking, 'service_days') && $booking->service_days == 'weekdays') {
                    $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
                    $totalDays = 0;

                    foreach ($period as $date) {
                        // Check if the day is not Sunday (6 is Sunday in PHP's w format?? 0 is Sunday, 6 is Saturday)
                        // PHP 'w' format: 0 (for Sunday) through 6 (for Saturday)
                        if ($date->format('w') != 0) {
                            $totalDays++;
                        }
                    }
                } else {
                    $totalDays = $startDate->diff($endDate)->days;
                }
            } else {
                // If no end date, default to 1 day for grooming bookings
                $totalDays = 1;
            }

            $booking->total_days = $totalDays;
        }

        return $allBookings;
    }

    public function groomingDisputes()
    {
        $data['title'] = '';

        $data['disputes'] = $this->bookingModel->getGroomingDisputes();
        return $this->loadView('admin/bookings/grooming-approvals', $data);
    }

    public function getGroomingData($bookingId, $providerId, $petId)
    {
        $packages = $this->bookingModel->getGroomingTrackingpackages($bookingId, $providerId, $petId);
        $addons = $this->bookingModel->getGroomingTrackingAddons($bookingId, $providerId, $petId);

        return $this->response->setJSON([
            'packages' => $packages,
            'addons' => $addons,
        ]);
    }

    public function bookings($status)
    {
        $request = service('request');
        $search = $request->getGet('search');
        $date = $request->getGet('date');
        $perPage = 25;

        $data['title'] = ucfirst($status === 'Confirmed' ? 'In Progress' : $status) . ' Bookings';
        $data['page'] = 'bookingrequest';
        $data['status'] = $status;

        // Build base query
        $db = \Config\Database::connect();

        // Map status
        $dbStatus = $status;
        if ($status === 'in_progress')
            $dbStatus = 'Confirmed';
        if ($status === 'halted')
            $dbStatus = 'Halted';
        if ($status === 'cancelled')
            $dbStatus = 'Cancelled';
        if ($status === 'completed')
            $dbStatus = 'Completed';


        $page = $request->getGet('page') ?: 1;
        $result = $this->bookingModel->getBookingsPaginated($dbStatus, $search, $date);

        $data['bookings'] = $result['bookings'];
        $data['pager'] = $result['pager'];

        // Post-process only the paginated results (25 rows max)
        foreach ($data['bookings'] as $booking) {
            $booking->totalQuotes = $this->bookingModel->getOtherQuotes($booking->id, $booking->provider_id ?? 0, $booking->service_id);

            $addressParts = array_filter([
                $booking->houseno_floor ?? '',
                $booking->building_blockno ?? '',
                $booking->landmark_areaname ?? '',
                $booking->address ?? '',
            ]);
            $booking->formatted_address = trim(implode(", ", $addressParts));

            // Fetch addons if needed for details modal
            $booking->addons = $this->bookingModel->getBookingAddons($booking->id, $booking->service_id);
        }

        return $this->loadView('admin/bookings/bookings', $data);
    }

    public function approveOrRejectGroomingTracking()
    {
        $rules = [
            'booking_id' => 'required',
            'provider_id' => 'required',
            'pet_id' => 'required',
            'action' => 'required|in_list[create,update,approve,reject]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }

        $json = json_decode(json_encode($this->request->getPost()));
        log_message('info', 'Received JSON: ' . json_encode($json));

        $data = [
            'booking_id' => $json->booking_id,
            'provider_id' => $json->provider_id,
            'pet_id' => $json->pet_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $booking = $this->bookingModel->getGroomingPriceCalculate($json->booking_id, $json->provider_id);
        $discount = floatval($booking->discount);
        $platform_charges = floatval(getenv('PLATFORM_PERCENTAGE'));
        $bookingPackages = $this->bookingModel->getBookingPackages($data['booking_id']);
        $bookingPackageIds = array_column($bookingPackages, 'package_id');

        $pendingAmount = 0;
        $completedAmount = 0;

        $completedAmount += $this->processPackages($json->packages, $json->action, $json->pet_id, $data, $bookingPackageIds, $pendingAmount);
        $completedAmount += $this->processAddons($json->addons, $json->action, $json->pet_id, $data, $pendingAmount);

        $vanAmount = ($booking->service_mode === 'van_service') ? floatval(getenv('VAN_SERVICE')) : 0;
        $totalWithVanAmount = $completedAmount + $vanAmount;
        $discountAmount = ($discount > 0) ? ($totalWithVanAmount * $discount) / 100 : 0;
        $netAmount = $totalWithVanAmount - $discountAmount;
        $platformAmount = ($netAmount * $platform_charges) / 100;
        $providerAmount = $netAmount - $platformAmount;
        $platformFee = $netAmount - $providerAmount;
        if ($json->action === 'approve') {
            $db = \Config\Database::connect();
            $db->transStart();

            if ($netAmount > 0) {
                $this->bookingModel->creditWallet($json->provider_id, $providerAmount);
                $this->bookingModel->debitGroomingRefundAmount($json->user_id, $netAmount, $json->pet_id);
                $this->bookingModel->debitSpGroomingRefundAmount($json->provider_id, $providerAmount, $json->pet_id);

                $balanceAfter = $this->bookingModel->getServiceWalletSum($json->user_id)['service_amount'];
                $refundAmount = $this->bookingModel->getWithdrawalWalletSum($json->user_id);

                $spBalanceAfter = $this->bookingModel->getProviderServiceWalletSum($json->provider_id)['service_amount'];
                $spEarnAmount = $this->bookingModel->getProviderWithdrawalWalletSum($json->provider_id);

                $txnId = 'TXN_GR_' . strtoupper(bin2hex(random_bytes(8)));

                $idempotencyKeySP = "groom_approve_sp_{$json->booking_id}_{$json->pet_id}";
                $this->bookingModel->logTransaction(
                    $json->provider_id,
                    '2',
                    $json->booking_id,
                    'credit',
                    $providerAmount,
                    $spBalanceAfter,
                    $spEarnAmount,
                    'approved_amount',
                    'Approved Grooming service amount',
                    'sp_wallet_histories',
                    $idempotencyKeySP,
                    $txnId
                );

                $idempotencyKeyUser = "groom_approve_user_{$json->booking_id}_{$json->pet_id}";
                $this->bookingModel->logTransaction(
                    $json->user_id,
                    '2',
                    $json->booking_id,
                    'debit',
                    $netAmount,
                    $balanceAfter,
                    $refundAmount,
                    'approved_amount',
                    'Approved Grooming service amount',
                    'user_wallet_histories',
                    $idempotencyKeyUser,
                    $txnId
                );

                $idempotencyKeyPlatform = "groom_platform_{$json->booking_id}_{$json->pet_id}";
                $this->bookingModel->logPlatformTransaction(
                    $json->booking_id,
                    $json->provider_id,
                    $json->user_id,
                    '2',
                    $platformFee,
                    'credit',
                    'Grooming service amount',
                    'platform_wallet_histories',
                    $idempotencyKeyPlatform,
                    $txnId
                );
            }

            if ($pendingAmount > 0) {
                $discounPendingAmount = ($discount > 0) ? ($pendingAmount * $discount) / 100 : 0;
                $pendingNetAmount = $pendingAmount - $discounPendingAmount;
                $platformPendingAmount = ($pendingNetAmount * $platform_charges) / 100;
                $providerPendingAmount = $pendingNetAmount - $platformPendingAmount;

                $this->bookingModel->addRefundAmount($json->user_id, $pendingNetAmount, $json->pet_id, $service_id = '2');
                $this->bookingModel->debitGroomingRefundAmount($json->user_id, $pendingNetAmount, $json->pet_id);
                $this->bookingModel->debitSpGroomingRefundAmount($json->provider_id, $providerPendingAmount, $json->pet_id);
                $balanceAfter = $this->bookingModel->getServiceWalletSum($json->user_id)['service_amount'];
                $refundAmount = $this->bookingModel->getWithdrawalWalletSum($json->user_id);

                $spBalanceAfter = $this->bookingModel->getProviderServiceWalletSum($json->provider_id)['service_amount'];
                $spEarnAmount = $this->bookingModel->getProviderWithdrawalWalletSum($json->provider_id);

                $txnIdRej = 'TXN_GR_REJ_' . strtoupper(bin2hex(random_bytes(8)));

                $idempotencyKeyRejSP = "groom_reject_sp_{$json->booking_id}_{$json->pet_id}";
                $this->bookingModel->logTransaction(
                    $json->provider_id,
                    '2',
                    $json->booking_id,
                    'debit',
                    $providerPendingAmount,
                    $spBalanceAfter,
                    $spEarnAmount,
                    'cancelled_amount',
                    'Rejected Grooming service',
                    'sp_wallet_histories',
                    $idempotencyKeyRejSP,
                    $txnIdRej
                );

                $idempotencyKeyRejUser = "groom_reject_user_{$json->booking_id}_{$json->pet_id}";
                $this->bookingModel->logTransaction(
                    $json->user_id,
                    '2',
                    $json->booking_id,
                    'credit',
                    $pendingNetAmount,
                    $balanceAfter,
                    $refundAmount,
                    'refund_amount',
                    'Rejected Grooming service refund',
                    'user_wallet_histories',
                    $idempotencyKeyRejUser,
                    $txnIdRej
                );
            }
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => false, 'message' => 'Grooming tracking approval transaction failed']);
            }
            $allPetsApproved = $this->bookingModel->checkAllPetsApproved($json->booking_id);

            log_message('debug', 'Booking ID: ' . $json->booking_id . ' | AllPetsApproved: ' . ($allPetsApproved ? 'true' : 'false'));

            if ($allPetsApproved) {
                log_message('debug', 'Booking ' . $json->booking_id . ' - updating booking and quotation for provider: ' . $json->provider_id);

                $this->bookingModel->updateBookingStatus($json->booking_id);
                $this->bookingModel->updateQuotation($json->booking_id, $json->provider_id);

                log_message('debug', 'Booking ' . $json->booking_id . ' update completed.');
            } else {
                log_message('debug', 'Booking ' . $json->booking_id . ' not yet fully approved.');
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => ucfirst($json->action) . ' completed successfully',
        ]);
    }

    private function processPackages($packages, $action, $pet_id, &$data, $bookingPackageIds, &$pendingAmount)
    {
        $completedAmount = 0;

        foreach ($packages as $package) {
            $data['package_id'] = $package->package_id;
            $data['addon'] = '';
            $data['status'] = $package->status;
            $record = $this->bookingModel->getGroomingTrackingByPackage($data['booking_id'], $data['package_id'], $pet_id);

            $this->handleTracking($action, $record, $data, $pet_id);

            if (in_array($package->package_id, $bookingPackageIds)) {
                $price = floatval($this->bookingModel->getPackageById($package->package_id)->price ?? 0);
                $status = $record->status ?? $package->status ?? null;

                if ($status === 'completed') {
                    $completedAmount += $price;
                } elseif ($status === 'pending') {
                    $pendingAmount += $price;
                }
            }
        }

        return $completedAmount;
    }

    private function processAddons($addons, $action, $pet_id, &$data, &$pendingAmount)
    {
        $completedAmount = 0;

        foreach ($addons as $addon) {
            $data['addon'] = $addon->name;
            $data['status'] = $addon->status;
            $data['package_id'] = 0;
            $record = $this->bookingModel->getGroomingTrackingByAddon($data['booking_id'], $addon->name, $pet_id);

            $this->handleTracking($action, $record, $data, $pet_id);

            $price = floatval($this->bookingModel->getAddonByName($addon->name)->price ?? 0);
            $status = $record->status ?? $addon->status ?? null;

            if ($status === 'completed') {
                $completedAmount += $price;
            } elseif ($status === 'pending') {
                $pendingAmount += $price;
            }
        }

        return $completedAmount;
    }

    private function handleTracking($action, $record, $data, $pet_id)
    {
        $this->bookingModel->updateRide($data['booking_id'], 'completed');
        if (in_array($action, ['create', 'update'])) {
            if ($action === 'create' && !$record) {
                $this->bookingModel->createGroomingTrackingPkg($data);
            } elseif ($action === 'update') {
                if ($record) {
                    $this->bookingModel->updateGroomingTracking($data, $record->id);
                    $this->bookingModel->updateGroomingBooking($data['booking_id'], 'in_progress');
                } else {
                    $this->bookingModel->createGroomingTrackingPkg($data);
                    $this->bookingModel->updateGroomingBooking($data['booking_id'], 'in_progress');
                }
            }
            $this->bookingModel->updatePetTracking($data['booking_id'], $pet_id, 'in_progress');
        } elseif ($action === 'approve') {

            if (!$record) {
                $createdId = $this->bookingModel->createGroomingTrackingPkg($data);
                $this->bookingModel->approveGroomingTracking($data, $createdId);
            } else {
                $this->bookingModel->approveGroomingTracking($data, $record->id);
            }
            $this->bookingModel->updatePetTracking($data['booking_id'], $pet_id, 'approved', 'completed');
        } elseif ($action === 'reject') {
            $data['status'] = 'rejected';
            if (!$record) {
                $createdId = $this->bookingModel->createGroomingTrackingPkg($data);
                $this->bookingModel->rejectGroomingTracking($createdId);
            } else {
                $this->bookingModel->rejectGroomingTracking($record->id);
            }
            $this->bookingModel->updateGroomingBooking($data['booking_id'], 'rejected');
            $this->bookingModel->updatePetTracking($data['booking_id'], $pet_id, 'rejected');
        }
    }


    public function repostBooking()
    {
        $booking_id = $this->request->getPost('booking_id');
        $start_date = $this->request->getPost('start_date');
        $service_id = $this->request->getPost('service_id');

        $notificationTitle = '';
        $booking = null;
        $reposted = false;

        if ($service_id == '1') {
            // Handle service 1 if needed
        } elseif ($service_id == '2') {
            $booking = $this->bookingModel->getBooking('grooming_service_bookings', $booking_id);
            $providers = $this->bookingModel->getServiceProviders($service_id);
            $reposted = $this->bookingModel->repostGroomingBooking('grooming_service_bookings', $booking_id, $start_date);
            $notificationTitle = 'New job alert - Grooming';
        } elseif ($service_id == '3') {
            $booking = $this->bookingModel->getBooking('training_service_bookings', $booking_id);
            $providers = $this->bookingModel->getServiceProviders($service_id);
            $reposted = $this->bookingModel->repostTrainingBooking('training_service_bookings', $booking_id, $start_date);
            $notificationTitle = 'New job alert - Training';
        } elseif ($service_id == '4') {
            $notificationTitle = 'New job alert - Walking';
            $booking = $this->bookingModel->getBooking('walking_service_bookings', $booking_id);
            $providers = $this->bookingModel->getServiceProviders($service_id);
            $totalDays = $this->bookingModel->getPackageDetails($booking->package_id)->duration_days;
            $end_date = calculateEndDate($start_date, $totalDays, $booking->service_days);
            $reposted = $this->bookingModel->repostWalkingBooking('walking_service_bookings', $booking_id, $start_date, $end_date);
        }

        if ($reposted && !empty($notificationTitle) && $booking && isset($providers)) {
            $notifiedProviders = [];
            foreach ($providers as $provider) {
                // Calculate distance for all services (assuming location is relevant)
                if (
                    isset($booking->booking_latitude) && isset($booking->booking_longitude) &&
                    isset($provider->service_latitude) && isset($provider->service_longitude)
                ) {
                    $d = distanceCalculator(
                        $booking->booking_latitude,
                        $booking->booking_longitude,
                        $provider->service_latitude,
                        $provider->service_longitude
                    );
                    $provider->distance = round($d, 2);

                    // Notify providers within 30km (adjust threshold as needed)
                    if ($d <= 30) {
                        $PushNotify = new PushNotify();
                        $PushNotify->notify($provider->device_token, $notificationTitle, 'Don\'t miss out—submit your quote now!');
                        $notifiedProviders[] = $provider->provider_id;
                    }
                } else {
                    // If location isn't relevant, notify all providers
                    $PushNotify = new PushNotify();
                    $PushNotify->notify($provider->device_token, $notificationTitle, 'Don\'t miss out—submit your quote now!');
                    $notifiedProviders[] = $provider->provider_id;
                }
            }

            // Notify the user that their booking was reposted
            if (isset($booking->user_device_token)) {
                $serviceName = '';
                switch ($service_id) {
                    case '2':
                        $serviceName = 'grooming';
                        break;
                    case '3':
                        $serviceName = 'training';
                        break;
                    case '4':
                        $serviceName = 'walking';
                        break;
                }

                $PushNotify = new PushNotify();
                $PushNotify->notify(
                    $booking->user_device_token,
                    "Your {$serviceName} request has been reposted",
                    'Please check the app for new quotes.'
                );
            }
        }

        return $this->response->setJSON([
            'status' => $reposted,
            'message' => $reposted ? 'Repost completed successfully' : 'Repost failed',
        ]);
    }

    public function trackings()
    {
        $data['title'] = '';
        $data['booking_id'] = $this->request->getGet('booking_id');
        $data['service_id'] = $this->request->getGet('service_id');
        $data['provider_id'] = $this->request->getGet('provider_id');
        return $this->loadView('admin/bookings/trackings', $data);
    }

    public function getBookingDetails()
    {
        $booking_id = $this->request->getPost('booking_id');
        $service_id = $this->request->getPost('service_id');
        $provider_id = $this->request->getPost('provider_id');
        $totalPets = 0;

        if ($service_id == '4') {
            $data = [
                'trackings' => $this->bookingModel->getTracking($booking_id),
                'walletDetails' => $this->bookingModel->getWalletDetails($booking_id),
            ];
            $booking = $this->bookingModel->getBookingforPriceCalculate($booking_id, $provider_id);
            $frequency = $this->getServiceFrequency(frequencyString: $booking->service_frequency);
            $totalPets = $this->bookingModel->getBookingPets($booking_id);


            $walks = $frequency * $booking->duration_days;
            $netAmount = (float) $booking->receivable_amount / ($walks * $totalPets);
            $clnetAmount = (float) $booking->bid_amount / ($walks * $totalPets);
            $platformCharges = $booking->platform_charges / ($walks * $totalPets);
        } else if ($service_id == '2') {
            $totalPets = $this->bookingModel->getBookingPets($booking_id);
            $data = [
                'trackings' => $this->bookingModel->getGroomingTracking($booking_id),
                'walletDetails' => $this->bookingModel->getGroomingWalletDetails($booking_id),
            ];
        } else if ($service_id == '3') {
            $totalPets = $this->bookingModel->getBookingPets($booking_id);

            // Get all tracking records
            $trackings = $this->bookingModel->getTrainingTracking($booking_id);
            $data = [
                'trackings' => $trackings,
                'walletDetails' => $this->bookingModel->getTrainingWalletDetails($booking_id),
            ];
        } else if ($service_id == '6') {
            $totalPets = $this->bookingModel->getBookingPets($booking_id);
            $trackings = $this->bookingModel->getSittingTracking($booking_id);

            $netAmount = 0;
            $clnetAmount = 0;
            $platformCharges = 0;

            $db = \Config\Database::connect();
            $quotation = $db->table('quotations')
                ->where('booking_id', $booking_id)
                ->where('provider_id', $provider_id)
                ->whereIn('status', ['Accepted', 'Completed'])
                ->get()
                ->getRow();

            $totalTrackings = count($trackings) > 0 ? count($trackings) / $totalPets : 0;
            if ($quotation && $totalTrackings > 0 && $totalPets > 0) {
                $netAmount = (float) $quotation->receivable_amount / ($totalTrackings * $totalPets);
                $clnetAmount = (float) $quotation->bid_amount / ($totalTrackings * $totalPets);
                $platformCharges = (float) $quotation->platform_charges / ($totalTrackings * $totalPets);
            }

            $data = [
                'trackings' => $trackings,
                'walletDetails' => $this->bookingModel->getSittingWalletDetails($booking_id),
            ];
        } else {

            $data = [
                'trackings' => [],
                'walletDetails' => [],
            ];
        }


        $calcuttaTZ = new DateTimeZone('Asia/Kolkata');
        $service_amount = 0;
        foreach ($data['trackings'] as $track) {
            if (isset($track->start_time) && $track->start_time != '0000-00-00 00:00:00') {
                $start_time = new DateTime($track->start_time, new DateTimeZone('UTC'));
                $start_time->setTimezone($calcuttaTZ);
                $track->start_time = $start_time->format('Y-m-d h:i:s');
            } else {
                $track->start_time = null;
            }
            if (isset($track->end_time) && $track->end_time != '0000-00-00 00:00:00') {
                $end_time = new DateTime($track->end_time, new DateTimeZone('UTC'));
                $end_time->setTimezone($calcuttaTZ);
                $track->end_time = $end_time->format('Y-m-d h:i:s');
            } else {
                $track->end_time = null;
            }


            if ($service_id == '4' || $service_id == '6') {

                $track->netAmount = $netAmount;
                $track->clnetAmount = $clnetAmount;
                $track->platformCharges = $platformCharges;
                $service_amount = $track->service_amount * $totalPets;
            } else if ($service_id == '3') {

                $totalPets = $this->bookingModel->getBookingPets($booking_id);

                $trackings = $this->bookingModel->getTrainingTracking($booking_id);
                $packageAddons = [];
                foreach ($trackings as $track) {
                    if ($track->is_included_in_packages == '1' || $track->is_included_in_packages == 1) {
                        $packageId = $track->package_id ?? null;
                        if ($packageId) {
                            if (!isset($packageAddons[$packageId])) {
                                $totalIncludedAddons = $this->bookingModel->getPackageAddonCount($packageId);
                                $packageAddons[$packageId] = [
                                    'price' => $track->package_price,
                                    'total_addons' => $totalIncludedAddons,
                                ];
                            }
                        }
                    }
                }
                foreach ($trackings as $track) {
                    if ($track->is_included_in_packages == '1' || $track->is_included_in_packages == 1) {
                        $packageId = $track->package_id ?? null;
                        if ($packageId && isset($packageAddons[$packageId])) {
                            $package = $packageAddons[$packageId];
                            $track->addon_price = $package['price'] / $package['total_addons'];
                        }
                    } else {
                        $track->addon_price = $track->addon_price ?? $track->price;
                    }
                }
                $userAmount = 0;
                $platformAmount = 0;
                $providerAmount = 0;

                foreach ($trackings as $track) {
                    $addonPrice = $track->addon_price ?? $track->price;
                    if ($track->status == 'approved') {
                        $discount = $booking->discount ?? 0;
                        $platformPercentage = floatval(getenv('PLATFORM_PERCENTAGE')) ?? 10;

                        $discountAmount = ($discount > 0) ? ($addonPrice * $discount) / 100 : 0;
                        $userAmount = $addonPrice - $discountAmount;
                        $platformAmount = ($userAmount * $platformPercentage) / 100;
                        $providerAmount = $userAmount - $platformAmount;
                    }
                    $track->clnetAmount = round($userAmount, 2);
                    $track->platformCharges = round($platformAmount, 2);
                    $track->netAmount = round($providerAmount, 2);
                }
                $data = [
                    'trackings' => $trackings,
                    'walletDetails' => $this->bookingModel->getTrainingWalletDetails($booking_id),
                ];
            }
        }

        $data['service_amount'] = $service_amount;
        $data['total_pets'] = $totalPets;

        return $this->response->setJSON([
            'status' => true,
            'data' => $data,

        ]);
    }

    public function Payments()
    {
        $data['title'] = '';
        $data['payments'] = [];
        $payments = $this->bookingModel->getPayments('booking');
        foreach ($payments as $payment) {
            if ($payment->status == 'FAILED') {
                $payment->description = phonepeResponseDescription($payment->response_code);
            } else {
                $payment->description = '';
            }
            $data['payments'][] = $payment;
        }
        return $this->loadView('admin/payments', $data);
    }
    public function spPayments()
    {
        $data['title'] = '';
        $data['payments'] = $this->bookingModel->getSpPayments('registration');
        return $this->loadView('admin/sp_payments', $data);
    }
    public function deleteBooking($booking_id, $service_id)
    {
        if ($service_id == '4' || $service_id == 4) {
            $this->bookingModel->deleteWalkingBooking($booking_id);
        } else if ($service_id == '3' || $service_id == 3) {
            $this->bookingModel->deleteTrainingBooking($booking_id);
        } else if ($service_id == '2' || $service_id == 2) {
            $this->bookingModel->deleteGroomingBooking($booking_id);
        } else {
            $this->bookingModel->deleteBooking($booking_id);
        }
        // $this->bookingModel->deleteBooking($booking_id, $service_id);
        return $this->response->setJSON([
            'status' => true,
            'message' => 'Booking deleted successfully',
        ]);
    }

    public function updateCancelReason()
    {
        $booking_id = $this->request->getPost('booking_id');
        $cancel_reason = $this->request->getPost('cancel_reason');
        $service_id = $this->request->getPost('service_id');
        if ($service_id == '4' || $service_id == 4) {
            $this->bookingModel->updateCancelReasonWalking($booking_id, $cancel_reason);
        } elseif ($service_id == '3' || $service_id == 3) {
            $this->bookingModel->updateCancelReasonTraining($booking_id, $cancel_reason);
        } elseif ($service_id == '2' || $service_id == 2) {
            $this->bookingModel->updateCancelReasonGrooming($booking_id, $cancel_reason);
        } else {
            $this->bookingModel->updateCancelReason($booking_id, $cancel_reason);
        }
        return $this->response->setJSON([
            'status' => true,
            'message' => 'Cancel reason updated successfully',
        ]);
    }
    public function updateComment()
    {
        $booking_id = $this->request->getPost('booking_id');
        $comment = $this->request->getPost('comment');
        $service_id = $this->request->getPost('service_id');
        if ($service_id == '4' || $service_id == 4) {
            $this->bookingModel->updateCommentWalking($booking_id, $comment);
        } elseif ($service_id == '3' || $service_id == 3) {
            $this->bookingModel->updateCommentTraining($booking_id, $comment);
        } elseif ($service_id == '2' || $service_id == 2) {
            $this->bookingModel->updateCommentGrooming($booking_id, $comment);
        } else {
            $this->bookingModel->updateComment($booking_id, $comment);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Comment updated successfully',
        ]);
    }


    private function getServiceFrequency($frequencyString)
    {
        switch ($frequencyString) {
            case 'once a day':
                return 1;
            case 'twice a day':
                return 2;
            case 'thrice a day':
                return 3;
            default:
                return 0;
        }
    }
    public function clientStatistics()
    {
        $data['title'] = '';

        $data['stats'] = [
            'walking' => $this->bookingModel->getBookingStats(),
            'grooming' => $this->bookingModel->getGroomingBookingStats(),
            'training' => $this->bookingModel->getTrainingBookingStats()
        ];
        $data['combined_stats'] = $this->combineServiceStats($data['stats']);

        return $this->loadView('admin/user/client_bookings_statistics', $data);
    }

    private function combineServiceStats($stats)
    {
        $combined = [];

        foreach ($stats as $serviceType => $serviceStats) {

            foreach ($serviceStats as $state => $stateData) {

                if (!isset($combined[$state])) {
                    $combined[$state] = [
                        'name' => $state,
                        'clients_signed_up' => 0,
                        'jobs_posted' => 0,
                        'unassigned_jobs' => 0,
                        'assigned' => 0,
                        'sp_replaced' => 0,
                        'cancelled' => 0,
                        'refunds' => 0,
                        'completed' => 0,
                        'renewals' => 0,
                        'cities' => []
                    ];
                }

                // Sum state-level statistics
                foreach ($stateData as $key => $value) {
                    if ($key !== 'name' && $key !== 'cities') {
                        $combined[$state][$key] += $value;
                    }
                }

                // Combine city-level data
                foreach ($stateData['cities'] as $city => $cityData) {

                    if (!isset($combined[$state]['cities'][$city])) {
                        $combined[$state]['cities'][$city] = [
                            'name' => $city,
                            'clients_signed_up' => 0,
                            'jobs_posted' => 0,
                            'unassigned_jobs' => 0,
                            'assigned' => 0,
                            'sp_replaced' => 0,
                            'cancelled' => 0,
                            'refunds' => 0,
                            'completed' => 0,
                            'renewals' => 0
                        ];
                    }

                    foreach ($cityData as $key => $value) {
                        if ($key !== 'name' && is_numeric($value)) {
                            $combined[$state]['cities'][$city][$key] += $value;
                        }
                    }
                }
            }
        }

        return $combined;
    }
    public function providerStatistics()
    {
        $data['title'] = '';

        // Get all providers grouped by type
        $data['walkers'] = $this->model->getByService('4');
        $data['groomers'] = $this->model->getByService('2');
        $data['trainers'] = $this->model->getByService('3');
        $data['boarders'] = $this->model->getByService('1');

        // Calculate statistics for each provider type
        $data['walker_stats'] = $this->calculateNestedProviderStats($data['walkers']);
        $data['groomer_stats'] = $this->calculateNestedProviderStats($data['groomers']);
        $data['trainer_stats'] = $this->calculateNestedProviderStats($data['trainers']);
        $data['boarder_stats'] = $this->calculateNestedProviderStats($data['boarders']);

        return $this->loadView('admin/provider/service_providers_statistics', $data);
    }

    public function providerDetailsStatistics($service_id)
    {
        $data['title'] = '';

        // Determine service name
        $service_names = [
            '4' => 'Walker',
            '2' => 'Groomer',
            '3' => 'Trainer',
            '1' => 'Boarder'
        ];
        $data['service_name'] = $service_names[$service_id] ?? 'Service';

        // Get all providers grouped by type
        $data['sp'] = $this->model->getByService($service_id);

        // Calculate statistics for each provider type
        $data['sp_stats'] = $this->calculateNestedProviderStats($data['sp']);
        return $this->loadView('admin/provider/provider_statistics_details', $data);
    }

    private function calculateNestedProviderStats($providers)
    {
        $stats = [
            'total' => count($providers),
            'active' => 0,
            'suspended' => 0,
            'total_rating' => 0,
            'total_completion' => 0,
            'states' => []
        ];

        foreach ($providers as $provider) {
            // Calculate verification completion percentage
            $provider->completionPercentage = $this->calculateCompletionPercentage($provider->id);

            // Update overall stats
            if ($provider->status == 'active') {
                $stats['active']++;
            } elseif ($provider->status == 'inactive') {
                $stats['suspended']++;
            }

            $stats['total_rating'] += $provider->rating;
            $stats['total_completion'] += $provider->completionPercentage;

            // Build nested structure: State -> City -> Area
            if (!isset($stats['states'][$provider->state])) {
                $stats['states'][$provider->state] = [
                    'name' => $provider->state,
                    'count' => 0,
                    'active' => 0,
                    'suspended' => 0,
                    'total_rating' => 0,
                    'total_completion' => 0,
                    'cities' => []
                ];
            }

            // Update state stats
            $stats['states'][$provider->state]['count']++;
            if ($provider->status == 'active') {
                $stats['states'][$provider->state]['active']++;
            } elseif ($provider->status == 'inactive') {
                $stats['states'][$provider->state]['suspended']++;
            }
            $stats['states'][$provider->state]['total_rating'] += $provider->rating;
            $stats['states'][$provider->state]['total_completion'] += $provider->completionPercentage;

            // City level
            if (!isset($stats['states'][$provider->state]['cities'][$provider->city])) {
                $stats['states'][$provider->state]['cities'][$provider->city] = [
                    'name' => $provider->city,
                    'count' => 0,
                    'active' => 0,
                    'suspended' => 0,
                    'total_rating' => 0,
                    'total_completion' => 0,
                    'areas' => []
                ];
            }

            // Update city stats
            $stats['states'][$provider->state]['cities'][$provider->city]['count']++;
            if ($provider->status == 'active') {
                $stats['states'][$provider->state]['cities'][$provider->city]['active']++;
            } elseif ($provider->status == 'inactive') {
                $stats['states'][$provider->state]['cities'][$provider->city]['suspended']++;
            }
            $stats['states'][$provider->state]['cities'][$provider->city]['total_rating'] += $provider->rating;
            $stats['states'][$provider->state]['cities'][$provider->city]['total_completion'] += $provider->completionPercentage;

            // Area level (if applicable)
            if (!empty($provider->area)) {
                if (!isset($stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area])) {
                    $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area] = [
                        'name' => $provider->area,
                        'count' => 0,
                        'active' => 0,
                        'suspended' => 0,
                        'total_rating' => 0,
                        'total_completion' => 0
                    ];
                }

                // Update area stats
                $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area]['count']++;
                if ($provider->status == 'active') {
                    $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area]['active']++;
                } elseif ($provider->status == 'inactive') {
                    $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area]['suspended']++;
                }
                $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area]['total_rating'] += $provider->rating;
                $stats['states'][$provider->state]['cities'][$provider->city]['areas'][$provider->area]['total_completion'] += $provider->completionPercentage;
            }
        }

        // Calculate averages for all levels
        if ($stats['total'] > 0) {
            $stats['avg_rating'] = round($stats['total_rating'] / $stats['total'], 1);
            $stats['avg_completion'] = round($stats['total_completion'] / $stats['total'], 1);

            foreach ($stats['states'] as &$state) {
                $state['avg_rating'] = round($state['total_rating'] / $state['count'], 1);
                $state['avg_completion'] = round($state['total_completion'] / $state['count'], 1);

                foreach ($state['cities'] as &$city) {
                    $city['avg_rating'] = round($city['total_rating'] / $city['count'], 1);
                    $city['avg_completion'] = round($city['total_completion'] / $city['count'], 1);

                    foreach ($city['areas'] as &$area) {
                        $area['avg_rating'] = round($area['total_rating'] / $area['count'], 1);
                        $area['avg_completion'] = round($area['total_completion'] / $area['count'], 1);
                    }
                }
            }
        }

        return $stats;
    }

    private function calculateCompletionPercentage($providerId)
    {
        $verifications = $this->model->getAllVerfications($providerId);
        $verificationArray = (array) $verifications;

        $verificationFields = [
            'email_verified',
            'mobile_verified',
            'identity_proof_verified',
            'address_proof_verified',
            'police_verification_verified',
            'post_card_verified',
            'walking_verified',
            'profile_picture_verified',
            'call_verification',
            'training_verified',
            'grooming_verified',
            'boarding_verified',
            'aadhaar_card_no',
            'upi_id'
        ];

        $totalVerifications = count($verificationFields);
        $completedVerifications = 0;

        foreach ($verificationFields as $field) {
            if (!empty($verificationArray[$field])) {
                $completedVerifications++;
            }
        }

        return ($totalVerifications > 0) ? round(($completedVerifications / $totalVerifications) * 100, 1) : 0;
    }

    public function missedWalksReports()
    {
        $data['walks'] = $this->bookingModel->getUntrackedWalks();
        return $this->loadView('admin/bookings/missed_walks_report', $data);
    }
    public function updateReason()
    {
        $trackId = $this->request->getPost('track_id');
        $reason = $this->request->getPost('reason');
        $this->bookingModel->updateUntrackReason($trackId, $reason);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Reason updated successfully',
        ]);
    }

    public function getBookingsData($status)
    {
        ob_start();

        $request = service('request');
        $draw = $request->getPost('draw');
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';
        $orderColumn = $request->getPost('order')[0]['column'] ?? 0;
        $orderDirection = $request->getPost('order')[0]['dir'] ?? 'desc';
        $serviceType = $request->getPost('service_type');
        $columns = [
            'updated_at',
            'id',
            'user_name',
            'user_phone',
            'pet_name',
            'breed',
            'service_name',
            'bid_amount',
            'state'
        ];

        $orderBy = $columns[$orderColumn] ?? 'updated_at';

        // Route to appropriate method based on status
        if ($status == 'Confirmed') {
            $result = $this->bookingModel->getBookingsServerSide($status, $start, $length, $searchValue, $orderBy, $orderDirection);
        } elseif ($status == 'halted') {
            $result = $this->getHaltedBookingsPaginated($start, $length, $searchValue, $orderBy, $orderDirection);
        } elseif ($status == 'cancelled') {
            $result = $this->getCancelledBookingsPaginated($start, $length, $searchValue, $orderBy, $orderDirection);
        } elseif ($status == 'deleted') {
            $result = $this->getDeletedBookingsPaginated($start, $length, $searchValue, $orderBy, $orderDirection);
        } else {
            $result = $this->getCompletedBookingsPaginated($start, $length, $searchValue, $orderBy, $orderDirection);
        }

        // Process data for display
        $processedData = [];
        $counter = $start + 1;

        foreach ($result['data'] as $booking) {
            // Add provider quotes if applicable
            if (isset($booking->provider_id)) {
                $booking->totalQuotes = $this->bookingModel->getOtherQuotes($booking->id, $booking->provider_id, $booking->service_id);
            }

            // Determine status text
            $status_text = $booking->status ?? '';
            if ($booking->status == 'Confirmed') {
                $status_text = 'In Progress';
            } elseif ($booking->status == 'Completed') {
                $status_text = 'Completed';
            }

            $row = [
                'DT_RowId' => 'row_' . $booking->id,
                'id' => $booking->id,
                'updated_at' => $booking->updated_at ?? '--',
                'sno' => $counter++,
                'user_name' => $booking->user_name ?? '--',
                'user_phone' => $booking->user_phone ?? '--',
                'pet_name' => $booking->pet_name ?? '--',
                'breed' => $booking->breed ?? '--',
                'provider_id' => $booking->provider_id ?? '',
                'price' => $booking->bid_amount ?? $booking->total_price ?? '--',
                'status_text' => $status_text ?? '',
                'provider_name' => $booking->provider_name ?? '--',
                'provider_phone' => $booking->provider_phone ?? '--',
                'service_start_date' => $booking->service_start_date ?? '--',
                'service_end_date' => $booking->service_end_date ?? '--',
                'totalQuotes' => $booking->totalQuotes ?? 0,
                'comment' => $booking->comment ?? '',
                'cancel_reason' => $booking->cancel_reason ?? '',
                'user_withdrawal_amount' => $booking->user_withdrawal_amount ?? '--',
                'created_at' => $booking->created_at ?? '--',
                'deleted_at' => $booking->deleted_at ?? '--',
                'deleted_by' => $booking->deleted_by ?? 'User',
                'service_type' => $booking->service_type ?? 'walking',
                'service_frequency' => $booking->service_frequency ?? '',
                'walk_duration' => $booking->walk_duration ?? '',
                'service_days' => $booking->service_days ?? '',
                'preferable_time' => $booking->preferable_time ?? ''
            ];

            $processedData[] = $row;
        }

        ob_clean();

        return $this->response->setJSON([
            'success' => true,
            'draw' => intval($draw),
            'recordsTotal' => intval($result['total']),
            'recordsFiltered' => intval($result['filtered']),
            'data' => $processedData
        ]);
    }

    private function getHaltedBookingsPaginated($start, $length, $search, $orderBy, $orderDir)
    {
        // Initialize variables
        $seen = [];
        $filtered = [];

        // Get all halted bookings (this should ideally be optimized at DB level)
        $bookings = $this->bookingModel->getHaltedWalkingBookings();

        // Apply deduplication first
        foreach ($bookings as $booking) {
            $number = trim(strtolower($booking->user_phone ?? ''));
            $pet = trim(strtolower($booking->pet_name ?? ''));
            $service = trim(strtolower($booking->service_name ?? ''));

            $baseKey = "{$number}_{$pet}";
            $comboKey = "{$baseKey}_{$service}";

            // Allow unique combination (number, pet, service_type)
            if (!isset($seen[$comboKey])) {
                $seen[$comboKey] = true;
                $filtered[] = $booking;
            }
        }

        // Get total after deduplication
        $total = count($filtered);

        // Apply search filter
        if (!empty($search)) {
            $filtered = array_filter($filtered, function ($booking) use ($search) {
                return stripos($booking->user_name, $search) !== false ||
                    stripos($booking->user_phone, $search) !== false ||
                    stripos($booking->service_name, $search) !== false;
            });
        }

        // Get filtered count
        $filteredCount = count($filtered);

        // Apply sorting
        usort($filtered, function ($a, $b) use ($orderBy, $orderDir) {
            $aValue = $a->{$orderBy} ?? '';
            $bValue = $b->{$orderBy} ?? '';

            if ($orderDir === 'desc') {
                return $bValue <=> $aValue;
            }
            return $aValue <=> $bValue;
        });

        // Apply pagination to FILTERED array
        $paginatedData = array_slice($filtered, $start, $length);

        return [
            'total' => $total,
            'filtered' => $filteredCount,
            'data' => $paginatedData
        ];
    }

    private function getCancelledBookingsPaginated($start, $length, $search, $orderBy, $orderDir)
    {
        $bookings = $this->bookingModel->getCancelledBookings('Cancelled');

        // Get total count
        $total = count($bookings);

        // Apply search filter
        if (!empty($search)) {
            $bookings = array_filter($bookings, function ($booking) use ($search) {
                return stripos($booking->user_name, $search) !== false ||
                    stripos($booking->user_phone, $search) !== false ||
                    stripos($booking->provider_name, $search) !== false;
            });
        }

        // Get filtered count
        $filtered = count($bookings);

        // Apply sorting
        usort($bookings, function ($a, $b) use ($orderBy, $orderDir) {
            $aValue = $a->{$orderBy} ?? '';
            $bValue = $b->{$orderBy} ?? '';

            if ($orderDir === 'desc') {
                return $bValue <=> $aValue;
            }
            return $aValue <=> $bValue;
        });

        // Apply pagination
        $paginatedData = array_slice($bookings, $start, $length);

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $paginatedData
        ];
    }

    private function getDeletedBookingsPaginated($start, $length, $search, $orderBy, $orderDir)
    {
        $bookings = $this->bookingModel->getDeletedBookings('deleted');

        // Get total count
        $total = count($bookings);

        // Apply search filter
        if (!empty($search)) {
            $bookings = array_filter($bookings, function ($booking) use ($search) {
                return stripos($booking->user_name, $search) !== false ||
                    stripos($booking->user_phone, $search) !== false;
            });
        }

        // Get filtered count
        $filtered = count($bookings);

        // Apply sorting
        usort($bookings, function ($a, $b) use ($orderBy, $orderDir) {
            $aValue = $a->{$orderBy} ?? '';
            $bValue = $b->{$orderBy} ?? '';

            if ($orderDir === 'desc') {
                return $bValue <=> $aValue;
            }
            return $aValue <=> $bValue;
        });

        // Apply pagination
        $paginatedData = array_slice($bookings, $start, $length);

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $paginatedData
        ];
    }

    private function getCompletedBookingsPaginated($start, $length, $search, $orderBy, $orderDir)
    {
        $bookings = $this->bookingModel->getCompletedBookings();

        // Get total count
        $total = count($bookings);

        // Apply search filter
        if (!empty($search)) {
            $bookings = array_filter($bookings, function ($booking) use ($search) {
                return stripos($booking->user_name, $search) !== false ||
                    stripos($booking->user_phone, $search) !== false ||
                    stripos($booking->provider_name, $search) !== false;
            });
        }

        // Get filtered count
        $filtered = count($bookings);

        // Apply sorting
        usort($bookings, function ($a, $b) use ($orderBy, $orderDir) {
            $aValue = $a->{$orderBy} ?? '';
            $bValue = $b->{$orderBy} ?? '';

            if ($orderDir === 'desc') {
                return $bValue <=> $aValue;
            }
            return $aValue <=> $bValue;
        });

        // Apply pagination
        $paginatedData = array_slice($bookings, $start, $length);

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $paginatedData
        ];
    }


    public function getNearbyProviders($bookingId)
    {
        $booking = $this->bookingModel->getBookingById($bookingId);

        if (!$booking) {
            return $this->response->setJSON(['error' => 'Booking not found']);
        }

        // Batch fetch all provider data at once
        $nearbyProviders = $this->bookingModel->getNearbyProvidersByService(
            $booking->service_id,
            $booking->booking_latitude,
            $booking->booking_longitude,
            10
        );

        if (empty($nearbyProviders)) {
            return $this->response->setJSON([
                'providers' => [],
                'booking' => $booking
            ]);
        }

        // Get all provider IDs for batch processing
        $providerIds = array_column($nearbyProviders, 'provider_id');

        // Batch fetch ratings for all providers
        $ratings = $this->bookingModel->getAverageRatingsBatch($providerIds);

        // Batch fetch verifications for all providers
        $allVerifications = $this->bookingModel->getAllVerificationsBatch($providerIds);

        $verificationFields = [
            'email_verified',
            'mobile_verified',
            'identity_proof_verified',
            'address_proof_verified',
            'police_verification_verified',
            'post_card_verified',
            'walking_verified',
            'profile_picture_verified',
            'call_verification',
            'training_verified',
            'grooming_verified',
            'boarding_verified',
        ];

        $totalVerifications = count($verificationFields);

        foreach ($nearbyProviders as $provider) {
            // Set rating from batch results
            $provider->rating = $ratings[$provider->provider_id] ?? 0;

            // Get verifications from batch results
            $verificationArray = (array) ($allVerifications[$provider->provider_id] ?? []);

            $completedVerifications = 0;
            foreach ($verificationFields as $field) {
                if (!empty($verificationArray[$field])) {
                    $completedVerifications++;
                }
            }

            $provider->completionPercentage = ($totalVerifications > 0)
                ? round(($completedVerifications / $totalVerifications) * 100, 1)
                : 0;
        }

        return $this->response->setJSON([
            'providers' => $nearbyProviders,
            'booking' => $booking
        ]);
    }
}
