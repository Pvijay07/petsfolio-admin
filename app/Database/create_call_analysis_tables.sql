-- MySQL / MariaDB Schema for Call Recording AI Analysis System

CREATE TABLE IF NOT EXISTS `call_recordings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NULL,
  `provider_id` INT NULL,
  `customer_id` INT NULL,
  `call_type` ENUM('inbound', 'outbound', 'verification', 'support') NOT NULL DEFAULT 'inbound',
  `recording_url` VARCHAR(500) NOT NULL,
  `duration` INT NOT NULL DEFAULT 0,
  `status` ENUM('uploaded', 'queued', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'uploaded',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  KEY `idx_provider_id` (`provider_id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `call_analysis_jobs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `recording_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('queued', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'queued',
  `retry_count` INT NOT NULL DEFAULT 0,
  `started_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `error` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  KEY `idx_recording_id` (`recording_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `call_analysis` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `recording_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `transcript` LONGTEXT NULL,
  `summary` TEXT NULL,
  `overall_rating` DECIMAL(4,2) NOT NULL DEFAULT 0.00,
  `communication` INT NOT NULL DEFAULT 0,
  `professionalism` INT NOT NULL DEFAULT 0,
  `empathy` INT NOT NULL DEFAULT 0,
  `compliance` INT NOT NULL DEFAULT 0,
  `customer_sentiment` VARCHAR(50) NULL,
  `provider_sentiment` VARCHAR(50) NULL,
  `resolution` VARCHAR(50) NULL,
  `risk_score` INT NOT NULL DEFAULT 0,
  `strengths` JSON NULL,
  `weaknesses` JSON NULL,
  `recommendations` JSON NULL,
  `tags` JSON NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_call_analysis_recording` FOREIGN KEY (`recording_id`) REFERENCES `call_recordings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `call_keywords` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `analysis_id` BIGINT UNSIGNED NOT NULL,
  `keyword` VARCHAR(100) NOT NULL,
  `frequency` INT NOT NULL DEFAULT 1,
  KEY `idx_analysis_id` (`analysis_id`),
  KEY `idx_keyword` (`keyword`),
  CONSTRAINT `fk_call_keywords_analysis` FOREIGN KEY (`analysis_id`) REFERENCES `call_analysis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
