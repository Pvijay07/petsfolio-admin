<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use DateTime;
use DateTimeZone;

class BookingPaymentController extends Controller
{
  protected $bookingModel;
  // protected $walletModel;
  protected $request;

  public function __construct()
  {
    $this->bookingModel = new \App\Models\Booking();
    // $this->walletModel = new \App\Models\Wallet();
  }

  /**
   * Get booking payment details with proper wallet calculations
   */
  public function getBookingDetails()
  {
    $booking_id  = $this->request->getPost('booking_id');
    $service_id  = $this->request->getPost('service_id');
    $provider_id = $this->request->getPost('provider_id');

    switch ($service_id) {
      case '4': // Walking Service
        $data = $this->getWalkingPaymentDetails($booking_id, $provider_id);
        break;
      case '2': // Grooming Service
        $data = $this->getGroomingPaymentDetails($booking_id, $provider_id);
        break;
      case '3': // Training Service
        $data = $this->getTrainingPaymentDetails($booking_id, $provider_id);
        break;
      default:
        $data = ['trackings' => [], 'walletDetails' => [], 'error' => 'Invalid service'];
    }

    return $this->response->setJSON([
      'status' => true,
      'data'   => $data,
    ]);
  }

  /**
   * Walking Service Payment Logic
   * - Client pays total amount upfront
   * - Each walk completion: debit from client service wallet, credit to provider service wallet (-10% platform)
   * - Walk rejected: refund to client withdrawal wallet
   * - Service cancelled: refund all remaining to client withdrawal wallet
   */
  private function getWalkingPaymentDetails($booking_id, $provider_id)
  {
    $booking       = $this->bookingModel->getBookingforPriceCalculate($booking_id, $provider_id, 'walking');
    $trackings     = $this->bookingModel->getTracking($booking_id);
    $walletDetails = $this->bookingModel->getWalletDetails($booking_id);

    // Calculate per walk amount
    $frequency  = $this->getServiceFrequency($booking->service_frequency);
    $totalPets  = $this->bookingModel->getBookingPets($booking_id);
    $totalWalks = $frequency * $booking->duration_days;

    $perWalkClientAmount    = (float) $booking->bid_amount / ($totalWalks * $totalPets);
    $platformPercentage     = (float) getenv('PLATFORM_PERCENTAGE') ?? 10;
    $perWalkPlatformCharges = ($perWalkClientAmount * $platformPercentage) / 100;
    $perWalkProviderAmount  = $perWalkClientAmount - $perWalkPlatformCharges;

    // Initial payment
    $clientServiceWallet   = (float) $booking->bid_amount;
    $providerServiceWallet = (float) $booking->receivable_amount;

    $clientWithdrawalWallet = 0;
    // $providerServiceWallet    = 0;
    $providerWithdrawalWallet = 0;

    $paymentHistory = [];
    $calcuttaTZ     = new DateTimeZone('Asia/Kolkata');

    foreach ($trackings as $index => $track) {
      // Convert timezone
      if (isset($track->start_time) && $track->start_time != '0000-00-00 00:00:00') {
        $start_time = new DateTime($track->start_time, new DateTimeZone('UTC'));
        $start_time->setTimezone($calcuttaTZ);
        $track->start_time = $start_time->format('Y-m-d h:i:s A');
      }

      if (isset($track->end_time) && $track->end_time != '0000-00-00 00:00:00') {
        $end_time = new DateTime($track->end_time, new DateTimeZone('UTC'));
        $end_time->setTimezone($calcuttaTZ);
        $track->end_time = $end_time->format('Y-m-d h:i:s A');
      }

      $transaction = [
        'walk_number'      => $index + 1,
        'date'             => $track->tracking_date,
        'start_time'       => $track->start_time,
        'end_time'         => $track->end_time,
        'status'           => $track->status,
        'is_approved'      => $track->is_approved,
        'payment_status'   => $track->payment_status,
        'per_walk_amount'  => round($perWalkClientAmount * $totalPets, 2),
        'platform_charges' => round($perWalkPlatformCharges * $totalPets, 2),
        'provider_amount'  => round($perWalkProviderAmount * $totalPets, 2),
      ];

      // Calculate wallet balances based on status
      if ($track->is_approved == "true" && $track->status == 'completed') {
        // Walk approved: debit from client service wallet, credit to provider
        $walkAmount = $perWalkClientAmount * $totalPets;

        $clientServiceWallet      -= $walkAmount;
        $providerServiceWallet    -= ($perWalkProviderAmount * $totalPets);
        $providerWithdrawalWallet += $walkAmount;

        $transaction['action']                  = 'Walk Approved';
        $transaction['client_service_debit']    = round($walkAmount, 2);
        $transaction['provider_service_credit'] = round($perWalkProviderAmount * $totalPets, 2);
      } elseif ($track->status == 'rejected') {
        // Walk rejected: refund to client withdrawal wallet
        $walkAmount              = $perWalkClientAmount * $totalPets;
        $clientServiceWallet    -= $walkAmount;
        $clientWithdrawalWallet += $walkAmount;

        $transaction['action']                   = 'Walk Rejected - Refund';
        $transaction['client_service_debit']     = round($walkAmount, 2);
        $transaction['client_withdrawal_credit'] = round($walkAmount, 2);
      } elseif ($track->status == 'cancelled') {
        // Service cancelled: refund remaining to client withdrawal wallet
        $refundAmount            = $clientServiceWallet;
        $clientWithdrawalWallet += $refundAmount;
        $clientServiceWallet     = 0;

        // Also clear provider wallet
        $providerWithdrawalWallet += $providerServiceWallet;
        $providerServiceWallet     = 0;

        $transaction['action']                   = 'Service Cancelled - Full Refund';
        $transaction['client_withdrawal_credit'] = round($refundAmount, 2);
      }

      $transaction['client_service_balance']      = round($clientServiceWallet, 2);
      $transaction['client_withdrawal_balance']   = round($clientWithdrawalWallet, 2);
      $transaction['provider_service_balance']    = round($providerServiceWallet, 2);
      $transaction['provider_withdrawal_balance'] = round($providerWithdrawalWallet, 2);

      $paymentHistory[] = $transaction;
    }

    return [
      'service_type'       => 'Walking',
      'booking'            => $booking,
      'total_amount_paid'  => (float) $booking->bid_amount,
      'per_walk_breakdown' => [
        'client_pays'       => round($perWalkClientAmount * $totalPets, 2),
        'platform_charges'  => round($perWalkPlatformCharges * $totalPets, 2),
        'provider_receives' => round($perWalkProviderAmount * $totalPets, 2),
      ],
      'current_balances'   => [
        'client_service_wallet'      => round($clientServiceWallet, 2),
        'client_withdrawal_wallet'   => round($clientWithdrawalWallet, 2),
        'provider_service_wallet'    => round($providerServiceWallet, 2),
        'provider_withdrawal_wallet' => round($providerWithdrawalWallet, 2),
      ],
      'payment_history'    => $paymentHistory,
      'trackings'          => $trackings,
      'walletDetails'      => $walletDetails,
    ];
  }

  /**
   * Grooming Service Payment Logic
   * - Package completion: debit from client, credit to provider (-10% platform)
   * - Addon completion: debit from client, credit to provider (-10% platform)
   */
  private function getGroomingPaymentDetails($booking_id, $provider_id)
  {
    $booking       = $this->bookingModel->getBookingforPriceCalculate($booking_id, $provider_id, 'grooming');
    $trackings     = $this->bookingModel->getGroomingTracking($booking_id);
    $walletDetails = $this->bookingModel->getGroomingWalletDetails($booking_id);

    $platformPercentage = (float) getenv('PLATFORM_PERCENTAGE') ?? 10;

    $totalAmountPaid = (float) $booking->bid_amount;

    // Wallets start empty
    $clientServiceWallet      = 0;  // MUST always remain 0
    $clientWithdrawalWallet   = 0;
    $providerWithdrawalWallet = 0;
    $providerServiceWallet    = 0;  // MUST also reduce to 0 after deductions

    $approvedTotal = 0;      // Total price of approved addons/packages
    $approvedProviderEarnings = 0;

    $transactionHistory = [];

    foreach ($trackings as $index => $track) {

      $addonPrice        = (float) $track->addon_amount;
      $isPackageIncluded = $track->is_included_in_packages == '1';

      // If package item → override addon price
      $usedPrice = ($track->package_id && $track->package_price)
        ? (float) $track->package_price
        : $addonPrice;

      $platformCharges = ($usedPrice * $platformPercentage) / 100;
      $providerAmount  = $usedPrice - $platformCharges;

      $transaction = [
        'item_number'         => $index + 1,
        'addon_name'          => $track->addon,
        'pet_id'              => $track->pet_id,
        'status'              => $track->status,
        'payment_status'      => $track->payment_status ? 'Completed' : 'Pending',
        'is_approved'         => $track->is_approved,
        'is_package_included' => $isPackageIncluded,
        'addon_price'         => round($usedPrice, 2),
        'package_name'        => $track->package_name,
        'package_price'       => $track->package_price,
        'date'                => date('Y-m-d', strtotime($track->created_at))
      ];

      // APPROVED & COMPLETED = CLIENT APPROVAL
      if ($track->is_approved == 1 && strtolower($track->status) == 'completed') {

        $approvedTotal += $usedPrice;
        $approvedProviderEarnings += $providerAmount;

        // Provider receives payment
        $providerWithdrawalWallet += $providerAmount;

        $transaction['action']                  = $isPackageIncluded ? 'Package Approved' : 'Addon Approved';
        $transaction['client_service_debit']    = 0;
        $transaction['platform_charges']        = round($platformCharges, 2);
        $transaction['provider_service_credit'] = round($providerAmount, 2);
      } else {

        // No financial movement on rejected/pending items
        $transaction['action']                  = 'Pending/Rejected';
        $transaction['client_service_debit']    = 0;
        $transaction['platform_charges']        = 0;
        $transaction['provider_service_credit'] = 0;
      }

      // Always constant in each transaction
      $transaction['client_service_balance']      = 0;
      $transaction['provider_service_balance']    = 0;
      $transaction['provider_withdrawal_balance'] = round($providerWithdrawalWallet, 2);

      $transactionHistory[] = $transaction;
    }

    $clientWithdrawalWallet = $totalAmountPaid - $approvedTotal;

    return [
      'service_type'      => 'Grooming',
      'booking'           => $booking,
      'total_amount_paid' => $totalAmountPaid,

      // FINAL WALLET VALUES
      'current_balances'  => [
        'client_service_wallet'      => 0,
        'client_withdrawal_wallet'   => round($clientWithdrawalWallet, 2),
        'provider_service_wallet'    => 0, // Always zero after adjustments
        'provider_withdrawal_wallet' => round($providerWithdrawalWallet, 2),
      ],

      'payment_history'   => $transactionHistory,
      'trackings'         => $trackings,
      'walletDetails'     => $walletDetails,
    ];
  }




  /**
   * Training Service Payment Logic
   * - Package commands: price = package_price / total_commands
   * - Extra commands: fixed price from admin
   * - On approval: debit from client service wallet, credit to provider service wallet (-10% platform)
   * - Provider withdrawal wallet gets credited after platform charges
   */
  private function getTrainingPaymentDetails($booking_id, $provider_id)
  {
    $booking       = $this->bookingModel->getBookingforPriceCalculate($booking_id, $provider_id, 'training');
    $trackings     = $this->bookingModel->getTrainingTracking($booking_id);
    $walletDetails = $this->bookingModel->getTrainingWalletDetails($booking_id);

    $platformPercentage = (float) getenv('PLATFORM_PERCENTAGE') ?? 10;
    $discount           = (float) ($booking->discount ?? 0);

    // Calculate package addon pricing
    $packageAddons = [];
    foreach ($trackings as $track) {
      if ($track->is_included_in_packages == '1') {
        $packageId = $track->package_id ?? null;
        if ($packageId && !isset($packageAddons[$packageId])) {
          $totalIncludedAddons       = $this->bookingModel->getPackageAddonCount($packageId);
          $packageAddons[$packageId] = [
            'price'           => (float) $track->package_price,
            'total_addons'    => $totalIncludedAddons,
            'per_addon_price' => (float) $track->package_price / $totalIncludedAddons,
          ];
        }
      }
    }

    $clientServiceWallet   = (float) $booking->bid_amount;
    $providerServiceWallet = (float) $booking->receivable_amount;

    $clientWithdrawalWallet = 0;
    // $providerServiceWallet    = 0;
    $providerWithdrawalWallet = 0;

    $paymentHistory = [];

    foreach ($trackings as $index => $track) {
      $isPackageCommand = $track->is_included_in_packages == '1';
      $packageId        = $track->package_id ?? null;

      // Determine command price
      if ($isPackageCommand && isset($packageAddons[$packageId])) {
        $commandPrice = $packageAddons[$packageId]['per_addon_price'];
      } else {
        $commandPrice = (float) ($track->addon_price ?? $track->price ?? 0);
      }

      // Apply discount
      $discountAmount    = ($discount > 0) ? ($commandPrice * $discount) / 100 : 0;
      $finalClientAmount = $commandPrice - $discountAmount;
      // echo $commandPrice;
      // Calculate platform charges and provider amount
      $platformCharges = ($finalClientAmount * $platformPercentage) / 100;
      $providerAmount  = $finalClientAmount - $platformCharges;
      // echo $providerAmount;
      $transaction = [
        'command_number'     => $index + 1,
        'command_name'       => $track->addon,
        'pet_id'             => $track->pet_id,
        'tracking_date'      => $track->tracking_date,
        'status'             => $track->status,
        'is_package_command' => $isPackageCommand,
        'original_price'     => round($commandPrice, 2),
        'discount_amount'    => round($discountAmount, 2),
        'client_pays'        => round($finalClientAmount, 2),
        'platform_charges'   => round($platformCharges, 2),
        'provider_receives'  => round($providerAmount, 2),
      ];

      if ($track->status == 'approved') {
        // Debit from client service wallet
        $clientServiceWallet -= $finalClientAmount;

        // Credit to provider service wallet (before withdrawal)
        $providerServiceWallet -= $providerAmount;

        // Credit to provider withdrawal wallet (after platform charges)
        $providerWithdrawalWallet += $providerAmount;

        $transaction['action']                     = $isPackageCommand ? 'Package Command Approved' : 'Extra Command Approved';
        $transaction['client_service_debit']       = round($finalClientAmount, 2);
        $transaction['provider_service_credit']    = round($providerAmount, 2);
        $transaction['provider_withdrawal_credit'] = round($providerAmount, 2);
      } else {
        $transaction['action']                  = 'Pending Approval';
        $transaction['client_service_debit']    = 0;
        $transaction['provider_service_credit'] = 0;
      }

      $transaction['client_service_balance']      = round($clientServiceWallet, 2);
      $transaction['provider_service_balance']    = round($providerServiceWallet, 2);
      $transaction['provider_withdrawal_balance'] = round($providerWithdrawalWallet, 2);

      $paymentHistory[] = $transaction;
    }

    return [
      'service_type'        => 'Training',
      'booking'             => $booking,
      'total_amount_paid'   => (float) $booking->bid_amount,
      'package_breakdown'   => $packageAddons,
      'discount_percentage' => $discount,
      'current_balances'    => [
        'client_service_wallet'      => round($clientServiceWallet, 2),
        'client_withdrawal_wallet'   => round($clientWithdrawalWallet, 2),
        'provider_service_wallet'    => round($providerServiceWallet, 2),
        'provider_withdrawal_wallet' => round($providerWithdrawalWallet, 2),
      ],
      'payment_history'     => $paymentHistory,
      'trackings'           => $trackings,
      'walletDetails'       => $walletDetails,
    ];
  }

  /**
   * Helper function to get service frequency
   */
  private function getServiceFrequency($frequencyString)
  {
    $frequencies = [
      'once_a_day'   => 1,
      'twice_a_day'  => 2,
      'thrice_a_day' => 3,
    ];

    return $frequencies[$frequencyString] ?? 1;
  }
}
