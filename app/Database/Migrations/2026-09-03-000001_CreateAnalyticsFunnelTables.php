<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnalyticsFunnelTables extends Migration
{
    public function up()
    {
        // 1. analytics_events
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unknown', // android, ios, web
            ],
            'device' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'app_version' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'screen_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'service_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'provider_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'campaign_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'is_new_user' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'metadata' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('session_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('event_name');
        $this->forge->addKey('screen_name');
        $this->forge->addKey('platform');
        $this->forge->addKey('service_id');
        $this->forge->addKey('city');
        $this->forge->addKey('created_at');
        $this->forge->createTable('analytics_events', true);

        // 2. analytics_funnels (Configurable funnels)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'funnel_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'customer',
            ],
            'steps' => [
                'type' => 'LONGTEXT', // JSON array of step definitions
            ],
            'inactivity_window_minutes' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 30,
            ],
            'is_system' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('category');
        $this->forge->createTable('analytics_funnels', true);

        // 3. analytics_sessions (Session summary for fast aggregation)
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'unique'     => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unknown',
            ],
            'device' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'app_version' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'is_new_user' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_event_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'event_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'last_event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'last_screen_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'highest_funnel_step' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'has_dropped' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'dropoff_step' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('platform');
        $this->forge->addKey('started_at');
        $this->forge->addKey('dropoff_step');
        $this->forge->createTable('analytics_sessions', true);

        // Seed default funnels
        $this->seedDefaultFunnels();
    }

    public function down()
    {
        $this->forge->dropTable('analytics_events', true);
        $this->forge->dropTable('analytics_funnels', true);
        $this->forge->dropTable('analytics_sessions', true);
    }

    protected function seedDefaultFunnels()
    {
        $db = \Config\Database::connect();
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
}
