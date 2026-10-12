<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsFunnelModel extends Model
{
    protected $table            = 'analytics_funnels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'funnel_key',
        'name',
        'description',
        'category',
        'steps',
        'inactivity_window_minutes',
        'is_system',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ensure database tables exist automatically
     */
    public function ensureTables()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('analytics_events')) {
            $this->createEventsTable($forge);
        }

        if (!$db->tableExists('analytics_funnels')) {
            $this->createFunnelsTable($forge);
            $this->seedDefaultFunnels($db);
        } elseif ($db->table('analytics_funnels')->countAllResults() === 0) {
            $this->seedDefaultFunnels($db);
        }

        if (!$db->tableExists('analytics_sessions')) {
            $this->createSessionsTable($forge);
        }
    }

    protected function createEventsTable($forge)
    {
        $forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 128],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'platform' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'unknown'],
            'device' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'app_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'event_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'screen_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'booking_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'service_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'service_category' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provider_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'campaign_source' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'is_new_user' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'metadata' => ['type' => 'LONGTEXT', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $forge->addKey('id', true);
        $forge->addKey('session_id');
        $forge->addKey('user_id');
        $forge->addKey('event_name');
        $forge->addKey('screen_name');
        $forge->addKey('platform');
        $forge->addKey('service_id');
        $forge->addKey('city');
        $forge->addKey('created_at');
        $forge->createTable('analytics_events', true);
    }

    protected function createFunnelsTable($forge)
    {
        $forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'funnel_key' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'customer'],
            'steps' => ['type' => 'LONGTEXT'],
            'inactivity_window_minutes' => ['type' => 'INT', 'constraint' => 11, 'default' => 30],
            'is_system' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $forge->addKey('id', true);
        $forge->addKey('category');
        $forge->createTable('analytics_funnels', true);
    }

    protected function createSessionsTable($forge)
    {
        $forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 128, 'unique' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'platform' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'unknown'],
            'device' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'app_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'is_new_user' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'started_at' => ['type' => 'DATETIME', 'null' => true],
            'last_event_at' => ['type' => 'DATETIME', 'null' => true],
            'event_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'last_event_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'last_screen_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'highest_funnel_step' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'has_dropped' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'dropoff_step' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $forge->addKey('id', true);
        $forge->addKey('user_id');
        $forge->addKey('platform');
        $forge->addKey('started_at');
        $forge->addKey('dropoff_step');
        $forge->createTable('analytics_sessions', true);
    }

    protected function seedDefaultFunnels($db)
    {
        $now = date('Y-m-d H:i:s');
        $funnels = [
            [
                'funnel_key'  => 'customer_booking',
                'name'        => 'Customer Booking Journey',
                'description' => 'Complete customer registration to booking confirmation lifecycle funnel.',
                'category'    => 'customer',
                'inactivity_window_minutes' => 30,
                'is_system'   => 1,
                'is_active'   => 1,
                'steps'       => json_encode([
                    ['step_key' => 'step_1',  'name' => 'Registration Started',    'event_name' => 'registration_started',     'screen' => 'RegistrationScreen', 'icon' => 'fas fa-user-plus'],
                    ['step_key' => 'step_2',  'name' => 'OTP Verified',            'event_name' => 'otp_verified',             'screen' => 'OtpVerificationScreen', 'icon' => 'fas fa-shield-alt'],
                    ['step_key' => 'step_3',  'name' => 'Profile Completed',        'event_name' => 'profile_completed',        'screen' => 'ProfileSetupScreen', 'icon' => 'fas fa-id-card'],
                    ['step_key' => 'step_4',  'name' => 'Pet Added',                'event_name' => 'pet_added',                'screen' => 'AddPetScreen', 'icon' => 'fas fa-paw'],
                    ['step_key' => 'step_5',  'name' => 'Service Browsed',          'event_name' => 'service_list_viewed',      'screen' => 'ServicesListScreen', 'icon' => 'fas fa-th-list'],
                    ['step_key' => 'step_6',  'name' => 'Service Selected',         'event_name' => 'service_selected',         'screen' => 'ServiceDetailScreen', 'icon' => 'fas fa-check-circle'],
                    ['step_key' => 'step_7',  'name' => 'Provider Selected',        'event_name' => 'provider_selected',        'screen' => 'ProviderSelectScreen', 'icon' => 'fas fa-user-tie'],
                    ['step_key' => 'step_8',  'name' => 'Booking Details',          'event_name' => 'booking_details_completed','screen' => 'BookingFormScreen', 'icon' => 'fas fa-file-signature'],
                    ['step_key' => 'step_9',  'name' => 'Checkout Viewed',          'event_name' => 'checkout_viewed',          'screen' => 'CheckoutScreen', 'icon' => 'fas fa-shopping-cart'],
                    ['step_key' => 'step_10', 'name' => 'Payment Initiated',        'event_name' => 'payment_initiated',        'screen' => 'PaymentGatewayScreen', 'icon' => 'fas fa-credit-card'],
                    ['step_key' => 'step_11', 'name' => 'Payment Successful',       'event_name' => 'payment_success',          'screen' => 'PaymentSuccessScreen', 'icon' => 'fas fa-receipt'],
                    ['step_key' => 'step_12', 'name' => 'Booking Confirmed',        'event_name' => 'booking_confirmed',        'screen' => 'OrderSuccessScreen', 'icon' => 'fas fa-calendar-check'],
                ]),
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'funnel_key'  => 'quick_booking',
                'name'        => 'Quick Booking (Existing Pet Owners)',
                'description' => 'Fast-track booking flow for existing users who already have profiles and pets.',
                'category'    => 'customer',
                'inactivity_window_minutes' => 30,
                'is_system'   => 1,
                'is_active'   => 1,
                'steps'       => json_encode([
                    ['step_key' => 'step_1', 'name' => 'Service Browsed',       'event_name' => 'service_list_viewed',       'screen' => 'ServicesListScreen', 'icon' => 'fas fa-th-list'],
                    ['step_key' => 'step_2', 'name' => 'Service Selected',      'event_name' => 'service_selected',          'screen' => 'ServiceDetailScreen', 'icon' => 'fas fa-check-circle'],
                    ['step_key' => 'step_3', 'name' => 'Booking Details Done',  'event_name' => 'booking_details_completed', 'screen' => 'BookingFormScreen', 'icon' => 'fas fa-file-signature'],
                    ['step_key' => 'step_4', 'name' => 'Checkout Viewed',       'event_name' => 'checkout_viewed',           'screen' => 'CheckoutScreen', 'icon' => 'fas fa-shopping-cart'],
                    ['step_key' => 'step_5', 'name' => 'Payment Initiated',     'event_name' => 'payment_initiated',         'screen' => 'PaymentGatewayScreen', 'icon' => 'fas fa-credit-card'],
                    ['step_key' => 'step_6', 'name' => 'Payment Successful',    'event_name' => 'payment_success',           'screen' => 'PaymentSuccessScreen', 'icon' => 'fas fa-receipt'],
                    ['step_key' => 'step_7', 'name' => 'Booking Confirmed',     'event_name' => 'booking_confirmed',         'screen' => 'OrderSuccessScreen', 'icon' => 'fas fa-calendar-check'],
                ]),
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'funnel_key'  => 'provider_onboarding',
                'name'        => 'Provider Onboarding Funnel',
                'description' => 'Service Provider verification and onboarding steps from signup to first active walk/service.',
                'category'    => 'provider',
                'inactivity_window_minutes' => 60,
                'is_system'   => 1,
                'is_active'   => 1,
                'steps'       => json_encode([
                    ['step_key' => 'step_1', 'name' => 'SP Signup Started',     'event_name' => 'sp_registration_started',   'screen' => 'SPRegisterScreen', 'icon' => 'fas fa-user-plus'],
                    ['step_key' => 'step_2', 'name' => 'Phone OTP Verified',    'event_name' => 'sp_otp_verified',           'screen' => 'SPOtpScreen', 'icon' => 'fas fa-phone-alt'],
                    ['step_key' => 'step_3', 'name' => 'Profile Details Saved', 'event_name' => 'sp_profile_completed',      'screen' => 'SPProfileScreen', 'icon' => 'fas fa-user-edit'],
                    ['step_key' => 'step_4', 'name' => 'Service Area Selected', 'event_name' => 'sp_service_selected',       'screen' => 'SPAreaSelectScreen', 'icon' => 'fas fa-map-marked-alt'],
                    ['step_key' => 'step_5', 'name' => 'KYC / Aadhar Uploaded', 'event_name' => 'sp_kyc_submitted',         'screen' => 'SPKycUploadScreen', 'icon' => 'fas fa-id-card-alt'],
                    ['step_key' => 'step_6', 'name' => 'Verification Passed',   'event_name' => 'sp_verification_passed',   'screen' => 'SPVerifyScreen', 'icon' => 'fas fa-user-check'],
                    ['step_key' => 'step_7', 'name' => 'SP Activated & Live',   'event_name' => 'sp_activated',             'screen' => 'SPDashboardScreen', 'icon' => 'fas fa-star'],
                ]),
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'funnel_key'  => 'repeat_customer',
                'name'        => 'Repeat Customer Funnel',
                'description' => 'Re-engagement and repeat booking conversion of existing pet parents.',
                'category'    => 'customer',
                'inactivity_window_minutes' => 30,
                'is_system'   => 1,
                'is_active'   => 1,
                'steps'       => json_encode([
                    ['step_key' => 'step_1', 'name' => 'App Re-opened',         'event_name' => 'app_reopened',              'screen' => 'HomeScreen', 'icon' => 'fas fa-mobile-alt'],
                    ['step_key' => 'step_2', 'name' => 'Past Service Viewed',   'event_name' => 'past_service_viewed',       'screen' => 'PastOrdersScreen', 'icon' => 'fas fa-history'],
                    ['step_key' => 'step_3', 'name' => 'Rebook Clicked',        'event_name' => 'book_again_clicked',        'screen' => 'BookingFormScreen', 'icon' => 'fas fa-redo'],
                    ['step_key' => 'step_4', 'name' => 'Checkout Reached',      'event_name' => 'checkout_viewed',           'screen' => 'CheckoutScreen', 'icon' => 'fas fa-shopping-cart'],
                    ['step_key' => 'step_5', 'name' => 'Payment Success',       'event_name' => 'payment_success',           'screen' => 'PaymentSuccessScreen', 'icon' => 'fas fa-check-double'],
                ]),
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($funnels as $f) {
            $existing = $db->table('analytics_funnels')->where('funnel_key', $f['funnel_key'])->get()->getRow();
            if (!$existing) {
                $db->table('analytics_funnels')->insert($f);
            }
        }
    }

    /**
     * Get all active funnels
     */
    public function getActiveFunnels()
    {
        $this->ensureTables();
        return $this->where('is_active', 1)->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Get a specific funnel by key
     */
    public function getByKey(string $funnelKey)
    {
        $this->ensureTables();
        return $this->where('funnel_key', $funnelKey)->first();
    }

    /**
     * Get default funnel (customer_booking or first active)
     */
    public function getDefaultFunnel()
    {
        $this->ensureTables();
        $default = $this->where('funnel_key', 'customer_booking')->where('is_active', 1)->first();
        if (!$default) {
            $default = $this->where('is_active', 1)->orderBy('id', 'ASC')->first();
        }
        return $default;
    }
}
