-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2026 at 05:47 AM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u506206132_betaApp`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(225) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `is_included_in_packages` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` enum('low','normal','high','critical') NOT NULL DEFAULT 'normal',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_logs`
--

CREATE TABLE `ai_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `leadId` int(11) UNSIGNED NOT NULL,
  `prompt` longtext NOT NULL,
  `response` longtext NOT NULL,
  `model` varchar(100) NOT NULL,
  `tokensUsed` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `method` varchar(225) DEFAULT NULL,
  `status` varchar(225) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(225) DEFAULT NULL,
  `duration_ms` varchar(225) DEFAULT NULL,
  `request_data` text DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) NOT NULL,
  `doctor_id` bigint(20) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `pet_id` bigint(20) NOT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_state`
--

CREATE TABLE `assignment_state` (
  `id` int(11) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `lastAssignedIndex` int(11) NOT NULL DEFAULT -1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_states`
--

CREATE TABLE `assignment_states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL DEFAULT 'leadAssignment',
  `last_assigned_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `baileys_auth`
--

CREATE TABLE `baileys_auth` (
  `id` int(11) NOT NULL,
  `session_name` varchar(100) DEFAULT NULL,
  `creds` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`creds`)),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `baileys_keys`
--

CREATE TABLE `baileys_keys` (
  `id` bigint(20) NOT NULL,
  `session_name` varchar(100) DEFAULT NULL,
  `key_type` varchar(100) DEFAULT NULL,
  `key_id` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_verification_logs`
--

CREATE TABLE `bank_verification_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `bank_detail_id` int(11) NOT NULL,
  `verification_type` enum('BANK','UPI') NOT NULL,
  `status` varchar(50) NOT NULL,
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `url` varchar(225) NOT NULL,
  `type` varchar(225) NOT NULL,
  `link_id` varchar(225) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `banner_type` enum('promo','service','seasonal','emergency') NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boarding_service_bookings`
--

CREATE TABLE `boarding_service_bookings` (
  `id` varchar(100) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED DEFAULT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `service_start_date` date NOT NULL,
  `service_end_date` date NOT NULL,
  `preferable_time` mediumtext NOT NULL,
  `addons` mediumtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` datetime DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boarding_tracking`
--

CREATE TABLE `boarding_tracking` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `pet_id` int(11) NOT NULL,
  `tracking_date` date NOT NULL,
  `morning_photo` varchar(500) DEFAULT NULL,
  `morning_video` varchar(500) DEFAULT NULL,
  `afternoon_photo` varchar(500) DEFAULT NULL,
  `afternoon_video` varchar(500) DEFAULT NULL,
  `evening_photo` varchar(500) DEFAULT NULL,
  `evening_video` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_addons`
--

CREATE TABLE `booking_addons` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `addon` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_conversations`
--

CREATE TABLE `booking_conversations` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `service_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_provider_id` int(11) NOT NULL,
  `status` enum('active','closed','cancelled') DEFAULT 'active',
  `last_message` text DEFAULT NULL,
  `last_message_type` enum('text','image','file','predefined') DEFAULT 'text',
  `last_message_at` datetime DEFAULT NULL,
  `unread_count_client` int(11) DEFAULT 0,
  `unread_count_sp` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_invited_sps`
--

CREATE TABLE `booking_invited_sps` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'client_id',
  `provider_id` int(11) NOT NULL,
  `status` enum('pending','viewed','bid_submitted','expired') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_messages`
--

CREATE TABLE `booking_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('client','service_provider') NOT NULL,
  `message` text DEFAULT NULL,
  `message_type` enum('text','image','file','predefined') DEFAULT 'text',
  `question_id` int(11) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_pets`
--

CREATE TABLE `booking_pets` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(36) NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `track_status` enum('not_started','completed','approved','in_progress','rejected','terminate') DEFAULT NULL,
  `cancel_reason` varchar(225) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `callers`
--

CREATE TABLE `callers` (
  `id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `otp_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','blocked') DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_activity_logs`
--

CREATE TABLE `call_activity_logs` (
  `id` bigint(20) NOT NULL,
  `call_attempt_id` bigint(20) DEFAULT NULL,
  `caller_id` bigint(20) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_analysis`
--

CREATE TABLE `call_analysis` (
  `id` bigint(20) NOT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `sentiment` varchar(20) DEFAULT NULL,
  `interaction_count` int(11) DEFAULT NULL,
  `confidence_score` decimal(3,2) DEFAULT NULL,
  `clarity_score` decimal(3,2) DEFAULT NULL,
  `professionalism_score` decimal(3,2) DEFAULT NULL,
  `extracted_data` text DEFAULT NULL,
  `risk_flag` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_answers`
--

CREATE TABLE `call_answers` (
  `id` bigint(20) NOT NULL,
  `call_attempt_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `answer` longtext DEFAULT NULL,
  `uploaded_file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_attempts`
--

CREATE TABLE `call_attempts` (
  `id` bigint(20) NOT NULL,
  `sp_id` bigint(20) NOT NULL,
  `caller_id` bigint(20) NOT NULL,
  `attempt_no` int(11) NOT NULL,
  `call_sid` varchar(255) DEFAULT NULL,
  `call_status` enum('pending','initiated','ringing','answered','completed','busy','no_answer','rejected','switched_off','failed') DEFAULT 'pending',
  `started_at` datetime DEFAULT NULL,
  `answered_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 0,
  `recording_url` text DEFAULT NULL,
  `transcript` longtext DEFAULT NULL,
  `escalation_required` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_interactions`
--

CREATE TABLE `call_interactions` (
  `id` bigint(20) NOT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `user_query` text DEFAULT NULL,
  `bot_response` text DEFAULT NULL,
  `interaction_time` datetime DEFAULT NULL,
  `type` varchar(225) DEFAULT 'call'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `call_id` varchar(100) NOT NULL,
  `bot_id` varchar(50) DEFAULT NULL,
  `bot_name` varchar(150) DEFAULT NULL,
  `call_request_id` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `to_number` varchar(20) DEFAULT NULL,
  `from_number` varchar(20) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `call_date` datetime DEFAULT NULL,
  `call_status` varchar(50) DEFAULT NULL,
  `call_duration` int(11) DEFAULT 0,
  `call_direction` varchar(50) DEFAULT NULL,
  `hangup_source` varchar(50) DEFAULT NULL,
  `recording_url` varchar(500) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `sentiment` varchar(50) DEFAULT NULL,
  `full_conversation` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_questions`
--

CREATE TABLE `call_questions` (
  `id` bigint(20) NOT NULL,
  `question` text NOT NULL,
  `question_type` enum('text','textarea','radio','checkbox','dropdown','file') DEFAULT 'text',
  `options_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options_json`)),
  `is_required` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_ratings`
--

CREATE TABLE `call_ratings` (
  `id` bigint(20) NOT NULL,
  `call_attempt_id` bigint(20) NOT NULL,
  `communication_score` decimal(3,1) DEFAULT NULL,
  `confidence_score` decimal(3,1) DEFAULT NULL,
  `professionalism_score` decimal(3,1) DEFAULT NULL,
  `customer_interest_score` decimal(3,1) DEFAULT NULL,
  `overall_score` decimal(3,1) DEFAULT NULL,
  `strengths` longtext DEFAULT NULL,
  `weaknesses` longtext DEFAULT NULL,
  `missed_questions_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`missed_questions_json`)),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_recordings`
--

CREATE TABLE `call_recordings` (
  `id` bigint(20) NOT NULL,
  `call_attempt_id` bigint(20) NOT NULL,
  `recording_url` text NOT NULL,
  `recording_duration` int(11) DEFAULT 0,
  `transcript` longtext DEFAULT NULL,
  `ai_analysis` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashfree_webhook_logs`
--

CREATE TABLE `cashfree_webhook_logs` (
  `id` int(11) NOT NULL,
  `event_id` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `webhook_version` varchar(20) DEFAULT '2.0',
  `transfer_id` varchar(100) DEFAULT NULL,
  `beneficiary_id` varchar(100) DEFAULT NULL,
  `raw_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_data`)),
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`headers`)),
  `signature` varchar(255) DEFAULT NULL,
  `signature_valid` tinyint(1) DEFAULT 0,
  `processing_status` enum('pending','processing','processed','failed') DEFAULT 'pending',
  `processing_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`processing_result`)),
  `error_message` text DEFAULT NULL,
  `retry_count` int(11) DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_ai_prompt_versions`
--

CREATE TABLE `chatbot_ai_prompt_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `version` int(11) NOT NULL,
  `system_prompt` longtext NOT NULL,
  `temperature` decimal(3,2) DEFAULT 0.70,
  `max_tokens` int(11) DEFAULT 1000,
  `model` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_business_hours`
--

CREATE TABLE `chatbot_business_hours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `shift_order` tinyint(3) UNSIGNED DEFAULT 1,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_faqs`
--

CREATE TABLE `chatbot_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `language` varchar(5) DEFAULT 'en',
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `keywords` text DEFAULT NULL,
  `priority` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_keywords`
--

CREATE TABLE `chatbot_keywords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `response` text NOT NULL,
  `priority` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_logs`
--

CREATE TABLE `chatbot_logs` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_menu`
--

CREATE TABLE `chatbot_menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `language` varchar(5) DEFAULT 'en',
  `title` varchar(100) NOT NULL,
  `type` enum('reply','url','call') NOT NULL,
  `value` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_quick_replies`
--

CREATE TABLE `chatbot_quick_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chatbot_id` bigint(20) UNSIGNED NOT NULL,
  `language` varchar(5) DEFAULT 'en',
  `title` varchar(100) NOT NULL,
  `payload` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_session_state`
--

CREATE TABLE `chatbot_session_state` (
  `id` int(11) NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `flow` varchar(32) DEFAULT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `coverage_type` varchar(32) DEFAULT NULL,
  `duration_years` tinyint(4) DEFAULT NULL,
  `pets_json` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_settings`
--

CREATE TABLE `chatbot_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `chatbot_name` varchar(100) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `welcome_message` text DEFAULT NULL,
  `fallback_message` text DEFAULT NULL,
  `goodbye_message` text DEFAULT NULL,
  `ai_enabled` tinyint(1) DEFAULT 1,
  `human_handoff` tinyint(1) DEFAULT 1,
  `default_language` varchar(10) DEFAULT 'en',
  `response_delay` int(11) DEFAULT 2,
  `system_prompt` longtext DEFAULT NULL,
  `ai_model` varchar(100) DEFAULT NULL,
  `ai_temperature` decimal(3,2) DEFAULT 0.70,
  `ai_max_tokens` int(11) DEFAULT 1000,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_answers`
--

CREATE TABLE `chat_answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_questions`
--

CREATE TABLE `chat_questions` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT 0,
  `question` text NOT NULL,
  `category` enum('booking','service','payment','general') DEFAULT 'booking',
  `for_user` enum('client','service_provider','both') DEFAULT 'both',
  `booking_status` enum('pending','confirmed','ongoing','completed','cancelled') DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_channels`
--

CREATE TABLE `community_channels` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `handle` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` varchar(225) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_channel_joins`
--

CREATE TABLE `community_channel_joins` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `channel_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_comment_dislikes`
--

CREATE TABLE `community_comment_dislikes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_comment_likes`
--

CREATE TABLE `community_comment_likes` (
  `id` bigint(20) NOT NULL,
  `comment_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_notifications`
--

CREATE TABLE `community_notifications` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `actor_id` bigint(20) DEFAULT NULL,
  `type` enum('like','comment','follow','reply') DEFAULT NULL,
  `reference_id` bigint(20) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` bigint(20) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `category` enum('post','question') DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `post_text` text DEFAULT NULL,
  `post_type` enum('text','image','video','question') DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `shares_count` int(11) DEFAULT 0,
  `visibility` enum('public','followers') DEFAULT NULL,
  `comment_section` tinyint(1) DEFAULT NULL,
  `is_pinned` tinyint(1) DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `status` enum('active','deleted','blocked','scheduled') DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_channels`
--

CREATE TABLE `community_post_channels` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `channel_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_comments`
--

CREATE TABLE `community_post_comments` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `parent_comment_id` bigint(20) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `dislikes_count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_dislikes`
--

CREATE TABLE `community_post_dislikes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_likes`
--

CREATE TABLE `community_post_likes` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_media`
--

CREATE TABLE `community_post_media` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `media_type` enum('image','video') DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_reports`
--

CREATE TABLE `community_post_reports` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `title` varchar(225) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_saves`
--

CREATE TABLE `community_post_saves` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_shares`
--

CREATE TABLE `community_post_shares` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_tags`
--

CREATE TABLE `community_post_tags` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `tag` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_tags`
--

CREATE TABLE `community_tags` (
  `id` bigint(20) NOT NULL,
  `tag_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_user_follows`
--

CREATE TABLE `community_user_follows` (
  `id` bigint(20) NOT NULL,
  `follower_id` bigint(20) DEFAULT NULL,
  `following_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) NOT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `assigned_agent_id` bigint(20) DEFAULT NULL,
  `intent` varchar(50) DEFAULT NULL,
  `intent_strength` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `discount_type` enum('percentage','fixed_amount') NOT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `min_order_value` decimal(10,2) DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage_logs`
--

CREATE TABLE `coupon_usage_logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `booking_id` varchar(36) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_notifications`
--

CREATE TABLE `crm_notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_locks`
--

CREATE TABLE `cron_locks` (
  `name` varchar(100) NOT NULL,
  `locked_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demand_supply_snapshot`
--

CREATE TABLE `demand_supply_snapshot` (
  `id` int(11) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `service_type` varchar(30) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `daily_jobs` int(11) DEFAULT NULL,
  `active_sp_count` int(11) DEFAULT NULL,
  `genuine_sp_count` int(11) DEFAULT NULL,
  `required_sp` int(11) GENERATED ALWAYS AS (ceiling(`daily_jobs` * 1.4 / 3)) STORED,
  `gap` int(11) GENERATED ALWAYS AS (`required_sp` - `genuine_sp_count`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `otp` varchar(255) NOT NULL,
  `clinic_name` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `auth_token` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `address` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_documents`
--

CREATE TABLE `doctor_documents` (
  `id` bigint(20) NOT NULL,
  `doctor_id` bigint(20) NOT NULL,
  `document_type` enum('license','degree','id_proof') DEFAULT NULL,
  `file_url` text DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_referral_codes`
--

CREATE TABLE `doctor_referral_codes` (
  `id` bigint(20) NOT NULL,
  `doctor_id` bigint(20) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `short_url` varchar(225) DEFAULT NULL,
  `qr_code_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dog_breeds`
--

CREATE TABLE `dog_breeds` (
  `id` int(11) NOT NULL,
  `pet_type` int(11) DEFAULT NULL,
  `label` varchar(150) NOT NULL,
  `value` varchar(225) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `phone` varchar(225) DEFAULT NULL,
  `profile` varchar(225) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `password` varchar(225) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `fcm_token` varchar(225) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_scan_logs`
--

CREATE TABLE `event_scan_logs` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `scanned_by` int(11) NOT NULL,
  `scan_type` enum('valid','duplicate','invalid') DEFAULT NULL,
  `scanned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_tickets`
--

CREATE TABLE `event_tickets` (
  `id` int(11) NOT NULL,
  `user_id` varchar(225) DEFAULT NULL,
  `order_id` varchar(225) NOT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `status` enum('active','checked_in') DEFAULT 'active',
  `checked_in_by` int(11) DEFAULT NULL,
  `checked_in_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `qr_image` varchar(255) DEFAULT NULL,
  `ticket_status` enum('active','checked_in') DEFAULT 'active',
  `max_entries` int(11) DEFAULT 1,
  `used_entries` int(11) DEFAULT 0,
  `persons_allowed` int(11) DEFAULT 1,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_email` varchar(225) DEFAULT NULL,
  `location` varchar(225) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `package_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`package_details`)),
  `booking_id` varchar(255) DEFAULT NULL,
  `event_id` varchar(255) DEFAULT NULL,
  `whatsapp_status` varchar(255) DEFAULT NULL,
  `email_status` varchar(255) DEFAULT NULL,
  `pet_name` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extra_addons`
--

CREATE TABLE `extra_addons` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Feedback given by this user',
  `user_type` enum('client','provider') NOT NULL COMMENT 'Type of user who gave feedback',
  `rating` tinyint(3) UNSIGNED NOT NULL COMMENT '1–5 emoji rating',
  `comment` text DEFAULT NULL COMMENT 'Optional feedback text',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_ledger`
--

CREATE TABLE `financial_ledger` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED DEFAULT NULL,
  `txn_type` enum('credit','debit') NOT NULL,
  `category` enum('booking_payment','user_refund','provider_payout','platform_fee') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference_table` varchar(100) DEFAULT NULL,
  `reference_id` varchar(225) DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unique_hash` varchar(64) GENERATED ALWAYS AS (sha2(concat(`booking_id`,'-',`txn_type`,'-',`category`,'-',`amount`,'-',`reference_table`,'-',`reference_id`),256)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followups`
--

CREATE TABLE `followups` (
  `id` int(11) UNSIGNED NOT NULL,
  `leadId` int(11) UNSIGNED NOT NULL,
  `leadName` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'Call',
  `date` varchar(20) NOT NULL,
  `time` varchar(20) DEFAULT NULL,
  `priority` enum('Low','Medium','High') NOT NULL DEFAULT 'Medium',
  `notes` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grooming_booking_packages`
--

CREATE TABLE `grooming_booking_packages` (
  `id` int(11) UNSIGNED NOT NULL,
  `booking_id` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `package_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grooming_service_bookings`
--

CREATE TABLE `grooming_service_bookings` (
  `id` varchar(100) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) UNSIGNED DEFAULT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `service_start_date` date NOT NULL,
  `preferable_time` mediumtext NOT NULL,
  `addons` varchar(500) NOT NULL,
  `service_mode` enum('Home','Van') DEFAULT NULL,
  `status` enum('New','Confirmed','Cancelled','Completed','Halted') NOT NULL COMMENT 'New,Confirmed,Cancelled,Completed	',
  `track_status` enum('in_progress','completed','not_started','approved','rejected','terminate') DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `cancel_reason` varchar(225) DEFAULT NULL,
  `repost` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT NULL,
  `source` varchar(20) DEFAULT 'mobile_app',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(11) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `has_invited_sps` tinyint(1) DEFAULT 0,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(100) DEFAULT NULL,
  `coupon_discount` decimal(10,2) DEFAULT 0.00,
  `final_payable` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grooming_tracking`
--

CREATE TABLE `grooming_tracking` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `addon` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'pending',
  `is_approved` varchar(100) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `payment_status` varchar(100) DEFAULT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `rejected_by` enum('user','provider') DEFAULT NULL,
  `images` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_claims`
--

CREATE TABLE `insurance_claims` (
  `id` int(10) UNSIGNED NOT NULL,
  `claim_number` varchar(50) DEFAULT NULL,
  `policy_id` int(10) UNSIGNED NOT NULL,
  `incident_date` date DEFAULT NULL,
  `claim_type` enum('Accident','Illness','Surgery','Routine Care') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `clinic_name` varchar(150) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `invoice_file` varchar(255) DEFAULT NULL,
  `medical_report_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Paid','Under Review','Settled','In Progress') DEFAULT 'Pending',
  `remarks` text NOT NULL,
  `settlement_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_payments`
--

CREATE TABLE `insurance_payments` (
  `id` bigint(20) NOT NULL,
  `policy_id` bigint(20) NOT NULL,
  `renewal_id` bigint(20) DEFAULT NULL,
  `transaction_id` varchar(150) DEFAULT NULL,
  `pg_transaction_id` int(11) DEFAULT NULL,
  `payment_amount` decimal(12,2) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `payment_status` varchar(225) DEFAULT NULL,
  `response_code` varchar(225) DEFAULT NULL,
  `payment_mode` varchar(225) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_policies`
--

CREATE TABLE `insurance_policies` (
  `id` int(10) UNSIGNED NOT NULL,
  `policy_number` varchar(50) NOT NULL,
  `insurance_user_id` int(10) UNSIGNED NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `coverage_type` enum('Basic','Standard','Premium') DEFAULT 'Basic',
  `duration_years` int(11) DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Active','Expired','Pending','Cancelled') DEFAULT 'Pending',
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remarks` varchar(500) DEFAULT NULL,
  `premium_amount` decimal(10,2) DEFAULT NULL,
  `auto_renew` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_policies_quotations`
--

CREATE TABLE `insurance_policies_quotations` (
  `id` int(11) NOT NULL,
  `policy_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `message` varchar(2000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_policy_renewals`
--

CREATE TABLE `insurance_policy_renewals` (
  `id` int(11) NOT NULL,
  `policy_id` bigint(20) NOT NULL,
  `old_start_date` date NOT NULL,
  `old_end_date` date NOT NULL,
  `new_start_date` date NOT NULL,
  `new_end_date` date NOT NULL,
  `renewed_on` datetime DEFAULT current_timestamp(),
  `renewal_status` enum('success','failed','pending') DEFAULT 'success',
  `renewal_method` enum('auto','manual') DEFAULT 'manual',
  `renewed_by` bigint(20) DEFAULT NULL COMMENT 'user_id of admin or customer who renewed',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_providers`
--

CREATE TABLE `insurance_providers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_users`
--

CREATE TABLE `insurance_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_verified` tinyint(1) DEFAULT 0,
  `otp_sent_at` datetime DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `integrity_alerts`
--

CREATE TABLE `integrity_alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `issue_type` enum('wallet_history_mismatch','service_wallet_mismatch') NOT NULL,
  `expected_balance` decimal(10,2) DEFAULT 0.00,
  `actual_balance` decimal(10,2) DEFAULT 0.00,
  `difference` decimal(10,2) DEFAULT 0.00,
  `message` text DEFAULT NULL,
  `checked_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_base`
--

CREATE TABLE `knowledge_base` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--


-- --------------------------------------------------------

--
-- Table structure for table `loyalty_coupons`
--

CREATE TABLE `loyalty_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reward_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `status` enum('active','used','expired') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_milestones`
--

CREATE TABLE `loyalty_milestones` (
  `id` int(11) NOT NULL,
  `spend_amount` decimal(10,2) NOT NULL,
  `points_required` int(11) NOT NULL,
  `tier_name` varchar(50) DEFAULT NULL,
  `spin_enabled` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_rewards`
--

CREATE TABLE `loyalty_rewards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `reward_name` varchar(100) NOT NULL,
  `reward_type` enum('product','coupon','service','insurance','mystery') NOT NULL,
  `reward_value` decimal(10,2) DEFAULT 0.00,
  `probability` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_settings`
--

CREATE TABLE `loyalty_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(100) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `type` enum('int','float','string','json','boolean') DEFAULT 'string',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_spins`
--

CREATE TABLE `loyalty_spins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `reward_id` bigint(20) UNSIGNED NOT NULL,
  `points_used` int(11) NOT NULL,
  `spin_result` varchar(100) DEFAULT NULL,
  `is_collected` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_transactions`
--

CREATE TABLE `loyalty_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL,
  `type` enum('earn','redeem','expire','adjust') NOT NULL,
  `source` varchar(225) DEFAULT 'booking',
  `reference_id` bigint(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expiry_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_user_milestones`
--

CREATE TABLE `loyalty_user_milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `reward_id` int(11) DEFAULT NULL,
  `is_unlocked` tinyint(1) DEFAULT 0,
  `unlocked_at` datetime DEFAULT NULL,
  `is_spin_used` tinyint(1) DEFAULT 0,
  `spins_remaining` int(11) DEFAULT NULL,
  `is_collected` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_wallet`
--

CREATE TABLE `loyalty_wallet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_points` int(11) DEFAULT 0,
  `total_spent` decimal(10,2) DEFAULT 0.00,
  `lifetime_points` int(11) DEFAULT 0,
  `tier` enum('BRONZE','SILVER','GOLD','PLATINUM') DEFAULT 'BRONZE',
  `last_spin_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `spin_cooldown_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) DEFAULT 200.00,
  `discount_percent` int(11) DEFAULT 10,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','expired') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `conversation_id` bigint(20) DEFAULT NULL,
  `sender` enum('user','bot','agent') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `message_type` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` enum('user','provider') NOT NULL,
  `type` varchar(225) DEFAULT NULL,
  `message` varchar(1000) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_queue`
--

CREATE TABLE `notification_queue` (
  `id` int(10) UNSIGNED NOT NULL,
  `tokens` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','normal','high') NOT NULL DEFAULT 'normal',
  `status` enum('pending','processing','sent','failed') NOT NULL DEFAULT 'pending',
  `attempts` int(3) NOT NULL DEFAULT 0,
  `scheduled_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `route_name` varchar(500) DEFAULT NULL,
  `available_at` datetime DEFAULT current_timestamp(),
  `last_error` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(225) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(225) NOT NULL,
  `placeholders` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_logs`
--

CREATE TABLE `otp_logs` (
  `id` bigint(20) NOT NULL,
  `caller_id` bigint(20) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `package_name` varchar(100) NOT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sessions` varchar(100) DEFAULT NULL,
  `included_addons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `is_most_popular` tinyint(1) DEFAULT 0,
  `icon` varchar(255) DEFAULT NULL,
  `web_icon` varchar(225) DEFAULT NULL,
  `color` varchar(225) DEFAULT NULL,
  `price_per` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_addons`
--

CREATE TABLE `package_addons` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `is_included` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout_transactions`
--

CREATE TABLE `payout_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `withdrawal_id` int(11) DEFAULT NULL,
  `refund_id` int(11) DEFAULT NULL,
  `transfer_id` varchar(255) NOT NULL,
  `cf_transfer_id` varchar(255) DEFAULT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bank_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'INR',
  `transfer_mode` varchar(50) DEFAULT 'banktransfer',
  `status` varchar(50) NOT NULL DEFAULT 'PENDING',
  `remarks` text DEFAULT NULL,
  `reference_id` varchar(255) DEFAULT NULL,
  `utr` varchar(255) DEFAULT NULL,
  `acknowledged` tinyint(1) DEFAULT 0,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`)),
  `transaction_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `webhook_processed` tinyint(1) DEFAULT 0,
  `last_webhook_at` datetime DEFAULT NULL,
  `webhook_attempts` int(11) DEFAULT 0,
  `failure_reason` varchar(500) DEFAULT NULL,
  `failure_code` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_changes`
--

CREATE TABLE `pending_changes` (
  `id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `timings` varchar(225) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `effective_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_care_checklists`
--

CREATE TABLE `pet_care_checklists` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_care_routines`
--

CREATE TABLE `pet_care_routines` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_id` varchar(255) NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `feeding_instructions` text DEFAULT NULL,
  `walk_instructions` text DEFAULT NULL,
  `medicine_instructions` text DEFAULT NULL,
  `special_notes` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_feeding_schedules`
--

CREATE TABLE `pet_feeding_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `routine_id` int(10) UNSIGNED NOT NULL,
  `feeding_time` varchar(225) DEFAULT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `food_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_medications`
--

CREATE TABLE `pet_medications` (
  `id` int(10) UNSIGNED NOT NULL,
  `routine_id` int(10) UNSIGNED NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `medicine_time` varchar(225) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_types`
--

CREATE TABLE `pet_types` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_walk_schedules`
--

CREATE TABLE `pet_walk_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `routine_id` int(10) UNSIGNED NOT NULL,
  `walk_time` varchar(225) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_wallet`
--

CREATE TABLE `platform_wallet` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_wallet_histories`
--

CREATE TABLE `platform_wallet_histories` (
  `id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(500) DEFAULT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `provider_id` int(11) UNSIGNED DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `service_type` int(11) DEFAULT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `idempotency_key` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `premium_requests`
--

CREATE TABLE `premium_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `requested_at` datetime DEFAULT current_timestamp(),
  `status` enum('Requested','Assigned','Accepted','Rejected','Completed','Cancelled','Paid') DEFAULT 'Requested',
  `assigned_provider_id` int(10) UNSIGNED DEFAULT NULL,
  `admin_price` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `admin_notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `package_name` varchar(225) DEFAULT NULL,
  `package_price` decimal(10,2) DEFAULT NULL,
  `assigned_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) NOT NULL,
  `appointment_id` bigint(20) NOT NULL,
  `doctor_id` bigint(20) NOT NULL,
  `pet_id` bigint(20) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `medicines` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_attributes`
--

CREATE TABLE `provider_attributes` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `works_with_aggressive_pets` tinyint(1) DEFAULT NULL,
  `has_certification` tinyint(1) DEFAULT NULL,
  `previous_experience` tinyint(1) DEFAULT NULL,
  `documents_ready` tinyint(1) DEFAULT NULL,
  `communication_quality` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_beneficiaries`
--

CREATE TABLE `provider_beneficiaries` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) NOT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `beneficiary_id` varchar(255) NOT NULL,
  `cf_bene_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_calls`
--

CREATE TABLE `provider_calls` (
  `id` bigint(20) NOT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `from_number` varchar(20) DEFAULT NULL,
  `to_number` varchar(20) DEFAULT NULL,
  `call_direction` varchar(20) DEFAULT NULL,
  `call_status` varchar(50) DEFAULT NULL,
  `hangup_source` varchar(50) DEFAULT NULL,
  `call_duration` int(11) DEFAULT NULL,
  `call_date` datetime DEFAULT NULL,
  `recording_url` text DEFAULT NULL,
  `sentiment` varchar(20) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `low_interaction` tinyint(1) DEFAULT 0,
  `call_outcome` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_languages`
--

CREATE TABLE `provider_languages` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `language_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_reports`
--

CREATE TABLE `provider_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `report_reason` varchar(100) NOT NULL,
  `report_comment` text DEFAULT NULL,
  `status` enum('pending','in_review','resolved','rejected') NOT NULL DEFAULT 'pending',
  `reported_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_scores`
--

CREATE TABLE `provider_scores` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) DEFAULT NULL,
  `call_id` bigint(20) DEFAULT NULL,
  `experience_score` decimal(3,2) DEFAULT NULL,
  `communication_score` decimal(3,2) DEFAULT NULL,
  `availability_score` decimal(3,2) DEFAULT NULL,
  `document_score` decimal(3,2) DEFAULT NULL,
  `sentiment_score` decimal(3,2) DEFAULT NULL,
  `final_score` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_whatsapp_chats`
--

CREATE TABLE `provider_whatsapp_chats` (
  `jid` varchar(100) NOT NULL,
  `last_message` text DEFAULT NULL,
  `last_message_time` varchar(20) DEFAULT NULL,
  `unread` int(11) DEFAULT 0,
  `is_group` tinyint(1) DEFAULT 0,
  `profile_picture` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_whatsapp_contacts`
--

CREATE TABLE `provider_whatsapp_contacts` (
  `jid` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `push_name` varchar(255) DEFAULT NULL,
  `verified_name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `last_seen` varchar(100) DEFAULT NULL,
  `lid` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_whatsapp_messages`
--

CREATE TABLE `provider_whatsapp_messages` (
  `id` varchar(100) NOT NULL,
  `jid` varchar(100) NOT NULL,
  `text` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `sender` enum('me','contact') NOT NULL,
  `status` enum('sent','delivered','read','played','failed') DEFAULT 'sent',
  `timestamp` varchar(20) DEFAULT NULL,
  `quoted_message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quoted_message`)),
  `caption` text DEFAULT NULL,
  `media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media`)),
  `reply_to` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_whatsapp_sessions`
--

CREATE TABLE `provider_whatsapp_sessions` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `current_step` varchar(50) DEFAULT 'MAIN_MENU',
  `temp_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`temp_data`)),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` enum('user','provider') DEFAULT NULL,
  `message` varchar(5000) NOT NULL,
  `status` enum('new','pending','in_progress','completed') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `booking_id` varchar(36) NOT NULL,
  `invited_sp` tinyint(1) DEFAULT 0,
  `invitation_id` int(11) DEFAULT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `actual_amount` decimal(10,2) NOT NULL,
  `extra_amount` decimal(10,2) DEFAULT NULL,
  `discount` int(10) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `platform_charges` decimal(10,2) DEFAULT NULL,
  `gst` float DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `receivable_amount` decimal(10,2) DEFAULT NULL,
  `payable_amount` decimal(10,2) DEFAULT NULL,
  `addons` tinyint(1) DEFAULT NULL,
  `status` enum('New','Accepted','Rejected','Completed','Cancelled') NOT NULL COMMENT 'New,Accepted,Rejected,Completed,Cancelled',
  `service_mode` enum('home_service','van_service','every_day','alternative_day') DEFAULT NULL,
  `extra_sessions` varchar(225) DEFAULT NULL,
  `sp_timings` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quotations` varchar(500) DEFAULT NULL,
  `cancel_reason` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_addons`
--

CREATE TABLE `quotation_addons` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `addon` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `user_confirmed` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reconciliation_wallet`
--

CREATE TABLE `reconciliation_wallet` (
  `id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(100) NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `split_to` enum('user_refund','provider_payout','platform_fee') NOT NULL,
  `reference_id` varchar(225) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL,
  `referrer_type` enum('user','doctor') DEFAULT 'user',
  `referred_user_id` bigint(20) UNSIGNED NOT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `status` enum('pending','completed','rewarded') DEFAULT 'pending',
  `job_completed_at` datetime DEFAULT NULL,
  `reward_points` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `reward_given` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_progress`
--

CREATE TABLE `referral_progress` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `referred_user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `completed_amount` decimal(10,2) DEFAULT 0.00,
  `rewarded` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_activity_log`
--

CREATE TABLE `report_activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_orders`
--

CREATE TABLE `reward_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reward_id` bigint(20) UNSIGNED NOT NULL,
  `order_status` enum('pending','confirmed','shipped','delivered','cancelled','processing') DEFAULT 'pending',
  `address` text DEFAULT NULL,
  `houseno_floor` varchar(225) DEFAULT NULL,
  `building_blockno` varchar(225) DEFAULT NULL,
  `landmark_areaname` varchar(225) DEFAULT NULL,
  `area` varchar(225) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip_code` varchar(225) DEFAULT NULL,
  `latitude` varchar(225) DEFAULT NULL,
  `longitude` varchar(225) DEFAULT NULL,
  `type` varchar(225) DEFAULT NULL,
  `tracking_url` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ride_tracking`
--

CREATE TABLE `ride_tracking` (
  `id` int(11) NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(500) NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `ride_date` date NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` varchar(225) NOT NULL,
  `latitude` varchar(225) DEFAULT NULL,
  `longitude` varchar(225) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `description` varchar(225) DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salesperson_analytics`
--

CREATE TABLE `salesperson_analytics` (
  `id` int(10) UNSIGNED NOT NULL,
  `salesperson` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `totalCalls` int(11) NOT NULL DEFAULT 0,
  `talkTime` int(11) NOT NULL DEFAULT 0 COMMENT 'Talk time in seconds',
  `incoming` int(11) NOT NULL DEFAULT 0,
  `outgoing` int(11) NOT NULL DEFAULT 0,
  `missed` int(11) NOT NULL DEFAULT 0,
  `connected` int(11) NOT NULL DEFAULT 0,
  `rejected` int(11) NOT NULL DEFAULT 0,
  `notConnected` int(11) NOT NULL DEFAULT 0,
  `longestCall` int(11) NOT NULL DEFAULT 0 COMMENT 'Longest call duration in seconds',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_available_states`
--

CREATE TABLE `service_available_states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `short_code` varchar(10) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_pause_logs`
--

CREATE TABLE `service_pause_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pause_start_date` date NOT NULL,
  `pause_end_date` date DEFAULT NULL,
  `resume_date` date NOT NULL,
  `remaining_days` int(11) DEFAULT NULL,
  `status` enum('Scheduled','Resumed','Cancelled','Paused') DEFAULT 'Scheduled',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(500) NOT NULL,
  `permanent_latitude` decimal(10,7) NOT NULL,
  `permanent_longitude` decimal(10,7) NOT NULL,
  `service_address` varchar(500) NOT NULL,
  `service_latitude` decimal(10,7) NOT NULL,
  `service_longitude` decimal(10,7) NOT NULL,
  `geohash` varchar(12) DEFAULT NULL,
  `area` varchar(225) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `aadhar_name` varchar(255) DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `aadhar_verified` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending_verification','rejected','suspended','pending_kyc') DEFAULT NULL,
  `mobile_otp` varchar(255) DEFAULT NULL,
  `mail_otp` varchar(10) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `device_token` varchar(2000) DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `is_verified` varchar(11) DEFAULT NULL,
  `followup_status` varchar(225) DEFAULT NULL,
  `followup_comment` varchar(225) DEFAULT NULL,
  `followup_notes` varchar(225) DEFAULT NULL,
  `followup_date` date DEFAULT NULL,
  `reports_count` int(255) DEFAULT NULL,
  `mail_otp_expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `ai_rating` int(11) DEFAULT NULL,
  `rejection_reason` varchar(225) DEFAULT NULL,
  `registration_paid` tinyint(1) DEFAULT NULL,
  `ai_about` text DEFAULT NULL,
  `ai_review` text DEFAULT NULL,
  `experience_summary` varchar(255) DEFAULT NULL,
  `ai_strengths` text DEFAULT NULL,
  `ai_weaknesses` text DEFAULT NULL,
  `ai_flags` text DEFAULT NULL,
  `hiring_confidence` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `tracking_id` varchar(100) DEFAULT NULL,
  `tracking_url` text DEFAULT NULL,
  `status` enum('created','in_transit','out_for_delivery','delivered') DEFAULT 'created',
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `amazon_order_id` varchar(100) DEFAULT NULL,
  `tracking_source` enum('amazon','manual','shiprocket') DEFAULT 'manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitting_checklist_media`
--

CREATE TABLE `sitting_checklist_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `media_type` enum('image','video') NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitting_service_bookings`
--

CREATE TABLE `sitting_service_bookings` (
  `id` varchar(225) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `address_id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) UNSIGNED NOT NULL,
  `preferable_time` varchar(225) DEFAULT NULL,
  `service_start_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `cc_required` tinyint(1) DEFAULT NULL,
  `days` varchar(225) NOT NULL,
  `source` varchar(225) DEFAULT NULL,
  `type` varchar(225) DEFAULT NULL,
  `comment` varchar(225) DEFAULT NULL,
  `status` enum('New','Confirmed','Cancelled','Completed','onHold','Halted') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(225) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(225) DEFAULT NULL,
  `coupon_discount` decimal(10,2) DEFAULT NULL,
  `final_payable` decimal(10,2) DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `original_booking_id` varchar(225) DEFAULT NULL,
  `approval` varchar(225) DEFAULT NULL,
  `withdrawal_amount` decimal(10,2) DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT NULL,
  `payment_status` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitting_tracking`
--

CREATE TABLE `sitting_tracking` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `tracking_date` date DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('not_started','in_progress','completed','Untracked','rejected') DEFAULT NULL,
  `completed_by` varchar(20) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_approved` varchar(50) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `payment_status` enum('pending','failed','paid','rejected') DEFAULT NULL,
  `is_untracked` tinyint(1) DEFAULT 0,
  `is_payment_requested` tinyint(1) DEFAULT 0,
  `scheduled_start_time` datetime DEFAULT NULL COMMENT 'Planned start time set at booking',
  `is_late` tinyint(1) NOT NULL DEFAULT 0,
  `distance_walked` decimal(10,2) NOT NULL DEFAULT 0.00,
  `late_minutes` smallint(6) DEFAULT NULL,
  `service_time` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_aadhar`
--

CREATE TABLE `sp_aadhar` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_bank_details`
--

CREATE TABLE `sp_bank_details` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `type` enum('Bank','UPI') NOT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(50) DEFAULT NULL,
  `account_holder_name` varchar(100) DEFAULT NULL,
  `is_bank_verified` tinyint(1) DEFAULT NULL,
  `upi_number` varchar(20) DEFAULT NULL,
  `upi_id` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_boarding_details`
--

CREATE TABLE `sp_boarding_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `accepted_pets` enum('dog','cat') NOT NULL,
  `can_handle_aggressive_pets` varchar(225) NOT NULL,
  `boarding_type` enum('home','kennel') NOT NULL,
  `max_pets_capacity` enum('50','80') DEFAULT NULL,
  `food_type` enum('veg','nonveg') DEFAULT NULL,
  `is_certified_boarder` varchar(225) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `is_accidentally_insured` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `experience` varchar(225) DEFAULT NULL,
  `available_time` varchar(225) DEFAULT NULL,
  `confidence` varchar(225) DEFAULT NULL,
  `rating` float(10,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `interview_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interview_answers`)),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_bookings`
--

CREATE TABLE `sp_bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(100) NOT NULL,
  `service_id` int(11) NOT NULL,
  `type` enum('permanent','temporary') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `report_reason` varchar(100) DEFAULT NULL,
  `report_comment` varchar(1000) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_grooming_details`
--

CREATE TABLE `sp_grooming_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `verifier_id` int(11) DEFAULT NULL,
  `service_radius_km` int(11) NOT NULL,
  `can_handle_aggressive_pets` varchar(255) DEFAULT NULL,
  `grooming_type` enum('grooming','grooming_and_cut','grooming_and_trimming') DEFAULT NULL,
  `accepted_pets` enum('dog','cat','both') NOT NULL,
  `service_location` enum('home_service','van_service') DEFAULT NULL,
  `is_certified_groomer` varchar(255) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `is_accidentally_insured` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `experience` varchar(225) DEFAULT NULL,
  `available_time` varchar(225) DEFAULT NULL,
  `confidence` varchar(225) DEFAULT NULL,
  `rating` float(10,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `interview_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interview_answers`)),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_kyc`
--

CREATE TABLE `sp_kyc` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `aadhaar_full_name` varchar(5000) NOT NULL,
  `aadhaar_number` varchar(12) NOT NULL,
  `aadhaar_dob` date NOT NULL,
  `aadhaar_gender` varchar(2) NOT NULL,
  `aadhaar_photo` varchar(225) NOT NULL,
  `aadhaar_address` varchar(5000) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_reviews`
--

CREATE TABLE `sp_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) DEFAULT NULL,
  `rating` decimal(10,1) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `reviewed_by` enum('admin','user') DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `call_record` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_scores`
--

CREATE TABLE `sp_scores` (
  `sp_id` int(11) NOT NULL,
  `kyc_score` tinyint(4) DEFAULT 0,
  `police_verified` tinyint(1) DEFAULT 0,
  `experience_score` tinyint(4) DEFAULT 0,
  `behavior_score` tinyint(4) DEFAULT 0,
  `quality_score` tinyint(4) DEFAULT 0,
  `genuine_score` tinyint(4) GENERATED ALWAYS AS (`kyc_score` * 0.25 + `police_verified` * 20 + `experience_score` * 0.15 + `behavior_score` * 0.25 + `quality_score` * 0.35) STORED,
  `computed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_services`
--

CREATE TABLE `sp_services` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `payment_paid` tinyint(1) DEFAULT NULL,
  `call_verified` tinyint(1) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_service_images`
--

CREATE TABLE `sp_service_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(225) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_service_wallet`
--

CREATE TABLE `sp_service_wallet` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `walking_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `training_amount` decimal(10,2) DEFAULT 0.00,
  `boarding_amount` decimal(10,2) DEFAULT 0.00,
  `grooming_amount` decimal(10,2) DEFAULT 0.00,
  `sitting_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed','refunded') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_sitting_details`
--

CREATE TABLE `sp_sitting_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `service_radius_km` int(11) NOT NULL,
  `can_handle_aggressive_pets` enum('yes','no') NOT NULL,
  `is_certified_petsitter` enum('yes','no') NOT NULL,
  `accepted_pets` enum('dog','cat','both') DEFAULT NULL,
  `certificate` varchar(255) NOT NULL,
  `profile_video` varchar(255) DEFAULT NULL COMMENT 'URL of uploaded profile video; required for Tier 1',
  `profile_video_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = ops-verified video',
  `is_accidentally_insured` enum('yes','no') NOT NULL,
  `has_pets_at_home` enum('yes','no') NOT NULL DEFAULT 'no',
  `emergency_contact_name` varchar(255) NOT NULL,
  `emergency_contact_phone` varchar(255) NOT NULL,
  `experience` varchar(225) DEFAULT NULL,
  `available_time` varchar(225) DEFAULT NULL,
  `confidence` varchar(225) DEFAULT NULL,
  `rating` decimal(10,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `interview_answers` longtext DEFAULT NULL,
  `verifier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_stats`
--

CREATE TABLE `sp_stats` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `total_walks` int(11) NOT NULL DEFAULT 0 COMMENT 'Total walk sessions ever assigned',
  `completed_walks` int(11) NOT NULL DEFAULT 0 COMMENT 'walking_tracking rows with status=completed',
  `cancelled_walks` int(11) NOT NULL DEFAULT 0 COMMENT 'Walks cancelled by the walker (not the client)',
  `completion_rate` decimal(5,2) DEFAULT NULL COMMENT 'completed_walks / total_walks * 100',
  `cancellation_rate` decimal(5,2) DEFAULT NULL COMMENT 'cancelled_walks / total_walks * 100',
  `on_time_percentage` decimal(5,2) DEFAULT NULL COMMENT '% walks started within 10 min of scheduled_start_time',
  `avg_rating` decimal(3,2) DEFAULT NULL COMMENT 'Simple average from sp_reviews for walking service',
  `weighted_rating` decimal(3,2) DEFAULT NULL COMMENT 'Weighted: recent walks count more (decay window = 90 days)',
  `complaint_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Complaints in the last 30 days',
  `complaint_rate` decimal(5,2) DEFAULT NULL COMMENT 'complaint_count / completed_walks * 100',
  `strike_count` int(11) DEFAULT NULL,
  `repeat_client_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Distinct user_ids who booked this walker more than once',
  `last_30d_walks` int(11) NOT NULL DEFAULT 0 COMMENT 'Completed walks in the last 30 calendar days',
  `last_30d_avg_rating` decimal(3,2) DEFAULT NULL,
  `last_30d_on_time_pct` decimal(5,2) DEFAULT NULL,
  `last_30d_cancellation_rate` decimal(5,2) DEFAULT NULL,
  `last_30d_complaint_rate` decimal(5,2) DEFAULT NULL,
  `ranking_score` decimal(6,4) DEFAULT NULL COMMENT 'Composite score used for search ordering within a tier',
  `last_evaluated_at` datetime DEFAULT NULL COMMENT 'Last time the cron recalculated these stats',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `tier` varchar(225) DEFAULT NULL,
  `rejected_walks` int(11) DEFAULT NULL,
  `untracked_walks` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Computed walker performance stats — refreshed by weekly cron';

-- --------------------------------------------------------

--
-- Table structure for table `sp_strikes`
--

CREATE TABLE `sp_strikes` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `strike_type` enum('late','cancellation','complaint') NOT NULL COMMENT 'late=1pt, cancellation=2pt, complaint=3pt',
  `strike_weight` tinyint(1) NOT NULL COMMENT 'Points added to strike_count: late=1, cancellation=2, complaint=3',
  `source_type` enum('walking_tracking','walking_service_bookings','provider_reports','manual') NOT NULL,
  `source_id` varchar(100) DEFAULT NULL COMMENT 'ID from the source table (booking_id or tracking id)',
  `reason` varchar(500) DEFAULT NULL COMMENT 'Human-readable reason shown in transparency view',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = expired or manually pardoned',
  `expires_at` datetime NOT NULL COMMENT 'Strike expires 30 days after issue (rolling reset)',
  `pardoned_by` int(11) DEFAULT NULL COMMENT 'Admin ID who pardoned this strike; NULL = not pardoned',
  `pardoned_at` datetime DEFAULT NULL,
  `pardon_reason` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Immutable strike log; active strikes computed from is_active + expires_at';

-- --------------------------------------------------------

--
-- Table structure for table `sp_tiers`
--

CREATE TABLE `sp_tiers` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `tier` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1 = Highly Recommended, 2 = Recommended, 3 = Suggested',
  `tier_label` enum('tier_1','tier_2','tier_3') NOT NULL DEFAULT 'tier_3',
  `previous_tier` tinyint(1) DEFAULT NULL COMMENT 'Tier before the most recent change; useful for revert',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = auto-inactive (30 days no progress in Tier 3, or admin suspend)',
  `freeze_until` datetime DEFAULT NULL COMMENT 'Tier locked until this datetime (used for 8-strike 7-day freeze)',
  `is_frozen` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = tier cannot change (no upgrade or downgrade) until freeze_until',
  `earnings_multiplier` decimal(4,2) NOT NULL DEFAULT 1.00 COMMENT '1.2 for Tier 1, 1.0 for Tier 2, 0.8 for Tier 3',
  `strike_count` tinyint(3) NOT NULL DEFAULT 0 COMMENT 'Active strikes; resets every 30 days via cron',
  `strikes_reset_at` datetime DEFAULT NULL COMMENT 'Next scheduled strike reset date',
  `daily_bid_limit` smallint(5) DEFAULT NULL COMMENT 'NULL = unlimited; 5 for Tier 3; 3/week cap for Tier 2 on premium jobs',
  `radius_limit_km` smallint(5) DEFAULT NULL COMMENT 'NULL = no cap; 5 km for Tier 3',
  `can_access_premium` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = allowed to bid on premium/high-value bookings',
  `tier_assigned_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'When the current tier was first assigned',
  `last_evaluated_at` datetime DEFAULT NULL COMMENT 'Last auto-evaluation run timestamp',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `downgrade_grace_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Active tier assignment for each walker';

-- --------------------------------------------------------

--
-- Table structure for table `sp_tier_history`
--

CREATE TABLE `sp_tier_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `old_tier` tinyint(1) DEFAULT NULL COMMENT 'NULL on first assignment',
  `new_tier` tinyint(1) NOT NULL,
  `change_reason` enum('initial_assignment','auto_evaluation','strike_downgrade','tier_freeze','upgrade_request_approved','inactivity_inactive','manual_admin') NOT NULL,
  `triggered_by` int(11) DEFAULT NULL COMMENT 'Admin ID for manual changes; NULL for automated',
  `reference_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID in walker_tier_upgrade_requests if reason = upgrade_request_approved',
  `notes` varchar(1000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Append-only log of every tier change per walker';

-- --------------------------------------------------------

--
-- Table structure for table `sp_tier_upgrade_requests`
--

CREATE TABLE `sp_tier_upgrade_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `current_tier` tinyint(1) NOT NULL,
  `requested_tier` tinyint(1) NOT NULL,
  `eligibility_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON: {avg_rating, completed_walks, on_time_pct, cancellation_rate, police_verified, ...}',
  `auto_eligible` tinyint(1) DEFAULT NULL COMMENT '1 = auto approved, 0 = borderline → ops review, NULL = not yet checked',
  `auto_check_notes` varchar(1000) DEFAULT NULL COMMENT 'Which criteria passed/failed for transparency',
  `status` enum('pending','auto_approved','ops_review','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL COMMENT 'Admin/ops user ID',
  `reviewed_at` datetime DEFAULT NULL,
  `review_notes` varchar(1000) DEFAULT NULL,
  `sla_deadline` datetime DEFAULT NULL COMMENT 'Should be reviewed within 48h; used for SLA breach alerts',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Walker self-requested tier upgrades with auto-eligibility check + ops SLA';

-- --------------------------------------------------------

--
-- Table structure for table `sp_training_details`
--

CREATE TABLE `sp_training_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `verifier_id` int(11) DEFAULT NULL,
  `service_radius_km` varchar(255) DEFAULT NULL,
  `can_handle_aggressive_pets` enum('yes','no') DEFAULT NULL,
  `obedience_training` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `intermediate_training` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `behavioral_training` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `advanced_training` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `extra_addons` text DEFAULT NULL,
  `extra_skills` text DEFAULT NULL,
  `training_type` enum('every_day','alternative_day') NOT NULL,
  `is_certified_trainer` enum('yes','no') DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `is_accidentally_insured` enum('yes','no') DEFAULT NULL,
  `insurance_number` varchar(100) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(50) DEFAULT NULL,
  `experience` varchar(225) DEFAULT NULL,
  `available_time` varchar(225) DEFAULT NULL,
  `confidence` varchar(225) DEFAULT NULL,
  `rating` decimal(10,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `interview_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interview_answers`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_training_packages`
--

CREATE TABLE `sp_training_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `addons` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_verifications`
--

CREATE TABLE `sp_verifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `email_verified` tinyint(1) NOT NULL,
  `mobile_verified` tinyint(1) NOT NULL,
  `address_proof` varchar(255) NOT NULL,
  `address_proof_verified` tinyint(1) NOT NULL,
  `identity_proof` varchar(255) NOT NULL,
  `identity_proof_verified` tinyint(1) NOT NULL,
  `police_verification` varchar(255) NOT NULL,
  `police_verification_verified` tinyint(1) NOT NULL,
  `post_card` varchar(255) NOT NULL,
  `post_card_verified` tinyint(1) NOT NULL,
  `sitting_verified` tinyint(1) DEFAULT 0,
  `certificate` varchar(255) NOT NULL,
  `walking_verified` tinyint(1) DEFAULT 0,
  `training_verified` tinyint(1) DEFAULT 0,
  `grooming_verified` tinyint(1) DEFAULT 0,
  `boarding_verified` tinyint(1) DEFAULT 0,
  `certificate_verified` tinyint(1) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `profile_picture_verified` tinyint(1) NOT NULL,
  `call_verification` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_verified_bank_details`
--

CREATE TABLE `sp_verified_bank_details` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `reference_id` int(11) NOT NULL,
  `account_holder_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(20) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `branch` varchar(150) DEFAULT NULL,
  `micr` bigint(20) DEFAULT NULL,
  `name_match_score` decimal(5,2) DEFAULT NULL,
  `name_match_result` varchar(50) DEFAULT NULL,
  `account_status` varchar(50) DEFAULT NULL,
  `account_status_code` varchar(50) DEFAULT NULL,
  `utr` varchar(30) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_walking_details`
--

CREATE TABLE `sp_walking_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `verifier_id` int(11) DEFAULT NULL,
  `service_radius_km` int(11) NOT NULL,
  `can_handle_aggressive_pets` enum('yes','no') NOT NULL,
  `is_certified_walker` enum('yes','no') NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `profile_video` varchar(255) DEFAULT NULL COMMENT 'URL of uploaded profile video; required for Tier 1',
  `profile_video_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = ops-verified video',
  `is_accidentally_insured` enum('yes','no') NOT NULL,
  `emergency_contact_name` varchar(255) NOT NULL,
  `emergency_contact_phone` varchar(255) NOT NULL,
  `experience` varchar(225) DEFAULT NULL,
  `available_time` varchar(225) DEFAULT NULL,
  `confidence` varchar(225) DEFAULT NULL,
  `rating` decimal(10,1) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `interview_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interview_answers`)),
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_wallet_histories`
--

CREATE TABLE `sp_wallet_histories` (
  `id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(500) DEFAULT NULL,
  `provider_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `category` varchar(225) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) DEFAULT 0.00,
  `earned_balance` decimal(10,2) DEFAULT 0.00,
  `description` mediumtext DEFAULT NULL,
  `checksum` varchar(225) DEFAULT NULL,
  `prev_checksum` varchar(225) DEFAULT NULL,
  `hash_signature` varchar(128) DEFAULT NULL,
  `reference_type` enum('booking_amount','refund_amount','commission','withdrawal','manual_adjustment') DEFAULT NULL,
  `reference_id` varchar(225) DEFAULT NULL,
  `idempotency_key` varchar(5000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Triggers `sp_wallet_histories`
--
DELIMITER $$
CREATE TRIGGER `trg_sp_wallet_history_delete_audit` BEFORE DELETE ON `sp_wallet_histories` FOR EACH ROW BEGIN
  INSERT INTO wallet_tampered_log (user_id, tampered_txn_id, event_type, details)
  VALUES (
    OLD.provider_id,
    OLD.id,
    'delete',
    CONCAT('Manual delete detected on txn ID ', OLD.id, ' (amount=', OLD.amount, ')')
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_sp_wallet_history_update_audit` BEFORE UPDATE ON `sp_wallet_histories` FOR EACH ROW BEGIN
  -- Detect changes to sensitive fields
  IF (OLD.amount <> NEW.amount
      OR OLD.transaction_type <> NEW.transaction_type
      OR OLD.category <> NEW.category
      OR OLD.balance_after <> NEW.balance_after) THEN

    INSERT INTO wallet_tampered_log (user_id, tampered_txn_id, event_type, details)
    VALUES (
      OLD.provider_id,
      OLD.id,
      'update',
      CONCAT('Manual update detected on txn ID ', OLD.id, 
             ' | Old Amount: ', OLD.amount, 
             ' | New Amount: ', NEW.amount)
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sp_withdrawal_wallet`
--

CREATE TABLE `sp_withdrawal_wallet` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_assignments`
--

CREATE TABLE `target_assignments` (
  `id` int(11) NOT NULL,
  `repId` varchar(50) NOT NULL,
  `repName` varchar(255) NOT NULL,
  `templateId` int(11) NOT NULL,
  `tiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tiers`)),
  `assignedMonth` varchar(10) NOT NULL,
  `assignedBy` varchar(255) DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_templates`
--

CREATE TABLE `target_templates` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT 'Core Service',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `task_type` enum('one_time','repeated') DEFAULT 'one_time',
  `start_date` date NOT NULL,
  `repeat_interval` enum('daily','weekly','monthly','yearly') DEFAULT NULL,
  `alert_before_days` int(11) DEFAULT 2,
  `status` enum('pending','completed','cancelled','in_progress') DEFAULT 'pending',
  `resolved_comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_media`
--

CREATE TABLE `task_media` (
  `id` bigint(20) NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `pet_id` bigint(20) NOT NULL,
  `task_type` enum('feeding','medication','walk') NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `media_type` enum('image','video') NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(225) NOT NULL,
  `value` varchar(225) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `module_type` varchar(225) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('client','service_provider') NOT NULL,
  `category` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('open','in_progress','resolved','closed','reopened') DEFAULT 'open',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `is_rating_requested` tinyint(1) DEFAULT NULL,
  `is_bank_requested` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_feedback`
--

CREATE TABLE `ticket_feedback` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `rating` varchar(225) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('client','service_provider','support') NOT NULL,
  `message` text DEFAULT NULL,
  `attachments` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_service_bookings`
--

CREATE TABLE `training_service_bookings` (
  `id` varchar(50) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `service_start_date` date NOT NULL,
  `preferable_time` longtext NOT NULL,
  `addons` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('New','Confirmed','Cancelled','Completed','Halted','OnHold') DEFAULT NULL COMMENT 'New,Confirmed,Cancelled,Completed',
  `cancel_reason` varchar(5000) DEFAULT NULL,
  `original_booking_id` varchar(50) DEFAULT NULL,
  `type` varchar(225) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT NULL,
  `source` varchar(20) DEFAULT 'mobile_app',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(11) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `has_invited_sps` tinyint(1) DEFAULT 0,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(100) DEFAULT NULL,
  `coupon_discount` decimal(10,2) DEFAULT 0.00,
  `final_payable` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_tracking`
--

CREATE TABLE `training_tracking` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Primary Key',
  `booking_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL COMMENT 'Service provider ID',
  `pet_id` int(10) UNSIGNED NOT NULL COMMENT 'Pet ID being trained',
  `addon` varchar(255) DEFAULT NULL COMMENT 'addon ',
  `tracking_date` date NOT NULL,
  `status` enum('pending','in_progress','completed','rejected','approved') NOT NULL DEFAULT 'pending' COMMENT 'Training status',
  `video` varchar(225) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL COMMENT 'Datetime when approved',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending' COMMENT 'Payment status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Record creation timestamp',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Record update timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks training progress and statuses';

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `booking_id` varchar(255) DEFAULT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('booking','registration','subscription','addon','walking_registration','grooming_registration','training_registration','sitting_registration') NOT NULL DEFAULT 'booking',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `status` varchar(250) NOT NULL,
  `refund_status` varchar(50) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `cf_payment_method` varchar(100) DEFAULT NULL,
  `cf_refund_id` varchar(255) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `refund_date` datetime DEFAULT NULL,
  `response_code` varchar(50) DEFAULT NULL,
  `response_message` text DEFAULT NULL,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`)),
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `cf_settlement_id` varchar(255) DEFAULT NULL,
  `settlement_status` varchar(50) DEFAULT NULL,
  `cf_payment_link` text DEFAULT NULL,
  `cf_payment_session_id` varchar(255) DEFAULT NULL,
  `settlement_date` datetime DEFAULT NULL,
  `cf_order_id` varchar(255) DEFAULT NULL,
  `webhook_received` tinyint(1) DEFAULT 0,
  `webhook_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`webhook_data`)),
  `cf_payment_id` varchar(255) DEFAULT NULL,
  `failure_reason` varchar(500) DEFAULT NULL,
  `failure_code` varchar(225) DEFAULT NULL,
  `failure_stage` varchar(225) DEFAULT NULL,
  `idempotency_key` varchar(5000) DEFAULT NULL,
  `verified_via` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE `tutorials` (
  `id` int(11) NOT NULL,
  `user_type` varchar(225) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `link` varchar(2000) NOT NULL,
  `thumbnail` varchar(1000) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upgrade_requests`
--

CREATE TABLE `upgrade_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `command` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','paid','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `whatsapp_enabled` varchar(255) DEFAULT NULL,
  `has_app_installed` varchar(225) DEFAULT NULL,
  `sales_comment` varchar(500) DEFAULT NULL,
  `referral_code` varchar(20) DEFAULT NULL,
  `device_token` varchar(2000) DEFAULT NULL,
  `auth_token` varchar(1000) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `source` enum('browser','app') DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `city` varchar(225) DEFAULT NULL,
  `short_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `houseno_floor` varchar(255) NOT NULL,
  `building_blockno` varchar(255) NOT NULL,
  `landmark_areaname` varchar(255) DEFAULT NULL,
  `area` varchar(225) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_coins_histories`
--

CREATE TABLE `user_coins_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action_type` enum('HOST_PET','BOARD_PET') NOT NULL,
  `coins` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_coupons`
--

CREATE TABLE `user_coupons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `coupon_code` varchar(225) NOT NULL,
  `discount_type` varchar(225) DEFAULT NULL,
  `discount_value` varchar(255) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_pets`
--

CREATE TABLE `user_pets` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `breed` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `aggressiveness_level` enum('normal','slightly','high') NOT NULL DEFAULT 'normal',
  `insured` enum('yes','no') DEFAULT NULL,
  `licensed` enum('yes','no') DEFAULT NULL,
  `vaccinated` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_vaccination` date DEFAULT NULL,
  `vaccinated_rabies` enum('yes','no') DEFAULT NULL,
  `last_rabies_vaccination` date DEFAULT NULL,
  `dewormed_on` date DEFAULT NULL,
  `last_vet_visit` date DEFAULT NULL,
  `visit_purpose` varchar(255) DEFAULT NULL,
  `vet_name` varchar(255) DEFAULT NULL,
  `vet_phone` varchar(255) DEFAULT NULL,
  `vet_address` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_push_subscriptions`
--

CREATE TABLE `user_push_subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `endpoint` text NOT NULL,
  `public_key` text NOT NULL,
  `auth_token` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_refunds`
--

CREATE TABLE `user_refunds` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(150) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `amount` double(10,2) NOT NULL,
  `account_holder_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `branch` varchar(150) DEFAULT NULL,
  `utr` varchar(100) DEFAULT NULL,
  `status` enum('requested','pending','approved','rejected','in_progress','processed','completed') DEFAULT 'requested',
  `receipt` varchar(225) DEFAULT NULL,
  `admin_comments` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `booking_reference` varchar(100) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `requested_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `processed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cf_transfer_id` varchar(225) DEFAULT NULL,
  `remarks` varchar(225) DEFAULT NULL,
  `additional_data` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_service_wallet`
--

CREATE TABLE `user_service_wallet` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `walking_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `training_amount` decimal(10,2) DEFAULT 0.00,
  `boarding_amount` decimal(10,2) DEFAULT 0.00,
  `grooming_amount` decimal(10,2) DEFAULT 0.00,
  `sitting_amount` decimal(10,2) DEFAULT 0.00,
  `fixed_deposit` decimal(10,2) DEFAULT NULL,
  `status` enum('active','completed','refunded') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_wallet_histories`
--

CREATE TABLE `user_wallet_histories` (
  `id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(500) DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `booking_id` varchar(225) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `transaction_type` enum('credit','debit') NOT NULL,
  `category` varchar(225) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `refund_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` mediumtext DEFAULT NULL,
  `checksum` varchar(255) DEFAULT NULL,
  `prev_checksum` varchar(255) DEFAULT NULL,
  `hash_signature` varchar(128) DEFAULT NULL,
  `reference_type` enum('booking_amount','refund_amount','commission','withdrawal','manual_adjustment') DEFAULT NULL,
  `reference_id` varchar(225) DEFAULT NULL,
  `idempotency_key` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Triggers `user_wallet_histories`
--
DELIMITER $$
CREATE TRIGGER `before_wallet_delete` BEFORE DELETE ON `user_wallet_histories` FOR EACH ROW BEGIN
  INSERT INTO wallet_audit_log(wallet_id, action, old_data, new_data)
  VALUES (
    OLD.id,
    'DELETE',
    JSON_OBJECT('amount', OLD.amount, 'type', OLD.transaction_type, 'category', OLD.category, 'user_id', OLD.user_id),
    NULL
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_wallet_update` BEFORE UPDATE ON `user_wallet_histories` FOR EACH ROW BEGIN
  INSERT INTO wallet_audit_log(wallet_id, action, old_data, new_data)
  VALUES (
    OLD.id,
    'UPDATE',
    JSON_OBJECT('amount', OLD.amount, 'type', OLD.transaction_type, 'category', OLD.category, 'user_id', OLD.user_id),
    JSON_OBJECT('amount', NEW.amount, 'type', NEW.transaction_type, 'category', NEW.category, 'user_id', NEW.user_id)
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_wallet_history_delete_audit` BEFORE DELETE ON `user_wallet_histories` FOR EACH ROW BEGIN
  INSERT INTO wallet_tampered_log (user_id, tampered_txn_id, event_type, details)
  VALUES (
    OLD.user_id,
    OLD.id,
    'delete',
    CONCAT('Manual delete detected on txn ID ', OLD.id, ' (amount=', OLD.amount, ')')
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_wallet_history_update_audit` BEFORE UPDATE ON `user_wallet_histories` FOR EACH ROW BEGIN
  -- Detect changes to sensitive fields
  IF (OLD.amount <> NEW.amount
      OR OLD.transaction_type <> NEW.transaction_type
      OR OLD.category <> NEW.category
      OR OLD.balance_after <> NEW.balance_after) THEN

    INSERT INTO wallet_tampered_log (user_id, tampered_txn_id, event_type, details)
    VALUES (
      OLD.user_id,
      OLD.id,
      'update',
      CONCAT('Manual update detected on txn ID ', OLD.id, 
             ' | Old Amount: ', OLD.amount, 
             ' | New Amount: ', NEW.amount)
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_withdrawal_wallet`
--

CREATE TABLE `user_withdrawal_wallet` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `fixed_deposit` decimal(10,2) DEFAULT NULL,
  `refund_reason` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_questions`
--

CREATE TABLE `verification_questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_weight` decimal(5,2) DEFAULT 0.00,
  `question_text` text NOT NULL,
  `hint_text` text DEFAULT NULL,
  `options` text DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT 0.00,
  `is_trigger` tinyint(1) DEFAULT 0,
  `is_dependent` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `field_name` varchar(100) DEFAULT NULL,
  `training_type` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_rules`
--

CREATE TABLE `verification_rules` (
  `id` int(11) NOT NULL,
  `service_type` enum('walker','groomer','trainer','pet_sitter') DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(225) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `verification_required` tinyint(1) DEFAULT 1,
  `registration_fee` decimal(10,2) DEFAULT 49.00,
  `call_verification_required` tinyint(1) DEFAULT 1,
  `aadhaar_verification_required` tinyint(1) DEFAULT 1,
  `deferred_verification` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sp_tier_dashboard`
-- (See below for the actual view)
--
CREATE TABLE `v_sp_tier_dashboard` (
`provider_id` int(11) unsigned
,`sp_name` varchar(255)
,`provider_status` enum('active','inactive','pending_verification','rejected','suspended','pending_kyc')
,`tier` tinyint(1)
,`tier_label` enum('tier_1','tier_2','tier_3')
,`is_active` tinyint(1)
,`is_frozen` tinyint(1)
,`freeze_until` datetime
,`strike_count` tinyint(3)
,`earnings_multiplier` decimal(4,2)
,`can_access_premium` tinyint(1)
,`daily_bid_limit` smallint(5)
,`radius_limit_km` smallint(5)
,`completed_walks` int(11)
,`avg_rating` decimal(3,2)
,`weighted_rating` decimal(3,2)
,`on_time_percentage` decimal(5,2)
,`cancellation_rate` decimal(5,2)
,`complaint_rate` decimal(5,2)
,`last_30d_walks` int(11)
,`repeat_client_count` int(11)
,`ranking_score` decimal(6,4)
,`last_evaluated_at` datetime
,`police_verification_verified` tinyint(1)
,`is_accidentally_insured` enum('yes','no')
,`profile_video_verified` tinyint(1)
,`tier_assigned_at` datetime
,`tier_last_evaluated` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_tier1_eligible_sp`
-- (See below for the actual view)
--
CREATE TABLE `v_tier1_eligible_sp` (
`provider_id` int(11) unsigned
,`name` varchar(255)
,`ranking_score` decimal(6,4)
,`avg_rating` decimal(3,2)
,`last_30d_walks` int(11)
,`service_radius_km` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `walking_service_bookings`
--

CREATE TABLE `walking_service_bookings` (
  `id` varchar(36) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service_id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) UNSIGNED NOT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `service_frequency` enum('once a day','twice a day','thrice a day') NOT NULL,
  `walk_duration` enum('30 min walk','60 min walk') NOT NULL,
  `service_days` enum('weekdays','all days') NOT NULL,
  `service_start_date` date NOT NULL,
  `service_end_date` date DEFAULT NULL,
  `preferable_time` longtext NOT NULL,
  `addons` longtext DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('New','Confirmed','Cancelled','Completed','onHold','Halted') NOT NULL COMMENT 'New,Confirmed,Cancelled,Completed,onHold',
  `type` varchar(100) DEFAULT NULL COMMENT 'booking type eg: normal,extend,temporary,permanent',
  `approval` varchar(100) DEFAULT NULL COMMENT 'extend approval from provider true of false',
  `payment_status` enum('pending','completed') DEFAULT NULL COMMENT 'payment status completed or pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `is_paused` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = paused, 0 = active',
  `original_booking_id` varchar(100) DEFAULT NULL,
  `withdrawal_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `repost` tinyint(1) DEFAULT NULL COMMENT 'if current provider rejected extension client can repost extend true or false',
  `remaining_days` int(11) DEFAULT NULL,
  `source` varchar(20) DEFAULT 'mobile_app',
  `coupon_id` int(11) DEFAULT NULL,
  `cancel_reason` varchar(500) DEFAULT NULL,
  `coupon_code` varchar(100) DEFAULT NULL,
  `coupon_discount` decimal(10,2) DEFAULT 0.00,
  `final_payable` decimal(10,2) DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT 0,
  `days` varchar(225) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `premium_request_id` int(11) DEFAULT NULL,
  `has_complaint` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = at least one formal complaint lodged against this booking',
  `has_invited_sps` tinyint(1) DEFAULT 0,
  `comment` varchar(500) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `walking_tracking`
--

CREATE TABLE `walking_tracking` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `pet_id` int(10) UNSIGNED NOT NULL,
  `service_time` varchar(100) DEFAULT NULL,
  `scheduled_start_time` datetime DEFAULT NULL COMMENT 'Planned start time set at booking; used to compute lateness',
  `is_late` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 if walker arrived > 10 min after scheduled_start_time',
  `late_minutes` smallint(6) DEFAULT NULL COMMENT 'Actual minutes late (negative = early); NULL if not yet started',
  `distance_walked` decimal(10,2) NOT NULL,
  `tracking_date` date DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `completed_by` varchar(20) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_approved` varchar(50) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `payment_status` enum('pending','failed','paid','rejected') DEFAULT NULL,
  `is_untracked` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_payment_requested` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `walk_payment_requests`
--

CREATE TABLE `walk_payment_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `tracking_id` int(10) UNSIGNED NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_remark` varchar(255) DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `walk_reminder_logs`
--

CREATE TABLE `walk_reminder_logs` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(225) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `tracking_date` date NOT NULL,
  `service_time` time NOT NULL,
  `stage` enum('pre_reminder','scheduled','plus_5','plus_10') NOT NULL,
  `sent_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_type` enum('client','provider') NOT NULL,
  `pet_id` int(11) UNSIGNED NOT NULL,
  `service_type` enum('walking','training','boarding','grooming') NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `withdrawable_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed','refunded') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_audit_log`
--

CREATE TABLE `wallet_audit_log` (
  `id` int(11) NOT NULL,
  `wallet_id` int(11) DEFAULT NULL,
  `action` enum('UPDATE','DELETE') DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `changed_by` varchar(100) DEFAULT 'system',
  `changed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_tampered_log`
--

CREATE TABLE `wallet_tampered_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `tampered_txn_id` int(10) UNSIGNED DEFAULT NULL,
  `event_type` enum('insert','update','delete','checksum_mismatch') NOT NULL,
  `details` text DEFAULT NULL,
  `detected_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_type` enum('client','provider') NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `pet_id` int(11) UNSIGNED NOT NULL,
  `service_type` enum('walking','training','boarding','grooming') NOT NULL,
  `transaction_type` enum('credit','debit','refund','withdrawal') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_withdrawals`
--

CREATE TABLE `wallet_withdrawals` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','in_progress','completed','otp_sent') NOT NULL DEFAULT 'pending',
  `otp` varchar(10) DEFAULT NULL,
  `isBankConfirmed` tinyint(1) NOT NULL DEFAULT 0,
  `receipt` varchar(225) DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `cf_transfer_id` varchar(225) DEFAULT NULL,
  `cf_reference_id` varchar(225) DEFAULT NULL,
  `utr` varchar(225) DEFAULT NULL,
  `payout_initiated_at` timestamp NULL DEFAULT NULL,
  `webhook_processed` tinyint(1) DEFAULT 0,
  `last_webhook_at` datetime DEFAULT NULL,
  `failure_reason` varchar(500) DEFAULT NULL,
  `failure_code` varchar(500) DEFAULT NULL,
  `otp_sent_at` datetime DEFAULT NULL,
  `otp_attempts` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_events`
--

CREATE TABLE `webhook_events` (
  `id` bigint(20) NOT NULL,
  `event_id` varchar(255) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `processed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_retry_queue`
--

CREATE TABLE `webhook_retry_queue` (
  `id` int(11) NOT NULL,
  `webhook_log_id` int(11) NOT NULL,
  `original_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`original_data`)),
  `retry_count` int(11) DEFAULT 0,
  `max_retries` int(11) DEFAULT 3,
  `next_retry_at` datetime DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') DEFAULT 'pending',
  `last_error` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_chats`
--

CREATE TABLE `whatsapp_chats` (
  `jid` varchar(100) NOT NULL,
  `last_message` text DEFAULT NULL,
  `last_message_time` varchar(20) DEFAULT NULL,
  `unread` int(11) DEFAULT 0,
  `is_group` tinyint(1) DEFAULT 0,
  `profile_picture` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_contacts`
--

CREATE TABLE `whatsapp_contacts` (
  `jid` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `push_name` varchar(255) DEFAULT NULL,
  `verified_name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `last_seen` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_lead_conversations`
--

CREATE TABLE `whatsapp_lead_conversations` (
  `id` int(11) UNSIGNED NOT NULL,
  `leadId` int(11) UNSIGNED NOT NULL,
  `contactName` varchar(255) NOT NULL,
  `contactPhone` varchar(20) NOT NULL,
  `unreadCount` int(11) NOT NULL DEFAULT 0,
  `lastMessage` text DEFAULT NULL,
  `lastMessageTime` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_lead_messages`
--

CREATE TABLE `whatsapp_lead_messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `leadId` int(11) UNSIGNED NOT NULL,
  `messageId` varchar(255) DEFAULT NULL,
  `text` text NOT NULL,
  `direction` enum('incoming','outgoing') NOT NULL,
  `senderName` varchar(255) DEFAULT NULL,
  `status` enum('sent','delivered','read','failed') DEFAULT 'sent',
  `timestamp` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_messages`
--

CREATE TABLE `whatsapp_messages` (
  `id` varchar(100) NOT NULL,
  `jid` varchar(100) NOT NULL,
  `text` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `sender` enum('me','contact') NOT NULL,
  `status` enum('sent','delivered','read','played','failed') DEFAULT 'sent',
  `timestamp` varchar(20) DEFAULT NULL,
  `quoted_message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quoted_message`)),
  `caption` text DEFAULT NULL,
  `media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media`)),
  `reply_to` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_sessions`
--

CREATE TABLE `whatsapp_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `current_menu` varchar(50) NOT NULL DEFAULT 'MAIN_MENU',
  `current_step` varchar(50) NOT NULL DEFAULT 'SELECT_OPTION',
  `selected_pet` bigint(20) UNSIGNED DEFAULT NULL,
  `selected_service` varchar(50) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `temp_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`temp_data`)),
  `last_message` text DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `language` varchar(10) NOT NULL DEFAULT 'en',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_receipts`
--

CREATE TABLE `withdrawal_receipts` (
  `id` int(10) UNSIGNED NOT NULL,
  `withdrawl_id` int(10) UNSIGNED DEFAULT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `receipt` varchar(500) DEFAULT NULL,
  `requested_date` date NOT NULL,
  `status` enum('completed','pending','in_progress','rejected') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_logs`
--
ALTER TABLE `ai_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `assignment_state`
--
ALTER TABLE `assignment_state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name_unique` (`key_name`);

--
-- Indexes for table `assignment_states`
--
ALTER TABLE `assignment_states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_assignment_state_key` (`key`);

--
-- Indexes for table `baileys_auth`
--
ALTER TABLE `baileys_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_name` (`session_name`);

--
-- Indexes for table `baileys_keys`
--
ALTER TABLE `baileys_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_name` (`session_name`,`key_type`,`key_id`);

--
-- Indexes for table `bank_verification_logs`
--
ALTER TABLE `bank_verification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provider_id` (`provider_id`),
  ADD KEY `idx_bank_detail_id` (`bank_detail_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `boarding_service_bookings`
--
ALTER TABLE `boarding_service_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boarding_service_bookings_ibfk_1` (`user_id`),
  ADD KEY `boarding_service_bookings_ibfk_3` (`package_id`),
  ADD KEY `boarding_service_id` (`service_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `boarding_tracking`
--
ALTER TABLE `boarding_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_conversations`
--
ALTER TABLE `booking_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client` (`client_id`),
  ADD KEY `idx_sp` (`service_provider_id`),
  ADD KEY `idx_service` (`service_id`);

--
-- Indexes for table `booking_invited_sps`
--
ALTER TABLE `booking_invited_sps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_sp` (`provider_id`);

--
-- Indexes for table `booking_messages`
--
ALTER TABLE `booking_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `booking_pets`
--
ALTER TABLE `booking_pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `booking_pets_ibfk_4` (`pet_id`);

--
-- Indexes for table `callers`
--
ALTER TABLE `callers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `call_activity_logs`
--
ALTER TABLE `call_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_attempt_id` (`call_attempt_id`),
  ADD KEY `caller_id` (`caller_id`);

--
-- Indexes for table `call_analysis`
--
ALTER TABLE `call_analysis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_id` (`call_id`);

--
-- Indexes for table `call_answers`
--
ALTER TABLE `call_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_attempt_id` (`call_attempt_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `call_attempts`
--
ALTER TABLE `call_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sps_id` (`sp_id`),
  ADD KEY `caller_id` (`caller_id`),
  ADD KEY `call_status` (`call_status`);

--
-- Indexes for table `call_interactions`
--
ALTER TABLE `call_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_interactions_call` (`call_id`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_call_id` (`call_id`),
  ADD KEY `idx_to_number` (`to_number`);

--
-- Indexes for table `call_questions`
--
ALTER TABLE `call_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_ratings`
--
ALTER TABLE `call_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_attempt_id` (`call_attempt_id`);

--
-- Indexes for table `call_recordings`
--
ALTER TABLE `call_recordings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_attempt_id` (`call_attempt_id`);

--
-- Indexes for table `cashfree_webhook_logs`
--
ALTER TABLE `cashfree_webhook_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_id_unique` (`event_id`),
  ADD KEY `idx_transfer_id` (`transfer_id`),
  ADD KEY `idx_beneficiary_id` (`beneficiary_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_processing_status` (`processing_status`);

--
-- Indexes for table `chatbot_ai_prompt_versions`
--
ALTER TABLE `chatbot_ai_prompt_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_active_version` (`chatbot_id`,`is_active`),
  ADD KEY `idx_chatbot_version` (`chatbot_id`,`version`);

--
-- Indexes for table `chatbot_business_hours`
--
ALTER TABLE `chatbot_business_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_day` (`chatbot_id`,`day`,`shift_order`);

--
-- Indexes for table `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chatbot_status` (`chatbot_id`,`status`,`priority`);
ALTER TABLE `chatbot_faqs` ADD FULLTEXT KEY `ft_question` (`question`);
ALTER TABLE `chatbot_faqs` ADD FULLTEXT KEY `ft_keywords` (`keywords`);

--
-- Indexes for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_keyword` (`chatbot_id`,`keyword`,`status`);

--
-- Indexes for table `chatbot_logs`
--
ALTER TABLE `chatbot_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_menu`
--
ALTER TABLE `chatbot_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu` (`chatbot_id`,`status`,`sort_order`);

--
-- Indexes for table `chatbot_quick_replies`
--
ALTER TABLE `chatbot_quick_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chatbot_reply` (`chatbot_id`,`status`,`sort_order`);

--
-- Indexes for table `chatbot_session_state`
--
ALTER TABLE `chatbot_session_state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_company_id` (`company_id`);

--
-- Indexes for table `chat_answers`
--
ALTER TABLE `chat_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_question` (`question_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_questions`
--
ALTER TABLE `chat_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_service` (`service_id`),
  ADD KEY `idx_status` (`booking_status`);

--
-- Indexes for table `community_channels`
--
ALTER TABLE `community_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_channel_joins`
--
ALTER TABLE `community_channel_joins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_comment_dislikes`
--
ALTER TABLE `community_comment_dislikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_notifications`
--
ALTER TABLE `community_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `community_post_channels`
--
ALTER TABLE `community_post_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_comments`
--
ALTER TABLE `community_post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_parent` (`parent_comment_id`);

--
-- Indexes for table `community_post_dislikes`
--
ALTER TABLE `community_post_dislikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_media`
--
ALTER TABLE `community_post_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_reports`
--
ALTER TABLE `community_post_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_saves`
--
ALTER TABLE `community_post_saves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`post_id`,`user_id`);

--
-- Indexes for table `community_post_shares`
--
ALTER TABLE `community_post_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_tag` (`tag`);

--
-- Indexes for table `community_tags`
--
ALTER TABLE `community_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);

--
-- Indexes for table `community_user_follows`
--
ALTER TABLE `community_user_follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_usage_logs`
--
ALTER TABLE `coupon_usage_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_notifications`
--
ALTER TABLE `crm_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_locks`
--
ALTER TABLE `cron_locks`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `demand_supply_snapshot`
--
ALTER TABLE `demand_supply_snapshot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `doctor_documents`
--
ALTER TABLE `doctor_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_referral_codes`
--
ALTER TABLE `doctor_referral_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `dog_breeds`
--
ALTER TABLE `dog_breeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_scan_logs`
--
ALTER TABLE `event_scan_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`);

--
-- Indexes for table `extra_addons`
--
ALTER TABLE `extra_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_user_type_index` (`user_type`),
  ADD KEY `feedback_user_id_index` (`user_id`);

--
-- Indexes for table `financial_ledger`
--
ALTER TABLE `financial_ledger`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_unique_hash` (`unique_hash`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_provider_id` (`provider_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_reference` (`reference_table`,`reference_id`);

--
-- Indexes for table `followups`
--
ALTER TABLE `followups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `followups_leadId_foreign` (`leadId`);

--
-- Indexes for table `grooming_booking_packages`
--
ALTER TABLE `grooming_booking_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `grooming_service_bookings`
--
ALTER TABLE `grooming_service_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `grooming_service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `grooming_tracking`
--
ALTER TABLE `grooming_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `claim_number` (`claim_number`),
  ADD KEY `idx_policy` (`policy_id`);

--
-- Indexes for table `insurance_payments`
--
ALTER TABLE `insurance_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `policy_number` (`policy_number`),
  ADD KEY `idx_ins_user` (`insurance_user_id`),
  ADD KEY `idx_pet` (`pet_id`);

--
-- Indexes for table `insurance_policies_quotations`
--
ALTER TABLE `insurance_policies_quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insurance_policy_renewals`
--
ALTER TABLE `insurance_policy_renewals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insurance_providers`
--
ALTER TABLE `insurance_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insurance_users`
--
ALTER TABLE `insurance_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `integrity_alerts`
--
ALTER TABLE `integrity_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `knowledge_base`
--
ALTER TABLE `knowledge_base`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_coupons`
--
ALTER TABLE `loyalty_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `loyalty_milestones`
--
ALTER TABLE `loyalty_milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_rewards`
--
ALTER TABLE `loyalty_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_milestone` (`milestone_id`);

--
-- Indexes for table `loyalty_settings`
--
ALTER TABLE `loyalty_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `loyalty_spins`
--
ALTER TABLE `loyalty_spins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_milestone` (`milestone_id`);

--
-- Indexes for table `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_reference` (`reference_id`);

--
-- Indexes for table `loyalty_user_milestones`
--
ALTER TABLE `loyalty_user_milestones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_milestone` (`user_id`,`milestone_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `loyalty_wallet`
--
ALTER TABLE `loyalty_wallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user` (`user_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_membership` (`user_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_type` (`user_type`);

--
-- Indexes for table `notification_queue`
--
ALTER TABLE `notification_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_scheduled` (`status`,`scheduled_at`),
  ADD KEY `idx_priority` (`priority`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `caller_id` (`caller_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `package_addons`
--
ALTER TABLE `package_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payout_transactions`
--
ALTER TABLE `payout_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_transfer_id` (`transfer_id`),
  ADD KEY `idx_cf_transfer_id` (`cf_transfer_id`),
  ADD KEY `idx_provider_id` (`provider_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_webhook_processed` (`webhook_processed`);

--
-- Indexes for table `pending_changes`
--
ALTER TABLE `pending_changes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_care_checklists`
--
ALTER TABLE `pet_care_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `pet_care_routines`
--
ALTER TABLE `pet_care_routines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `pet_feeding_schedules`
--
ALTER TABLE `pet_feeding_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routine_id` (`routine_id`);

--
-- Indexes for table `pet_medications`
--
ALTER TABLE `pet_medications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routine_id` (`routine_id`);

--
-- Indexes for table `pet_types`
--
ALTER TABLE `pet_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_walk_schedules`
--
ALTER TABLE `pet_walk_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routine_id` (`routine_id`);

--
-- Indexes for table `platform_wallet`
--
ALTER TABLE `platform_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platform_wallet_histories`
--
ALTER TABLE `platform_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `premium_requests`
--
ALTER TABLE `premium_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `provider_attributes`
--
ALTER TABLE `provider_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_id` (`call_id`);

--
-- Indexes for table `provider_beneficiaries`
--
ALTER TABLE `provider_beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_provider_bank` (`provider_id`,`bank_id`),
  ADD KEY `idx_cf_bene_id` (`cf_bene_id`);

--
-- Indexes for table `provider_calls`
--
ALTER TABLE `provider_calls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `call_id` (`call_id`),
  ADD KEY `idx_call_provider` (`provider_id`),
  ADD KEY `idx_call_id` (`call_id`);

--
-- Indexes for table `provider_languages`
--
ALTER TABLE `provider_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_reports`
--
ALTER TABLE `provider_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_provider_id` (`provider_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `provider_scores`
--
ALTER TABLE `provider_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_id` (`call_id`);

--
-- Indexes for table `provider_whatsapp_chats`
--
ALTER TABLE `provider_whatsapp_chats`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `provider_whatsapp_contacts`
--
ALTER TABLE `provider_whatsapp_contacts`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `provider_whatsapp_messages`
--
ALTER TABLE `provider_whatsapp_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jid` (`jid`);

--
-- Indexes for table `provider_whatsapp_sessions`
--
ALTER TABLE `provider_whatsapp_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `quotation_addons`
--
ALTER TABLE `quotation_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `reconciliation_wallet`
--
ALTER TABLE `reconciliation_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_referral` (`referred_user_id`),
  ADD KEY `idx_referrer` (`referrer_id`);

--
-- Indexes for table `referral_progress`
--
ALTER TABLE `referral_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_activity_log`
--
ALTER TABLE `report_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_id` (`report_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_action` (`action`);

--
-- Indexes for table `reward_orders`
--
ALTER TABLE `reward_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`order_status`);

--
-- Indexes for table `ride_tracking`
--
ALTER TABLE `ride_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `salesperson_analytics`
--
ALTER TABLE `salesperson_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salesperson_date_unique` (`salesperson`,`date`),
  ADD KEY `idx_salesperson` (`salesperson`),
  ADD KEY `idx_date` (`date`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_available_states`
--
ALTER TABLE `service_available_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_pause_logs`
--
ALTER TABLE `service_pause_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_geohash_service` (`geohash`,`status`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `sitting_checklist_media`
--
ALTER TABLE `sitting_checklist_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_media_type` (`media_type`);

--
-- Indexes for table `sitting_service_bookings`
--
ALTER TABLE `sitting_service_bookings`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sitting_tracking`
--
ALTER TABLE `sitting_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `sp_aadhar`
--
ALTER TABLE `sp_aadhar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_bank_details`
--
ALTER TABLE `sp_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_boarding_details`
--
ALTER TABLE `sp_boarding_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_bookings`
--
ALTER TABLE `sp_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `sp_grooming_details`
--
ALTER TABLE `sp_grooming_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_kyc`
--
ALTER TABLE `sp_kyc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_reviews`
--
ALTER TABLE `sp_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_type` (`service_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_sprev_provider_service` (`provider_id`,`service_id`);

--
-- Indexes for table `sp_scores`
--
ALTER TABLE `sp_scores`
  ADD PRIMARY KEY (`sp_id`);

--
-- Indexes for table `sp_services`
--
ALTER TABLE `sp_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `sp_service_images`
--
ALTER TABLE `sp_service_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `sp_service_wallet`
--
ALTER TABLE `sp_service_wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_sitting_details`
--
ALTER TABLE `sp_sitting_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_stats`
--
ALTER TABLE `sp_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_walker_stats_provider` (`provider_id`),
  ADD KEY `idx_ws_ranking_score` (`ranking_score`),
  ADD KEY `idx_ws_last_evaluated` (`last_evaluated_at`);

--
-- Indexes for table `sp_strikes`
--
ALTER TABLE `sp_strikes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wstrike_provider` (`provider_id`),
  ADD KEY `idx_wstrike_active` (`is_active`,`expires_at`),
  ADD KEY `idx_wstrike_type` (`strike_type`);

--
-- Indexes for table `sp_tiers`
--
ALTER TABLE `sp_tiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wt_provider` (`provider_id`),
  ADD KEY `idx_wt_tier` (`tier`),
  ADD KEY `idx_wt_active` (`is_active`),
  ADD KEY `idx_wt_freeze` (`freeze_until`);

--
-- Indexes for table `sp_tier_history`
--
ALTER TABLE `sp_tier_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wth_provider` (`provider_id`),
  ADD KEY `idx_wth_created` (`created_at`);

--
-- Indexes for table `sp_tier_upgrade_requests`
--
ALTER TABLE `sp_tier_upgrade_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wtur_provider` (`provider_id`),
  ADD KEY `idx_wtur_status` (`status`),
  ADD KEY `idx_wtur_sla` (`sla_deadline`);

--
-- Indexes for table `sp_training_details`
--
ALTER TABLE `sp_training_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_training_packages`
--
ALTER TABLE `sp_training_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_verifications`
--
ALTER TABLE `sp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_verified_bank_details`
--
ALTER TABLE `sp_verified_bank_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_walking_details`
--
ALTER TABLE `sp_walking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_wallet_histories`
--
ALTER TABLE `sp_wallet_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `sp_withdrawal_wallet`
--
ALTER TABLE `sp_withdrawal_wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `target_assignments`
--
ALTER TABLE `target_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rep_month_unique` (`repId`,`assignedMonth`);

--
-- Indexes for table `target_templates`
--
ALTER TABLE `target_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_media`
--
ALTER TABLE `task_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_feedback`
--
ALTER TABLE `ticket_feedback`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ticket_feedback` (`ticket_id`,`user_id`),
  ADD KEY `idx_ticket_id` (`ticket_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_emoji` (`rating`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ticket_id` (`ticket_id`),
  ADD KEY `idx_sender_id` (`sender_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `training_service_bookings`
--
ALTER TABLE `training_service_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `training_tracking`
--
ALTER TABLE `training_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_cf_order_id` (`cf_order_id`),
  ADD KEY `idx_cf_payment_id` (`cf_payment_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_user_provider` (`user_id`,`provider_id`);

--
-- Indexes for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upgrade_requests`
--
ALTER TABLE `upgrade_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_code` (`referral_code`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_coins_histories`
--
ALTER TABLE `user_coins_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_pets`
--
ALTER TABLE `user_pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_pets_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_push_subscriptions`
--
ALTER TABLE `user_push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `endpoint` (`endpoint`) USING HASH;

--
-- Indexes for table `user_refunds`
--
ALTER TABLE `user_refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_service_wallet`
--
ALTER TABLE `user_service_wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_wallet_histories`
--
ALTER TABLE `user_wallet_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_wallet_id` (`user_id`);

--
-- Indexes for table `user_withdrawal_wallet`
--
ALTER TABLE `user_withdrawal_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_questions`
--
ALTER TABLE `verification_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_rules`
--
ALTER TABLE `verification_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `walking_service_bookings`
--
ALTER TABLE `walking_service_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `walking_service_bookings_user_id_foreign` (`user_id`),
  ADD KEY `walking_service_bookings_walking_service_id_foreign` (`service_id`),
  ADD KEY `walking_service_bookings_package_id_foreign` (`package_id`),
  ADD KEY `idx_wsb_complaint` (`has_complaint`,`created_at`);

--
-- Indexes for table `walking_tracking`
--
ALTER TABLE `walking_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_wt_provider_status_date` (`provider_id`,`status`,`created_at`),
  ADD KEY `idx_wt_provider_scheduled` (`provider_id`,`scheduled_start_time`);

--
-- Indexes for table `walk_payment_requests`
--
ALTER TABLE `walk_payment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `walk_reminder_logs`
--
ALTER TABLE `walk_reminder_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reminder` (`booking_id`,`tracking_date`,`service_time`,`stage`),
  ADD KEY `tracking_date` (`tracking_date`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `wallet_audit_log`
--
ALTER TABLE `wallet_audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_tampered_log`
--
ALTER TABLE `wallet_tampered_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `wallet_withdrawals`
--
ALTER TABLE `wallet_withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `webhook_events`
--
ALTER TABLE `webhook_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_id` (`event_id`);

--
-- Indexes for table `webhook_retry_queue`
--
ALTER TABLE `webhook_retry_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_webhook_log_id` (`webhook_log_id`),
  ADD KEY `idx_status_next_retry` (`status`,`next_retry_at`);

--
-- Indexes for table `whatsapp_chats`
--
ALTER TABLE `whatsapp_chats`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `whatsapp_contacts`
--
ALTER TABLE `whatsapp_contacts`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `whatsapp_lead_conversations`
--
ALTER TABLE `whatsapp_lead_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lead_unique` (`leadId`);

--
-- Indexes for table `whatsapp_lead_messages`
--
ALTER TABLE `whatsapp_lead_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leadId_idx` (`leadId`);

--
-- Indexes for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jid` (`jid`);

--
-- Indexes for table `whatsapp_sessions`
--
ALTER TABLE `whatsapp_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_whatsapp_phone` (`phone`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_current_menu` (`current_menu`),
  ADD KEY `idx_current_step` (`current_step`),
  ADD KEY `idx_selected_pet` (`selected_pet`);

--
-- Indexes for table `withdrawal_receipts`
--
ALTER TABLE `withdrawal_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `withdrawl_id` (`withdrawl_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_logs`
--
ALTER TABLE `ai_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignment_state`
--
ALTER TABLE `assignment_state`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignment_states`
--
ALTER TABLE `assignment_states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `baileys_auth`
--
ALTER TABLE `baileys_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `baileys_keys`
--
ALTER TABLE `baileys_keys`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_verification_logs`
--
ALTER TABLE `bank_verification_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boarding_tracking`
--
ALTER TABLE `boarding_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_addons`
--
ALTER TABLE `booking_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_conversations`
--
ALTER TABLE `booking_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_invited_sps`
--
ALTER TABLE `booking_invited_sps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_messages`
--
ALTER TABLE `booking_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_pets`
--
ALTER TABLE `booking_pets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `callers`
--
ALTER TABLE `callers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_activity_logs`
--
ALTER TABLE `call_activity_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_analysis`
--
ALTER TABLE `call_analysis`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_answers`
--
ALTER TABLE `call_answers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_attempts`
--
ALTER TABLE `call_attempts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_interactions`
--
ALTER TABLE `call_interactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_questions`
--
ALTER TABLE `call_questions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_ratings`
--
ALTER TABLE `call_ratings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_recordings`
--
ALTER TABLE `call_recordings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashfree_webhook_logs`
--
ALTER TABLE `cashfree_webhook_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_ai_prompt_versions`
--
ALTER TABLE `chatbot_ai_prompt_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_business_hours`
--
ALTER TABLE `chatbot_business_hours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_logs`
--
ALTER TABLE `chatbot_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_menu`
--
ALTER TABLE `chatbot_menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_quick_replies`
--
ALTER TABLE `chatbot_quick_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_session_state`
--
ALTER TABLE `chatbot_session_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_answers`
--
ALTER TABLE `chat_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_questions`
--
ALTER TABLE `chat_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_channels`
--
ALTER TABLE `community_channels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_channel_joins`
--
ALTER TABLE `community_channel_joins`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_comment_dislikes`
--
ALTER TABLE `community_comment_dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_notifications`
--
ALTER TABLE `community_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_channels`
--
ALTER TABLE `community_post_channels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_comments`
--
ALTER TABLE `community_post_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_dislikes`
--
ALTER TABLE `community_post_dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_media`
--
ALTER TABLE `community_post_media`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_reports`
--
ALTER TABLE `community_post_reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_saves`
--
ALTER TABLE `community_post_saves`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_shares`
--
ALTER TABLE `community_post_shares`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_tags`
--
ALTER TABLE `community_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_user_follows`
--
ALTER TABLE `community_user_follows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_usage_logs`
--
ALTER TABLE `coupon_usage_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_notifications`
--
ALTER TABLE `crm_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `demand_supply_snapshot`
--
ALTER TABLE `demand_supply_snapshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_documents`
--
ALTER TABLE `doctor_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_referral_codes`
--
ALTER TABLE `doctor_referral_codes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dog_breeds`
--
ALTER TABLE `dog_breeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_scan_logs`
--
ALTER TABLE `event_scan_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_tickets`
--
ALTER TABLE `event_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extra_addons`
--
ALTER TABLE `extra_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_ledger`
--
ALTER TABLE `financial_ledger`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followups`
--
ALTER TABLE `followups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grooming_booking_packages`
--
ALTER TABLE `grooming_booking_packages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grooming_tracking`
--
ALTER TABLE `grooming_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_payments`
--
ALTER TABLE `insurance_payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_policies_quotations`
--
ALTER TABLE `insurance_policies_quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_policy_renewals`
--
ALTER TABLE `insurance_policy_renewals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_providers`
--
ALTER TABLE `insurance_providers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_users`
--
ALTER TABLE `insurance_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `integrity_alerts`
--
ALTER TABLE `integrity_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `knowledge_base`
--
ALTER TABLE `knowledge_base`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_coupons`
--
ALTER TABLE `loyalty_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_milestones`
--
ALTER TABLE `loyalty_milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_rewards`
--
ALTER TABLE `loyalty_rewards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_settings`
--
ALTER TABLE `loyalty_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_spins`
--
ALTER TABLE `loyalty_spins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_user_milestones`
--
ALTER TABLE `loyalty_user_milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_wallet`
--
ALTER TABLE `loyalty_wallet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_queue`
--
ALTER TABLE `notification_queue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_addons`
--
ALTER TABLE `package_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payout_transactions`
--
ALTER TABLE `payout_transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_changes`
--
ALTER TABLE `pending_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_care_checklists`
--
ALTER TABLE `pet_care_checklists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_care_routines`
--
ALTER TABLE `pet_care_routines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_feeding_schedules`
--
ALTER TABLE `pet_feeding_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_medications`
--
ALTER TABLE `pet_medications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_types`
--
ALTER TABLE `pet_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_walk_schedules`
--
ALTER TABLE `pet_walk_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_wallet`
--
ALTER TABLE `platform_wallet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_wallet_histories`
--
ALTER TABLE `platform_wallet_histories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `premium_requests`
--
ALTER TABLE `premium_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_attributes`
--
ALTER TABLE `provider_attributes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_beneficiaries`
--
ALTER TABLE `provider_beneficiaries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_calls`
--
ALTER TABLE `provider_calls`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_languages`
--
ALTER TABLE `provider_languages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_reports`
--
ALTER TABLE `provider_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_scores`
--
ALTER TABLE `provider_scores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_whatsapp_sessions`
--
ALTER TABLE `provider_whatsapp_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_addons`
--
ALTER TABLE `quotation_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reconciliation_wallet`
--
ALTER TABLE `reconciliation_wallet`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_progress`
--
ALTER TABLE `referral_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_activity_log`
--
ALTER TABLE `report_activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_orders`
--
ALTER TABLE `reward_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ride_tracking`
--
ALTER TABLE `ride_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesperson_analytics`
--
ALTER TABLE `salesperson_analytics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_available_states`
--
ALTER TABLE `service_available_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_pause_logs`
--
ALTER TABLE `service_pause_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitting_checklist_media`
--
ALTER TABLE `sitting_checklist_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitting_tracking`
--
ALTER TABLE `sitting_tracking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_aadhar`
--
ALTER TABLE `sp_aadhar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_bank_details`
--
ALTER TABLE `sp_bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_boarding_details`
--
ALTER TABLE `sp_boarding_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_bookings`
--
ALTER TABLE `sp_bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_grooming_details`
--
ALTER TABLE `sp_grooming_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_kyc`
--
ALTER TABLE `sp_kyc`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_reviews`
--
ALTER TABLE `sp_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_services`
--
ALTER TABLE `sp_services`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_service_images`
--
ALTER TABLE `sp_service_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_service_wallet`
--
ALTER TABLE `sp_service_wallet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_sitting_details`
--
ALTER TABLE `sp_sitting_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_stats`
--
ALTER TABLE `sp_stats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_strikes`
--
ALTER TABLE `sp_strikes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_tiers`
--
ALTER TABLE `sp_tiers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_tier_history`
--
ALTER TABLE `sp_tier_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_tier_upgrade_requests`
--
ALTER TABLE `sp_tier_upgrade_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_training_details`
--
ALTER TABLE `sp_training_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_training_packages`
--
ALTER TABLE `sp_training_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_verifications`
--
ALTER TABLE `sp_verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_verified_bank_details`
--
ALTER TABLE `sp_verified_bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_walking_details`
--
ALTER TABLE `sp_walking_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_wallet_histories`
--
ALTER TABLE `sp_wallet_histories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_withdrawal_wallet`
--
ALTER TABLE `sp_withdrawal_wallet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_assignments`
--
ALTER TABLE `target_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_templates`
--
ALTER TABLE `target_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_media`
--
ALTER TABLE `task_media`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_feedback`
--
ALTER TABLE `ticket_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_tracking`
--
ALTER TABLE `training_tracking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upgrade_requests`
--
ALTER TABLE `upgrade_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_coins_histories`
--
ALTER TABLE `user_coins_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_coupons`
--
ALTER TABLE `user_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_pets`
--
ALTER TABLE `user_pets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_push_subscriptions`
--
ALTER TABLE `user_push_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_refunds`
--
ALTER TABLE `user_refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_service_wallet`
--
ALTER TABLE `user_service_wallet`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_wallet_histories`
--
ALTER TABLE `user_wallet_histories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_withdrawal_wallet`
--
ALTER TABLE `user_withdrawal_wallet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_questions`
--
ALTER TABLE `verification_questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_rules`
--
ALTER TABLE `verification_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `walking_tracking`
--
ALTER TABLE `walking_tracking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `walk_payment_requests`
--
ALTER TABLE `walk_payment_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `walk_reminder_logs`
--
ALTER TABLE `walk_reminder_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_audit_log`
--
ALTER TABLE `wallet_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_tampered_log`
--
ALTER TABLE `wallet_tampered_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_withdrawals`
--
ALTER TABLE `wallet_withdrawals`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_events`
--
ALTER TABLE `webhook_events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_retry_queue`
--
ALTER TABLE `webhook_retry_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_lead_conversations`
--
ALTER TABLE `whatsapp_lead_conversations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_lead_messages`
--
ALTER TABLE `whatsapp_lead_messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_sessions`
--
ALTER TABLE `whatsapp_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawal_receipts`
--
ALTER TABLE `withdrawal_receipts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `v_sp_tier_dashboard`
--
DROP TABLE IF EXISTS `v_sp_tier_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u506206132_betaApp`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_sp_tier_dashboard`  AS SELECT `sp`.`id` AS `provider_id`, `sp`.`name` AS `sp_name`, `sp`.`status` AS `provider_status`, `st`.`tier` AS `tier`, `st`.`tier_label` AS `tier_label`, `st`.`is_active` AS `is_active`, `st`.`is_frozen` AS `is_frozen`, `st`.`freeze_until` AS `freeze_until`, `st`.`strike_count` AS `strike_count`, `st`.`earnings_multiplier` AS `earnings_multiplier`, `st`.`can_access_premium` AS `can_access_premium`, `st`.`daily_bid_limit` AS `daily_bid_limit`, `st`.`radius_limit_km` AS `radius_limit_km`, `ss`.`completed_walks` AS `completed_walks`, `ss`.`avg_rating` AS `avg_rating`, `ss`.`weighted_rating` AS `weighted_rating`, `ss`.`on_time_percentage` AS `on_time_percentage`, `ss`.`cancellation_rate` AS `cancellation_rate`, `ss`.`complaint_rate` AS `complaint_rate`, `ss`.`last_30d_walks` AS `last_30d_walks`, `ss`.`repeat_client_count` AS `repeat_client_count`, `ss`.`ranking_score` AS `ranking_score`, `ss`.`last_evaluated_at` AS `last_evaluated_at`, `sv`.`police_verification_verified` AS `police_verification_verified`, `swd`.`is_accidentally_insured` AS `is_accidentally_insured`, `swd`.`profile_video_verified` AS `profile_video_verified`, `st`.`tier_assigned_at` AS `tier_assigned_at`, `st`.`last_evaluated_at` AS `tier_last_evaluated` FROM ((((`service_providers` `sp` left join `sp_tiers` `st` on(`st`.`provider_id` = `sp`.`id`)) left join `sp_stats` `ss` on(`ss`.`provider_id` = `sp`.`id`)) left join `sp_verifications` `sv` on(`sv`.`provider_id` = `sp`.`id`)) left join `sp_walking_details` `swd` on(`swd`.`provider_id` = `sp`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_tier1_eligible_sp`
--
DROP TABLE IF EXISTS `v_tier1_eligible_sp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u506206132_betaApp`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_tier1_eligible_sp`  AS SELECT `st`.`provider_id` AS `provider_id`, `sp`.`name` AS `name`, `ss`.`ranking_score` AS `ranking_score`, `ss`.`avg_rating` AS `avg_rating`, `ss`.`last_30d_walks` AS `last_30d_walks`, `swd`.`service_radius_km` AS `service_radius_km` FROM (((`sp_tiers` `st` join `sp_stats` `ss` on(`ss`.`provider_id` = `st`.`provider_id`)) join `service_providers` `sp` on(`sp`.`id` = `st`.`provider_id`)) join `sp_walking_details` `swd` on(`swd`.`provider_id` = `st`.`provider_id`)) WHERE `st`.`tier` = 1 AND `st`.`is_active` = 1 AND `st`.`is_frozen` = 0 ORDER BY `ss`.`ranking_score` DESC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addons`
--
ALTER TABLE `addons`
  ADD CONSTRAINT `addons_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `boarding_service_bookings`
--
ALTER TABLE `boarding_service_bookings`
  ADD CONSTRAINT `boarding_service_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `boarding_service_bookings_ibfk_3` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `boarding_service_bookings_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `boarding_service_bookings_ibfk_5` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD CONSTRAINT `booking_addons_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_pets`
--
ALTER TABLE `booking_pets`
  ADD CONSTRAINT `booking_pets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_pets_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_ai_prompt_versions`
--
ALTER TABLE `chatbot_ai_prompt_versions`
  ADD CONSTRAINT `fk_prompt_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_business_hours`
--
ALTER TABLE `chatbot_business_hours`
  ADD CONSTRAINT `fk_hours_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  ADD CONSTRAINT `fk_faq_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  ADD CONSTRAINT `fk_keyword_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_menu`
--
ALTER TABLE `chatbot_menu`
  ADD CONSTRAINT `fk_menu_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chatbot_quick_replies`
--
ALTER TABLE `chatbot_quick_replies`
  ADD CONSTRAINT `fk_reply_chatbot` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbot_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_documents`
--
ALTER TABLE `doctor_documents`
  ADD CONSTRAINT `doctor_documents_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_referral_codes`
--
ALTER TABLE `doctor_referral_codes`
  ADD CONSTRAINT `doctor_referral_codes_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `followups`
--
ALTER TABLE `followups`
  ADD CONSTRAINT `followups_leadId_foreign` FOREIGN KEY (`leadId`) REFERENCES `leads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grooming_booking_packages`
--
ALTER TABLE `grooming_booking_packages`
  ADD CONSTRAINT `grooming_booking_packages_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grooming_booking_packages_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `grooming_service_bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grooming_service_bookings`
--
ALTER TABLE `grooming_service_bookings`
  ADD CONSTRAINT `grooming_service_bookings_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grooming_service_bookings_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grooming_tracking`
--
ALTER TABLE `grooming_tracking`
  ADD CONSTRAINT `grooming_tracking_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `grooming_service_bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  ADD CONSTRAINT `fk_claim_policy` FOREIGN KEY (`policy_id`) REFERENCES `insurance_policies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `insurance_policies`
--
ALTER TABLE `insurance_policies`
  ADD CONSTRAINT `fk_policy_user` FOREIGN KEY (`insurance_user_id`) REFERENCES `insurance_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_5` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quotations_ibfk_6` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_addons`
--
ALTER TABLE `quotation_addons`
  ADD CONSTRAINT `quotation_addons_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quotation_addons_ibfk_2` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ride_tracking`
--
ALTER TABLE `ride_tracking`
  ADD CONSTRAINT `ride_tracking_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sitting_service_bookings`
--
ALTER TABLE `sitting_service_bookings`
  ADD CONSTRAINT `sitting_service_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sp_aadhar`
--
ALTER TABLE `sp_aadhar`
  ADD CONSTRAINT `sp_aadhar_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sp_boarding_details`
--
ALTER TABLE `sp_boarding_details`
  ADD CONSTRAINT `sp_boarding_details_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sp_stats`
--
ALTER TABLE `sp_stats`
  ADD CONSTRAINT `fk_ws_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sp_strikes`
--
ALTER TABLE `sp_strikes`
  ADD CONSTRAINT `fk_wstrike_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sp_tiers`
--
ALTER TABLE `sp_tiers`
  ADD CONSTRAINT `fk_wt_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sp_tier_history`
--
ALTER TABLE `sp_tier_history`
  ADD CONSTRAINT `fk_wth_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sp_tier_upgrade_requests`
--
ALTER TABLE `sp_tier_upgrade_requests`
  ADD CONSTRAINT `fk_wtur_provider` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
