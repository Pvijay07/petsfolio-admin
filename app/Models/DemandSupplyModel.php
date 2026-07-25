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
        
        // Sum bookings created today across all 4 tables
        $tables = [
            'walking_service_bookings',
            'grooming_service_bookings',
            'training_service_bookings',
            'sitting_service_bookings'
        ];
        $totalBookings = 0;
        foreach ($tables as $table) {
            $count = $this->db->table($table)
                ->where('service_start_date', $todayDate)
                ->countAllResults();
            $totalBookings += $count;
        }
            
        // Unsuccessful bookings (cancelled with "no provider available" reason)
        $unsuccessful = $this->db->table('booking_pets')
            ->select('COUNT(id) as total')
            ->where('created_at >=', $todayDate . ' 00:00:00')
            ->where('cancel_reason LIKE', '%no provider%')
            ->get()->getRowArray();

        return [
            'daily_bookings' => $totalBookings,
            'unsuccessful_searches' => $unsuccessful['total'] ?? 0,
            'peak_hours' => '6 AM - 8 AM, 5 PM - 7 PM',
            'cancellations_due_to_sp' => $unsuccessful['total'] ?? 0
        ];
    }

    /**
     * Aggregates live supply (provider statistics).
     */
    public function getLiveSupplyStats(): array
    {
        // Total active providers
        $activeSp = $this->db->table('service_providers')
            ->where('status', 'active')
            ->countAllResults();

        // Count of SPs with Genuine Score > 70
        // We link sp_stats with ranking_score > 70
        $genuineSp = $this->db->table('sp_stats')
            ->where('ranking_score >=', 70.0)
            ->countAllResults();

        // Calculate drop-off rate (inactive providers / total ever registered)
        $totalRegistered = $this->db->table('service_providers')->countAllResults();
        $inactiveSp = $this->db->table('service_providers')->where('status', 'inactive')->countAllResults();
        $dropOffRate = $totalRegistered > 0 ? round(($inactiveSp / $totalRegistered) * 100, 1) : 0;

        return [
            'active_sps' => $activeSp,
            'genuine_sps' => $genuineSp,
            'drop_off_rate_percent' => $dropOffRate,
            'sp_to_job_ratio' => $activeSp > 0 ? round($activeSp / 100, 2) : 0 // placeholder
        ];
    }

    /**
     * Resolves the area name from a pincode
     */
    public function resolveAreaName(string $pincode): string
    {
        $pincode = trim($pincode);
        $pincodeToArea = [
            '500081' => 'Hitec City',
            '500032' => 'Gachibowli',
            '500003' => 'Secunderabad',
            '500072' => 'Kukatpally',
            '500084' => 'Kondapur'
        ];

        if (isset($pincodeToArea[$pincode])) {
            return $pincodeToArea[$pincode];
        }

        // Try to fetch dynamically from service providers
        $sp = $this->db->table('service_providers')
            ->select('area')
            ->like('service_address', $pincode)
            ->orLike('area', $pincode)
            ->where("area != '' AND area NOT REGEXP '^[0-9]+$'")
            ->get()->getRowArray();
            
        if (!empty($sp) && !empty($sp['area']) && !is_numeric($sp['area'])) {
            return ucwords(strtolower(trim($sp['area'])));
        }

        return 'Zone ' . $pincode;
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
