<?php

namespace App\Libraries;

use Config\Database;
use Exception;

/**
 * BusinessEventLogger
 * 
 * Enterprise Business Event Layer for Petsfolio.
 * Standardizes ~160+ business events across all customer journeys, booking lifecycles,
 * provider workflows, payments, and cancellations to provide a deterministic Single Source
 * of Truth (SSOT) for BI analytics, funnel conversion, and drop-off diagnosis.
 */
class BusinessEventLogger
{
    // ==========================================
    // EVENT CATEGORIES
    // ==========================================
    public const CAT_CLIENT         = 'CLIENT';
    public const CAT_PET            = 'PET';
    public const CAT_SERVICE        = 'SERVICE';
    public const CAT_WALKING        = 'WALKING';
    public const CAT_GROOMING       = 'GROOMING';
    public const CAT_TRAINING       = 'TRAINING';
    public const CAT_BOARDING       = 'BOARDING';
    public const CAT_SITTING        = 'SITTING';
    public const CAT_BOOKING        = 'BOOKING';
    public const CAT_PROVIDER       = 'PROVIDER';
    public const CAT_PAYMENT        = 'PAYMENT';
    public const CAT_WALLET         = 'WALLET';
    public const CAT_CANCELLATION   = 'CANCELLATION';
    public const CAT_SUPPORT        = 'SUPPORT';
    public const CAT_COMMUNICATION  = 'COMMUNICATION';
    public const CAT_LOYALTY        = 'LOYALTY';
    public const CAT_REVIEW         = 'REVIEW';

    // ==========================================
    // 1. CLIENT / USER EVENTS (15)
    // ==========================================
    public const CLIENT_REGISTERED          = 'CLIENT_REGISTERED';
    public const CLIENT_PROFILE_COMPLETED   = 'CLIENT_PROFILE_COMPLETED';
    public const CLIENT_PROFILE_INCOMPLETE  = 'CLIENT_PROFILE_INCOMPLETE';
    public const CLIENT_LOGIN               = 'CLIENT_LOGIN';
    public const CLIENT_INACTIVE            = 'CLIENT_INACTIVE';
    public const CLIENT_REACTIVATED         = 'CLIENT_REACTIVATED';
    public const CLIENT_APP_UNINSTALLED     = 'CLIENT_APP_UNINSTALLED';
    public const CLIENT_APP_REINSTALLED     = 'CLIENT_APP_REINSTALLED';
    public const CLIENT_LOGGED_OUT          = 'CLIENT_LOGGED_OUT';
    public const CLIENT_BLOCKED             = 'CLIENT_BLOCKED';
    public const CLIENT_DELETED_ACCOUNT     = 'CLIENT_DELETED_ACCOUNT';
    public const CLIENT_CONTACT_UPDATED     = 'CLIENT_CONTACT_UPDATED';
    public const CLIENT_ADDRESS_ADDED       = 'CLIENT_ADDRESS_ADDED';
    public const CLIENT_ADDRESS_UPDATED     = 'CLIENT_ADDRESS_UPDATED';
    public const CLIENT_ADDRESS_REMOVED     = 'CLIENT_ADDRESS_REMOVED';

    // ==========================================
    // 2. PET MANAGEMENT EVENTS (12)
    // ==========================================
    public const PET_ADDED                  = 'PET_ADDED';
    public const PET_PROFILE_COMPLETED      = 'PET_PROFILE_COMPLETED';
    public const PET_PROFILE_INCOMPLETE     = 'PET_PROFILE_INCOMPLETE';
    public const PET_UPDATED                = 'PET_UPDATED';
    public const PET_DELETED                = 'PET_DELETED';
    public const PET_VACCINATION_ADDED      = 'PET_VACCINATION_ADDED';
    public const PET_VACCINATION_EXPIRED    = 'PET_VACCINATION_EXPIRED';
    public const PET_MEDICAL_INFO_ADDED     = 'PET_MEDICAL_INFO_ADDED';
    public const MULTIPLE_PETS_ADDED        = 'MULTIPLE_PETS_ADDED';
    public const PET_SIZE_SELECTED          = 'PET_SIZE_SELECTED';
    public const PET_BREED_SELECTED         = 'PET_BREED_SELECTED';
    public const PET_REQUIREMENT_UPDATED    = 'PET_REQUIREMENT_UPDATED';

    // ==========================================
    // 3. SERVICE DISCOVERY EVENTS (12)
    // ==========================================
    public const SERVICE_VIEWED             = 'SERVICE_VIEWED';
    public const SERVICE_SELECTED           = 'SERVICE_SELECTED';
    public const SERVICE_SEARCHED           = 'SERVICE_SEARCHED';
    public const PROVIDER_SEARCHED          = 'PROVIDER_SEARCHED';
    public const PROVIDER_VIEWED            = 'PROVIDER_VIEWED';
    public const PROVIDER_PROFILE_VIEWED    = 'PROVIDER_PROFILE_VIEWED';
    public const PROVIDER_REVIEWS_VIEWED    = 'PROVIDER_REVIEWS_VIEWED';
    public const PROVIDER_CONTACTED         = 'PROVIDER_CONTACTED';
    public const SERVICE_LOCATION_SELECTED  = 'SERVICE_LOCATION_SELECTED';
    public const NO_PROVIDER_FOUND          = 'NO_PROVIDER_FOUND';
    public const PROVIDER_FOUND             = 'PROVIDER_FOUND';
    public const SERVICE_REQUIREMENT_SUBMITTED = 'SERVICE_REQUIREMENT_SUBMITTED';

    // ==========================================
    // 4. WALKING SERVICE EVENTS (12)
    // ==========================================
    public const WALKING_REQUEST_CREATED    = 'WALKING_REQUEST_CREATED';
    public const WALKING_QUOTATION_RECEIVED = 'WALKING_QUOTATION_RECEIVED';
    public const WALKING_QUOTATION_VIEWED   = 'WALKING_QUOTATION_VIEWED';
    public const WALKING_QUOTATION_ACCEPTED = 'WALKING_QUOTATION_ACCEPTED';
    public const WALKING_QUOTATION_REJECTED = 'WALKING_QUOTATION_REJECTED';
    public const WALKING_BOOKING_CONFIRMED  = 'WALKING_BOOKING_CONFIRMED';
    public const WALKING_STARTED            = 'WALKING_STARTED';
    public const WALKING_TRACKING_STARTED   = 'WALKING_TRACKING_STARTED';
    public const WALKING_COMPLETED          = 'WALKING_COMPLETED';
    public const WALKING_CANCELLED          = 'WALKING_CANCELLED';
    public const WALKING_MISSED             = 'WALKING_MISSED';
    public const WALKING_RESCHEDULED        = 'WALKING_RESCHEDULED';

    // ==========================================
    // 5. GROOMING SERVICE EVENTS (10)
    // ==========================================
    public const GROOMING_REQUEST_CREATED   = 'GROOMING_REQUEST_CREATED';
    public const GROOMING_QUOTATION_RECEIVED = 'GROOMING_QUOTATION_RECEIVED';
    public const GROOMING_QUOTATION_VIEWED  = 'GROOMING_QUOTATION_VIEWED';
    public const GROOMING_QUOTATION_ACCEPTED = 'GROOMING_QUOTATION_ACCEPTED';
    public const GROOMING_BOOKING_CONFIRMED = 'GROOMING_BOOKING_CONFIRMED';
    public const GROOMING_STARTED           = 'GROOMING_STARTED';
    public const GROOMING_COMPLETED         = 'GROOMING_COMPLETED';
    public const GROOMING_CANCELLED         = 'GROOMING_CANCELLED';
    public const GROOMING_RESCHEDULED       = 'GROOMING_RESCHEDULED';
    public const GROOMING_MISSED            = 'GROOMING_MISSED';

    // ==========================================
    // 6. TRAINING SERVICE EVENTS (10)
    // ==========================================
    public const TRAINING_REQUEST_CREATED   = 'TRAINING_REQUEST_CREATED';
    public const TRAINING_QUOTATION_RECEIVED = 'TRAINING_QUOTATION_RECEIVED';
    public const TRAINING_QUOTATION_VIEWED  = 'TRAINING_QUOTATION_VIEWED';
    public const TRAINING_QUOTATION_ACCEPTED = 'TRAINING_QUOTATION_ACCEPTED';
    public const TRAINING_BOOKING_CONFIRMED = 'TRAINING_BOOKING_CONFIRMED';
    public const TRAINING_STARTED           = 'TRAINING_STARTED';
    public const TRAINING_COMPLETED         = 'TRAINING_COMPLETED';
    public const TRAINING_CANCELLED         = 'TRAINING_CANCELLED';
    public const TRAINING_RESCHEDULED       = 'TRAINING_RESCHEDULED';
    public const TRAINING_MISSED            = 'TRAINING_MISSED';

    // ==========================================
    // 7. BOARDING / SITTING EVENTS (10)
    // ==========================================
    public const BOARDING_REQUEST_CREATED   = 'BOARDING_REQUEST_CREATED';
    public const BOARDING_QUOTATION_RECEIVED = 'BOARDING_QUOTATION_RECEIVED';
    public const BOARDING_QUOTATION_ACCEPTED = 'BOARDING_QUOTATION_ACCEPTED';
    public const BOARDING_BOOKING_CONFIRMED = 'BOARDING_BOOKING_CONFIRMED';
    public const BOARDING_STARTED           = 'BOARDING_STARTED';
    public const BOARDING_COMPLETED         = 'BOARDING_COMPLETED';
    public const BOARDING_CANCELLED         = 'BOARDING_CANCELLED';
    public const SITTING_REQUEST_CREATED    = 'SITTING_REQUEST_CREATED';
    public const SITTING_BOOKING_CONFIRMED  = 'SITTING_BOOKING_CONFIRMED';
    public const SITTING_COMPLETED          = 'SITTING_COMPLETED';

    // ==========================================
    // 8. BOOKING LIFECYCLE EVENTS (15)
    // ==========================================
    public const BOOKING_CREATED            = 'BOOKING_CREATED';
    public const BOOKING_PENDING            = 'BOOKING_PENDING';
    public const BOOKING_CONFIRMED          = 'BOOKING_CONFIRMED';
    public const BOOKING_ACCEPTED           = 'BOOKING_ACCEPTED';
    public const BOOKING_REJECTED           = 'BOOKING_REJECTED';
    public const BOOKING_CANCELLED          = 'BOOKING_CANCELLED';
    public const BOOKING_RESCHEDULED        = 'BOOKING_RESCHEDULED';
    public const BOOKING_EXPIRED            = 'BOOKING_EXPIRED';
    public const BOOKING_HALTED             = 'BOOKING_HALTED';
    public const BOOKING_REACTIVATED        = 'BOOKING_REACTIVATED';
    public const BOOKING_COMPLETED          = 'BOOKING_COMPLETED';
    public const BOOKING_FAILED             = 'BOOKING_FAILED';
    public const BOOKING_NO_SHOW            = 'BOOKING_NO_SHOW';
    public const BOOKING_DISPUTED           = 'BOOKING_DISPUTED';
    public const BOOKING_REFUNDED           = 'BOOKING_REFUNDED';

    // ==========================================
    // 9. PROVIDER LIFECYCLE EVENTS (15)
    // ==========================================
    public const PROVIDER_REGISTERED        = 'PROVIDER_REGISTERED';
    public const PROVIDER_PROFILE_COMPLETED = 'PROVIDER_PROFILE_COMPLETED';
    public const PROVIDER_PROFILE_INCOMPLETE = 'PROVIDER_PROFILE_INCOMPLETE';
    public const PROVIDER_VERIFICATION_STARTED = 'PROVIDER_VERIFICATION_STARTED';
    public const PROVIDER_VERIFIED          = 'PROVIDER_VERIFIED';
    public const PROVIDER_VERIFICATION_FAILED = 'PROVIDER_VERIFICATION_FAILED';
    public const PROVIDER_ONLINE            = 'PROVIDER_ONLINE';
    public const PROVIDER_OFFLINE           = 'PROVIDER_OFFLINE';
    public const PROVIDER_AVAILABLE         = 'PROVIDER_AVAILABLE';
    public const PROVIDER_UNAVAILABLE       = 'PROVIDER_UNAVAILABLE';
    public const PROVIDER_SERVICE_ADDED     = 'PROVIDER_SERVICE_ADDED';
    public const PROVIDER_SERVICE_REMOVED   = 'PROVIDER_SERVICE_REMOVED';
    public const PROVIDER_BID_SUBMITTED     = 'PROVIDER_BID_SUBMITTED';
    public const PROVIDER_BID_ACCEPTED      = 'PROVIDER_BID_ACCEPTED';
    public const PROVIDER_BID_REJECTED      = 'PROVIDER_BID_REJECTED';

    // ==========================================
    // 10. PAYMENT EVENTS (12)
    // ==========================================
    public const PAYMENT_INITIATED          = 'PAYMENT_INITIATED';
    public const PAYMENT_SUCCESS            = 'PAYMENT_SUCCESS';
    public const PAYMENT_FAILED             = 'PAYMENT_FAILED';
    public const PAYMENT_PENDING            = 'PAYMENT_PENDING';
    public const PAYMENT_ABANDONED          = 'PAYMENT_ABANDONED';
    public const PAYMENT_EXPIRED            = 'PAYMENT_EXPIRED';
    public const PAYMENT_REFUNDED           = 'PAYMENT_REFUNDED';
    public const PAYMENT_PARTIAL_REFUND     = 'PAYMENT_PARTIAL_REFUND';
    public const PAYMENT_REFUND_FAILED      = 'PAYMENT_REFUND_FAILED';
    public const PAYMENT_DUPLICATE          = 'PAYMENT_DUPLICATE';
    public const PAYMENT_GATEWAY_ERROR      = 'PAYMENT_GATEWAY_ERROR';
    public const PAYMENT_DISPUTED           = 'PAYMENT_DISPUTED';

    // ==========================================
    // 11. WALLET EVENTS (12)
    // ==========================================
    public const WALLET_CREATED             = 'WALLET_CREATED';
    public const WALLET_CREDITED            = 'WALLET_CREDITED';
    public const WALLET_DEBITED             = 'WALLET_DEBITED';
    public const WALLET_HOLD_CREATED        = 'WALLET_HOLD_CREATED';
    public const WALLET_HOLD_RELEASED       = 'WALLET_HOLD_RELEASED';
    public const WALLET_REFUND              = 'WALLET_REFUND';
    public const WALLET_INSUFFICIENT_BALANCE = 'WALLET_INSUFFICIENT_BALANCE';
    public const WALLET_TRANSACTION_FAILED  = 'WALLET_TRANSACTION_FAILED';
    public const PROVIDER_WALLET_CREDITED   = 'PROVIDER_WALLET_CREDITED';
    public const PROVIDER_WITHDRAWAL_REQUESTED = 'PROVIDER_WITHDRAWAL_REQUESTED';
    public const PROVIDER_WITHDRAWAL_COMPLETED = 'PROVIDER_WITHDRAWAL_COMPLETED';
    public const PROVIDER_WITHDRAWAL_FAILED = 'PROVIDER_WITHDRAWAL_FAILED';

    // ==========================================
    // 12. CANCELLATION & HALT REASONS (15)
    // ==========================================
    public const REASON_CLIENT_CANCELLED    = 'CLIENT_CANCELLED';
    public const REASON_PROVIDER_CANCELLED  = 'PROVIDER_CANCELLED';
    public const REASON_CLIENT_NOT_INTERESTED = 'CLIENT_NOT_INTERESTED';
    public const REASON_CLIENT_INACTIVE     = 'CLIENT_INACTIVE';
    public const REASON_NO_PROVIDER_NEARBY  = 'NO_PROVIDER_NEARBY';
    public const REASON_NO_PROVIDER_AVAILABLE = 'NO_PROVIDER_AVAILABLE';
    public const REASON_NO_BIDS             = 'NO_BIDS';
    public const REASON_BIDS_TOO_HIGH       = 'BIDS_TOO_HIGH';
    public const REASON_PRICE_TOO_HIGH      = 'PRICE_TOO_HIGH';
    public const REASON_CLIENT_NOT_RESPONDING = 'CLIENT_NOT_RESPONDING';
    public const REASON_PAYMENT_FAILED      = 'PAYMENT_FAILED';
    public const REASON_OFF_PLATFORM_SERVICE = 'OFF_PLATFORM_SERVICE';
    public const REASON_SERVICE_UNAVAILABLE = 'SERVICE_UNAVAILABLE';
    public const REASON_SLOT_UNAVAILABLE    = 'SLOT_UNAVAILABLE';
    public const REASON_UNKNOWN             = 'UNKNOWN_REASON';

    // ==========================================
    // 13. SUPPORT, COMMUNICATION & ENGAGEMENT (16)
    // ==========================================
    public const TICKET_CREATED             = 'TICKET_CREATED';
    public const TICKET_ASSIGNED            = 'TICKET_ASSIGNED';
    public const TICKET_RESOLVED            = 'TICKET_RESOLVED';
    public const TICKET_CLOSED              = 'TICKET_CLOSED';
    public const CALL_LOGGED                = 'CALL_LOGGED';
    public const CHAT_STARTED               = 'CHAT_STARTED';
    public const NOTIFICATION_SENT          = 'NOTIFICATION_SENT';
    public const NOTIFICATION_OPENED        = 'NOTIFICATION_OPENED';
    public const REVIEW_SUBMITTED           = 'REVIEW_SUBMITTED';
    public const REVIEW_REPORTED            = 'REVIEW_REPORTED';
    public const LOYALTY_POINTS_EARNED      = 'LOYALTY_POINTS_EARNED';
    public const LOYALTY_POINTS_REDEEMED    = 'LOYALTY_POINTS_REDEEMED';
    public const COUPON_APPLIED             = 'COUPON_APPLIED';
    public const COUPON_REJECTED            = 'COUPON_REJECTED';
    public const REFERRAL_INVITED           = 'REFERRAL_INVITED';
    public const REFERRAL_CONVERTED         = 'REFERRAL_CONVERTED';

    /**
     * Map each event type to its default category.
     */
    public static function getDefaultCategory(string $eventType): string
    {
        if (str_starts_with($eventType, 'CLIENT_')) return self::CAT_CLIENT;
        if (str_starts_with($eventType, 'PET_') || $eventType === self::MULTIPLE_PETS_ADDED) return self::CAT_PET;
        if (str_starts_with($eventType, 'SERVICE_') || str_starts_with($eventType, 'NO_PROVIDER')) return self::CAT_SERVICE;
        if (str_starts_with($eventType, 'WALKING_')) return self::CAT_WALKING;
        if (str_starts_with($eventType, 'GROOMING_')) return self::CAT_GROOMING;
        if (str_starts_with($eventType, 'TRAINING_')) return self::CAT_TRAINING;
        if (str_starts_with($eventType, 'BOARDING_') || str_starts_with($eventType, 'SITTING_')) return self::CAT_BOARDING;
        if (str_starts_with($eventType, 'BOOKING_')) return self::CAT_BOOKING;
        if (str_starts_with($eventType, 'PROVIDER_')) return self::CAT_PROVIDER;
        if (str_starts_with($eventType, 'PAYMENT_')) return self::CAT_PAYMENT;
        if (str_starts_with($eventType, 'WALLET_')) return self::CAT_WALLET;
        if (str_starts_with($eventType, 'TICKET_')) return self::CAT_SUPPORT;
        if (str_starts_with($eventType, 'CALL_') || str_starts_with($eventType, 'CHAT_') || str_starts_with($eventType, 'NOTIFICATION_')) return self::CAT_COMMUNICATION;
        if (str_starts_with($eventType, 'REVIEW_')) return self::CAT_REVIEW;
        if (str_starts_with($eventType, 'LOYALTY_') || str_starts_with($eventType, 'COUPON_') || str_starts_with($eventType, 'REFERRAL_')) return self::CAT_LOYALTY;
        return self::CAT_BOOKING;
    }

    /**
     * Log a standardized business event.
     *
     * @param string      $eventType     One of self::* constants (e.g. WALKING_REQUEST_CREATED)
     * @param string|null $category      Category string (auto-detected if null)
     * @param array       $params        Optional fields: user_id, user_type, booking_id, service_id, provider_id, amount, reason, source, metadata, event_time
     * @return int|null   Inserted event ID or null on failure
     */
    public static function log(string $eventType, ?string $category = null, array $params = []): ?int
    {
        try {
            $db = Database::connect();
            $category = $category ?: self::getDefaultCategory($eventType);

            $data = [
                'event_type'     => $eventType,
                'event_category' => $category,
                'user_id'        => !empty($params['user_id']) ? (int)$params['user_id'] : null,
                'user_type'      => in_array($params['user_type'] ?? '', ['client', 'provider', 'admin', 'system']) ? $params['user_type'] : 'client',
                'booking_id'     => !empty($params['booking_id']) ? (string)$params['booking_id'] : null,
                'service_id'     => !empty($params['service_id']) ? (int)$params['service_id'] : null,
                'provider_id'    => !empty($params['provider_id']) ? (int)$params['provider_id'] : null,
                'amount'         => !empty($params['amount']) ? (float)$params['amount'] : 0.00,
                'reason'         => !empty($params['reason']) ? substr((string)$params['reason'], 0, 255) : null,
                'source'         => !empty($params['source']) ? substr((string)$params['source'], 0, 50) : 'system',
                'metadata'       => !empty($params['metadata']) ? json_encode($params['metadata']) : null,
                'event_time'     => !empty($params['event_time']) ? $params['event_time'] : date('Y-m-d H:i:s'),
            ];

            $builder = $db->table('business_events');
            $builder->insert($data);
            return $db->insertID() ?: null;
        } catch (Exception $e) {
            log_message('error', 'BusinessEventLogger Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper to log booking event.
     */
    public static function logBooking(string $eventType, string $bookingId, int $serviceId, ?int $userId = null, ?int $providerId = null, float $amount = 0.00, ?string $reason = null, array $metadata = []): ?int
    {
        return self::log($eventType, null, [
            'booking_id'  => $bookingId,
            'service_id'  => $serviceId,
            'user_id'     => $userId,
            'provider_id' => $providerId,
            'amount'      => $amount,
            'reason'      => $reason,
            'metadata'    => $metadata,
        ]);
    }

    /**
     * Helper to log payment event.
     */
    public static function logPayment(string $eventType, ?string $bookingId = null, ?int $userId = null, float $amount = 0.00, ?string $reason = null, array $metadata = []): ?int
    {
        return self::log($eventType, self::CAT_PAYMENT, [
            'booking_id' => $bookingId,
            'user_id'    => $userId,
            'amount'     => $amount,
            'reason'     => $reason,
            'metadata'   => $metadata,
        ]);
    }
}
