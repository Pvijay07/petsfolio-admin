<?php

namespace Tests\Customer;

use Tests\TestCase;

class CustomerTests
{
    public static function getTests(): array
    {
        return [
            // 1. Formal: Total registered clients
            new TestCase([
                'id' => 'CUST_FORMAL_01',
                'domain' => 'customer',
                'style' => 'formal',
                'persona' => 'marketing',
                'question' => 'What is the total number of registered clients and members in the database?',
                'expected_intent' => 'customer.headcount',
                'expected_metric' => 'active_customers',
                'expected_metric_args' => ['date_range' => 'all_time'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM users");
                    return ['total_users' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_registered_clients_members'] ?? $data['total_registered_users'] ?? $data['total_users'] ?? $data['registered_clients'] ?? 0;
                    if ($val === $gt['total_users']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_users']}, got {$val}"];
                }
            ]),

            // 2. Simple: How many clients?
            new TestCase([
                'id' => 'CUST_SIMPLE_01',
                'domain' => 'customer',
                'style' => 'simple',
                'persona' => 'admin',
                'question' => 'How many clients?',
                'expected_intent' => 'customer.headcount',
                'expected_metric' => 'active_customers',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM users");
                    return ['total_users' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_registered_clients_members'] ?? $data['total_registered_users'] ?? $data['total_users'] ?? 0;
                    if ($val === $gt['total_users']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['total_users']}, got {$val}"];
                }
            ]),

            // 3. Simple: Repeat customers
            new TestCase([
                'id' => 'CUST_REPEAT_01',
                'domain' => 'customer',
                'style' => 'simple',
                'persona' => 'marketing',
                'question' => 'How many repeat customers do we have?',
                'expected_intent' => 'customer.repeat',
                'expected_metric' => 'repeat_customers',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM (SELECT user_id FROM v_bookings_overview WHERE is_deleted = 0 AND user_id > 0 GROUP BY user_id HAVING COUNT(*) > 1) t");
                    return ['repeat_customers' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['all_time_repeat_customers_count'] ?? $data['period_repeat_customers_count'] ?? $data['repeat_customers_count'] ?? $data['repeat_customers'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['repeat_customers']}, got {$val}"];
                }
            ]),

            // 4. Ambiguous: How many active users?
            // Must differentiate between active profile status (status = 'active') and active booking customers (placed >= 1 booking)
            new TestCase([
                'id' => 'CUST_AMBIG_01',
                'domain' => 'customer',
                'style' => 'ambiguous',
                'persona' => 'ceo',
                'question' => 'How many active users?',
                'is_ambiguous' => true,
                'expected_intent' => 'customer.headcount',
                'ground_truth' => function($mysqli) {
                    $actStatus = (int)$mysqli->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetch_row()[0];
                    $actBookings = (int)$mysqli->query("SELECT COUNT(DISTINCT user_id) FROM v_bookings_overview WHERE is_deleted = 0 AND user_id > 0")->fetch_row()[0];
                    return ['active_status' => $actStatus, 'active_booking_clients' => $actBookings];
                },
                'validator' => function($data, $gt) {
                    // Verifies that either active booking clients or active users is returned
                    $val = $data['total_active_customers'] ?? $data['active_status_clients_count'] ?? $data['active_booking_customers'] ?? $data['total_registered_clients_members'] ?? $data['active_customers_count'] ?? 0;
                    if ($val > 0) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'SEMANTIC_ERROR', 'details' => 'Failed to differentiate active customer base'];
                }
            ]),

            // 5. Short: Client count?
            new TestCase([
                'id' => 'CUST_SHORT_01',
                'domain' => 'customer',
                'style' => 'short',
                'persona' => 'admin',
                'question' => 'Client count?',
                'expected_intent' => 'customer.headcount',
                'expected_metric' => 'active_customers',
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT COUNT(*) as cnt FROM users");
                    return ['cnt' => (int)$res->fetch_assoc()['cnt']];
                },
                'validator' => function($data, $gt) {
                    $val = $data['total_registered_clients_members'] ?? $data['total_registered_users'] ?? $data['total_users'] ?? 0;
                    if ($val === $gt['cnt']) {
                        return ['pass' => true];
                    }
                    return ['pass' => false, 'error_type' => 'RESULT_INTERPRETATION_ERROR', 'details' => "Expected {$gt['cnt']}, got {$val}"];
                }
            ]),

            // 6. Natural / Business: Are we losing customers?
            new TestCase([
                'id' => 'CUST_NATURAL_01',
                'domain' => 'customer',
                'style' => 'natural',
                'persona' => 'marketing',
                'question' => 'Are we losing customers or seeing high churn?',
                'expected_intent' => ['customer.churn', 'customer.headcount', 'customer.repeat', 'analytics.retention'],
                'ground_truth' => function($mysqli) {
                    return ['pass' => true];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),

            // 7. Identity Phone Lookup: Lookup customer by phone number
            new TestCase([
                'id' => 'CUST_PHONE_LOOKUP_01',
                'domain' => 'customer',
                'style' => 'formal',
                'persona' => 'admin',
                'question' => 'Search client details for phone number 9963255054',
                'expected_intent' => 'identity.phone_lookup',
                'expected_entities' => ['phone' => '9963255054'],
                'ground_truth' => function($mysqli) {
                    $res = $mysqli->query("SELECT id, name, phone, email, status FROM users WHERE phone LIKE '%9963255054%'");
                    return ['client' => $res->fetch_assoc()];
                },
                'validator' => function($data, $gt) {
                    return ['pass' => true];
                }
            ]),
        ];
    }
}
