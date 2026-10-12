<?php

namespace App\Libraries\AIAnalyst;

/**
 * BusinessDictionary
 * 
 * Single Source of Truth (SSOT) defining all Petsfolio enterprise business terms,
 * metric definitions, calculation rules, table relationships, status meanings,
 * service IDs, and package pricing models.
 * 
 * Implements the BI Semantic Layer & Business Dictionary architecture recommended
 * for high-accuracy, hallucination-free Text-to-BI analytics.
 */
class BusinessDictionary
{
    /**
     * Service IDs and Schema Definitions.
     */
    public const SERVICES = [
        'walking' => [
            'service_id'    => 4,
            'name'          => 'Walking',
            'label'         => 'Dog Walking',
            'table'         => 'walking_service_bookings',
            'details_table' => 'sp_walking_details',
            'species'       => ['Dog'],
            'pricing_base'  => 'Starts from ₹96 per walk',
            'regular_packages' => [
                'Express'    => '3 Days',
                'Short-Term' => '7 Days',
                'Mid-Term'   => '15 Days',
                'Long-Term'  => '30 Days'
            ],
            'elite_packages' => [
                'Paw Start'  => '30 Days',
                'Paw Prime'  => '60 Days',
                'Paw Elite'  => '180 Days',
                'Paw Royal'  => '365 Days'
            ],
            'frequencies' => ['Once a Day', 'Twice a Day', 'Thrice a Day'],
            'durations'   => ['30-Minute', '60-Minute'],
            'addons'      => ['Poo Picking (Paid)', 'Paw Cleaning (Complimentary in Elite)', 'Combing (Complimentary in Elite)']
        ],
        'grooming' => [
            'service_id'    => 2,
            'name'          => 'Grooming',
            'label'         => 'Pet Grooming',
            'table'         => 'grooming_service_bookings',
            'details_table' => 'sp_grooming_details',
            'species'       => ['Dog', 'Cat'],
            'pricing_base'  => 'Starts from ₹799',
            'packages'      => ['Basic Package', 'Anti-Tick Bath Package', 'Hair Trimming Package'],
            'trim_styles'   => ['Semi Trim', 'Full Trim', 'Stylish Trim'],
            'addons'        => [
                'Ear Cleaning',
                'Anal Gland Cleaning',
                'Knot Removal',
                'De-shedding',
                'Nail Clipping',
                'Mouth Spray',
                'Body Massage',
                'Paw Massage',
                'Dash of Cologne',
                'Sanitary Clipping',
                'Eye Cleaning',
                'Teeth Cleaning'
            ]
        ],
        'training' => [
            'service_id'    => 3,
            'name'          => 'Training',
            'label'         => 'Pet Training',
            'table'         => 'training_service_bookings',
            'details_table' => 'sp_training_details',
            'species'       => ['Dog'],
            'pricing_base'  => 'Starts from ₹4,999',
            'packages'      => [
                'Puppy Training'      => '6 Sessions',
                'Intermediate'        => '15 Sessions',
                'Behaviour Training'  => '20 Sessions',
                'Advanced Training'   => '30 Sessions'
            ]
        ],
        'sitting' => [
            'service_id'    => 6,
            'name'          => 'Sitting',
            'label'         => 'Pet Sitting',
            'table'         => 'sitting_service_bookings',
            'details_table' => 'sp_sitting_details',
            'species'       => ['Dog', 'Cat'],
            'pricing_base'  => 'Starts from ₹599 per visit',
            'packages'      => [
                'Overnight Care' => '24 Hours',
                'Day Care Visit' => '10 Hours',
                'Hourly Visit'   => '4 Hours'
            ],
            'tasks'         => ['Feeding', 'Medication', 'Walk']
        ],
        // 'boarding' => [
        //     'service_id'    => 1,
        //     'name'          => 'Boarding',
        //     'label'         => 'Pet Boarding',
        //     'table'         => 'boarding_service_bookings',
        //     'details_table' => 'sp_boarding_details',
        //     'species'       => ['Dog', 'Cat'],
        //     'pricing_base'  => 'Per day / night boarding facility'
        // ]
    ];

    /**
     * Provider Lifecycle Status Definitions.
     */
    public const PROVIDER_STATUSES = [
        'active' => [
            'label'       => 'Active & Approved Provider',
            'sql_filter'  => "sp.status = 'active' AND (sps.is_verified = 1 OR sps.call_verified = 1)",
            'description' => 'Fully onboarded, verified, and receiving customer booking requests.'
        ],
        'waiting_for_call' => [
            'label'       => 'Waiting for Call Verification',
            'sql_filter'  => "sps.payment_paid = 1 AND (sps.call_verified = 0 OR sps.call_verified IS NULL)",
            'description' => 'Service provider paid ₹49 registration fee, awaiting 24-48hr telephonic interview.'
        ],
        'manager_approval' => [
            'label'       => 'Awaiting Manager Approval',
            'sql_filter'  => "sp.status = 'active' AND sps.payment_paid = 1 AND sps.call_verified = 1 AND spv.identity_proof_verified = 1 AND (sps.is_verified = 0 OR sps.is_verified IS NULL)",
            'description' => 'Telephonic and Aadhaar identity verified; awaiting final operations manager sign-off.'
        ],
        'pending_registration' => [
            'label'       => 'Fee Pending / Registration Incomplete',
            'sql_filter'  => "sps.id IS NULL OR (sps.payment_paid = 0 OR sps.payment_paid IS NULL)",
            'description' => 'Signed up on partner app but has not completed the ₹49 onboarding fee payment.'
        ],
        'pending_kyc' => [
            'label'       => 'Pending Aadhaar KYC',
            'sql_filter'  => "sp.status = 'pending_kyc' OR sp.is_verified = 0",
            'description' => 'Aadhaar identity documentation is missing or unverified.'
        ],
        'inactive' => [
            'label'       => 'Inactive / Suspended Provider',
            'sql_filter'  => "sp.status IN ('inactive', 'suspended', 'rejected')",
            'description' => 'Account disabled due to inactivity, disciplinary strikes, or failed verification.'
        ]
    ];

    /**
     * Booking Status Definitions.
     */
    public const BOOKING_STATUSES = [
        'active_jobs' => [
            'label'       => 'Active / Ongoing Jobs',
            'statuses'    => ['Confirmed', 'Accepted', 'In Progress', 'in-progress', 'Assigned', '1'],
            'is_deleted'  => 0,
            'description' => 'Currently active services assigned to service providers and ongoing.'
        ],
        'completed' => [
            'label'       => 'Completed Bookings',
            'statuses'    => ['Completed', 'completed', 'Approved', 'approved'],
            'is_deleted'  => 0,
            'description' => 'Successfully finished services with payouts disbursed.'
        ],
        'cancelled' => [
            'label'       => 'Cancelled Bookings',
            'statuses'    => ['Cancelled', 'cancelled', 'Canceled'],
            'is_deleted'  => 0,
            'description' => 'Cancelled bookings subject to refund evaluation policy.'
        ],
        'new_requests' => [
            'label'       => 'New Unassigned Requests',
            'statuses'    => ['New', 'new', 'Request', 'Pending', 'Service Provider Not Assigned'],
            'is_deleted'  => 0,
            'description' => 'Created client requests open for provider quotation bidding.'
        ],
        'halted_bookings' => [
            'label'       => 'Halted Bookings',
            'statuses'    => ['Halted', 'halted', 'H'],
            'is_deleted'  => 0,
            'description' => 'Bookings that are on hold and waiting for some action to be taken.'
        ],
        'deleted_bookings' => [
            'label'       => 'Deleted Bookings',
            'statuses'    => ['Deleted', 'deleted', 'D'],
            'is_deleted'  => 1,
            'description' => 'Bookings that have been deleted from the system.'
        ]
    ];

    /**
     * Authoritative Financial & Revenue Formulas (Ground Truth from Backend API).
     * Synchronized with \App\Services\PricingService, \App\Services\ServicePaymentService,
     * \App\Services\WalletService, and \App\Controllers\api\PaymentController.
     */
    public const FINANCIAL_DEFINITIONS = [
        'pricing_versions' => [
            'term'        => 'Pricing Engine Architecture',
            'version_1'   => 'Legacy: 0% Client Platform Fee, 18% GST, 10% Provider Platform Commission on Net Base.',
            'version_2'   => 'Active Production: 10% Client Platform Fee (CLIENT_PLATFORM_PERCENTAGE), 18% GST on Subtotal (GST), 15% Provider Platform Commission on Net Base (PROVIDER_PLATFORM_PERCENTAGE).',
            'description' => 'Petsfolio supports multi-version pricing with Version 2 active as the standard production model.'
        ],
        'gross_revenue' => [
            'term'        => 'Gross Revenue / Gross Booking Value (GBV / GMV)',
            'api_logic'   => "Total Client Payable = Subtotal + GST - DiscountAmount for confirmed/paid bookings. Inquiries with status='New' and unpaid payment_status are unconverted pipeline quotes, NOT confirmed revenue.",
            'db_formula'  => "SUM(total_amount) FROM v_bookings_overview WHERE is_deleted = 0 AND (LOWER(payment_status) IN ('paid', 'success', 'completed') OR LOWER(status) IN ('completed', 'confirmed', 'in progress', 'active'))",
            'description' => 'Total gross monetary amount billed to and paid by clients for confirmed services across Walking, Grooming, Training, Sitting, and Boarding. Unpaid requests in New status are tracked separately as Unpaid Pipeline Inquiries.'
        ],
        'client_platform_fee' => [
            'term'        => 'Client Platform Fee',
            'api_logic'   => "BasePrice * (client_platform_percentage / 100) [10% for Version 2, 0% for Version 1].",
            'db_formula'  => "Included in client payable total and invoice breakdown (InvoiceService.php).",
            'description' => 'Convenience and platform fee paid by the pet parent on top of base service cost.'
        ],
        'provider_platform_commission' => [
            'term'        => 'Provider Platform Commission',
            'api_logic'   => "NetBase * (provider_platform_percentage / 100) [15% for Version 2, 10% for Version 1], where NetBase = BasePrice - DiscountAmount.",
            'db_formula'  => "SUM(commission_amount) FROM platform_wallet_histories WHERE transaction_type = 'credit'",
            'description' => 'Platform commission percentage retained from service provider earnings on completed/ongoing services.'
        ],
        'platform_earnings' => [
            'term'        => 'Petsfolio Platform Earnings (Gross Net Margin)',
            'api_logic'   => "Total Client Payable - SP Receivable - GST = ClientPlatformFee + ProviderPlatformCommission (adjusted for discount).",
            'db_formula'  => "Clean Valid Commission from platform_wallet_histories + Successful Registration Fees from transactions.",
            'description' => 'Total gross operating revenue retained by Petsfolio from bookings and provider onboarding before taxes and refunds.'
        ],
        'gst_liability' => [
            'term'        => 'GST Liability (Statutory 18% Tax)',
            'api_logic'   => "(BasePrice + ClientPlatformFee) * (gst_percentage / 100) [18% standard GST collected from clients].",
            'db_formula'  => "SUM(commission_amount) FROM platform_wallet_histories WHERE commission_percentage IN ('18', '0.18') AND (booking_status IS NULL OR booking_status != 'Cancelled')",
            'description' => '18% statutory Goods and Services Tax collected from clients and remitted to the government (strictly non-platform revenue).'
        ],
        'provider_payouts' => [
            'term'        => 'Service Provider Escrow & Withdrawable Earnings',
            'api_logic'   => "Total SP Receivable = NetBase - ProviderPlatformCommission (85% of Net Base in V2, 90% in V1).",
            'db_formula'  => "Escrow held in sp_service_wallet; transferred to sp_withdrawal_wallet upon session approval; logged in sp_wallet_histories (category: approved_amount, earning_amount, service_amount).",
            'description' => 'Service provider share held in service escrow upon confirmation, unlocked to withdrawal wallet upon service delivery, and disbursed to bank via Cashfree Payouts.'
        ],
        'registration_fee' => [
            'term'        => 'Service Provider Registration & Verification Fee',
            'api_logic'   => "₹49.00 base + 18% GST (₹8.82) = ₹57.82 collected via Cashfree payment gateway order (PaymentController.php:374).",
            'db_formula'  => "SUM(amount) FROM transactions WHERE LOWER(transaction_type) IN ('walking_registration', 'sitting_registration', 'grooming_registration', 'training_registration', 'service_registration') AND LOWER(status) IN ('success', 'completed', 'paid', 'successful', '1')",
            'description' => 'Non-refundable onboarding and background verification fee collected from service providers upon registration.'
        ],
        'user_refunds' => [
            'term'        => 'Client Refunds & Cancelled Service Escrow',
            'api_logic'   => "Unused service escrow returned from user_service_wallet to client withdrawal/refund balance upon booking cancellation or session rejection.",
            'db_formula'  => "SUM(amount) FROM user_refunds WHERE LOWER(status) IN ('completed', 'approved', 'success', 'processed', 'paid', '1')",
            'description' => 'Total refunds returned to client wallet balances or disbursed back to original payment methods.'
        ],
        'net_revenue' => [
            'term'        => 'Bottom-Line Net Revenue (After Refunds)',
            'api_logic'   => "Platform Earnings + Registration Fees - Processed Client Refunds.",
            'db_formula'  => "(service_commission_clean_valid + registration_fee_total) - total_user_refunds",
            'description' => 'True retained platform net revenue after deducting all processed client refunds and cancellation reversals.'
        ]
    ];

    /**
     * Customer & Client Definitions.
     */
    public const CUSTOMER_DEFINITIONS = [
        'registered_client' => [
            'table'       => 'users',
            'description' => 'Any user who registered via mobile OTP in Petsfolio Client Application.'
        ],
        'active_booking_client' => [
            'table'       => 'v_bookings_overview',
            'description' => 'Client with at least 1 booking placed in the specified period.'
        ],
        'repeat_client' => [
            'table'       => 'v_bookings_overview',
            'description' => 'Client with 2 or more confirmed/completed bookings.'
        ],
        'churned_client' => [
            'table'       => 'v_bookings_overview',
            'description' => 'Registered client who placed bookings previously but zero in the last 90 days.'
        ]
    ];

    /**
     * Provider Activity vs Client Requests Definitions.
     * CRITICAL RULE: Providers DO NOT create service requests; clients create requests.
     * Providers submit quotations (bids), receive assignments, and withdraw earnings.
     */
    public const PROVIDER_ACTIVITY_DEFINITIONS = [
        'quotations_bids' => [
            'table'       => 'quotations',
            'description' => 'Bids placed by service providers on client service requests. Statuses: New, Accepted, Rejected, Completed, Cancelled.'
        ],
        'assigned_bookings' => [
            'table'       => 'sp_bookings',
            'description' => 'Confirmed booking assignments allocated to a service provider after bid acceptance.'
        ],
        'provider_earnings' => [
            'table'       => 'sp_service_wallet',
            'description' => 'Service wallet tracking earnings across walking, training, boarding, grooming, and sitting.'
        ],
        'provider_withdrawals' => [
            'table'       => 'sp_withdrawal_wallet',
            'description' => 'Withdrawal wallet balance available for cashout via Cashfree payout gateway.'
        ]
    ];

    /**
     * Authoritative Service Provider Domain Table Definitions.
     * Ground-truth mappings for all 17 core service provider database tables.
     */
    public const SERVICE_PROVIDER_TABLES = [
        'sp_grooming_details' => [
            'label'       => 'Service Provider Grooming Registration Details',
            'description' => 'Service provider grooming registration details, experience, certificates, emergency contact, and call verification status.',
            'category'    => 'registration_details',
            'service'     => 'grooming'
        ],
        'sp_kyc' => [
            'label'       => 'Service Provider Aadhaar KYC Details',
            'description' => 'Service provider Aadhaar KYC details (DigiLocker / Cashfree Aadhaar verification, Aadhaar number, DOB, address, photo).',
            'category'    => 'identity_kyc'
        ],
        'sp_reviews' => [
            'label'       => 'Service Provider Reviews & Ratings',
            'description' => 'Ratings and reviews of service provider given by client and admin (includes call record audio links, review comments, ratings 1-5).',
            'category'    => 'feedback_reputation'
        ],
        'sp_services' => [
            'label'       => 'Service Provider Registered Services & Verification Status',
            'description' => 'Service provider registered services with payment paid (payment_paid = 1 for ₹49 fee), call verified (call_verified = 1 for telephonic interview), and is_verified (is_verified = 1 for operations manager approval status).',
            'category'    => 'service_onboarding'
        ],
        'sp_service_images' => [
            'label'       => 'Service Provider Service Images & Videos',
            'description' => 'Service provider service related images and videos with status approved/rejected by admin.',
            'category'    => 'media_verification'
        ],
        'sp_service_wallet' => [
            'label'       => 'Service Provider Service Escrow Wallet',
            'description' => 'Service provider service escrow amount servicewise (walking_amount, training_amount, boarding_amount, grooming_amount, sitting_amount) that holds amount after booking confirmation.',
            'category'    => 'escrow_wallet'
        ],
        'sp_sitting_details' => [
            'label'       => 'Service Provider Sitting Registration Details',
            'description' => 'Service provider sitting registration details (service radius, pet types accepted, insurance, emergency contact, certifications).',
            'category'    => 'registration_details',
            'service'     => 'sitting'
        ],
        'sp_training_details' => [
            'label'       => 'Service Provider Training Registration Details',
            'description' => 'Service provider training registration details (obedience, intermediate, behavioral, advanced skills, certificates, call status).',
            'category'    => 'registration_details',
            'service'     => 'training'
        ],
        'sp_training_packages' => [
            'label'       => 'Service Provider Training Packages',
            'description' => 'Packages for service providers that provider selects while registering for training (package names, addons).',
            'category'    => 'service_packages',
            'service'     => 'training'
        ],
        'sp_verifications' => [
            'label'       => 'Service Provider Multi-Factor Verifications',
            'description' => 'Service provider verifications details like mobile, email, identity (Aadhaar KYC), police verification, address proof, and servicewise certifications.',
            'category'    => 'compliance_verification'
        ],
        'sp_verified_bank_details' => [
            'label'       => 'Service Provider Verified Bank Details',
            'description' => 'Service provider bank details for withdrawal amount to bank (account holder name, account number, IFSC code, bank name, name match score, verification UTR).',
            'category'    => 'payout_banking'
        ],
        'sp_walking_details' => [
            'label'       => 'Service Provider Walking Registration Details',
            'description' => 'Service provider walking registration details (service radius, pet aggression handling, certifications, interview answers, emergency contacts).',
            'category'    => 'registration_details',
            'service'     => 'walking'
        ],
        'sp_wallet_histories' => [
            'label'       => 'Service Provider Wallet History',
            'description' => 'Service provider wallet history: immutable financial audit ledger of provider credits and debits with running balances and cryptographic checksum verification.',
            'category'    => 'financial_ledger'
        ],
        'sp_withdrawal_wallet' => [
            'label'       => 'Service Provider Earned Amount Holds Wallet',
            'description' => 'Service provider earned amount holds wallet: tracks accumulated liquid withdrawable earnings available for Cashfree bank payout transfer.',
            'category'    => 'payout_wallet'
        ],
        'provider_agreements' => [
            'label'       => 'Provider Agreements & Acceptance Timestamps',
            'description' => 'Service provider agreement details for who received agreements with when received timestamps (terms & conditions, privacy policy, email sent at, mobile sent at, dispatch evidence).',
            'category'    => 'legal_agreements'
        ],
        'provider_identity_verifications' => [
            'label'       => 'Provider Identity Verifications (Aadhaar KYC)',
            'description' => 'Service provider KYC verifications details (Aadhaar DigiLocker verification, Cashfree reference ID, document status, Aadhaar last 4 digits, verified demographic data).',
            'category'    => 'identity_kyc'
        ],
        'provider_identity_verification_attempts' => [
            'label'       => 'Provider Identity Verification Attempts Audit',
            'description' => 'Service provider KYC verification attempts details (Aadhaar verification attempts, Cashfree reference, audit status, and failure reason logs).',
            'category'    => 'identity_kyc_audit'
        ],
        'wallet_withdrawals' => [
            'label'       => 'Service Provider Withdrawal Requests',
            'description' => 'Service provider withdrawal requests with status (pending, approved, rejected, in_progress, completed, otp_sent), bank account details, and Cashfree payout transfer identifiers (cf_transfer_id, utr).',
            'category'    => 'payout_requests'
        ]
    ];

    /**
     * Deprecated / Dormant / Unused Tables.
     * These tables are explicitly excluded from active BI analytics and schema context per domain rules.
     */
    public const EXCLUDED_DEPRECATED_TABLES = [
        'admin',
        'admin_alerts',
        'admin_notifications',
        'agents',
        'api_logs',
        'ai_logs',
        'appointments',
        'app_stats',
        'assignment_state',
        'assignment_states',
        'baileys_auth',
        'baileys_keys',
        'bank_verification_logs',
        'boarding_service_bookings',
        'boarding_tracking',
        'callers',
        'cameras',
        'cashfree_webhook_logs',
        'chatbot_ai_prompt_versions',
        'chatbot_faqs',
        'chatbot_keywords',
        'chatbot_logs',
        'chatbot_menu',
        'chatbot_quick_replies',
        'chatbot_session_state',
        'chatbot_settings',
        'chat_messages',
        'conversations',
        'doctors',
        'doctor_documents',
        'doctor_referral_codes',
        'event_scan_logs',
        'event_tickets',
        'financial_ledger',
        'followups',
        'halted_bookings_flat',
        'insurance_providers',
        'integrity_alerts',
        'memberships',
        'notification_preferences',
        'notification_queue1',
        'provider_leaves',
        'provider_languages',
        'provider_reports',
        'provider_scores',
        'provider_whatsapp_chats',
        'provider_whatsapp_contacts',
        'provider_whatsapp_messages',
        'provider_whatsapp_sessions',
        'push_subscriptions',
        'reconciliation_wallet',
        'queries',
        'report_activity_log',
        'shipments',
        'sitter_camera_configs',
        'wallet_audit_log',
        'wallet_transactions',
        'webhook_events',
        'webhook_retry_queue',
        'whatsapp_chats',
        'whatsapp_contacts',
        'whatsapp_lead_conversations',
        'whatsapp_lead_messages',
        'whatsapp_messages',
        'whatsapp_sessions',
        'withdrawal_receipts'
    ];

    /**
     * Active Client & User Domain Tables.
     */
    public const CLIENT_USER_TABLES = [
        'users' => [
            'label'       => 'Registered Pet Parents / Clients',
            'description' => 'Master customer accounts registered via mobile OTP in Petsfolio Client Application.',
            'category'    => 'client_master',
            'key_columns' => ['id', 'name', 'phone', 'email', 'status', 'created_at']
        ],
        'user_pets' => [
            'label'       => 'Client Pet Profiles',
            'description' => 'Registered pets owned by clients with breed, pet type, age, vaccination, and special care instructions.',
            'category'    => 'client_pets',
            'key_columns' => ['id', 'user_id', 'pet_name', 'pet_type', 'pet_breed', 'gender', 'dob', 'vaccination_status', 'is_aggressive']
        ],
        'user_addresses' => [
            'label'       => 'Client Service Addresses',
            'description' => 'Client saved addresses for doorstep service delivery with GPS coordinates, geohash, and landmark.',
            'category'    => 'client_locations',
            'key_columns' => ['id', 'user_id', 'address', 'city', 'state', 'pincode', 'latitude', 'longitude', 'is_default']
        ],
        'user_devices' => [
            'label'       => 'Client Device Tokens',
            'description' => 'Client mobile app devices, push FCM tokens, device platform (Android/iOS), and app version.',
            'category'    => 'client_telemetry',
            'key_columns' => ['id', 'user_id', 'device_token', 'platform', 'app_version', 'updated_at']
        ],
        'user_refunds' => [
            'label'       => 'Client Refund Requests & Payouts',
            'description' => 'Client wallet refund requests and processed payouts returned to bank or original payment method.',
            'category'    => 'client_financials',
            'key_columns' => ['id', 'user_id', 'amount', 'reason', 'status', 'created_at', 'updated_at']
        ],
        'user_service_wallet' => [
            'label'       => 'Client Service Escrow Wallet',
            'description' => 'Client wallet holding segregated booking escrow balances (walking_amount, training_amount, grooming_amount, sitting_amount).',
            'category'    => 'client_financials',
            'key_columns' => ['id', 'user_id', 'pet_id', 'walking_amount', 'training_amount', 'grooming_amount', 'sitting_amount']
        ],
        'user_withdrawal_wallet' => [
            'label'       => 'Client Refund & Withdrawable Wallet',
            'description' => 'Client wallet holding available refund balance ready to be utilized for new bookings or bank cashout.',
            'category'    => 'client_financials',
            'key_columns' => ['id', 'user_id', 'amount', 'created_at', 'updated_at']
        ],
        'user_wallet_histories' => [
            'label'       => 'Client Wallet Transaction Ledger',
            'description' => 'Immutable audit ledger of customer wallet credits and debits with running balances and cryptographic checksum verification.',
            'category'    => 'client_financials',
            'key_columns' => ['id', 'user_id', 'booking_id', 'service_id', 'transaction_type', 'amount', 'balance_after', 'checksum', 'created_at']
        ],
        'client_agreements' => [
            'label'       => 'Client Service Agreements',
            'description' => 'Service agreement sign-offs and terms acceptance records by clients with timestamps.',
            'category'    => 'client_compliance',
            'key_columns' => ['id', 'user_id', 'agreement_version', 'accepted_at', 'ip_address']
        ],
        'client_provider_pairs' => [
            'label'       => 'Client Preferred Provider Pairing',
            'description' => 'Historical pairing and customer preference mappings between clients and specific service providers.',
            'category'    => 'client_preferences',
            'key_columns' => ['id', 'client_id', 'provider_id', 'service_type', 'total_bookings', 'last_service_date']
        ]
    ];

    /**
     * Active Booking & Fulfillment Domain Tables.
     */
    public const BOOKING_FULFILLMENT_TABLES = [
        'v_bookings_overview' => [
            'label'       => 'Unified Bookings Analytics View',
            'description' => 'Core consolidated view of all booking requests across Walking, Grooming, Training, and Sitting with client, pet, pricing, and lifecycle status.',
            'category'    => 'booking_master',
            'key_columns' => ['booking_id', 'service_type', 'user_id', 'provider_id', 'user_name', 'status', 'total_amount', 'service_start_date', 'service_end_date', 'city', 'is_deleted']
        ],
        'walking_service_bookings' => [
            'label'       => 'Dog Walking Bookings',
            'description' => 'Dog walking service requests and contracts (package days, frequency, walk duration, time preferences, start/end dates, total amount).',
            'category'    => 'booking_service',
            'service'     => 'walking',
            'key_columns' => ['id', 'booking_id', 'user_id', 'service_frequency', 'duration_days', 'walk_duration', 'service_days', 'total_price', 'status', 'is_deleted']
        ],
        'walking_tracking' => [
            'label'       => 'Walking Tracking Sessions',
            'description' => 'Daily walk tracking execution logs (start/end time, GPS distance, route geopath, pee/poo events, walk photos, verification approval).',
            'category'    => 'booking_fulfillment',
            'service'     => 'walking',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'pet_id', 'tracking_date', 'service_time', 'start_time', 'end_time', 'distance', 'status']
        ],
        'walk_payment_requests' => [
            'label'       => 'Walk Payout Release Requests',
            'description' => 'Daily walk payout verification and disbursement requests submitted by or on behalf of walkers.',
            'category'    => 'booking_fulfillment',
            'service'     => 'walking',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'tracking_id', 'amount', 'status', 'created_at']
        ],
        'walk_reminder_logs' => [
            'label'       => 'Walk Reminder Notification Logs',
            'description' => 'Audit log of automated walk schedule reminders dispatched to clients and walkers before scheduled walks.',
            'category'    => 'booking_fulfillment',
            'service'     => 'walking',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'user_id', 'reminder_type', 'sent_at']
        ],
        'grooming_service_bookings' => [
            'label'       => 'Pet Grooming Bookings',
            'description' => 'Pet grooming service requests (package selected, appointment date/time, service mode: home grooming vs van, address, pricing).',
            'category'    => 'booking_service',
            'service'     => 'grooming',
            'key_columns' => ['id', 'booking_id', 'user_id', 'service_date', 'service_time', 'service_mode', 'total_amount', 'status', 'is_deleted']
        ],
        'grooming_booking_packages' => [
            'label'       => 'Grooming Package & Styling Selections',
            'description' => 'Packages, hair trimming styles, and add-ons configured for grooming bookings.',
            'category'    => 'booking_service',
            'service'     => 'grooming',
            'key_columns' => ['id', 'booking_id', 'package_id', 'package_name', 'trim_style', 'created_at']
        ],
        'grooming_tracking' => [
            'label'       => 'Grooming Session Tracking',
            'description' => 'Grooming service delivery tracking (check-in, before/after session photos, completion approval).',
            'category'    => 'booking_fulfillment',
            'service'     => 'grooming',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'pet_id', 'service_date', 'status', 'created_at']
        ],
        'training_service_bookings' => [
            'label'       => 'Pet Training Bookings',
            'description' => 'Pet training bookings (training course package, session count, schedule, behavioral focus, total pricing).',
            'category'    => 'booking_service',
            'service'     => 'training',
            'key_columns' => ['id', 'booking_id', 'user_id', 'training_type', 'duration_days', 'total_price', 'status', 'is_deleted']
        ],
        'training_tracking' => [
            'label'       => 'Training Command & Session Tracking',
            'description' => 'Training execution tracking logs (commands taught, dog responsiveness, session completion milestones).',
            'category'    => 'booking_fulfillment',
            'service'     => 'training',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'pet_id', 'command_name', 'session_date', 'status']
        ],
        'sitting_service_bookings' => [
            'label'       => 'Pet Sitting Bookings',
            'description' => 'Pet sitting bookings (hourly care, day care 10hr, overnight care 24hr, start/end dates, visit tasks).',
            'category'    => 'booking_service',
            'service'     => 'sitting',
            'key_columns' => ['id', 'booking_id', 'user_id', 'service_start_date', 'service_end_date', 'package_price', 'status', 'is_deleted']
        ],
        'sitting_tracking' => [
            'label'       => 'Sitting Visit Tracking',
            'description' => 'Pet sitting check-in, visit logs, feeding/medication updates, and completion sign-offs.',
            'category'    => 'booking_fulfillment',
            'service'     => 'sitting',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'pet_id', 'visit_date', 'checkin_time', 'checkout_time', 'status']
        ],
        'sitting_checklist_media' => [
            'label'       => 'Sitting Checklist Photos & Media',
            'description' => 'Photos, videos, and checklist verification evidence captured by sitters during sitting sessions.',
            'category'    => 'booking_fulfillment',
            'service'     => 'sitting',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'task_name', 'media_url', 'created_at']
        ],
        'booking_pets' => [
            'label'       => 'Booking Pet Associations',
            'description' => 'Maps individual pets included in multi-pet booking requests with pet-specific pricing splits.',
            'category'    => 'booking_structure',
            'key_columns' => ['id', 'booking_id', 'pet_id', 'service_type', 'created_at']
        ],
        'booking_addons' => [
            'label'       => 'Booking Add-on Services',
            'description' => 'Selected add-ons attached to bookings (poo picking, de-shedding, tick treatment, extra walks) with itemized prices.',
            'category'    => 'booking_structure',
            'key_columns' => ['id', 'booking_id', 'addon_id', 'name', 'price', 'created_at']
        ],
        'booking_invited_sps' => [
            'label'       => 'Booking Provider Invitation Broadcasts',
            'description' => 'Audit log of nearby qualified service providers invited/notified to submit bids for new booking requests.',
            'category'    => 'booking_dispatch',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'distance_km', 'status', 'created_at']
        ],
        'booking_conversations' => [
            'label'       => 'Booking In-App Chat Threads',
            'description' => 'Communication threads between customer and assigned service provider for active bookings.',
            'category'    => 'booking_communications',
            'key_columns' => ['id', 'booking_id', 'user_id', 'provider_id', 'created_at', 'updated_at']
        ],
        'booking_messages' => [
            'label'       => 'Booking Chat Messages',
            'description' => 'Chat messages exchanged within booking conversation channels.',
            'category'    => 'booking_communications',
            'key_columns' => ['id', 'conversation_id', 'sender_id', 'sender_type', 'message', 'is_read', 'created_at']
        ],
        'premium_requests' => [
            'label'       => 'Premium Service Custom Requests',
            'description' => 'Customized high-touch service requests requiring concierge operations assignment.',
            'category'    => 'booking_service',
            'key_columns' => ['id', 'user_id', 'service_id', 'details', 'status', 'created_at']
        ],
        'ride_tracking' => [
            'label'       => 'Pet Taxi & Ride Tracking',
            'description' => 'Live ride tracking logs for pet transit, vet transport, and van appointments.',
            'category'    => 'booking_fulfillment',
            'key_columns' => ['id', 'booking_id', 'driver_id', 'pickup_time', 'drop_time', 'status']
        ]
    ];

    /**
     * Active Quotation & Bidding Domain Tables.
     */
    public const QUOTATION_BIDDING_TABLES = [
        'quotations' => [
            'label'       => 'Provider Quotation Bids',
            'description' => 'Bids submitted by service providers for client booking requests (bid_amount, receivable_amount, gst, status: New, Accepted, Rejected, Completed, Cancelled).',
            'category'    => 'quotation_master',
            'key_columns' => ['id', 'booking_id', 'provider_id', 'service_type', 'bid_amount', 'receivable_amount', 'gst', 'status', 'created_at']
        ],
        'quotation_addons' => [
            'label'       => 'Quotation Add-on Pricing',
            'description' => 'Line-item add-ons included and priced within individual provider quotation bids.',
            'category'    => 'quotation_addons',
            'key_columns' => ['id', 'quotation_id', 'addon_id', 'price', 'created_at']
        ],
        'v_quotations_overview' => [
            'label'       => 'Quotations Analytics View',
            'description' => 'Aggregated view analyzing provider bidding volume, bid response latencies, and quotation conversion rates.',
            'category'    => 'quotation_analytics',
            'key_columns' => ['quotation_id', 'booking_id', 'provider_id', 'service_type', 'bid_amount', 'status', 'created_at']
        ]
    ];

    /**
     * Active Community Domain Tables.
     */
    public const COMMUNITY_TABLES = [
        'community_channels' => [
            'label'       => 'Community Channels & Discussion Groups',
            'description' => 'Discussion groups and topic channels for pet parents and service providers (id, name, user_type, handle, description, icon, status, is_private).',
            'category'    => 'community_structure',
            'key_columns' => ['id', 'name', 'user_type', 'handle', 'description', 'status', 'is_private', 'created_at']
        ],
        'community_channel_joins' => [
            'label'       => 'Community Channel Memberships',
            'description' => 'User memberships and subscriber records in community discussion channels (id, user_id, user_type, channel_id, created_at).',
            'category'    => 'community_memberships',
            'key_columns' => ['id', 'user_id', 'user_type', 'channel_id', 'created_at']
        ],
        'community_posts' => [
            'label'       => 'Community Posts & Questions Feed',
            'description' => 'User feed posts, advice queries, and stories with engagement counters (id, channel_id, category, user_id, user_type, post_text, post_type, likes_count, comments_count, shares_count, status).',
            'category'    => 'community_feed',
            'key_columns' => ['id', 'channel_id', 'category', 'user_id', 'user_type', 'post_text', 'post_type', 'likes_count', 'comments_count', 'shares_count', 'status', 'created_at']
        ],
        'community_post_media' => [
            'label'       => 'Community Post Media Attachments',
            'description' => 'Images and videos attached to community posts (id, post_id, media_type, media_url, created_at).',
            'category'    => 'community_media',
            'key_columns' => ['id', 'post_id', 'media_type', 'media_url', 'created_at']
        ],
        'community_post_channels' => [
            'label'       => 'Community Post Channel Mappings',
            'description' => 'Multi-channel associations linking specific posts to discussion channels.',
            'category'    => 'community_structure',
            'key_columns' => ['id', 'post_id', 'channel_id']
        ],
        'community_post_likes' => [
            'label'       => 'Community Post Reactions & Likes',
            'description' => 'User like/upvote reactions on community posts (id, post_id, user_id, user_type, created_at).',
            'category'    => 'community_engagement',
            'key_columns' => ['id', 'post_id', 'user_id', 'user_type', 'created_at']
        ],
        'community_post_comments' => [
            'label'       => 'Community Post Comments & Replies',
            'description' => 'User comments and threaded replies under community posts (id, post_id, user_id, user_type, parent_comment_id, comment, likes_count, created_at).',
            'category'    => 'community_engagement',
            'key_columns' => ['id', 'post_id', 'user_id', 'user_type', 'parent_comment_id', 'comment', 'likes_count', 'created_at']
        ],
        'community_notifications' => [
            'label'       => 'Community Activity Notifications',
            'description' => 'Push and in-app notifications generated for likes, comments, and mentions in community threads.',
            'category'    => 'community_notifications',
            'key_columns' => ['id', 'user_id', 'sender_id', 'notification_type', 'post_id', 'comment_id', 'message', 'is_read', 'created_at']
        ],
        'community_post_saves' => [
            'label'       => 'Community Saved / Bookmarked Posts',
            'description' => 'Saved posts bookmarked by pet parents for future reference (id, user_id, post_id, created_at).',
            'category'    => 'community_engagement',
            'key_columns' => ['id', 'user_id', 'post_id', 'created_at']
        ],
        'community_post_tags' => [
            'label'       => 'Community Post Tags',
            'description' => 'Topic and pet category hashtags associated with posts (id, post_id, tag_id, tag_name).',
            'category'    => 'community_structure',
            'key_columns' => ['id', 'post_id', 'tag_name']
        ]
    ];

    /**
     * Active Calls & Telephony Communications Domain Tables.
     */
    public const CALLS_COMMUNICATION_TABLES = [
        'calls' => [
            'label'       => 'In-App WebRTC Audio & Video Calls',
            'description' => 'Live peer-to-peer audio and video calls in Petsfolio apps between client and service provider for booking consultations and meet-and-greets (id, booking_id, client_id, provider_id, initiated_by, room_name, status: initiating, ringing, accepted, in_progress, completed, missed, rejected, failed, cancelled, duration_seconds, started_at, ended_at).',
            'category'    => 'calls_webrtc',
            'key_columns' => ['id', 'booking_id', 'client_id', 'provider_id', 'initiated_by', 'status', 'duration_seconds', 'started_at', 'ended_at']
        ],
        'call_attempts' => [
            'label'       => 'Service Provider Telephony Call Attempts',
            'description' => 'Automated and agent call connection attempts to service providers (id, sp_id, caller_id, attempt_no, call_status: pending, initiated, ringing, answered, completed, busy, no_answer, rejected, switched_off, failed, duration_seconds, recording_url, transcript, escalation_required).',
            'category'    => 'calls_telephony',
            'key_columns' => ['id', 'sp_id', 'caller_id', 'attempt_no', 'call_status', 'duration_seconds', 'recording_url', 'escalation_required', 'created_at']
        ],
        'call_recordings' => [
            'label'       => 'Call Audio Recordings Vault',
            'description' => 'Ingested call audio files for auditing and speech QA (id, call_id, booking_id, provider_id, customer_id, call_type, service_type, recording_url, duration, status: uploaded, queued, processing, completed, failed, verifier_id).',
            'category'    => 'calls_recordings',
            'key_columns' => ['id', 'call_id', 'booking_id', 'provider_id', 'customer_id', 'call_type', 'service_type', 'duration', 'status', 'created_at']
        ],
        'call_analysis' => [
            'label'       => 'Call AI QA & Compliance Analysis',
            'description' => 'AI speech transcription, quality audit, and policy violation detection (id, recording_id, transcript, summary, overall_rating, qa_score, communication, professionalism, empathy, compliance, customer_sentiment, provider_sentiment, risk_score, off_platform_payment, contact_sharing, outside_service_request, refund_requested, admin_alert_required, verification_status).',
            'category'    => 'calls_ai_qa',
            'key_columns' => ['id', 'recording_id', 'qa_score', 'overall_rating', 'communication', 'professionalism', 'compliance', 'customer_sentiment', 'risk_score', 'off_platform_payment', 'contact_sharing', 'outside_service_request', 'admin_alert_required', 'verification_status']
        ],
        'call_analysis_jobs' => [
            'label'       => 'Call AI Speech-to-Text Pipeline Jobs',
            'description' => 'Asynchronous background transcription and LLM evaluation processing jobs for call recordings.',
            'category'    => 'calls_ai_qa',
            'key_columns' => ['id', 'recording_id', 'status', 'attempts', 'created_at', 'updated_at']
        ],
        'call_keywords' => [
            'label'       => 'Call Transcript Keywords & Intent Signals',
            'description' => 'Extracted keywords and frequencies from transcribed calls for pattern analysis (id, analysis_id, keyword, frequency).',
            'category'    => 'calls_ai_qa',
            'key_columns' => ['id', 'analysis_id', 'keyword', 'frequency']
        ],
        'executive_call_records' => [
            'label'       => 'Operations Executive Call Records',
            'description' => 'Onboarding and verification calls conducted by operations executives with providers with payout tracking (id, executive_id, provider_id, service_id, executive_type: register, verification, call_date, payout_status: pending, paid, rate).',
            'category'    => 'calls_operations',
            'key_columns' => ['id', 'executive_id', 'provider_id', 'service_id', 'executive_type', 'call_date', 'payout_status', 'rate']
        ],
        'provider_calls' => [
            'label'       => 'Provider Telephonic Verification Calls',
            'description' => 'Direct verification and support phone calls placed to service providers (id, call_id, provider_id, from_number, to_number, call_direction, call_status, call_duration, sentiment, summary, call_outcome).',
            'category'    => 'calls_verification',
            'key_columns' => ['id', 'provider_id', 'from_number', 'to_number', 'call_direction', 'call_status', 'call_duration', 'sentiment', 'call_outcome', 'call_date']
        ]
    ];

    /**
     * Active Insurance Domain Tables.
     */
    public const INSURANCE_TABLES = [
        'insurance_policies' => [
            'label'       => 'Pet Health Insurance Policies',
            'description' => 'Pet insurance policy enrollments (id, policy_number, insurance_user_id, pet_id, provider, coverage_type: Basic, Standard, Premium, duration_years, start_date, end_date, status: Active, Expired, Pending, Cancelled, documents, remarks).',
            'category'    => 'insurance_policies',
            'key_columns' => ['id', 'policy_number', 'insurance_user_id', 'pet_id', 'provider', 'coverage_type', 'duration_years', 'start_date', 'end_date', 'status']
        ],
        'insurance_users' => [
            'label'       => 'Insurance Enrolled Pet Parents',
            'description' => 'Pet owners who have signed up or enrolled for pet insurance with OTP verification (id, user_id, full_name, email, phone, address, otp_verified).',
            'category'    => 'insurance_users',
            'key_columns' => ['id', 'user_id', 'full_name', 'email', 'phone', 'address', 'otp_verified', 'created_at']
        ],
        'insurance_policies_quotations' => [
            'label'       => 'Insurance Policy Quotations & Estimates',
            'description' => 'Quotations, coverage proposals, and premium estimates generated for pet insurance requests (id, policy_id, amount, subject, message, created_at).',
            'category'    => 'insurance_quotations',
            'key_columns' => ['id', 'policy_id', 'amount', 'subject', 'message', 'created_at']
        ]
    ];

    /**
     * Check if a table is explicitly deprecated / dormant / unused.
     */
    public static function isTableExcluded(string $tableName): bool
    {
        return in_array(strtolower(trim($tableName)), self::EXCLUDED_DEPRECATED_TABLES, true);
    }

    /**
     * Get authoritative definition for a service provider table.
     */
    public static function getProviderTableDefinition(string $tableName): ?array
    {
        return self::SERVICE_PROVIDER_TABLES[$tableName] ?? null;
    }

    /**
     * Get all service provider table definitions.
     */
    public static function getAllProviderTableDefinitions(): array
    {
        return self::SERVICE_PROVIDER_TABLES;
    }

    /**
     * Resolves a natural language service name to canonical service info.
     */
    public static function resolveService(?string $name): ?array
    {
        if (empty($name)) {
            return null;
        }
        $lower = strtolower(trim($name));
        foreach (self::SERVICES as $key => $meta) {
            if ($lower === $key || str_contains($lower, $key) || str_contains(strtolower($meta['name']), $lower) || str_contains(strtolower($meta['label']), $lower)) {
                return array_merge(['key' => $key], $meta);
            }
        }
        if (str_contains($lower, 'dog walk') || str_contains($lower, 'walking')) {
            return array_merge(['key' => 'walking'], self::SERVICES['walking']);
        }
        if (str_contains($lower, 'bath') || str_contains($lower, 'trim') || str_contains($lower, 'groom')) {
            return array_merge(['key' => 'grooming'], self::SERVICES['grooming']);
        }
        if (str_contains($lower, 'trainer') || str_contains($lower, 'puppy') || str_contains($lower, 'train')) {
            return array_merge(['key' => 'training'], self::SERVICES['training']);
        }
        if (str_contains($lower, 'sitter') || str_contains($lower, 'overnight') || str_contains($lower, 'sit')) {
            return array_merge(['key' => 'sitting'], self::SERVICES['sitting']);
        }
        if (str_contains($lower, 'board') || str_contains($lower, 'kennel') || str_contains($lower, 'stay')) {
            return array_merge(['key' => 'boarding'], self::SERVICES['boarding']);
        }
        return null;
    }

    /**
     * Checks if a search candidate matches generic service/location/question words.
     */
    public static function isStopwordCandidate(string $text): bool
    {
        $lower = strtolower(trim($text));
        $stopwords = [
            'how',
            'many',
            'count',
            'number',
            'total',
            'walkers',
            'walker',
            'walking',
            'trainers',
            'trainer',
            'training',
            'groomers',
            'groomer',
            'grooming',
            'sitters',
            'sitter',
            'sitting',
            'boarders',
            'boarder',
            'boarding',
            'providers',
            'provider',
            'service',
            'services',
            'sp',
            'sps',
            'hyderabad',
            'bangalore',
            'bengaluru',
            'mumbai',
            'delhi',
            'chennai',
            'pune',
            'kolkata',
            'secunderabad',
            'there',
            'in',
            'at',
            'city',
            'area',
            'locality',
            'active',
            'inactive',
            'approved',
            'waiting',
            'call',
            'manager',
            'approval',
            'fee',
            'pending',
            'registration',
            'repeated',
            'repeat',
            'returning',
            'clients',
            'client',
            'customers',
            'customer',
            'any',
            'payout',
            'payouts',
            'withdrawal',
            'withdrawals',
            'request',
            'requests',
            'balance',
            'wallet',
            'wallets',
            'cashfree',
            'petsfolio',
            'platform',
            'company'
        ];
        $tokens = preg_split('/\s+/', $lower);
        $meaningful = 0;
        foreach ($tokens as $t) {
            if ($t !== '' && !in_array($t, $stopwords, true)) {
                $meaningful++;
            }
        }
        return $meaningful === 0;
    }

    /**
     * Standardized Enterprise Business Event Taxonomy (SSOT)
     * Defines ~160+ business events across 16 core platform areas.
     */
    public const BUSINESS_EVENT_TAXONOMY = [
        'CLIENT' => [
            'label' => 'Customer / Client Lifecycle',
            'events' => [
                'CLIENT_REGISTERED'         => 'New pet parent registered on platform via mobile app/web.',
                'CLIENT_PROFILE_COMPLETED'  => 'Client profile filled with name, email, phone, gender, and avatar.',
                'CLIENT_PROFILE_INCOMPLETE' => 'Client registered but missing profile fields (name, email, or gender).',
                'CLIENT_LOGIN'              => 'Client authenticated into mobile application or web portal.',
                'CLIENT_INACTIVE'           => 'Client has not opened app or placed requests in 60+ days.',
                'CLIENT_REACTIVATED'        => 'Dormant client returned and placed a request or engaged with app.',
                'CLIENT_APP_UNINSTALLED'    => 'Client device uninstalled the mobile app (detected via FCM drop).',
                'CLIENT_APP_REINSTALLED'    => 'Client re-installed mobile app and restored session.',
                'CLIENT_LOGGED_OUT'         => 'Client explicitly logged out from partner or parent app.',
                'CLIENT_BLOCKED'            => 'Account suspended due to policy violation or abuse.',
                'CLIENT_DELETED_ACCOUNT'    => 'Client exercised right to be forgotten or requested account removal.',
                'CLIENT_CONTACT_UPDATED'    => 'Client updated phone number or email address.',
                'CLIENT_ADDRESS_ADDED'      => 'Client saved a new service address/location.',
                'CLIENT_ADDRESS_UPDATED'    => 'Client edited existing service address details or coordinates.',
                'CLIENT_ADDRESS_REMOVED'    => 'Client removed saved address.'
            ]
        ],
        'PET' => [
            'label' => 'Pet Management & Profiles',
            'events' => [
                'PET_ADDED'                 => 'New pet registered under client profile.',
                'PET_PROFILE_COMPLETED'     => 'Pet profile fully populated with breed, age, size, gender, vaccination.',
                'PET_PROFILE_INCOMPLETE'    => 'Pet added with partial information.',
                'PET_UPDATED'               => 'Pet details or photos updated.',
                'PET_DELETED'               => 'Pet profile removed.',
                'PET_VACCINATION_ADDED'     => 'Vaccination record certificate uploaded.',
                'PET_VACCINATION_EXPIRED'   => 'Pet vaccination date expired, requiring renewal.',
                'PET_MEDICAL_INFO_ADDED'    => 'Allergies, medications, or dietary instructions saved.',
                'MULTIPLE_PETS_ADDED'       => 'Client configured multiple pets (multi-pet household).',
                'PET_SIZE_SELECTED'         => 'Pet size tier selected (Small, Medium, Large, Giant).',
                'PET_BREED_SELECTED'        => 'Specific breed designated for tailored service pricing.',
                'PET_REQUIREMENT_UPDATED'   => 'Behavioral notes or aggression warnings updated.'
            ]
        ],
        'SERVICE' => [
            'label' => 'Service Discovery & Intent',
            'events' => [
                'SERVICE_VIEWED'            => 'Client viewed service overview screen (Walking, Grooming, Training).',
                'SERVICE_SELECTED'          => 'Client selected service package or frequency.',
                'SERVICE_SEARCHED'          => 'Client searched for pet care services or packages.',
                'PROVIDER_SEARCHED'         => 'Client or system searched for nearby service providers.',
                'PROVIDER_VIEWED'           => 'Provider card or summary viewed in discovery list.',
                'PROVIDER_PROFILE_VIEWED'   => 'Client inspected full provider credentials, badges, and bio.',
                'PROVIDER_REVIEWS_VIEWED'   => 'Client opened star ratings and feedback for a provider.',
                'PROVIDER_CONTACTED'        => 'Client initiated call or in-app chat with service provider.',
                'SERVICE_LOCATION_SELECTED' => 'Geographic service locality or pin selected for booking.',
                'NO_PROVIDER_FOUND'         => 'Zero providers available within dispatch radius for chosen slot.',
                'PROVIDER_FOUND'            => 'One or more eligible providers identified in target service area.',
                'SERVICE_REQUIREMENT_SUBMITTED' => 'Client submitted requirements, initiating dispatch broadcast.'
            ]
        ],
        'WALKING' => [
            'label' => 'Dog Walking Lifecycle',
            'events' => [
                'WALKING_REQUEST_CREATED'   => 'Dog walking booking created in walking_service_bookings.',
                'WALKING_QUOTATION_RECEIVED'=> 'Walker submitted quotation / bid for walking request.',
                'WALKING_QUOTATION_VIEWED'  => 'Client viewed submitted walker quote on app.',
                'WALKING_QUOTATION_ACCEPTED'=> 'Client accepted walker quotation and proceeded to payment.',
                'WALKING_QUOTATION_REJECTED'=> 'Walker quotation declined by client or expired.',
                'WALKING_BOOKING_CONFIRMED' => 'Walker assigned, schedule locked, booking in Confirmed state.',
                'WALKING_STARTED'           => 'Walker checked in at client residence to commence session.',
                'WALKING_TRACKING_STARTED'  => 'GPS tracking recording distance, route, and duration.',
                'WALKING_COMPLETED'         => 'Walk finished, summary sent to client, session marked Completed.',
                'WALKING_CANCELLED'         => 'Walking service cancelled prior to or during schedule.',
                'WALKING_MISSED'            => 'Walker failed to show up for scheduled walk.',
                'WALKING_RESCHEDULED'       => 'Walk timing adjusted to alternate time slot or day.'
            ]
        ],
        'GROOMING' => [
            'label' => 'Pet Grooming Lifecycle',
            'events' => [
                'GROOMING_REQUEST_CREATED'  => 'Grooming request submitted for home or van service.',
                'GROOMING_QUOTATION_RECEIVED' => 'Groomer submitted bid / quotation.',
                'GROOMING_QUOTATION_VIEWED' => 'Client inspected grooming quotation details & addons.',
                'GROOMING_QUOTATION_ACCEPTED' => 'Grooming quotation accepted by pet parent.',
                'GROOMING_BOOKING_CONFIRMED'=> 'Groomer scheduled and booking confirmed.',
                'GROOMING_STARTED'          => 'Groomer arrived and began grooming session.',
                'GROOMING_COMPLETED'        => 'Grooming completed with photo upload and checklist.',
                'GROOMING_CANCELLED'        => 'Grooming booking cancelled before arrival.',
                'GROOMING_RESCHEDULED'      => 'Grooming appointment rescheduled.',
                'GROOMING_MISSED'           => 'Groomer or client missed appointment window.'
            ]
        ],
        'TRAINING' => [
            'label' => 'Pet Training Lifecycle',
            'events' => [
                'TRAINING_REQUEST_CREATED'  => 'Training course requested (Puppy, Intermediate, Behaviour, Advanced).',
                'TRAINING_QUOTATION_RECEIVED' => 'Trainer submitted quotation with session schedule.',
                'TRAINING_QUOTATION_VIEWED' => 'Client viewed trainer quote and session structure.',
                'TRAINING_QUOTATION_ACCEPTED' => 'Client accepted trainer quotation.',
                'TRAINING_BOOKING_CONFIRMED'=> 'Training package confirmed with primary certified trainer.',
                'TRAINING_STARTED'          => 'First training session commenced.',
                'TRAINING_COMPLETED'        => 'All milestone curriculum training sessions finished.',
                'TRAINING_CANCELLED'        => 'Training package cancelled.',
                'TRAINING_RESCHEDULED'      => 'Training session date or slot rescheduled.',
                'TRAINING_MISSED'           => 'Training session unattended by trainer or pet.'
            ]
        ],
        'BOARDING' => [
            'label' => 'Pet Boarding & Sitting Lifecycle',
            'events' => [
                'BOARDING_REQUEST_CREATED'  => 'Boarding stay dates and facility requested.',
                'BOARDING_QUOTATION_RECEIVED' => 'Boarder submitted price quote for stay.',
                'BOARDING_QUOTATION_ACCEPTED' => 'Boarding reservation quotation accepted.',
                'BOARDING_BOOKING_CONFIRMED'=> 'Boarding stay confirmed and pet space reserved.',
                'BOARDING_STARTED'          => 'Pet checked in at host boarding facility.',
                'BOARDING_COMPLETED'        => 'Pet safely checked out at end of stay.',
                'BOARDING_CANCELLED'        => 'Boarding booking cancelled.',
                'SITTING_REQUEST_CREATED'   => 'Pet sitter home visit requested.',
                'SITTING_BOOKING_CONFIRMED' => 'Pet sitter booking confirmed.',
                'SITTING_COMPLETED'         => 'Pet sitter visit finished.'
            ]
        ],
        'BOOKING' => [
            'label' => 'Unified Booking Lifecycle State Machine',
            'events' => [
                'BOOKING_CREATED'           => 'New booking record initiated across any service.',
                'BOOKING_PENDING'           => 'Awaiting quotes or provider availability.',
                'BOOKING_CONFIRMED'         => 'Provider assigned, payment verified, active schedule.',
                'BOOKING_ACCEPTED'          => 'Assigned provider accepted customer booking request.',
                'BOOKING_REJECTED'          => 'Assigned provider declined customer booking request.',
                'BOOKING_CANCELLED'         => 'Booking cancelled by client, provider, or administrator.',
                'BOOKING_RESCHEDULED'       => 'Booking dates or timing adjusted.',
                'BOOKING_EXPIRED'           => 'Booking expired with zero bids or acceptance.',
                'BOOKING_HALTED'            => 'Booking stalled (halted) due to lack of providers or client response.',
                'BOOKING_REACTIVATED'       => 'Halted booking restored to active flow after operator intervention.',
                'BOOKING_COMPLETED'         => 'All scheduled service sessions successfully delivered.',
                'BOOKING_FAILED'            => 'Service delivery aborted due to operational breakdown.',
                'BOOKING_NO_SHOW'           => 'Provider or customer was absent during service window.',
                'BOOKING_DISPUTED'          => 'Client or provider opened dispute or formal grievance.',
                'BOOKING_REFUNDED'          => 'Full or partial refund processed back to client.'
            ]
        ],
        'PROVIDER' => [
            'label' => 'Service Provider / Partner Lifecycle',
            'events' => [
                'PROVIDER_REGISTERED'       => 'New partner registered on Petsfolio Partner App.',
                'PROVIDER_PROFILE_COMPLETED'=> 'Provider profile details, experience, and service areas filled.',
                'PROVIDER_PROFILE_INCOMPLETE' => 'Provider signup missing documents or registration fee.',
                'PROVIDER_VERIFICATION_STARTED' => 'Aadhaar KYC or background verification initiated.',
                'PROVIDER_VERIFIED'         => 'Provider passed Aadhaar & telephonic manager verification.',
                'PROVIDER_VERIFICATION_FAILED' => 'Provider failed KYC or background screening.',
                'PROVIDER_ONLINE'           => 'Provider toggled duty switch to Online (ready for dispatch).',
                'PROVIDER_OFFLINE'          => 'Provider toggled duty switch to Offline.',
                'PROVIDER_AVAILABLE'        => 'Provider marked available for upcoming dispatch windows.',
                'PROVIDER_UNAVAILABLE'      => 'Provider marked busy or on leave.',
                'PROVIDER_SERVICE_ADDED'    => 'Provider activated new service skill (e.g. Walking + Grooming).',
                'PROVIDER_SERVICE_REMOVED'  => 'Provider deactivated service skill.',
                'PROVIDER_BID_SUBMITTED'    => 'Provider submitted quotation / bid for a client request.',
                'PROVIDER_BID_ACCEPTED'     => 'Client accepted provider bid.',
                'PROVIDER_BID_REJECTED'     => 'Client selected a competing provider or declined quote.'
            ]
        ],
        'PAYMENT' => [
            'label' => 'Payment & Checkout Funnel',
            'events' => [
                'PAYMENT_INITIATED'         => 'Client opened Cashfree checkout or payment gateway modal.',
                'PAYMENT_SUCCESS'           => 'Payment captured and verified by webhook/API.',
                'PAYMENT_FAILED'            => 'Payment declined by bank, gateway, or insufficient funds.',
                'PAYMENT_PENDING'           => 'Payment in progress awaiting bank settlement.',
                'PAYMENT_ABANDONED'         => 'Client exited checkout without entering payment details.',
                'PAYMENT_EXPIRED'           => 'Payment session link expired.',
                'PAYMENT_REFUNDED'          => 'Full payment refunded back to client original payment mode.',
                'PAYMENT_PARTIAL_REFUND'    => 'Partial refund disbursed (e.g. for missed days).',
                'PAYMENT_REFUND_FAILED'     => 'Payment gateway refund API error.',
                'PAYMENT_DUPLICATE'         => 'Duplicate transaction attempt prevented by idempotency.',
                'PAYMENT_GATEWAY_ERROR'     => 'Gateway downtime, timeout, or network error.',
                'PAYMENT_DISPUTED'          => 'Chargeback or payment dispute filed by client bank.'
            ]
        ],
        'WALLET' => [
            'label' => 'Wallet & Payout Operations',
            'events' => [
                'WALLET_CREATED'            => 'In-app wallet opened for client or partner.',
                'WALLET_CREDITED'           => 'Cashback, referral reward, or deposit added to wallet.',
                'WALLET_DEBITED'            => 'Wallet funds deducted for booking payment.',
                'WALLET_HOLD_CREATED'       => 'Funds held in escrow pending service delivery.',
                'WALLET_HOLD_RELEASED'      => 'Held escrow funds released to provider or refunded.',
                'WALLET_REFUND'             => 'Cancelled booking refunded directly into wallet credits.',
                'WALLET_INSUFFICIENT_BALANCE' => 'Checkout attempt failed due to low wallet balance.',
                'WALLET_TRANSACTION_FAILED' => 'Internal ledger balance transfer failure.',
                'PROVIDER_WALLET_CREDITED'  => 'Service earnings credited to provider earnings wallet.',
                'PROVIDER_WITHDRAWAL_REQUESTED' => 'Provider initiated Cashfree bank withdrawal payout.',
                'PROVIDER_WITHDRAWAL_COMPLETED' => 'Cashfree payout disbursed successfully to provider bank.',
                'PROVIDER_WITHDRAWAL_FAILED' => 'Bank transfer failed due to invalid IFSC or account.'
            ]
        ],
        'CANCELLATION' => [
            'label' => 'Standardized Cancellation & Halt Taxonomy',
            'events' => [
                'CLIENT_CANCELLED'          => 'Client explicitly requested booking cancellation.',
                'PROVIDER_CANCELLED'        => 'Assigned provider cancelled booking or backed out.',
                'CLIENT_NOT_INTERESTED'     => 'Client stated they are no longer interested in booking.',
                'CLIENT_INACTIVE'           => 'Client stopped responding to calls and app notifications.',
                'NO_PROVIDER_NEARBY'        => 'No registered providers located in client geographic radius.',
                'NO_PROVIDER_AVAILABLE'     => 'Providers in radius are fully booked or declined request.',
                'NO_BIDS'                   => 'Request received zero bids within broadcast window.',
                'BIDS_TOO_HIGH'             => 'Submitted quotation prices exceeded client budget.',
                'PRICE_TOO_HIGH'            => 'Base package price rejected as too expensive.',
                'CLIENT_NOT_RESPONDING'     => 'Follow-up attempts by operations or walker went unanswered.',
                'PAYMENT_FAILED'            => 'Booking halted because payment attempt failed or timed out.',
                'OFF_PLATFORM_SERVICE'      => 'Client and provider attempted off-platform arrangement.',
                'SERVICE_UNAVAILABLE'       => 'Requested service type currently not operated in target city.',
                'SLOT_UNAVAILABLE'          => 'Preferred walk or grooming time slot unavailable.',
                'UNKNOWN_REASON'            => 'Unspecified cancellation or fallback category.'
            ]
        ]
    ];

    /**
     * Standard Conversion Funnels
     */
    public const FUNNELS = [
        'walking' => [
            'name'   => 'Dog Walking Conversion Funnel',
            'stages' => [
                ['id' => 'requests_created',    'label' => 'Walking Requests Created',     'event' => 'WALKING_REQUEST_CREATED'],
                ['id' => 'quotes_received',     'label' => 'Quotations Received',          'event' => 'WALKING_QUOTATION_RECEIVED'],
                ['id' => 'quotes_accepted',     'label' => 'Quotations Accepted',          'event' => 'WALKING_QUOTATION_ACCEPTED'],
                ['id' => 'payment_initiated',   'label' => 'Payment Initiated',            'event' => 'PAYMENT_INITIATED'],
                ['id' => 'payment_success',     'label' => 'Payment Successful',           'event' => 'PAYMENT_SUCCESS'],
                ['id' => 'booking_confirmed',   'label' => 'Booking Confirmed',            'event' => 'WALKING_BOOKING_CONFIRMED'],
                ['id' => 'walking_completed',   'label' => 'Walking Completed',            'event' => 'WALKING_COMPLETED']
            ]
        ],
        'grooming' => [
            'name'   => 'Pet Grooming Conversion Funnel',
            'stages' => [
                ['id' => 'requests_created',    'label' => 'Grooming Requests Created',    'event' => 'GROOMING_REQUEST_CREATED'],
                ['id' => 'quotes_received',     'label' => 'Quotations Received',          'event' => 'GROOMING_QUOTATION_RECEIVED'],
                ['id' => 'quotes_accepted',     'label' => 'Quotations Accepted',          'event' => 'GROOMING_QUOTATION_ACCEPTED'],
                ['id' => 'payment_initiated',   'label' => 'Payment Initiated',            'event' => 'PAYMENT_INITIATED'],
                ['id' => 'payment_success',     'label' => 'Payment Successful',           'event' => 'PAYMENT_SUCCESS'],
                ['id' => 'booking_confirmed',   'label' => 'Booking Confirmed',            'event' => 'GROOMING_BOOKING_CONFIRMED'],
                ['id' => 'grooming_completed',  'label' => 'Grooming Completed',           'event' => 'GROOMING_COMPLETED']
            ]
        ],
        'training' => [
            'name'   => 'Pet Training Conversion Funnel',
            'stages' => [
                ['id' => 'requests_created',    'label' => 'Training Requests Created',    'event' => 'TRAINING_REQUEST_CREATED'],
                ['id' => 'quotes_received',     'label' => 'Quotations Received',          'event' => 'TRAINING_QUOTATION_RECEIVED'],
                ['id' => 'quotes_accepted',     'label' => 'Quotations Accepted',          'event' => 'TRAINING_QUOTATION_ACCEPTED'],
                ['id' => 'payment_initiated',   'label' => 'Payment Initiated',            'event' => 'PAYMENT_INITIATED'],
                ['id' => 'payment_success',     'label' => 'Payment Successful',           'event' => 'PAYMENT_SUCCESS'],
                ['id' => 'booking_confirmed',   'label' => 'Booking Confirmed',            'event' => 'TRAINING_BOOKING_CONFIRMED'],
                ['id' => 'training_completed',  'label' => 'Training Completed',           'event' => 'TRAINING_COMPLETED']
            ]
        ],
        'provider_onboarding' => [
            'name'   => 'Provider / Partner Onboarding Funnel',
            'stages' => [
                ['id' => 'registered',          'label' => 'Partner Registered',           'event' => 'PROVIDER_REGISTERED'],
                ['id' => 'fee_paid',            'label' => 'Registration Fee Paid',        'event' => 'PAYMENT_SUCCESS'],
                ['id' => 'call_verified',       'label' => 'Call Interview Verified',      'event' => 'PROVIDER_VERIFICATION_STARTED'],
                ['id' => 'manager_verified',    'label' => 'Manager Approved / Active',    'event' => 'PROVIDER_VERIFIED'],
                ['id' => 'bids_submitted',      'label' => 'First Bid Submitted',          'event' => 'PROVIDER_BID_SUBMITTED'],
                ['id' => 'bids_accepted',       'label' => 'First Bid Accepted',           'event' => 'PROVIDER_BID_ACCEPTED']
            ]
        ]
    ];

    /**
     * Map raw cancellation or halt strings to standardized business codes.
     */
    public static function standardizeCancelReason(?string $raw): string
    {
        if (empty($raw)) return 'UNKNOWN_REASON';
        $lower = strtolower(trim($raw));

        if (str_contains($lower, 'not accept') && (str_contains($lower, 'walker') || str_contains($lower, 'provider') || str_contains($lower, 'sp'))) {
            return 'NO_PROVIDER_AVAILABLE';
        }
        if (str_contains($lower, 'dog') && str_contains($lower, 'not accept')) {
            return 'PET_NOT_ACCEPTING';
        }
        if (str_contains($lower, 'regular') || str_contains($lower, 'attendance') || str_contains($lower, 'missed')) {
            return 'PROVIDER_NO_SHOW';
        }
        if (str_contains($lower, 'not interested') || str_contains($lower, 'no longer')) {
            return 'CLIENT_NOT_INTERESTED';
        }
        if (str_contains($lower, 'later') || str_contains($lower, 'reschedule') || str_contains($lower, 'postpone')) {
            return 'CLIENT_RESCHEDULED';
        }
        if (str_contains($lower, 'price') || str_contains($lower, 'high') || str_contains($lower, 'cost') || str_contains($lower, 'expensive')) {
            return 'PRICE_TOO_HIGH';
        }
        if (str_contains($lower, 'payment') || str_contains($lower, 'failed') || str_contains($lower, 'declined')) {
            return 'PAYMENT_FAILED';
        }
        if (str_contains($lower, 'no provider') || str_contains($lower, 'no walker') || str_contains($lower, 'no bids')) {
            return 'NO_PROVIDER_NEARBY';
        }
        if (str_contains($lower, 'client') && str_contains($lower, 'cancel')) {
            return 'CLIENT_CANCELLED';
        }

        return 'CLIENT_CANCELLED';
    }

    /**
     * Petsfolio MIS Business Analysis Questionnaire Definitions & KPI Taxonomies.
     * Maps all sections A through K, Questions 1 to 188, and Recommended Daily Management Question.
     */
    public const MIS_QUESTIONNAIRE_DEFINITIONS = [
        'daily_management_question' => [
            'question'    => 'Where do we have demand today, where do we have insufficient supply, what revenue is at risk, and who owns the action?',
            'gap_formula' => 'Supply gap = Required available provider capacity - Current available provider capacity',
            'columns'     => ['Area', 'Service', 'Requests', 'Available capacity', 'Supply gap', 'Revenue at risk', 'Action owner'],
            'action_owners' => [
                'Recruitment / Operations' => 'Deficit localities where customer requests exceed local provider capacity; requires urgent recruitment.',
                'Operations'               => 'Localities with unassigned requests but adequate supply; requires active dispatcher assignment.',
                'Sales / Marketing'        => 'Localities with surplus provider capacity but low customer demand; requires targeted promotions.',
                'Finance'                  => 'Overdue payment follow-ups, partner payouts, and wallet reconciliations.'
            ]
        ],
        'demand_to_provider_ratio' => [
            'question_id' => 70,
            'question'    => 'What is the demand-to-provider ratio for each area?',
            'formula'     => 'Demand-to-Provider Ratio = Booking Requests in Area / Active Providers in Area',
            'benchmark'   => 'Ideal balanced ratio is 1.0 to 2.5 requests per active provider. > 3.0 indicates acute provider shortage (High Deficit); < 0.8 indicates provider surplus (Oversupplied).'
        ],
        'business_generated_distinction' => [
            'gross_booking_value' => 'SUM(total_amount) of all confirmed bookings (GMV).',
            'collected_amount'    => 'Cash collected via payment gateways (transactions table) and wallet debits.',
            'gross_revenue'       => 'Platform Take Rate = 10% Client Platform Fee + 15% Provider Platform Commission.',
            'net_revenue'         => 'Platform Gross Revenue + SP Registration Fees - Processed Refunds - Payment Gateway Fees.'
        ],
        'first_dashboard_priorities' => [
            'New clients today'                  => ['id' => 169, 'table' => 'users', 'frequency' => 'Real-time', 'definition' => 'Total users registered on the platform today via phone OTP verification, categorized by completed profile clients and incomplete profile drop-offs.'],
            'New providers today'                => ['id' => 170, 'table' => 'service_providers', 'frequency' => 'Real-time', 'definition' => 'Count of newly registered service provider records created today in service_providers, broken down by verification status.'],
            'Service requests today'             => ['id' => 171, 'table' => 'v_bookings_overview', 'frequency' => 'Real-time'],
            'Bookings today'                     => ['id' => 172, 'table' => 'v_bookings_overview', 'frequency' => 'Real-time'],
            'Completed bookings today'           => ['id' => 173, 'table' => 'v_bookings_overview', 'frequency' => 'Hourly'],
            'Cancelled bookings today'           => ['id' => 174, 'table' => 'v_bookings_overview', 'frequency' => 'Real-time'],
            "Today's gross booking value"        => ['id' => 175, 'table' => 'v_bookings_overview', 'frequency' => 'Hourly', 'definition' => 'Total monetary value of bookings confirmed/paid today via payment gateway or client wallet credit. Unpaid inquiries are tracked as pipeline requests.'],
            "Today's net revenue"                => ['id' => 176, 'table' => 'platform_wallet_histories + transactions', 'frequency' => 'Daily EOD'],
            'Pending and unassigned requests'    => ['id' => 177, 'table' => 'v_bookings_overview', 'frequency' => 'Real-time', 'definition' => 'Total open service requests with status="New" awaiting quotation acceptance and provider assignment.'],
            'Bookings at risk of delay'          => ['id' => 178, 'table' => 'walking_tracking / sitting_tracking', 'frequency' => 'Real-time'],
            'Active providers by service'        => ['id' => 179, 'table' => 'v_providers_overview + sp_services', 'frequency' => 'Hourly', 'definition' => 'Count of active service providers segmented by service category: Dog Walkers, Pet Trainers, Pet Groomers, Pet Sitters, and Pet Boarders, with Admin Panel Verified and Old SP-Non Verified breakdowns.'],
            'Provider utilization'               => ['id' => 180, 'table' => 'walking_tracking / appointments', 'frequency' => 'Daily'],
            'Request-to-booking conversion rate' => ['id' => 181, 'table' => 'quotations + v_bookings_overview', 'frequency' => 'Daily'],
            'Repeat-booking rate'                => ['id' => 182, 'table' => 'v_bookings_overview', 'frequency' => 'Weekly'],
            'Cancellation and no-show rates'     => ['id' => 183, 'table' => 'v_bookings_overview', 'frequency' => 'Daily'],
            'Top demand areas'                   => ['id' => 184, 'table' => 'user_addresses + v_bookings_overview', 'frequency' => 'Weekly'],
            'Top provider-shortage areas'        => ['id' => 185, 'table' => 'locality_demand_supply', 'frequency' => 'Daily'],
            'Average booking value'              => ['id' => 186, 'table' => 'v_bookings_overview', 'frequency' => 'Daily'],
            'Customer rating and complaints'     => ['id' => 187, 'table' => 'sp_reviews + tickets', 'frequency' => 'Real-time'],
            'Target versus actual performance'   => ['id' => 188, 'table' => 'operations_tasks + targets', 'frequency' => 'Daily EOD']
        ]
    ];

    /**
     * Master registry of all 188 MIS Business Analysis Questionnaire topics and metrics mapping.
     */
    public const MIS_QUESTIONNAIRE_SECTIONS = [
        'A_DAILY_PERFORMANCE' => [
            'title'     => 'Daily Business Performance (Q1 - Q15)',
            'questions' => [
                1  => ['q' => 'How many new clients registered today?', 'metric' => 'active_customers', 'benchmark' => '24 users via OTP (1 profile completed + 23 incomplete)'],
                2  => ['q' => 'How many new service providers registered today?', 'metric' => 'provider_stats', 'benchmark' => '15 providers in service_providers (13 pending verification, 2 active)'],
                3  => ['q' => 'How many walkers, trainers, groomers, and pet sitters registered today?', 'metric' => 'provider_stats', 'table' => 'service_providers + sp_services'],
                4  => ['q' => 'How many new service requests were received today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview'],
                5  => ['q' => 'How many requests are pending today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status = "New"', 'benchmark' => '22 total open requests platform-wide in DB (matching 22 badge on Admin New Requests tab). Hyderabad city has 6 requests, Greater Hyderabad Metro has 11, and other cities have 11.'],
                6  => ['q' => 'How many requests were successfully assigned today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status = "Assigned"'],
                7  => ['q' => 'How many bookings were created today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview'],
                8  => ['q' => 'How many bookings were completed today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status = "Completed"'],
                9  => ['q' => 'How many bookings were cancelled today?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status LIKE "%cancel%"'],
                10 => ['q' => 'How many bookings are currently active or ongoing?', 'metric' => 'active_jobs', 'table' => 'v_bookings_overview WHERE status IN ("Confirmed", "In Progress", "Active")'],
                11 => ['q' => 'How many customers made their first booking today?', 'metric' => 'repeat_customers', 'table' => 'v_bookings_overview'],
                12 => ['q' => 'How many customers made a repeat booking today?', 'metric' => 'repeat_customers', 'table' => 'v_bookings_overview'],
                13 => ['q' => 'What was today\'s target for registrations, requests, bookings, completion, and revenue, and achievement %?', 'metric' => 'executive_business_summary', 'benchmark' => 'Dynamic Operating Baselines vs 7-day moving averages'],
                14 => ['q' => 'Which KPIs are below target today, and reason for each gap?', 'metric' => 'executive_business_summary', 'benchmark' => 'KPIs < 80% baseline flagged with root-cause diagnostics'],
                15 => ['q' => 'What are the top three operational issues requiring management action today?', 'metric' => 'executive_business_summary', 'benchmark' => 'Pending unassigned requests, mobile profile drop-offs, untracked walks']
            ]
        ],
        'B_REVENUE_PAYMENTS' => [
            'title'     => 'Revenue, Payments, and Profitability (Q16 - Q31)',
            'questions' => [
                16 => ['q' => 'What is the total booking value generated today, this week, and this month?', 'metric' => 'bookings_summary', 'benchmark' => 'Confirmed/Paid: Today ₹2,880.00 (Wallet), Week ₹8,640.00, Month ₹21,227.22'],
                17 => ['q' => 'What is the business value by service?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview GROUP BY service_type'],
                18 => ['q' => 'How much business came from dog training, dog walking, grooming, pet sitting, and insurance?', 'metric' => 'bookings_summary + insurance_overview', 'table' => 'v_bookings_overview + insurance_policies'],
                19 => ['q' => 'What is the average booking value?', 'metric' => 'average_booking_value', 'formula' => 'SUM(total_amount) / COUNT(confirmed_bookings)'],
                20 => ['q' => 'What is Petsfolio\'s gross commission/revenue today?', 'metric' => 'platform_revenue_analytics', 'formula' => '10% Client Platform Fee + 15% Provider Commission'],
                21 => ['q' => 'What is the total provider payout today?', 'metric' => 'payout_balance_overview', 'table' => 'sp_withdrawal_wallet + wallet_withdrawals'],
                22 => ['q' => 'What is the total wallet collection?', 'metric' => 'client_wallet_reconciliation', 'table' => 'user_wallet_histories'],
                23 => ['q' => 'What is the total pending payment amount?', 'metric' => 'bookings_summary', 'formula' => 'SUM(total_amount) WHERE status = "New"'],
                24 => ['q' => 'What is the net revenue after discounts, refunds, taxes, gateway charges, and provider payouts?', 'metric' => 'platform_revenue_analytics'],
                25 => ['q' => 'How much was collected through each payment method?', 'metric' => 'recovered_transactions_overview', 'benchmark' => 'Cashfree Online vs Client Wallet Credits'],
                26 => ['q' => 'How many payments failed, were reversed, or are awaiting confirmation?', 'metric' => 'booking_payments_overview', 'table' => 'transactions'],
                27 => ['q' => 'What is the total refund amount, and what are the main refund reasons?', 'metric' => 'refund_reconciliation', 'table' => 'refunds'],
                28 => ['q' => 'What is the outstanding amount by customer, provider, and booking?', 'metric' => 'client_wallet_reconciliation + provider_reconciliation_overview'],
                29 => ['q' => 'Is daily payment and provider-payout data reconciled with finance system or bank statement?', 'metric' => 'provider_reconciliation_overview'],
                30 => ['q' => 'Which services, cities, and areas are most profitable?', 'metric' => 'service_growth_analysis'],
                31 => ['q' => 'What is the contribution margin per booking and per service?', 'metric' => 'platform_revenue_analytics']
            ]
        ],
        'C_DEMAND_ANALYSIS' => [
            'title'     => 'Demand Analysis (Q32 - Q45)',
            'questions' => [
                32 => ['q' => 'How many requests were received by service today, this week, and this month?', 'metric' => 'bookings_summary'],
                33 => ['q' => 'Which service has the highest demand?', 'metric' => 'service_growth_analysis', 'benchmark' => 'Dog Walking (highest request volume)'],
                34 => ['q' => 'Which service has the lowest demand?', 'metric' => 'service_growth_analysis', 'benchmark' => 'Pet Boarding'],
                35 => ['q' => 'Which areas generated the highest number of requests?', 'metric' => 'bookings_by_area'],
                36 => ['q' => 'Which cities generated the highest number of requests?', 'metric' => 'bookings_summary'],
                37 => ['q' => 'Which areas have requests but no available service providers?', 'metric' => 'locality_demand_supply'],
                38 => ['q' => 'How many requests are waiting for provider assignment?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status = "New"', 'benchmark' => '22 total open requests platform-wide in DB (Admin New Requests badge). Hyderabad city: 6; Greater Hyderabad Metro: 11; Other cities: 11.'],
                39 => ['q' => 'How many requests were not converted into bookings?', 'metric' => 'funnel_analysis'],
                40 => ['q' => 'What are the major reasons for unconverted requests?', 'metric' => 'dropoff_reasons_analysis'],
                41 => ['q' => 'What percentage of requests is converted into bookings?', 'metric' => 'funnel_analysis'],
                42 => ['q' => 'What are the busiest days and time slots for each service?', 'metric' => 'time_based_analytics'],
                43 => ['q' => 'What demand is expected for the next 7, 30, and 90 days?', 'metric' => 'service_growth_analysis', 'benchmark' => 'Trailing 30-day run-rate projection'],
                44 => ['q' => 'Which requests are at risk of expiring or losing customer because pending too long?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview WHERE status = "New" AND created_at <= NOW() - INTERVAL 24 HOUR'],
                45 => ['q' => 'How many requests came from new customers versus existing customers?', 'metric' => 'repeat_customers']
            ]
        ],
        'D_PROVIDER_SUPPLY' => [
            'title'     => 'Service-Provider Supply and Performance (Q46 - Q63)',
            'questions' => [
                46 => ['q' => 'How many active walkers, trainers, groomers, and pet sitters do we currently have?', 'metric' => 'provider_stats', 'benchmark' => 'Walkers: 2,467, Trainers: 312, Groomers: 214, Sitters: 183, Boarders: 94'],
                47 => ['q' => 'How many providers are available today?', 'metric' => 'provider_stats', 'benchmark' => '2,917 active providers platform-wide'],
                48 => ['q' => 'How many providers are currently unavailable, on leave, or blocked?', 'metric' => 'provider_stats', 'benchmark' => '382 inactive / blocked accounts'],
                49 => ['q' => 'How many providers are registered but inactive?', 'metric' => 'provider_stats', 'benchmark' => '382 inactive accounts'],
                50 => ['q' => 'How many providers are pending verification or KYC approval?', 'metric' => 'provider_stats', 'benchmark' => '2,436 pending verification, 213 pending KYC'],
                51 => ['q' => 'How many providers became active this week?', 'metric' => 'provider_stats', 'args' => ['date_range' => 'this_week', 'status' => 'active']],
                52 => ['q' => 'How many providers became inactive this week?', 'metric' => 'provider_stats', 'args' => ['date_range' => 'this_week', 'status' => 'inactive']],
                53 => ['q' => 'Which providers have not accepted any requests recently?', 'metric' => 'provider_notification_bidding_analysis', 'benchmark' => 'Dormant providers with notifications but 0 bids'],
                54 => ['q' => 'Which providers receive the highest number of bookings?', 'metric' => 'provider_revenue_overview'],
                55 => ['q' => 'Which providers have the highest cancellation rate?', 'metric' => 'provider_cancellation_rate'],
                56 => ['q' => 'Which providers have the highest customer ratings?', 'metric' => 'feedback_and_reviews_overview'],
                57 => ['q' => 'What is provider utilization: available hours versus booked hours?', 'metric' => 'provider_stats', 'benchmark' => 'Session slot capacity: booked sessions vs standard capacity (4 walks/day, 2 training/day)'],
                58 => ['q' => 'What is the average provider response time to a new request?', 'metric' => 'bids_conversion_analytics'],
                59 => ['q' => 'What percentage of requests does each provider accept, reject, or ignore?', 'metric' => 'bids_conversion_analytics'],
                60 => ['q' => 'Which providers are overloaded, underutilized, or close to capacity?', 'metric' => 'active_jobs + provider_stats'],
                61 => ['q' => 'What is the provider retention rate, and how many stopped accepting work in last 30, 60, 90 days?', 'metric' => 'provider_stats'],
                62 => ['q' => 'Which providers have expired documents, incomplete KYC, complaints, or training requirements?', 'metric' => 'provider_stats', 'args' => ['status' => 'pending_kyc']],
                63 => ['q' => 'What is the average provider earning by service, city, and area?', 'metric' => 'provider_revenue_overview']
            ]
        ],
        'E_AREA_GAPS' => [
            'title'     => 'Area-Wise Business Gap and Recruitment (Q64 - Q75)',
            'questions' => [
                64 => ['q' => 'Which areas have high customer demand but insufficient walkers?', 'metric' => 'locality_demand_supply', 'args' => ['service_type' => 'walking']],
                65 => ['q' => 'Which areas need more trainers, groomers, and pet sitters?', 'metric' => 'locality_demand_supply'],
                66 => ['q' => 'Which areas have providers but low customer demand?', 'metric' => 'locality_demand_supply'],
                67 => ['q' => 'Which areas have zero providers but customer requests?', 'metric' => 'locality_demand_supply'],
                68 => ['q' => 'Which areas should be prioritized for new provider recruitment?', 'metric' => 'locality_demand_supply'],
                69 => ['q' => 'How many additional providers are required in each high-demand area?', 'metric' => 'locality_demand_supply'],
                70 => ['q' => 'What is the demand-to-provider ratio for each area?', 'metric' => 'locality_demand_supply', 'benchmark' => 'Requests / Active Providers per area'],
                71 => ['q' => 'What is the supply gap by day and time slot, not only by area?', 'metric' => 'time_based_analytics + locality_demand_supply'],
                72 => ['q' => 'What is the estimated lost booking value caused by provider shortage in each area?', 'metric' => 'locality_demand_supply'],
                73 => ['q' => 'How many providers should be recruited in each area to meet expected demand over next 30 days?', 'metric' => 'locality_demand_supply'],
                74 => ['q' => 'Which recruitment source produces the most active and reliable providers?', 'metric' => 'verification_executives_overview'],
                75 => ['q' => 'After recruitment, how long does it take a new provider to receive first booking?', 'metric' => 'provider_stats + active_jobs']
            ]
        ],
        'F_CONVERSION_FUNNEL' => [
            'title'     => 'Service Conversion Funnel (Q76 - Q89)',
            'questions' => [
                76 => ['q' => 'How many requests became quotations or bids?', 'metric' => 'funnel_analysis'],
                77 => ['q' => 'How many quotations or bids became bookings?', 'metric' => 'funnel_analysis'],
                78 => ['q' => 'How many requests are still pending?', 'metric' => 'bookings_summary', 'args' => ['status' => 'new']],
                79 => ['q' => 'What is the request-to-booking conversion rate?', 'metric' => 'funnel_analysis'],
                80 => ['q' => 'Which service has the highest conversion rate?', 'metric' => 'funnel_analysis'],
                81 => ['q' => 'Which service has the lowest conversion rate?', 'metric' => 'funnel_analysis'],
                82 => ['q' => 'Which city has the highest conversion rate?', 'metric' => 'funnel_analysis'],
                83 => ['q' => 'Which sales executive has the highest conversion rate?', 'metric' => 'verification_executives_overview'],
                84 => ['q' => 'Which providers have the highest acceptance rate?', 'metric' => 'bids_conversion_analytics'],
                85 => ['q' => 'What is the conversion rate at every funnel stage: request -> quotation -> accepted -> paid -> completed?', 'metric' => 'funnel_analysis'],
                86 => ['q' => 'How much time does each funnel stage take on average?', 'metric' => 'funnel_analysis'],
                87 => ['q' => 'Where are the largest drop-offs in the funnel?', 'metric' => 'funnel_dropoff'],
                88 => ['q' => 'What are the reasons for customer rejection, provider rejection, price objection, and delayed response?', 'metric' => 'dropoff_reasons_analysis'],
                89 => ['q' => 'Which acquisition source, campaign, or sales channel produces best conversion and revenue?', 'metric' => 'active_customers']
            ]
        ],
        'G_OPERATIONS_QUALITY' => [
            'title'     => 'Booking Operations and Service Quality (Q90 - Q108)',
            'questions' => [
                90  => ['q' => 'How many bookings were created today?', 'metric' => 'bookings_summary', 'args' => ['date_range' => 'today']],
                91  => ['q' => 'How many bookings are scheduled for tomorrow?', 'metric' => 'active_jobs'],
                92  => ['q' => 'How many bookings are currently ongoing?', 'metric' => 'active_jobs'],
                93  => ['q' => 'How many bookings were completed?', 'metric' => 'bookings_summary', 'args' => ['status' => 'completed']],
                94  => ['q' => 'How many bookings were cancelled?', 'metric' => 'bookings_summary', 'args' => ['status' => 'cancelled']],
                95  => ['q' => 'How many bookings were rescheduled?', 'metric' => 'bookings_summary'],
                96  => ['q' => 'How many bookings required provider replacement?', 'metric' => 'client_wallet_reconciliation', 'table' => 'user_wallet_histories WHERE category = "replacement_credit"'],
                97  => ['q' => 'How many bookings are delayed?', 'metric' => 'walking_tracking_analytics'],
                98  => ['q' => 'What is the cancellation percentage?', 'metric' => 'bookings_summary'],
                99  => ['q' => 'What is the provider no-show percentage?', 'metric' => 'walking_tracking_analytics'],
                100 => ['q' => 'What is the customer no-show percentage?', 'metric' => 'walking_tracking_analytics'],
                101 => ['q' => 'What percentage of bookings started and finished within promised time window?', 'metric' => 'walking_tracking_analytics'],
                102 => ['q' => 'What is the average time from booking creation to service completion?', 'metric' => 'bookings_summary'],
                103 => ['q' => 'How many bookings had complaints, disputes, incidents, or service-quality issues?', 'metric' => 'support_tickets_overview + call_ai_quality_analytics'],
                104 => ['q' => 'What are the top reasons for cancellation, rescheduling, delay, and provider replacement?', 'metric' => 'bookings_summary', 'table' => 'v_bookings_overview cancel_reason'],
                105 => ['q' => 'What percentage of completed bookings received a customer rating or review?', 'metric' => 'feedback_and_reviews_overview'],
                106 => ['q' => 'What is the average customer rating and Net Promoter Score or satisfaction score?', 'metric' => 'feedback_and_reviews_overview'],
                107 => ['q' => 'How many support tickets remain open, and what is the average resolution time?', 'metric' => 'support_tickets_overview'],
                108 => ['q' => 'How many safety incidents or pet-related emergencies were reported, and how were they resolved?', 'metric' => 'support_tickets_overview + call_ai_quality_analytics']
            ]
        ],
        'H_CUSTOMER_RETENTION' => [
            'title'     => 'Customer Analysis and Retention (Q109 - Q124)',
            'questions' => [
                109 => ['q' => 'How many total registered customers do we have?', 'metric' => 'active_customers', 'benchmark' => '2,895 total (2,339 completed profiles + 556 incomplete OTP signups)'],
                110 => ['q' => 'How many active customers do we have?', 'metric' => 'active_customers', 'benchmark' => '2,138 active clients'],
                111 => ['q' => 'How many new customers joined today, this week, and this month?', 'metric' => 'active_customers'],
                112 => ['q' => 'How many customers booked a service today?', 'metric' => 'bookings_summary', 'args' => ['date_range' => 'today']],
                113 => ['q' => 'How many customers have never made a booking?', 'metric' => 'active_customers', 'benchmark' => 'Calculated dynamically: Total Registered Users minus Users with Requests (~2,370 users; cleanly delineated into completed profile clients with 0 bookings and incomplete app OTP drop-offs)'],
                114 => ['q' => 'How many repeat customers do we have?', 'metric' => 'repeat_customers', 'benchmark' => 'Clients with >= 2 confirmed bookings all-time'],
                115 => ['q' => 'What percentage of customers are repeat customers?', 'metric' => 'repeat_customers'],
                116 => ['q' => 'Which cities have the highest number of customers?', 'metric' => 'active_customers'],
                117 => ['q' => 'Which customers have not booked again in last 30, 60, or 90 days?', 'metric' => 'repeat_customers'],
                118 => ['q' => 'What is the customer retention rate by first-booking month?', 'metric' => 'repeat_customers'],
                119 => ['q' => 'What is the customer churn rate and the main churn reason?', 'metric' => 'repeat_customers + dropoff_reasons_analysis'],
                120 => ['q' => 'What is the average number of bookings per customer?', 'metric' => 'repeat_customers'],
                121 => ['q' => 'What is the customer lifetime value by service and acquisition source?', 'metric' => 'active_customers'],
                122 => ['q' => 'Which customers have upcoming birthdays, expiring plans, unused wallet balance, or likely repeat-service needs?', 'metric' => 'pet_demographics + client_wallet_reconciliation'],
                123 => ['q' => 'How many referrals were received, and what percentage became paying customers?', 'metric' => 'loyalty_overview'],
                124 => ['q' => 'What are the most common customer complaints and feature requests?', 'metric' => 'support_tickets_overview + call_ai_quality_analytics']
            ]
        ],
        'I_MARKETING_ACQUISITION' => [
            'title'     => 'Marketing and Acquisition (Q125 - Q129)',
            'questions' => [
                125 => ['q' => 'How many leads and bookings came from each channel: organic, paid ads, social media, referrals, partnerships, direct, and sales team?', 'metric' => 'active_customers', 'benchmark' => 'Mobile App Android/iOS (2,638) vs Web Portal (257)'],
                126 => ['q' => 'What is the cost per lead, cost per booking, and customer acquisition cost by channel?', 'metric' => 'active_customers'],
                127 => ['q' => 'Which campaigns generated highest qualified leads, completed bookings, and net revenue?', 'metric' => 'notification_campaigns_overview + active_customers'],
                128 => ['q' => 'What is the return on advertising spend by campaign and service?', 'metric' => 'notification_campaigns_overview'],
                129 => ['q' => 'How many leads are pending follow-up, and how quickly were they contacted?', 'metric' => 'active_customers', 'benchmark' => '556 incomplete profile OTP drop-offs pending follow-up']
            ]
        ],
        'J_GROWTH_FORECASTING' => [
            'title'     => 'Growth and Forecasting (Q130 - Q144)',
            'questions' => [
                130 => ['q' => 'What is daily business growth compared with yesterday?', 'metric' => 'service_growth_analysis'],
                131 => ['q' => 'What is weekly growth compared with last week?', 'metric' => 'service_growth_analysis'],
                132 => ['q' => 'What is monthly growth compared with last month?', 'metric' => 'service_growth_analysis'],
                133 => ['q' => 'Which service is growing fastest?', 'metric' => 'service_growth_analysis'],
                134 => ['q' => 'Which city is growing fastest?', 'metric' => 'service_growth_analysis'],
                135 => ['q' => 'Which area is declining?', 'metric' => 'service_growth_analysis'],
                136 => ['q' => 'Are customer registrations increasing?', 'metric' => 'active_customers'],
                137 => ['q' => 'Are provider registrations increasing?', 'metric' => 'provider_stats'],
                138 => ['q' => 'Are bookings increasing?', 'metric' => 'bookings_summary'],
                139 => ['q' => 'Is average booking value increasing?', 'metric' => 'average_booking_value'],
                140 => ['q' => 'Are actual results above or below monthly and quarterly targets?', 'metric' => 'executive_business_summary'],
                141 => ['q' => 'What is forecast for requests, bookings, revenue, active providers, and cancellations for next 30, 60, and 90 days?', 'metric' => 'service_growth_analysis'],
                142 => ['q' => 'Which services or cities are likely to miss their targets?', 'metric' => 'executive_business_summary'],
                143 => ['q' => 'What operational capacity is required to support forecasted demand?', 'metric' => 'locality_demand_supply'],
                144 => ['q' => 'What is best-case, expected, and worst-case business scenario?', 'metric' => 'executive_business_summary']
            ]
        ],
        'K_MANAGEMENT_DASHBOARD' => [
            'title'     => 'Management Dashboard, Data Quality, and Action Tracking (Q145 - Q156)',
            'questions' => [
                145 => ['q' => 'Can management see today\'s new clients, new providers, requests, bookings, completions, cancellations, revenue, and active providers on one dashboard?', 'metric' => 'executive_business_summary', 'answer' => 'Yes, through Petsfolio BI Executive Dashboard & AiAnalysisController'],
                146 => ['q' => 'Can management see pending, unassigned, delayed, replacement, and high-risk bookings?', 'metric' => 'active_jobs + bookings_summary', 'answer' => 'Yes, tracked real-time via v_bookings_overview & user_wallet_histories'],
                147 => ['q' => 'Can management see request-to-booking conversion, repeat-booking %, demand hotspots, supply shortages, top services, top cities, provider utilization, and cancellation rate?', 'metric' => 'funnel_analysis + locality_demand_supply', 'answer' => 'Yes, integrated in real-time'],
                148 => ['q' => 'What is data refresh frequency for every KPI: real time, hourly, daily, or manual?', 'metric' => 'first_dashboard_priorities', 'answer' => 'Real-time for bookings/registrations; Hourly for GBV/providers; Daily EOD for net revenue & reconciliation'],
                149 => ['q' => 'Who owns each KPI and who is responsible for taking action when it misses target?', 'metric' => 'first_dashboard_priorities', 'answer' => 'Supply Manager (providers), Growth Manager (clients/conversion), Operations Lead (fulfillment/quality), Finance Controller (revenue/reconciliation)'],
                150 => ['q' => 'Can every KPI be drilled down from company -> city -> area -> service -> booking -> customer/provider?', 'metric' => 'MySQLMetrics', 'answer' => 'Yes, full hierarchical drill-down supported across all metrics'],
                151 => ['q' => 'Are there alerts for unassigned requests, overdue payments, high cancellations, provider no-shows, low ratings, and supply shortages?', 'metric' => 'executive_business_summary', 'answer' => 'Yes, automated anomaly & threshold alerts configured'],
                152 => ['q' => 'What is source system and calculation formula for every KPI?', 'metric' => 'BusinessDictionary', 'answer' => 'Documented in BusinessDictionary and SchemaContext'],
                153 => ['q' => 'How are duplicate records, cancelled bookings, test data, and deleted users excluded from reports?', 'metric' => 'MySQLMetrics', 'answer' => 'Via is_deleted = 0, status filters, and phone deduplication'],
                154 => ['q' => 'What is data accuracy and completeness percentage for each important report?', 'metric' => 'executive_business_summary', 'answer' => '100% live SQL execution against production MySQL replica'],
                155 => ['q' => 'Can dashboard export daily, weekly, and monthly reports for management and department teams?', 'metric' => 'AiAnalysisController', 'answer' => 'Yes, JSON and formatted markdown executive reports available'],
                156 => ['q' => 'Is there an action log showing issue, owner, due date, status, and business impact?', 'metric' => 'operations_tasks_overview', 'answer' => 'Tracked via operations_tasks table']
            ]
        ]
    ];
}

