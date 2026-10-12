-- Database Migration for Advanced Permission Management System
-- Creates user_permissions table for user-wise direct permission overrides
-- Pre-populates comprehensive permissions for all system functionalities

-- 1. Create user_permissions table
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `permission_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_user_permission` (`user_id`, `permission_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Insert missing permissions for all system functionalities
INSERT IGNORE INTO `permissions` (`id`, `name`, `module`) VALUES
(80, 'view', 'Call Analysis'),
(81, 'upload', 'Call Analysis'),
(82, 'audit', 'Call Analysis'),
(83, 'view', 'Notification Hub'),
(84, 'manage_templates', 'Notification Hub'),
(85, 'manage_campaigns', 'Notification Hub'),
(86, 'view_logs', 'Notification Hub'),
(87, 'view', 'Refund Management'),
(88, 'process', 'Refund Management'),
(89, 'view', 'Loyalty Management'),
(90, 'edit', 'Loyalty Management'),
(91, 'view', 'Community Admin'),
(92, 'moderate', 'Community Admin'),
(93, 'view', 'Verification Questions'),
(94, 'edit', 'Verification Questions');
