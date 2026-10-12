<?php

namespace App\Services;

use App\Libraries\PushNotify;
use CodeIgniter\Database\BaseConnection;

/**
 * SpViolationWorkflowService
 * Orchestrates the automated Contact Sharing + Off-App Payment Detection Workflow:
 * 1. Create Critical Violation Case
 * 2. Check Provider Active Services
 * 3. Place Temporary Account Hold & High-Priority Admin Alert
 * 4. Alert Provider About Active Services OR Notify Provider About Temporary Hold
 * 5. Admin Review Evidence & Adjudication (Confirm vs Dismiss)
 * 6. Apply First-Time Penalty OR Release Hold & Clear Incorrect Flag
 * 7. Hold Expiry & Automatic Account Access Restoration
 * (No Wallet Holds - strictly account-level matching hold)
 */
class SpViolationWorkflowService
{
    protected BaseConnection $db;
    protected PushNotify $pushNotify;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->pushNotify = new PushNotify();
    }

    /**
     * Entry point: Triggered when AI detects Contact Sharing + Off-App Payment / Bypass intent.
     *
     * @param int $recordingId
     * @param array $rawAnalysis
     * @param int|null $providerId
     * @param int|null $customerId
     * @return array
     */
    public function handleCriticalViolationDetected(int $recordingId, array $rawAnalysis, ?int $providerId = null, ?int $customerId = null): array
    {
        // 1. Fetch recording metadata if providerId or customerId not passed
        $rec = $this->db->table('call_recordings')->where('id', $recordingId)->get()->getRowArray();
        if ($rec) {
            $providerId  = $providerId ?: (int)($rec['provider_id'] ?? 0);
            $customerId  = $customerId ?: (int)($rec['customer_id'] ?? 0);
            $callId      = !empty($rec['call_id']) ? (int)$rec['call_id'] : null;
            $serviceType = !empty($rec['service_type']) ? $rec['service_type'] : 'walking';
        } else {
            $callId      = null;
            $serviceType = $rawAnalysis['service_type'] ?? 'walking';
        }

        if (!$providerId) {
            log_message('warning', "SpViolationWorkflowService: Cannot create case without provider_id for recording {$recordingId}");
            return ['status' => false, 'message' => 'Provider ID missing'];
        }

        // Prevent duplicate open/under_review cases for the same recording
        $existingCase = $this->db->table('sp_violation_cases')
            ->where('recording_id', $recordingId)
            ->whereIn('status', ['open', 'under_review'])
            ->get()
            ->getRowArray();

        if ($existingCase) {
            return [
                'status'                  => true,
                'already_exists'          => true,
                'case_id'                 => (int)$existingCase['id'],
                'case_number'             => $existingCase['case_number'],
                'has_active_services'     => (bool)$existingCase['has_active_services'],
                'affected_bookings_count' => (int)$existingCase['affected_bookings_count']
            ];
        }

        // 2. Extract Evidence Snippet and Timestamp Marker
        $evidenceSnippet   = $this->extractEvidenceSnippet($rawAnalysis);
        $evidenceTimestamp = $this->extractEvidenceTimestamp($rawAnalysis);

        // Generate unique case number: e.g. CASE-202610-0042
        $countToday = $this->db->table('sp_violation_cases')->countAllResults();
        $caseNumber = 'CASE-' . date('Ym') . '-' . str_pad((string)($countToday + 1), 4, '0', STR_PAD_LEFT);

        // 3. Check Provider Active Services
        $activeServices = $this->getActiveServices($providerId);
        $hasActiveServices = !empty($activeServices);
        $affectedCount     = count($activeServices);
        $affectedIds       = array_column($activeServices, 'booking_id');

        $this->db->transStart();

        // 4. Create Critical Violation Case
        $caseData = [
            'case_number'             => $caseNumber,
            'recording_id'            => $recordingId,
            'call_id'                 => $callId,
            'provider_id'             => $providerId,
            'customer_id'             => $customerId ?: null,
            'service_type'            => $serviceType,
            'violation_type'          => 'contact_share_and_off_app_pay',
            'severity'                => 'critical',
            'evidence_snippet'        => $evidenceSnippet,
            'evidence_timestamp'      => $evidenceTimestamp,
            'has_active_services'     => $hasActiveServices ? 1 : 0,
            'affected_bookings_count' => $affectedCount,
            'affected_booking_ids'    => json_encode($affectedIds),
            'status'                  => 'open',
            'created_at'              => date('Y-m-d H:i:s'),
            'updated_at'              => date('Y-m-d H:i:s'),
        ];
        $this->db->table('sp_violation_cases')->insert($caseData);
        $caseId = (int)$this->db->insertID();

        // 5. Place Temporary Account Hold (Default 24 hours - No wallet hold)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $holdData = [
            'case_id'      => $caseId,
            'provider_id'  => $providerId,
            'hold_type'    => 'temporary_investigation_hold',
            'status'       => 'active',
            'reason'       => 'Temporary Hold: Contact Sharing & Off-Platform Payment detected in call',
            'started_at'   => date('Y-m-d H:i:s'),
            'expires_at'   => $expiresAt,
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        $this->db->table('sp_account_holds')->insert($holdData);
        $holdId = (int)$this->db->insertID();

        // Update case with hold_id
        $this->db->table('sp_violation_cases')->where('id', $caseId)->update(['hold_id' => $holdId]);

        // Place hold on service_providers table (pauses new lead assignments)
        $this->db->table('service_providers')->where('id', $providerId)->update([
            'is_on_hold'      => 1,
            'hold_expires_at' => $expiresAt,
            'hold_reason'     => "Under Critical Investigation ({$caseNumber})",
        ]);

        // 6. Notify Admin: High-Priority Alert
        $this->notifyAdminHighPriorityAlert($caseId, $caseNumber, $providerId, $affectedCount, $evidenceSnippet);

        $this->db->transComplete();

        // 7. Branch Notification to Provider
        $provider = $this->db->table('service_providers')->where('id', $providerId)->get()->getRowArray();
        $deviceToken = $provider['device_token'] ?? null;

        if ($hasActiveServices) {
            // Alert Provider About Active Services
            $this->notifyProviderActiveServices($providerId, $deviceToken, $affectedCount);
        } else {
            // Notify Provider About Temporary Hold
            $this->notifyProviderTemporaryHold($providerId, $deviceToken);
        }

        return [
            'status'                  => true,
            'case_id'                 => $caseId,
            'case_number'             => $caseNumber,
            'hold_id'                 => $holdId,
            'has_active_services'     => $hasActiveServices,
            'affected_bookings_count' => $affectedCount,
            'affected_booking_ids'    => $affectedIds,
        ];
    }

    /**
     * Query all active / in-progress bookings for a provider across all service types.
     */
    public function getActiveServices(int $providerId): array
    {
        $today = date('Y-m-d');

        // Check accepted quotes linked to active service bookings across all 5 services
        $query = $this->db->query("
            SELECT 
                b.booking_id,
                b.service_type as service_name,
                b.status as booking_status,
                b.service_start_date as service_date,
                COALESCE(b.service_end_date, b.service_start_date) as service_end_date,
                b.user_name as client_name,
                u.phone as client_phone
            FROM quotations q
            JOIN v_bookings_overview b ON b.booking_id = q.booking_id
            LEFT JOIN users u ON u.id = b.user_id
            WHERE q.provider_id = ?
              AND LOWER(q.status) IN ('accepted', 'confirmed', 'selected', 'approved', 'paid', '1')
              AND b.is_deleted = 0
              AND LOWER(b.status) NOT IN ('cancelled', 'completed', 'terminated', 'rejected')
              AND (b.service_end_date IS NULL OR b.service_end_date >= '{$today}' OR b.service_start_date >= '{$today}')
            GROUP BY b.booking_id
            ORDER BY b.service_start_date ASC
        ", [$providerId]);

        return $query->getResultArray();
    }

    /**
     * Admin Confirms Violation:
     * - Apply First-Time Penalty (strike increment, warning record)
     * - Maintain hold until expiration or set custom hold duration
     * - Status marked as 'confirmed'
     */
    public function adminConfirmViolation(int $caseId, int $adminId, string $adminRemarks, int $holdHours = 24): array
    {
        $case = $this->db->table('sp_violation_cases')->where('id', $caseId)->get()->getRowArray();
        if (!$case) {
            return ['status' => false, 'message' => 'Case not found.'];
        }

        $providerId = (int)$case['provider_id'];

        $this->db->transStart();

        // 1. Calculate strike count for this provider
        $pastStrikes = $this->db->table('sp_penalties')->where('provider_id', $providerId)->countAllResults();
        $newStrikeCount = $pastStrikes + 1;

        // 2. Insert into sp_penalties (1st-Time Penalty / Strike)
        $penaltyData = [
            'case_id'         => $caseId,
            'provider_id'     => $providerId,
            'strike_count'    => $newStrikeCount,
            'penalty_type'    => 'strike_warning',
            'probation_until' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'notes'           => $adminRemarks ?: 'Violation confirmed by admin. Strike applied.',
            'applied_by'      => $adminId,
            'created_at'      => date('Y-m-d H:i:s'),
        ];
        $this->db->table('sp_penalties')->insert($penaltyData);
        $penaltyId = (int)$this->db->insertID();

        // 3. Adjust hold duration if custom hours given
        $newHoldExpiry = date('Y-m-d H:i:s', strtotime("+{$holdHours} hours"));
        $this->db->table('sp_account_holds')->where('case_id', $caseId)->update([
            'status'     => 'active',
            'expires_at' => $newHoldExpiry,
            'reason'     => "Confirmed Violation (Strike #{$newStrikeCount}): {$adminRemarks}",
        ]);

        $this->db->table('service_providers')->where('id', $providerId)->update([
            'is_on_hold'      => 1,
            'hold_expires_at' => $newHoldExpiry,
            'hold_reason'     => "Confirmed Violation Strike #{$newStrikeCount}",
        ]);

        // 4. Update Case status
        $this->db->table('sp_violation_cases')->where('id', $caseId)->update([
            'status'        => 'confirmed',
            'penalty_id'    => $penaltyId,
            'reviewed_by'   => $adminId,
            'reviewed_at'   => date('Y-m-d H:i:s'),
            'admin_remarks' => $adminRemarks,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();

        // 5. Send push notification to Provider
        $provider = $this->db->table('service_providers')->where('id', $providerId)->get()->getRowArray();
        if (!empty($provider['device_token'])) {
            $title = "⚠️ Policy Violation Confirmed (Strike #{$newStrikeCount})";
            $body  = "Admin review confirmed a policy violation regarding contact sharing / off-app payment. Account hold active until " . date('M d, h:i A', strtotime($newHoldExpiry)) . ". Please review partner guidelines.";
            $this->pushNotify->notify($provider['device_token'], $title, $body);
        }

        return [
            'status'       => true,
            'message'      => "Violation confirmed. Strike #{$newStrikeCount} applied. Hold set until " . date('d M Y, h:i A', strtotime($newHoldExpiry)),
            'strike_count' => $newStrikeCount,
            'expires_at'   => $newHoldExpiry,
        ];
    }

    /**
     * Admin Dismisses Violation:
     * - Release Hold and Clear Incorrect Flag
     * - Restore eligible account access immediately
     * - Mark status as 'dismissed'
     */
    public function adminDismissViolation(int $caseId, int $adminId, string $adminRemarks): array
    {
        $case = $this->db->table('sp_violation_cases')->where('id', $caseId)->get()->getRowArray();
        if (!$case) {
            return ['status' => false, 'message' => 'Case not found.'];
        }

        $providerId  = (int)$case['provider_id'];
        $recordingId = (int)$case['recording_id'];

        $this->db->transStart();

        // 1. Release Hold in sp_account_holds
        $this->db->table('sp_account_holds')->where('case_id', $caseId)->update([
            'status'       => 'released_by_admin',
            'released_at'  => date('Y-m-d H:i:s'),
            'released_by'  => $adminId,
            'release_note' => $adminRemarks ?: 'Cleared by admin: False positive / no violation',
        ]);

        // 2. Clear Hold from service_providers table
        $this->db->table('service_providers')->where('id', $providerId)->update([
            'is_on_hold'      => 0,
            'hold_expires_at' => null,
            'hold_reason'     => null,
        ]);

        // 3. Mark Case as dismissed
        $this->db->table('sp_violation_cases')->where('id', $caseId)->update([
            'status'        => 'dismissed',
            'reviewed_by'   => $adminId,
            'reviewed_at'   => date('Y-m-d H:i:s'),
            'admin_remarks' => $adminRemarks ?: 'Dismissed by admin. Hold cleared.',
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // 4. Update call_analysis table
        $this->db->table('call_analysis')->where('recording_id', $recordingId)->update([
            'admin_review_status' => 'reviewed',
            'admin_remarks'       => 'Cleared: ' . ($adminRemarks ?: 'False alarm cleared by Admin'),
        ]);

        $this->db->transComplete();

        // 5. Send push notification to Provider
        $provider = $this->db->table('service_providers')->where('id', $providerId)->get()->getRowArray();
        if (!empty($provider['device_token'])) {
            $title = "✅ Account Hold Released";
            $body  = "Your account review is complete and the temporary hold has been cleared. Full booking eligibility is restored.";
            $this->pushNotify->notify($provider['device_token'], $title, $body);
        }

        return [
            'status'  => true,
            'message' => 'Hold released and incorrect flag successfully cleared. Provider restored to full eligibility.',
        ];
    }

    /**
     * Cron Job Method:
     * Check holds where expires_at <= NOW() and restore eligible account access.
     */
    public function processExpiredHolds(): array
    {
        $now = date('Y-m-d H:i:s');
        $expiredHolds = $this->db->table('sp_account_holds')
            ->where('status', 'active')
            ->where('expires_at <=', $now)
            ->get()
            ->getResultArray();

        $restoredCount = 0;

        foreach ($expiredHolds as $hold) {
            $holdId     = (int)$hold['id'];
            $providerId = (int)$hold['provider_id'];
            $caseId     = (int)$hold['case_id'];

            $this->db->transStart();

            // Mark hold expired
            $this->db->table('sp_account_holds')->where('id', $holdId)->update([
                'status'      => 'expired',
                'released_at' => $now,
            ]);

            // Clear provider hold
            $this->db->table('service_providers')->where('id', $providerId)->update([
                'is_on_hold'      => 0,
                'hold_expires_at' => null,
                'hold_reason'     => null,
            ]);

            // If case was confirmed, mark it resolved
            $this->db->table('sp_violation_cases')
                ->where('id', $caseId)
                ->where('status', 'confirmed')
                ->update(['status' => 'resolved', 'updated_at' => $now]);

            $this->db->transComplete();

            // Notify Provider that eligible account access is restored
            $provider = $this->db->table('service_providers')->where('id', $providerId)->get()->getRowArray();
            if (!empty($provider['device_token'])) {
                $title = "✅ Account Access Restored";
                $body  = "Your temporary hold period has ended. Your Petsfolio partner account is now active to receive new bookings.";
                $this->pushNotify->notify($provider['device_token'], $title, $body);
            }

            $restoredCount++;
        }

        return [
            'status'         => true,
            'processed'      => count($expiredHolds),
            'restored_count' => $restoredCount,
        ];
    }

    /**
     * Fetch complete details of a violation case for the Admin review modal / view.
     */
    public function getCaseDetails(int $caseId): ?array
    {
        $case = $this->db->table('sp_violation_cases c')
            ->select('
                c.*,
                sp.name as provider_name,
                sp.phone as provider_phone,
                sp.city as provider_city,
                sp.area as provider_area,
                sp.is_on_hold as provider_is_on_hold,
                sp.hold_expires_at as provider_hold_expires_at,
                u.name as client_name,
                u.phone as client_phone,
                cr.recording_url,
                cr.duration as recording_duration,
                ca.summary as call_summary,
                ca.transcript as call_transcript,
                ca.ai_rating_category,
                ca.overall_rating,
                ca.risk_score,
                h.status as hold_status,
                h.expires_at as hold_expires_at,
                p.strike_count,
                p.penalty_type,
                admin.name as reviewer_name
            ')
            ->join('service_providers sp', 'sp.id = c.provider_id', 'left')
            ->join('users u', 'u.id = c.customer_id', 'left')
            ->join('call_recordings cr', 'cr.id = c.recording_id', 'left')
            ->join('call_analysis ca', 'ca.recording_id = c.recording_id', 'left')
            ->join('sp_account_holds h', 'h.id = c.hold_id', 'left')
            ->join('sp_penalties p', 'p.id = c.penalty_id', 'left')
            ->join('employees admin', 'admin.id = c.reviewed_by', 'left')
            ->where('c.id', $caseId)
            ->get()
            ->getRowArray();

        if (!$case) {
            return null;
        }

        // Fetch affected bookings details if any
        $bookingIds = json_decode($case['affected_booking_ids'] ?? '[]', true) ?: [];
        $case['affected_bookings'] = [];
        if (!empty($bookingIds)) {
            $case['affected_bookings'] = $this->getActiveServices((int)$case['provider_id']);
        }

        return $case;
    }

    // ──────────────── Private Helpers ────────────────

    private function extractEvidenceSnippet(array $rawAnalysis): string
    {
        if (!empty($rawAnalysis['policy_violations']) && is_array($rawAnalysis['policy_violations'])) {
            $snippets = [];
            foreach ($rawAnalysis['policy_violations'] as $v) {
                if (is_array($v) && !empty($v['statement'])) {
                    $snippets[] = '"' . trim($v['statement']) . '"' . (!empty($v['category']) ? " ({$v['category']})" : "");
                } elseif (is_string($v)) {
                    $snippets[] = trim($v);
                }
            }
            if (!empty($snippets)) {
                return implode(" | ", array_slice($snippets, 0, 3));
            }
        }

        if (!empty($rawAnalysis['highlighted_conversations']) && is_array($rawAnalysis['highlighted_conversations'])) {
            $snippets = [];
            foreach ($rawAnalysis['highlighted_conversations'] as $h) {
                if (is_array($h) && !empty($h['statement']) && in_array(strtolower($h['type'] ?? ''), ['policy_violation', 'off_platform_payment', 'contact_sharing'])) {
                    $snippets[] = '"' . trim($h['statement']) . '"';
                }
            }
            if (!empty($snippets)) {
                return implode(" | ", array_slice($snippets, 0, 3));
            }
        }

        return 'Contact sharing and off-platform payment detected in call transcript.';
    }

    private function extractEvidenceTimestamp(array $rawAnalysis): string
    {
        if (!empty($rawAnalysis['policy_violations']) && is_array($rawAnalysis['policy_violations'])) {
            foreach ($rawAnalysis['policy_violations'] as $v) {
                if (is_array($v) && !empty($v['timestamp'])) {
                    return (string)$v['timestamp'];
                }
            }
        }

        if (!empty($rawAnalysis['highlighted_conversations']) && is_array($rawAnalysis['highlighted_conversations'])) {
            foreach ($rawAnalysis['highlighted_conversations'] as $h) {
                if (is_array($h) && !empty($h['timestamp'])) {
                    return (string)$h['timestamp'];
                }
            }
        }

        return '00:00';
    }

    private function notifyAdminHighPriorityAlert(int $caseId, string $caseNumber, int $providerId, int $affectedCount, string $evidence): void
    {
        // 1. Insert into admin_alerts
        $this->db->table('admin_alerts')->insert([
            'alert_type'   => 'critical_call_violation',
            'reference_id' => $caseNumber,
            'severity'     => 'critical',
            'status'       => 'new',
            'details'      => json_encode([
                'case_id'                 => $caseId,
                'case_number'             => $caseNumber,
                'provider_id'             => $providerId,
                'affected_bookings_count' => $affectedCount,
                'evidence'                => $evidence,
            ]),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // 2. Insert into admin_notifications
        $this->db->table('admin_notifications')->insert([
            'type'        => 'call_violation_critical',
            'title'       => "🚨 Critical Alert: Contact Share & Bypass ({$caseNumber})",
            'message'     => "Provider #{$providerId} detected discussing contact sharing and off-app payments. Temporary account hold placed. {$affectedCount} active service(s) affected.",
            'entity_type' => 'violation_case',
            'entity_id'   => $caseId,
            'priority'    => 'critical',
            'is_read'     => 0,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    private function notifyProviderActiveServices(int $providerId, ?string $deviceToken, int $affectedCount): void
    {
        $title = "⚠️ Notice: Scheduled Services Active";
        $body  = "Your account is under compliance review for new requests. However, you have {$affectedCount} active confirmed booking(s). You must complete your pet sessions as scheduled and submit all tracking updates in the app.";

        if (!empty($deviceToken)) {
            $this->pushNotify->notify($deviceToken, $title, $body);
        }

        // Log notification in notification_logs
        $this->db->table('notification_logs')->insert([
            'provider_id'   => $providerId,
            'user_type'     => 'provider',
            'channel'       => 'push',
            'title'         => $title,
            'body'          => $body,
            'status'        => !empty($deviceToken) ? 'sent' : 'queued',
            'device_token'  => $deviceToken,
            'created_at'    => date('Y-m-d H:i:s'),
            'sent_at'       => !empty($deviceToken) ? date('Y-m-d H:i:s') : null,
        ]);
    }

    private function notifyProviderTemporaryHold(int $providerId, ?string $deviceToken): void
    {
        $title = "⚠️ Account Notice: Temporary Hold";
        $body  = "Your Petsfolio partner account has been placed on temporary hold pending review of recent client communication. Our team will review and update you within 24 hours.";

        if (!empty($deviceToken)) {
            $this->pushNotify->notify($deviceToken, $title, $body);
        }

        // Log notification in notification_logs
        $this->db->table('notification_logs')->insert([
            'provider_id'   => $providerId,
            'user_type'     => 'provider',
            'channel'       => 'push',
            'title'         => $title,
            'body'          => $body,
            'status'        => !empty($deviceToken) ? 'sent' : 'queued',
            'device_token'  => $deviceToken,
            'created_at'    => date('Y-m-d H:i:s'),
            'sent_at'       => !empty($deviceToken) ? date('Y-m-d H:i:s') : null,
        ]);
    }
}
