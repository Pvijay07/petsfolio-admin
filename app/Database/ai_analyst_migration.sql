-- ============================================================
-- AI Business Analyst — Migration SQL (Schema Verified)
-- Run this in phpMyAdmin / Hostinger MySQL console
-- Database: u506206132_betaApp
-- ============================================================

-- -----------------------------------------------------------
-- 1. Core AI Analyst Tables
-- -----------------------------------------------------------

CREATE TABLE IF NOT EXISTS ai_analyst_conversations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  admin_id INT NOT NULL,
  title VARCHAR(255) DEFAULT 'New Conversation',
  is_deleted TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_admin_id (admin_id),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ai_analyst_messages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  conversation_id INT NOT NULL,
  role ENUM('user','assistant') NOT NULL,
  content TEXT NOT NULL,
  metrics_called JSON NULL COMMENT 'Which metric functions were invoked',
  metrics_results JSON NULL COMMENT 'Summarized results from metrics',
  chart_config JSON NULL COMMENT 'Chart.js config if chart was generated',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (conversation_id) REFERENCES ai_analyst_conversations(id) ON DELETE CASCADE,
  INDEX idx_conversation (conversation_id),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ai_analyst_queries (
  id INT PRIMARY KEY AUTO_INCREMENT,
  message_id INT NULL,
  metric_name VARCHAR(100) NOT NULL,
  metric_params JSON NULL,
  data_source ENUM('mysql','mongodb') NOT NULL DEFAULT 'mysql',
  row_count INT DEFAULT 0,
  exec_ms INT DEFAULT 0,
  status ENUM('ok','rejected','error') NOT NULL DEFAULT 'ok',
  error_msg TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_message (message_id),
  INDEX idx_metric (metric_name),
  INDEX idx_status (status),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ai_analyst_reports (
  id INT PRIMARY KEY AUTO_INCREMENT,
  report_type VARCHAR(50) NOT NULL,
  period_start DATE NULL,
  period_end DATE NULL,
  summary TEXT NULL,
  raw_metrics JSON NULL,
  delivered_via VARCHAR(50) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_type (report_type),
  INDEX idx_period (period_start, period_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- 2. Reporting Views (read-only, PII-masked, schema-matched)
-- -----------------------------------------------------------

-- 2a. Bookings Overview (walking, grooming, training, boarding, sitting unified)
CREATE OR REPLACE VIEW v_bookings_overview AS
SELECT 
  CAST('walking' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
  CAST(wsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
  wsb.user_id,
  CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
  CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
  CAST(wsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
  CAST(wsb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
  wsb.service_start_date,
  wsb.service_end_date,
  COALESCE(wsb.final_payable, wsb.total_price, 0) AS total_amount,
  CAST(wsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
  NULL AS payment_mode,
  CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
  CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
  CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
  wsb.created_at,
  wsb.updated_at,
  CASE WHEN wsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
FROM walking_service_bookings wsb
LEFT JOIN users u ON u.id = wsb.user_id
LEFT JOIN user_addresses ua ON ua.id = wsb.address_id
LEFT JOIN services s ON s.id = wsb.service_id

UNION ALL

SELECT 
  CAST('grooming' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
  CAST(gsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
  gsb.user_id,
  CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
  CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
  CAST(gsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
  CAST(gsb.service_mode AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
  gsb.service_start_date,
  NULL AS service_end_date,
  COALESCE(gsb.final_payable, gsb.total_price, 0) AS total_amount,
  CAST(gsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
  NULL AS payment_mode,
  CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
  CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
  CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
  gsb.created_at,
  gsb.updated_at,
  CASE WHEN gsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
FROM grooming_service_bookings gsb
LEFT JOIN users u ON u.id = gsb.user_id
LEFT JOIN user_addresses ua ON ua.id = gsb.address_id
LEFT JOIN services s ON s.id = gsb.service_id

UNION ALL

SELECT 
  CAST('training' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
  CAST(tsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
  tsb.user_id,
  CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
  CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
  CAST(tsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
  CAST(tsb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
  tsb.service_start_date,
  NULL AS service_end_date,
  COALESCE(tsb.final_payable, tsb.total_price, 0) AS total_amount,
  CAST(tsb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
  NULL AS payment_mode,
  CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
  CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
  CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
  tsb.created_at,
  tsb.updated_at,
  CASE WHEN tsb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
FROM training_service_bookings tsb
LEFT JOIN users u ON u.id = tsb.user_id
LEFT JOIN user_addresses ua ON ua.id = tsb.address_id
LEFT JOIN services s ON s.id = tsb.service_id

UNION ALL

SELECT 
  CAST('boarding' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
  CAST(bsb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
  bsb.user_id,
  CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
  CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
  CAST(bsb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
  CAST('boarding' AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
  bsb.service_start_date,
  bsb.service_end_date,
  COALESCE(bsb.total_price, 0) AS total_amount,
  NULL AS payment_status,
  NULL AS payment_mode,
  CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
  CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
  CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
  bsb.created_at,
  bsb.updated_at,
  CASE WHEN bsb.is_deleted IS NOT NULL THEN 1 ELSE 0 END AS is_deleted
FROM boarding_service_bookings bsb
LEFT JOIN users u ON u.id = bsb.user_id
LEFT JOIN user_addresses ua ON ua.id = bsb.address_id
LEFT JOIN services s ON s.id = bsb.service_id

UNION ALL

SELECT 
  CAST('sitting' AS CHAR) COLLATE utf8mb4_unicode_ci AS service_type,
  CAST(ssb.id AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_id,
  ssb.user_id,
  CAST(CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS CHAR) COLLATE utf8mb4_unicode_ci AS user_phone_masked,
  CAST(u.name AS CHAR) COLLATE utf8mb4_unicode_ci AS user_name,
  CAST(ssb.status AS CHAR) COLLATE utf8mb4_unicode_ci AS status,
  CAST(ssb.type AS CHAR) COLLATE utf8mb4_unicode_ci AS booking_type,
  ssb.service_start_date,
  NULL AS service_end_date,
  COALESCE(ssb.final_payable, ssb.total_price, 0) AS total_amount,
  CAST(ssb.payment_status AS CHAR) COLLATE utf8mb4_unicode_ci AS payment_status,
  NULL AS payment_mode,
  CAST(ua.city AS CHAR) COLLATE utf8mb4_unicode_ci AS city,
  CAST(ua.state AS CHAR) COLLATE utf8mb4_unicode_ci AS state,
  CAST(s.name AS CHAR) COLLATE utf8mb4_unicode_ci AS service_name,
  ssb.created_at,
  ssb.updated_at,
  CASE WHEN ssb.is_deleted = 1 THEN 1 ELSE 0 END AS is_deleted
FROM sitting_service_bookings ssb
LEFT JOIN users u ON u.id = ssb.user_id
LEFT JOIN user_addresses ua ON ua.id = ssb.address_id
LEFT JOIN services s ON s.id = ssb.service_id;

-- 2b. Users Overview (PII masked)
CREATE OR REPLACE VIEW v_users_overview AS
SELECT 
  u.id,
  u.name,
  CONCAT('***', RIGHT(COALESCE(u.phone, '0000'), 4)) AS phone_masked,
  CASE 
    WHEN u.email IS NOT NULL AND u.email LIKE '%@%' THEN CONCAT(LEFT(u.email, 2), '***@', SUBSTRING_INDEX(u.email, '@', -1))
    ELSE '***'
  END AS email_masked,
  u.gender,
  u.status,
  u.has_app_installed,
  u.created_at,
  u.updated_at,
  (SELECT COUNT(*) FROM walking_service_bookings wb WHERE wb.user_id = u.id) AS walking_bookings,
  (SELECT COUNT(*) FROM grooming_service_bookings gb WHERE gb.user_id = u.id) AS grooming_bookings,
  (SELECT COUNT(*) FROM training_service_bookings tb WHERE tb.user_id = u.id) AS training_bookings
FROM users u;

-- 2c. Providers Overview (PII masked)
CREATE OR REPLACE VIEW v_providers_overview AS
SELECT 
  sp.id,
  sp.name,
  CONCAT('***', RIGHT(COALESCE(sp.phone, '0000'), 4)) AS phone_masked,
  CASE 
    WHEN sp.email IS NOT NULL AND sp.email LIKE '%@%' THEN CONCAT(LEFT(sp.email, 2), '***@', SUBSTRING_INDEX(sp.email, '@', -1))
    ELSE '***'
  END AS email_masked,
  sp.gender,
  sp.status,
  sp.area,
  sp.city,
  sp.is_verified,
  sp.followup_status,
  sp.registration_paid,
  sp.created_at,
  spw.amount AS wallet_amount,
  COALESCE(st.tier_label, CONCAT('tier_', COALESCE(st.tier, 3))) AS tier_name,
  COALESCE(sc.genuine_score, 0) AS genuineness_score,
  COALESCE(ss.total_walks, 0) AS stat_total_bookings,
  COALESCE(ss.completed_walks, 0) AS stat_completed_bookings,
  COALESCE(ss.cancelled_walks, 0) AS stat_cancelled_bookings,
  COALESCE(ss.avg_rating, 0) AS stat_avg_rating
FROM service_providers sp
LEFT JOIN sp_withdrawal_wallet spw ON spw.provider_id = sp.id
LEFT JOIN sp_tiers st ON st.provider_id = sp.id
LEFT JOIN sp_scores sc ON sc.sp_id = sp.id
LEFT JOIN sp_stats ss ON ss.provider_id = sp.id;

-- 2d. Revenue Summary
CREATE OR REPLACE VIEW v_revenue_summary AS
SELECT
  t.id AS transaction_id,
  t.booking_id,
  t.transaction_type AS service_type,
  t.amount,
  (t.amount * 0.10) AS platform_fee,
  (t.amount * 0.90) AS provider_amount,
  t.payment_mode AS payment_method,
  t.status AS payment_status,
  t.created_at
FROM transactions t
WHERE t.status IN ('success', 'SUCCESS', 'COMPLETED', 'completed');

-- 2e. Quotations Overview
CREATE OR REPLACE VIEW v_quotations_overview AS
SELECT
  q.id,
  q.booking_id,
  q.provider_id,
  COALESCE(q.total_amount, q.bid_amount, q.actual_amount, 0) AS quoted_price,
  q.status AS quote_status,
  COALESCE(q.service_mode, 'general') AS service_type,
  q.created_at
FROM quotations q;

-- 2f. Services Overview
CREATE OR REPLACE VIEW v_services_overview AS
SELECT
  s.id,
  s.name,
  s.name AS service_type,
  s.status,
  s.created_at
FROM services s;
