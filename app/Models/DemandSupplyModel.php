<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DemandSupplyModel
 * 
 * Interacts with the demand_supply_snapshot table and calculates 
 * live demand-supply KPIs across pincodes and service types.
 */
class DemandSupplyModel extends Model
{
    protected $table = 'demand_supply_snapshot';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pincode',
        'service_type',
        'date',
        'daily_jobs',
        'active_sp_count',
        'genuine_sp_count'
    ];

    protected $useTimestamps = false;

    /**
     * Gets supply-demand stats grouped by pincode and service type.
     */
    public function getDemandSupplySummary(string $pincode = '', string $serviceType = ''): array
    {
        $builder = $this->db->table($this->table);

        if (!empty($pincode)) {
            $builder->where('pincode', $pincode);
        }
        if (!empty($serviceType)) {
            $builder->where('service_type', $serviceType);
        }

        // We ceiling calculation is built-in as generated store column, 
        // but we select fields explicitly and calculate if not present.
        $results = $builder->orderBy('date', 'DESC')->get()->getResultArray();

        // Calculate custom gap indicators if needed or supplement with active jobs
        foreach ($results as &$row) {
            $row['area'] = $this->resolveAreaName($row['pincode']);
            $row['required_sp'] = (int)ceil($row['daily_jobs'] * 1.4 / 3);
            $row['gap'] = $row['required_sp'] - $row['genuine_sp_count'];
            $row['sp_to_job_ratio'] = $row['daily_jobs'] > 0 ? round($row['active_sp_count'] / $row['daily_jobs'], 2) : 0;
            
            // Status flags based on gap
            if ($row['gap'] > 5) {
                $row['status'] = 'Critical';
            } elseif ($row['gap'] > 0) {
                $row['status'] = 'Warning';
            } else {
                $row['status'] = 'Healthy';
            }
        }

        return $results;
    }

    /**
     * Aggregates live demand (client actions) in the last 24 hours.
     * Searches booking records and unsuccessful searches.
     */
    public function getLiveDemandStats(): array
    {
        $todayDate = date('Y-m-d');
        
        // Count active / confirmed status bookings and today's bookings across all 4 service tables
        $tables = [
            'walking_service_bookings',
            'grooming_service_bookings',
            'training_service_bookings',
            'sitting_service_bookings'
        ];
        $totalBookings = 0;
        $activeConfirmedJobs = 0;

        foreach ($tables as $table) {
            $count = $this->db->table($table)
                ->groupStart()
                    ->like('service_start_date', $todayDate)
                    ->orLike('created_at', $todayDate)
                ->groupEnd()
                ->countAllResults();
            $totalBookings += $count;

            $activeCount = $this->db->table($table)
                ->whereIn('LOWER(status)', ['accepted', 'confirmed', 'in progress', 'in-progress', 'assigned', 'active', 'pending'])
                ->countAllResults();
            $activeConfirmedJobs += $activeCount;
        }
            
        // Unsuccessful bookings (cancelled with "no provider available" reason or status cancelled)
        $unsuccessful = $this->db->table('booking_pets')
            ->select('COUNT(id) as total')
            ->where('created_at >=', $todayDate . ' 00:00:00')
            ->groupStart()
                ->like('cancel_reason', 'no provider')
                ->orWhere('status', 'cancelled')
            ->groupEnd()
            ->get()->getRowArray();

        return [
            'daily_bookings'          => $totalBookings > 0 ? $totalBookings : $activeConfirmedJobs,
            'active_jobs_at_moment'   => $activeConfirmedJobs,
            'unsuccessful_searches'   => (int)($unsuccessful['total'] ?? 0),
            'peak_hours'              => '6 AM - 8 AM, 5 PM - 7 PM',
            'cancellations_due_to_sp' => (int)($unsuccessful['total'] ?? 0)
        ];
    }

    private static array $resolvedPincodeCache = [];

    /**
     * Aggregates live supply (provider statistics).
     */
    public function getLiveSupplyStats(): array
    {
        $statusCounts = $this->db->table('service_providers')
            ->select('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->get()->getResultArray();

        $totalRegistered = 0;
        $activeSp = 0;
        $inactiveSp = 0;
        $kycPending = 0;

        foreach ($statusCounts as $sc) {
            $cnt = (int)$sc['cnt'];
            $totalRegistered += $cnt;
            $st = strtolower(trim((string)($sc['status'] ?? '')));
            if (in_array($st, ['active', 'approved', '1'], true)) {
                $activeSp += $cnt;
            } elseif (in_array($st, ['inactive', '0', 'disabled', 'blocked', 'suspended', 'rejected'], true)) {
                $inactiveSp += $cnt;
            } elseif ($st === 'pending_kyc') {
                $kycPending += $cnt;
            }
        }

        // 2. Fee Pending (service_providers joining sp_services where registration fee is unpaid)
        $feePendingRow = $this->db->table('service_providers sp')
            ->join('sp_services ss', 'ss.provider_id = sp.id', 'left')
            ->groupStart()
                ->where('ss.id IS NULL')
                ->orWhere('ss.payment_paid', 0)
                ->orWhere('ss.payment_paid IS NULL')
            ->groupEnd()
            ->whereNotIn('sp.status', ['rejected', 'disabled', 'blocked', 'pending_kyc'])
            ->select('COUNT(DISTINCT sp.id) as cnt')
            ->get()->getRowArray();
        $feePending = (int)($feePendingRow['cnt'] ?? 0);

        // 3. Call Verification Pending (service_providers with payment_paid = 1 but call_verified = 0 or null)
        $callPendingRow = $this->db->table('service_providers sp')
            ->join('sp_services ss', 'ss.provider_id = sp.id', 'inner')
            ->where('ss.payment_paid', 1)
            ->groupStart()
                ->where('ss.call_verified', 0)
                ->orWhere('ss.call_verified IS NULL')
            ->groupEnd()
            ->whereNotIn('sp.status', ['rejected', 'disabled', 'blocked'])
            ->select('COUNT(DISTINCT sp.id) as cnt')
            ->get()->getRowArray();
        $callPending = (int)($callPendingRow['cnt'] ?? 0);

        // 4. KYC Pending (service_providers with pending_kyc or unverified Aadhar)
        $kycPendingRow = $this->db->table('service_providers sp')
            ->groupStart()
                ->where('sp.status', 'pending_kyc')
                ->orWhere('sp.aadhar_verified', '0')
                ->orWhere('sp.aadhar_verified IS NULL')
            ->groupEnd()
            ->whereNotIn('sp.status', ['rejected','pending_verification','active'])
            ->select('COUNT(DISTINCT sp.id) as cnt')
            ->get()->getRowArray();
        $totalKycPending = (int)($kycPendingRow['cnt'] ?? 0);

        $genuineSp = $this->db->table('sp_stats')
            ->where('ranking_score >=', 70.0)
            ->countAllResults();

        $dropOffRate = $totalRegistered > 0 ? round(($inactiveSp / $totalRegistered) * 100, 1) : 0;

        return [
            'total_registered'          => $totalRegistered,
            'active_sps'                => $activeSp,
            'inactive_sps'              => $inactiveSp,
            'genuine_sps'               => $genuineSp,
            'fee_pending'               => $feePending,
            'call_verification_pending' => $callPending,
            'kyc_pending'               => $totalKycPending,
            'drop_off_rate_percent'     => $dropOffRate,
            'sp_to_job_ratio'           => $activeSp > 0 ? round($activeSp / 100, 2) : 0
        ];
    }

    /**
     * Resolves the area name from a pincode
     */
    public function resolveAreaName(string $pincode): string
    {
        $pincode = trim($pincode);
        if (empty($pincode)) {
            return 'Unknown Zone';
        }

        if (isset(self::$resolvedPincodeCache[$pincode])) {
            return self::$resolvedPincodeCache[$pincode];
        }

        $pincodeToArea = [
            '500081' => 'Hitec City',
            '500032' => 'Gachibowli',
            '500003' => 'Secunderabad',
            '500072' => 'Kukatpally',
            '500084' => 'Kondapur',
            '500033' => 'Jubilee Hills',
            '500034' => 'Banjara Hills'
        ];

        if (isset($pincodeToArea[$pincode])) {
            self::$resolvedPincodeCache[$pincode] = $pincodeToArea[$pincode];
            return $pincodeToArea[$pincode];
        }

        $sp = $this->db->table('service_providers')
            ->select('area')
            ->groupStart()
                ->like('service_address', $pincode)
                ->orLike('area', $pincode)
            ->groupEnd()
            ->where("area != '' AND area NOT REGEXP '^[0-9]+$'")
            ->limit(1)
            ->get()->getRowArray();

        if (!empty($sp) && !empty($sp['area']) && !is_numeric($sp['area'])) {
            $resolved = ucwords(strtolower(trim($sp['area'])));
            self::$resolvedPincodeCache[$pincode] = $resolved;
            return $resolved;
        }

        $fallback = 'Zone ' . $pincode;
        self::$resolvedPincodeCache[$pincode] = $fallback;
        return $fallback;
    }

    /**
     * Resolves the city name from a pincode
     */
    public function resolveCityName(string $pincode): string
    {
        $pincode = trim($pincode);
        if (strpos($pincode, '50') === 0) return 'Hyderabad';
        if (strpos($pincode, '56') === 0) return 'Bengaluru';
        if (strpos($pincode, '11') === 0) return 'Delhi';
        if (strpos($pincode, '40') === 0 || strpos($pincode, '41') === 0) return 'Mumbai / Pune';
        if (strpos($pincode, '60') === 0) return 'Chennai';
        if (strpos($pincode, '70') === 0) return 'Kolkata';

        // Default if unknown
        return 'Hyderabad';
    }

    /**
     * Generates dynamic pincode options for the simulator
     */
    public function getSimulatorOptions(): array
    {
        $snapshots = $this->db->table($this->table)
            ->select('pincode')
            ->distinct()
            ->get()->getResultArray();

        $defaultCoords = [
            '500081' => ['lat' => 17.4483, 'lng' => 78.3741],
            '500032' => ['lat' => 17.4195, 'lng' => 78.3489],
            '500003' => ['lat' => 17.4399, 'lng' => 78.4983],
            '500072' => ['lat' => 17.4855, 'lng' => 78.3885],
            '500084' => ['lat' => 17.4651, 'lng' => 78.3587]
        ];

        $options = [];
        foreach ($snapshots as $snap) {
            $pin = trim($snap['pincode']);
            if (empty($pin)) continue;

            $area = $this->resolveAreaName($pin);
            // Rough spread around Hyderabad if unknown, real solution would use geocoding
            $lat = $defaultCoords[$pin]['lat'] ?? (17.3850 + (rand(-100, 100) / 1000));
            $lng = $defaultCoords[$pin]['lng'] ?? (78.4867 + (rand(-100, 100) / 1000));

            $options[] = [
                'value' => $pin,
                'label' => $pin . ' - ' . $area,
                'lat' => $lat,
                'lng' => $lng
            ];
        }

        // Add defaults if empty
        if (empty($options)) {
            $options = [
                ['value' => '500081', 'label' => '500081 - Hitec City', 'lat' => 17.4483, 'lng' => 78.3741],
                ['value' => '500032', 'label' => '500032 - Gachibowli', 'lat' => 17.4195, 'lng' => 78.3489]
            ];
        }

        return $options;
    }
}
