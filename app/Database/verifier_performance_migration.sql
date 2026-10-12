-- Database Migration for Call Verifier Performance Tracking
-- Adds verifier_id to call_recordings and QA audit fields to call_analysis

ALTER TABLE `call_recordings`
  ADD COLUMN `verifier_id` INT(11) NULL AFTER `customer_id`,
  ADD INDEX `idx_verifier_id` (`verifier_id`);

ALTER TABLE `call_analysis`
  ADD COLUMN `verification_status` ENUM('approved', 're_verification', 'escalated', 'pending', 'rejected') NOT NULL DEFAULT 'pending' AFTER `resolution`,
  ADD COLUMN `qa_score` DECIMAL(4,2) NOT NULL DEFAULT 0.00 AFTER `verification_status`,
  ADD COLUMN `is_escalated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `qa_score`,
  ADD COLUMN `has_complaint` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_escalated`,
  ADD INDEX `idx_verification_status` (`verification_status`),
  ADD INDEX `idx_is_escalated` (`is_escalated`),
  ADD INDEX `idx_has_complaint` (`has_complaint`);
