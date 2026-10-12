-- Database Migration for Client <-> Service Provider Call Summary & Service Conversation Analysis
-- Adds specialized columns to call_recordings and call_analysis for multi-service conversation analysis

ALTER TABLE `call_recordings`
  MODIFY COLUMN `call_type` VARCHAR(50) NOT NULL DEFAULT 'verification',
  ADD COLUMN `service_type` ENUM('walking', 'grooming', 'training', 'sitting') NULL AFTER `call_type`,
  ADD INDEX `idx_call_type` (`call_type`),
  ADD INDEX `idx_service_type` (`service_type`);

ALTER TABLE `call_analysis`
  ADD COLUMN `service_type` VARCHAR(50) NULL AFTER `recording_id`,
  ADD COLUMN `service_relevance_score` DECIMAL(4,2) NOT NULL DEFAULT 0.00 AFTER `overall_rating`,
  ADD COLUMN `service_related_pct` DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `service_relevance_score`,
  ADD COLUMN `unrelated_pct` DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `service_related_pct`,
  ADD COLUMN `has_abuse` TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_complaint`,
  ADD COLUMN `ai_rating_category` ENUM('good', 'review_recommended', 'critical') NOT NULL DEFAULT 'good' AFTER `risk_score`,
  ADD COLUMN `off_platform_payment` TINYINT(1) NOT NULL DEFAULT 0 AFTER `ai_rating_category`,
  ADD COLUMN `contact_sharing` TINYINT(1) NOT NULL DEFAULT 0 AFTER `off_platform_payment`,
  ADD COLUMN `outside_service_request` TINYINT(1) NOT NULL DEFAULT 0 AFTER `contact_sharing`,
  ADD COLUMN `refund_requested` TINYINT(1) NOT NULL DEFAULT 0 AFTER `outside_service_request`,
  ADD COLUMN `admin_alert_required` TINYINT(1) NOT NULL DEFAULT 0 AFTER `refund_requested`,
  ADD COLUMN `admin_review_status` ENUM('pending', 'reviewed', 'resolved') NOT NULL DEFAULT 'pending' AFTER `admin_alert_required`,
  ADD COLUMN `admin_remarks` TEXT NULL AFTER `admin_review_status`,
  ADD COLUMN `service_topics` JSON NULL AFTER `tags`,
  ADD COLUMN `important_statements` JSON NULL AFTER `service_topics`,
  ADD COLUMN `unrelated_statements` JSON NULL AFTER `important_statements`,
  ADD COLUMN `complaint_statements` JSON NULL AFTER `unrelated_statements`,
  ADD COLUMN `policy_violations` JSON NULL AFTER `complaint_statements`,
  ADD COLUMN `highlighted_conversations` JSON NULL AFTER `policy_violations`,
  ADD COLUMN `conversation_segments` JSON NULL AFTER `highlighted_conversations`,
  ADD COLUMN `alerts` JSON NULL AFTER `conversation_segments`,
  ADD INDEX `idx_service_relevance` (`service_relevance_score`),
  ADD INDEX `idx_ai_rating_category` (`ai_rating_category`),
  ADD INDEX `idx_admin_alert_required` (`admin_alert_required`),
  ADD INDEX `idx_admin_review_status` (`admin_review_status`);
