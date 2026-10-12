-- =====================================================
-- Petsfolio Version Release Management
-- Database Migration
-- Created: 2026-09-05
-- =====================================================

-- 1. release_versions (master record)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_versions` (
  `id`                    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_number`        VARCHAR(20) NOT NULL COMMENT 'e.g. 2.4.0',
  `build_number`          VARCHAR(50) DEFAULT NULL COMMENT 'Android/iOS build number',
  `platform`              ENUM('android','ios','both') NOT NULL DEFAULT 'both',
  `release_type`          ENUM('major','minor','bugfix','hotfix') NOT NULL DEFAULT 'minor',
  `status`                ENUM('draft','testing','qa_approved','backup_completed',
                               'management_approved','scheduled','releasing',
                               'live','monitoring','successful',
                               'rollback_requested','rolling_back','rolled_back')
                          NOT NULL DEFAULT 'draft',
  `previous_version_id`   INT UNSIGNED DEFAULT NULL,
  `rollback_version_id`   INT UNSIGNED DEFAULT NULL,
  `release_notes`         TEXT DEFAULT NULL,
  `planned_release_at`    DATETIME DEFAULT NULL,
  `released_at`           DATETIME DEFAULT NULL,
  `monitoring_period`     ENUM('30min','1hr','4hr','24hr') DEFAULT '1hr',
  `monitoring_ends_at`    DATETIME DEFAULT NULL,
  `created_by`            INT UNSIGNED NOT NULL COMMENT 'FK employees.id',
  `approved_at`           DATETIME DEFAULT NULL,
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_version` (`version_number`),
  INDEX `idx_platform` (`platform`),
  INDEX `idx_created_by` (`created_by`),
  INDEX `idx_planned` (`planned_release_at`),
  CONSTRAINT `fk_rv_prev` FOREIGN KEY (`previous_version_id`) REFERENCES `release_versions`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rv_rollback` FOREIGN KEY (`rollback_version_id`) REFERENCES `release_versions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. release_updates (categorized change items)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_updates` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `category`        ENUM('new_features','enhancements','bug_fixes','ui_ux',
                         'api_backend','database','security','third_party') NOT NULL,
  `title`           VARCHAR(255) NOT NULL,
  `description`     TEXT DEFAULT NULL,
  `ticket_id`       VARCHAR(50) DEFAULT NULL COMMENT 'e.g. PF-1024',
  `developer_id`    INT UNSIGNED DEFAULT NULL,
  `status`          ENUM('pending','in_progress','completed','verified') DEFAULT 'pending',
  `comments`        TEXT DEFAULT NULL,
  `sort_order`      INT DEFAULT 0,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_ticket` (`ticket_id`),
  INDEX `idx_status` (`status`),
  CONSTRAINT `fk_ru_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 3. release_tickets (related project tickets)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_tickets` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `ticket_id`       VARCHAR(50) NOT NULL COMMENT 'e.g. PF-1024',
  `description`     VARCHAR(500) DEFAULT NULL,
  `status`          ENUM('open','in_progress','fixed','completed','wontfix') DEFAULT 'open',
  `priority`        ENUM('critical','high','medium','low') DEFAULT 'medium',
  `assigned_to`     INT UNSIGNED DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_ticket` (`ticket_id`),
  CONSTRAINT `fk_rt_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. release_checklists (checklist sections per version)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_checklists` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `section`         ENUM('application','service_provider','admin','technical','qa') NOT NULL,
  `section_label`   VARCHAR(100) NOT NULL,
  `sort_order`      INT DEFAULT 0,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  CONSTRAINT `fk_rc_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 5. release_checklist_items (individual checklist items)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_checklist_items` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `checklist_id`    INT UNSIGNED NOT NULL,
  `version_id`      INT UNSIGNED NOT NULL,
  `item_text`       VARCHAR(500) NOT NULL,
  `status`          ENUM('pending','in_progress','passed','failed','na') DEFAULT 'pending',
  `checked_by`      INT UNSIGNED DEFAULT NULL,
  `checked_at`      DATETIME DEFAULT NULL,
  `comment`         TEXT DEFAULT NULL,
  `sort_order`      INT DEFAULT 0,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_checklist` (`checklist_id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_status` (`status`),
  CONSTRAINT `fk_rci_checklist` FOREIGN KEY (`checklist_id`) REFERENCES `release_checklists`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rci_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 6. release_backups (production backup records)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_backups` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`              INT UNSIGNED NOT NULL,
  `current_prod_version`    VARCHAR(20) NOT NULL,
  `current_build_number`    VARCHAR(50) DEFAULT NULL,
  `apk_aab_reference`       VARCHAR(500) DEFAULT NULL,
  `ipa_reference`           VARCHAR(500) DEFAULT NULL,
  `database_backup`         VARCHAR(500) DEFAULT NULL COMMENT 'e.g. db_backup_2026_09_05',
  `api_backup`              VARCHAR(500) DEFAULT NULL COMMENT 'git commit/tag',
  `config_backup`           VARCHAR(500) DEFAULT NULL,
  `backup_date`             DATETIME NOT NULL,
  `verified_by`             INT UNSIGNED DEFAULT NULL,
  `verified_at`             DATETIME DEFAULT NULL,
  `rollback_version`        VARCHAR(20) DEFAULT NULL,
  `notes`                   TEXT DEFAULT NULL,
  `created_at`              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  CONSTRAINT `fk_rb_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 7. release_approvals (structured multi-role approval)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_approvals` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `approval_type`   ENUM('developer','qa','product','management','release_manager') NOT NULL,
  `status`          ENUM('pending','approved','rejected') DEFAULT 'pending',
  `approved_by`     INT UNSIGNED DEFAULT NULL,
  `approved_at`     DATETIME DEFAULT NULL,
  `comments`        TEXT DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_type` (`approval_type`),
  INDEX `idx_status` (`status`),
  UNIQUE KEY `uk_version_type` (`version_id`, `approval_type`),
  CONSTRAINT `fk_ra_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 8. release_emails (approval email records)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_emails` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `subject`         VARCHAR(500) NOT NULL,
  `body_html`       LONGTEXT NOT NULL,
  `recipients`      TEXT NOT NULL COMMENT 'JSON array of emails',
  `sent_by`         INT UNSIGNED DEFAULT NULL,
  `sent_at`         DATETIME DEFAULT NULL,
  `status`          ENUM('draft','preview','sent','failed') DEFAULT 'draft',
  `error_message`   TEXT DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_status` (`status`),
  CONSTRAINT `fk_re_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 9. release_deployments (go-live tracking)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_deployments` (
  `id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`          INT UNSIGNED NOT NULL,
  `deployment_status`   ENUM('pending','started','deploying','deployed',
                             'health_check','monitoring','successful','failed') DEFAULT 'pending',
  `started_at`          DATETIME DEFAULT NULL,
  `deployed_at`         DATETIME DEFAULT NULL,
  `health_check_at`     DATETIME DEFAULT NULL,
  `monitoring_started`  DATETIME DEFAULT NULL,
  `completed_at`        DATETIME DEFAULT NULL,
  `deployed_by`         INT UNSIGNED DEFAULT NULL,
  `notes`               TEXT DEFAULT NULL,
  `created_at`          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_status` (`deployment_status`),
  CONSTRAINT `fk_rd_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 10. release_rollbacks (controlled rollback records)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_rollbacks` (
  `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`              INT UNSIGNED NOT NULL,
  `current_live_version`    VARCHAR(20) NOT NULL,
  `rollback_to_version`     VARCHAR(20) NOT NULL,
  `rollback_reason`         TEXT NOT NULL,
  `critical_issue`          TEXT DEFAULT NULL,
  `approved_by`             INT UNSIGNED DEFAULT NULL,
  `approved_at`             DATETIME DEFAULT NULL,
  `started_at`              DATETIME DEFAULT NULL,
  `completed_at`            DATETIME DEFAULT NULL,
  `status`                  ENUM('requested','approved','in_progress','completed','failed') DEFAULT 'requested',
  `post_rollback_notes`     TEXT DEFAULT NULL,
  `created_at`              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`              DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_status` (`status`),
  CONSTRAINT `fk_rr_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 11. release_comments (developer/QA/approval comments)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_comments` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `comment_type`    ENUM('developer','qa','approval','general') DEFAULT 'general',
  `comment`         TEXT NOT NULL,
  `created_by`      INT UNSIGNED NOT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_type` (`comment_type`),
  CONSTRAINT `fk_rco_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 12. release_audit_logs (complete audit trail)
-- =====================================================
CREATE TABLE IF NOT EXISTS `release_audit_logs` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id`      INT UNSIGNED NOT NULL,
  `user_id`         INT UNSIGNED NOT NULL,
  `action`          VARCHAR(100) NOT NULL COMMENT 'e.g. status_changed, backup_created',
  `old_value`       TEXT DEFAULT NULL,
  `new_value`       TEXT DEFAULT NULL,
  `description`     VARCHAR(500) DEFAULT NULL,
  `ip_address`      VARCHAR(45) DEFAULT NULL,
  `user_agent`      VARCHAR(500) DEFAULT NULL,
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_version` (`version_id`),
  INDEX `idx_user` (`user_id`),
  INDEX `idx_action` (`action`),
  INDEX `idx_created` (`created_at`),
  CONSTRAINT `fk_ral_version` FOREIGN KEY (`version_id`) REFERENCES `release_versions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- RBAC Permissions for Release Management
-- =====================================================
INSERT INTO `permissions` (`name`, `module`) VALUES
  ('release_management.view',        'Release Management'),
  ('release_management.create',      'Release Management'),
  ('release_management.edit',        'Release Management'),
  ('release_management.delete',      'Release Management'),
  ('release_management.approve_dev', 'Release Management'),
  ('release_management.approve_qa',  'Release Management'),
  ('release_management.approve_mgmt','Release Management'),
  ('release_management.go_live',     'Release Management'),
  ('release_management.rollback',    'Release Management'),
  ('release_management.send_email',  'Release Management'),
  ('release_management.audit_log',   'Release Management');
