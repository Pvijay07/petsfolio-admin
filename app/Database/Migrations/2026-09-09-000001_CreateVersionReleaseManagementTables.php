<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class CreateVersionReleaseManagementTables extends Migration
{
    public function up()
    {
        $db = Database::connect();

        // 1. release_versions
        $db->query("CREATE TABLE IF NOT EXISTS `release_versions` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. 2.4.0',
            `build_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Android/iOS build number',
            `platform` enum('android','ios','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
            `release_type` enum('major','minor','bugfix','hotfix') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'minor',
            `status` enum('draft','testing','qa_approved','backup_completed','management_approved','scheduled','releasing','live','monitoring','successful','rollback_requested','rolling_back','rolled_back') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
            `previous_version_id` int UNSIGNED DEFAULT NULL,
            `rollback_version_id` int UNSIGNED DEFAULT NULL,
            `release_notes` text COLLATE utf8mb4_unicode_ci,
            `planned_release_at` datetime DEFAULT NULL,
            `released_at` datetime DEFAULT NULL,
            `monitoring_period` enum('30min','1hr','4hr','24hr') COLLATE utf8mb4_unicode_ci DEFAULT '1hr',
            `monitoring_ends_at` datetime DEFAULT NULL,
            `created_by` int UNSIGNED NOT NULL COMMENT 'FK employees.id',
            `approved_at` datetime DEFAULT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_status` (`status`),
            KEY `idx_version` (`version_number`),
            KEY `idx_platform` (`platform`),
            KEY `idx_created_by` (`created_by`),
            KEY `idx_planned` (`planned_release_at`),
            KEY `fk_rv_prev` (`previous_version_id`),
            KEY `fk_rv_rollback` (`rollback_version_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 2. release_updates
        $db->query("CREATE TABLE IF NOT EXISTS `release_updates` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `category` enum('new_features','enhancements','bug_fixes','ui_ux','api_backend','database','security','third_party') COLLATE utf8mb4_unicode_ci NOT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `description` text COLLATE utf8mb4_unicode_ci,
            `ticket_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. PF-1024',
            `developer_id` int UNSIGNED DEFAULT NULL,
            `status` enum('pending','in_progress','completed','verified') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `comments` text COLLATE utf8mb4_unicode_ci,
            `sort_order` int DEFAULT '0',
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_category` (`category`),
            KEY `idx_ticket` (`ticket_id`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 3. release_checklists
        $db->query("CREATE TABLE IF NOT EXISTS `release_checklists` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `section` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `section_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `sort_order` int DEFAULT '0',
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 4. release_checklist_items
        $db->query("CREATE TABLE IF NOT EXISTS `release_checklist_items` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `checklist_id` int UNSIGNED NOT NULL,
            `version_id` int UNSIGNED NOT NULL,
            `item_text` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
            `status` enum('pending','in_progress','passed','failed','na') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `checked_by` int UNSIGNED DEFAULT NULL,
            `checked_at` datetime DEFAULT NULL,
            `comment` text COLLATE utf8mb4_unicode_ci,
            `sort_order` int DEFAULT '0',
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_checklist` (`checklist_id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 5. release_backups
        $db->query("CREATE TABLE IF NOT EXISTS `release_backups` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `current_prod_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
            `current_build_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `apk_aab_reference` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `ipa_reference` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `database_backup` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. db_backup_2026_09_05',
            `api_backup` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'git commit/tag',
            `config_backup` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `backup_date` datetime NOT NULL,
            `verified_by` int UNSIGNED DEFAULT NULL,
            `verified_at` datetime DEFAULT NULL,
            `rollback_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `notes` text COLLATE utf8mb4_unicode_ci,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 6. release_approvals
        $db->query("CREATE TABLE IF NOT EXISTS `release_approvals` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `approval_type` enum('developer','qa','product','management','release_manager') COLLATE utf8mb4_unicode_ci NOT NULL,
            `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `approved_by` int UNSIGNED DEFAULT NULL,
            `approved_at` datetime DEFAULT NULL,
            `comments` text COLLATE utf8mb4_unicode_ci,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_version_type` (`version_id`,`approval_type`),
            KEY `idx_version` (`version_id`),
            KEY `idx_type` (`approval_type`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 7. release_emails
        $db->query("CREATE TABLE IF NOT EXISTS `release_emails` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
            `body_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
            `recipients` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON array of emails',
            `sent_by` int UNSIGNED DEFAULT NULL,
            `sent_at` datetime DEFAULT NULL,
            `status` enum('draft','preview','sent','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
            `error_message` text COLLATE utf8mb4_unicode_ci,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 8. release_rollbacks
        $db->query("CREATE TABLE IF NOT EXISTS `release_rollbacks` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `current_live_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
            `rollback_to_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
            `rollback_reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `critical_issue` text COLLATE utf8mb4_unicode_ci,
            `approved_by` int UNSIGNED DEFAULT NULL,
            `approved_at` datetime DEFAULT NULL,
            `started_at` datetime DEFAULT NULL,
            `completed_at` datetime DEFAULT NULL,
            `status` enum('requested','approved','in_progress','completed','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'requested',
            `post_rollback_notes` text COLLATE utf8mb4_unicode_ci,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 9. release_audit_logs
        $db->query("CREATE TABLE IF NOT EXISTS `release_audit_logs` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `user_id` int UNSIGNED NOT NULL,
            `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. status_changed, backup_created',
            `old_value` text COLLATE utf8mb4_unicode_ci,
            `new_value` text COLLATE utf8mb4_unicode_ci,
            `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_user` (`user_id`),
            KEY `idx_action` (`action`),
            KEY `idx_created` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 10. release_comments
        $db->query("CREATE TABLE IF NOT EXISTS `release_comments` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `comment_type` enum('developer','qa','approval','general') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
            `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_by` int UNSIGNED NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_type` (`comment_type`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 11. release_deployments
        $db->query("CREATE TABLE IF NOT EXISTS `release_deployments` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `deployment_status` enum('pending','started','deploying','deployed','health_check','monitoring','successful','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `started_at` datetime DEFAULT NULL,
            `deployed_at` datetime DEFAULT NULL,
            `health_check_at` datetime DEFAULT NULL,
            `monitoring_started` datetime DEFAULT NULL,
            `completed_at` datetime DEFAULT NULL,
            `deployed_by` int UNSIGNED DEFAULT NULL,
            `notes` text COLLATE utf8mb4_unicode_ci,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_status` (`deployment_status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 12. release_tickets
        $db->query("CREATE TABLE IF NOT EXISTS `release_tickets` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `version_id` int UNSIGNED NOT NULL,
            `ticket_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. PF-1024',
            `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` enum('open','in_progress','fixed','completed','wontfix') COLLATE utf8mb4_unicode_ci DEFAULT 'open',
            `priority` enum('critical','high','medium','low') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
            `assigned_to` int UNSIGNED DEFAULT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_version` (`version_id`),
            KEY `idx_ticket` (`ticket_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function down()
    {
        $db = Database::connect();
        $tables = [
            'release_tickets',
            'release_deployments',
            'release_comments',
            'release_audit_logs',
            'release_rollbacks',
            'release_emails',
            'release_approvals',
            'release_backups',
            'release_checklist_items',
            'release_checklists',
            'release_updates',
            'release_versions'
        ];

        foreach ($tables as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
