<?php

namespace Config;

/**
 * PermissionRegistry — Single Source of Truth for all system permissions.
 *
 * To add a new permission:
 *   1. Add a line here with the slug and metadata.
 *   2. Click "Sync Permissions" in the admin permissions page.
 *
 * Slugs follow the format: module.action (e.g., 'bookings.view', 'employees.manage')
 */
class PermissionRegistry
{
    /**
     * Master list of all permissions in the system.
     *
     * @return array<string, array{module: string, name: string}>
     */
    public static function all(): array
    {
        return [
            // ── Dashboard ──────────────────────────────────────────
            'dashboard.view'            => ['module' => 'Dashboard',          'name' => 'View Dashboard'],

            // ── AI Analyst ─────────────────────────────────────────
            'ai_analyst.view'           => ['module' => 'AI Analyst',         'name' => 'View AI Analyst'],

            // ── Chat History ───────────────────────────────────────
            'chat_history.view'         => ['module' => 'Chat History',       'name' => 'View Chat History'],

            // ── Bookings ───────────────────────────────────────────
            'bookings.view_requests'    => ['module' => 'Bookings',           'name' => 'View Booking Requests'],
            'bookings.walking'          => ['module' => 'Bookings',           'name' => 'View Walking Bookings'],
            'bookings.grooming'         => ['module' => 'Bookings',           'name' => 'View Grooming Bookings'],
            'bookings.training'         => ['module' => 'Bookings',           'name' => 'View Training Bookings'],
            'bookings.boarding'         => ['module' => 'Bookings',           'name' => 'View Boarding Bookings'],
            'bookings.sitting'          => ['module' => 'Bookings',           'name' => 'View Sitting Bookings'],
            'bookings.premium'          => ['module' => 'Bookings',           'name' => 'View Premium Requests'],
            'bookings.missed_walks'     => ['module' => 'Bookings',           'name' => 'View Missed Walks'],
            'bookings.delete'           => ['module' => 'Bookings',           'name' => 'Delete Bookings'],
            'bookings.disputes'         => ['module' => 'Bookings',           'name' => 'View Disputes'],
            'bookings.all'              => ['module' => 'Bookings',           'name' => 'View All Bookings'],

            // ── Service Providers ──────────────────────────────────
            'providers.view'            => ['module' => 'Service Providers',  'name' => 'View Providers'],
            'providers.manage'          => ['module' => 'Service Providers',  'name' => 'Manage Providers'],
            'providers.verify'          => ['module' => 'Service Providers',  'name' => 'Verify Providers'],

            // ── Clients ────────────────────────────────────────────
            'clients.view'              => ['module' => 'Clients',            'name' => 'View Clients'],
            'clients.manage'            => ['module' => 'Clients',            'name' => 'Manage Clients'],

            // ── Employees ──────────────────────────────────────────
            'employees.view'            => ['module' => 'Employees',          'name' => 'View Employees'],
            'employees.manage'          => ['module' => 'Employees',          'name' => 'Manage Employees'],
            'audit_logs.view'           => ['module' => 'Employees',          'name' => 'View Activity & Audit Logs'],

            // ── Support ────────────────────────────────────────────
            'support.client'            => ['module' => 'Support',            'name' => 'Client Support'],
            'support.provider'          => ['module' => 'Support',            'name' => 'Provider Support'],

            // ── Finance ────────────────────────────────────────────
            'finance.sp_earnings'       => ['module' => 'Finance',            'name' => 'SP Earnings'],
            'finance.client_spendings'  => ['module' => 'Finance',            'name' => 'Client Spendings'],
            'finance.company_earnings'  => ['module' => 'Finance',            'name' => 'Company Earnings'],
            'finance.daily_bookings'    => ['module' => 'Finance',            'name' => 'Daily Bookings'],
            'finance.sp_payments'       => ['module' => 'Finance',            'name' => 'SP Payment Transactions'],
            'finance.provider_payouts'  => ['module' => 'Finance',            'name' => 'Provider Payouts'],
            'finance.client_wallets'    => ['module' => 'Finance',            'name' => 'Client Wallets'],
            'finance.payments'          => ['module' => 'Finance',            'name' => 'Client Payments'],
            'finance.reconciliation'    => ['module' => 'Finance',            'name' => 'Reconciliation'],
            'finance.tax'               => ['module' => 'Finance',            'name' => 'Tax Management'],

            // ── Statistics ─────────────────────────────────────────
            'statistics.client'         => ['module' => 'Statistics',         'name' => 'Client Statistics'],
            'statistics.provider'       => ['module' => 'Statistics',         'name' => 'Provider Statistics'],
            'statistics.app'            => ['module' => 'Statistics',         'name' => 'App Statistics'],

            // ── Community ──────────────────────────────────────────
            'community.view'            => ['module' => 'Community',          'name' => 'View Community'],
            'community.manage'          => ['module' => 'Community',          'name' => 'Manage Posts/Channels'],

            // ── Loyalty ────────────────────────────────────────────
            'loyalty.view'              => ['module' => 'Loyalty',            'name' => 'View Loyalty'],
            'loyalty.manage'            => ['module' => 'Loyalty',            'name' => 'Manage Programs/Rewards'],

            // ── Notifications ──────────────────────────────────────
            'notifications.view'        => ['module' => 'Notifications',      'name' => 'View Notifications'],
            'notifications.manage'      => ['module' => 'Notifications',      'name' => 'Manage/Send Notifications'],
            'notifications.campaigns'   => ['module' => 'Notifications',      'name' => 'Notification Campaigns'],

            // ── Tasks ──────────────────────────────────────────────
            'tasks.view'                => ['module' => 'Tasks',              'name' => 'View Tasks'],
            'tasks.manage'              => ['module' => 'Tasks',              'name' => 'Manage Tasks'],

            // ── Service States ─────────────────────────────────────
            'service_states.view'       => ['module' => 'Service States',     'name' => 'View Service States'],
            'service_states.manage'     => ['module' => 'Service States',     'name' => 'Manage Service States'],

            // ── Call Analysis ──────────────────────────────────────
            'call_analysis.view'        => ['module' => 'Call Analysis',      'name' => 'View Call Analysis'],
            'call_analysis.manage'      => ['module' => 'Call Analysis',      'name' => 'Manage Call Recordings'],

            // ── Verification ───────────────────────────────────────
            'verification.questions'    => ['module' => 'Verification',       'name' => 'Manage Verification Questions'],
            'verification.rules'        => ['module' => 'Verification',       'name' => 'Manage Verification Rules'],
            'verification.payouts'      => ['module' => 'Verification',       'name' => 'Call Verification Payouts'],

            // ── Walker Stats / SP Management ───────────────────────
            'walker.leaderboard'        => ['module' => 'Walker Stats',       'name' => 'View Walker Leaderboard'],
            'walker.sp_management'      => ['module' => 'Walker Stats',       'name' => 'SP Management Dashboard'],

            // ── Insurance ──────────────────────────────────────────
            'insurance.view'            => ['module' => 'Insurance',          'name' => 'View Insurance'],
            'insurance.manage'          => ['module' => 'Insurance',          'name' => 'Manage Policies/Claims'],

            // ── Events ─────────────────────────────────────────────
            'events.view'               => ['module' => 'Events',             'name' => 'View Events'],
            'events.manage'             => ['module' => 'Events',             'name' => 'Manage Events'],

            // ── Content ────────────────────────────────────────────
            'banners.view'              => ['module' => 'Content',            'name' => 'View Banners'],
            'banners.manage'            => ['module' => 'Content',            'name' => 'Manage Banners'],
            'tutorials.view'            => ['module' => 'Content',            'name' => 'View Tutorials'],
            'tutorials.manage'          => ['module' => 'Content',            'name' => 'Manage Tutorials'],

            // ── Configuration ──────────────────────────────────────
            'permissions.view'          => ['module' => 'Configuration',      'name' => 'View Permissions'],
            'permissions.manage'        => ['module' => 'Configuration',      'name' => 'Manage Permissions'],
            'pets.view'                 => ['module' => 'Configuration',      'name' => 'View Pet Types'],
            'pets.manage'               => ['module' => 'Configuration',      'name' => 'Manage Pet Types'],
            'breeds.view'               => ['module' => 'Configuration',      'name' => 'View Breeds'],
            'breeds.manage'             => ['module' => 'Configuration',      'name' => 'Manage Breeds'],
            'services.view'             => ['module' => 'Configuration',      'name' => 'View Services'],
            'services.manage'           => ['module' => 'Configuration',      'name' => 'Manage Services'],

            // ── Media ──────────────────────────────────────────────
            'media.approval'            => ['module' => 'Media',              'name' => 'Service Media Approval'],
        ];
    }
}
