-- =====================================================
-- Petsfolio Notification Campaign Manager
-- Database Migration
-- Created: 2026-07-29
-- =====================================================

-- 1. Enhance existing notification_templates table
-- =====================================================
ALTER TABLE `notification_templates`
  ADD COLUMN `name` VARCHAR(100) DEFAULT NULL AFTER `id`,
  ADD COLUMN `event_key` VARCHAR(100) DEFAULT NULL AFTER `name`,
  ADD COLUMN `body` TEXT DEFAULT NULL AFTER `content`,
  ADD COLUMN `channel` ENUM('push','sms','email','whatsapp','all') DEFAULT 'push' AFTER `body`,
  ADD COLUMN `variables` TEXT DEFAULT NULL AFTER `channel`;

-- Add index for event_key lookups
ALTER TABLE `notification_templates`
  ADD INDEX `idx_event_key` (`event_key`),
  ADD INDEX `idx_channel` (`channel`),
  ADD INDEX `idx_status` (`status`);


-- 2. Create notification_campaigns table
-- =====================================================
CREATE TABLE IF NOT EXISTS `notification_campaigns` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `type` ENUM('promotional','reminder','engagement','transactional') NOT NULL DEFAULT 'promotional',
  `target` ENUM('all','clients','providers','segment') NOT NULL DEFAULT 'all',
  `segment_rules` JSON DEFAULT NULL COMMENT 'JSON: {city, pet_type, service_type, status, registration_date, last_active}',
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  `route_name` VARCHAR(255) DEFAULT NULL COMMENT 'Deep link route for app',
  `channel` ENUM('push','sms','email','whatsapp','all') NOT NULL DEFAULT 'push',
  `template_id` INT(11) DEFAULT NULL COMMENT 'FK to notification_templates if using a template',
  `schedule_type` ENUM('immediate','scheduled','recurring') NOT NULL DEFAULT 'immediate',
  `scheduled_at` DATETIME DEFAULT NULL,
  `recurring_cron` VARCHAR(100) DEFAULT NULL COMMENT 'Cron expression for recurring campaigns',
  `start_at` DATETIME DEFAULT NULL,
  `end_at` DATETIME DEFAULT NULL,
  `status` ENUM('draft','scheduled','sending','sent','cancelled','failed','paused') NOT NULL DEFAULT 'draft',
  `total_audience` INT(11) UNSIGNED DEFAULT 0,
  `sent_count` INT(11) UNSIGNED DEFAULT 0,
  `failed_count` INT(11) UNSIGNED DEFAULT 0,
  `opened_count` INT(11) UNSIGNED DEFAULT 0,
  `clicked_count` INT(11) UNSIGNED DEFAULT 0,
  `error_message` TEXT DEFAULT NULL,
  `created_by` INT(11) UNSIGNED DEFAULT NULL COMMENT 'Employee ID who created',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `sent_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_schedule` (`schedule_type`, `scheduled_at`),
  INDEX `idx_type` (`type`),
  INDEX `idx_created_by` (`created_by`),
  INDEX `idx_channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 3. Create notification_logs table
-- =====================================================
CREATE TABLE IF NOT EXISTS `notification_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED DEFAULT NULL,
  `provider_id` INT(11) UNSIGNED DEFAULT NULL,
  `user_type` ENUM('user','provider') NOT NULL DEFAULT 'user',
  `template_id` INT(11) DEFAULT NULL,
  `campaign_id` INT(11) UNSIGNED DEFAULT NULL,
  `channel` ENUM('push','sms','email','whatsapp') NOT NULL DEFAULT 'push',
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT DEFAULT NULL,
  `status` ENUM('queued','sent','delivered','failed','opened','clicked') NOT NULL DEFAULT 'queued',
  `error_message` TEXT DEFAULT NULL,
  `device_token` VARCHAR(500) DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `delivered_at` DATETIME DEFAULT NULL,
  `opened_at` DATETIME DEFAULT NULL,
  `clicked_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user` (`user_id`, `user_type`),
  INDEX `idx_provider` (`provider_id`),
  INDEX `idx_campaign` (`campaign_id`),
  INDEX `idx_template` (`template_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_channel` (`channel`),
  INDEX `idx_sent_at` (`sent_at`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. Create user_notification_preferences table
-- =====================================================
CREATE TABLE IF NOT EXISTS `user_notification_preferences` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `user_type` ENUM('user','provider') NOT NULL DEFAULT 'user',
  `booking_updates` TINYINT(1) NOT NULL DEFAULT 1,
  `promotions` TINYINT(1) NOT NULL DEFAULT 1,
  `offers` TINYINT(1) NOT NULL DEFAULT 1,
  `reminders` TINYINT(1) NOT NULL DEFAULT 1,
  `push` TINYINT(1) NOT NULL DEFAULT 1,
  `sms` TINYINT(1) NOT NULL DEFAULT 1,
  `email` TINYINT(1) NOT NULL DEFAULT 0,
  `whatsapp` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user` (`user_id`, `user_type`),
  INDEX `idx_user_type` (`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
