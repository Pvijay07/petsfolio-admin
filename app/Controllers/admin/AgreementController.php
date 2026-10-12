<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class AgreementController extends BaseController
{
    protected $helpers = ['text', 'url', 'form'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Fetch SP agreements
        $spAgreements = [];
        if ($this->db->tableExists('provider_agreements')) {
            $spAgreements = $this->db->table('provider_agreements')
                ->orderBy('accepted_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        // 2. Fetch Client agreements
        $clientAgreements = [];
        if ($this->db->tableExists('client_agreements')) {
            $clientAgreements = $this->db->table('client_agreements')
                ->orderBy('accepted_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        // 3. Compute Summary Metrics
        $totalSpAgreements     = count($spAgreements);
        $totalClientAgreements = count($clientAgreements);
        $totalAgreements       = $totalSpAgreements + $totalClientAgreements;

        // Recent within last 24h
        $oneDayAgo = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $recentCount = 0;
        foreach ($spAgreements as $sa) {
            if (!empty($sa['accepted_at']) && $sa['accepted_at'] >= $oneDayAgo) {
                $recentCount++;
            }
        }
        foreach ($clientAgreements as $ca) {
            if (!empty($ca['accepted_at']) && $ca['accepted_at'] >= $oneDayAgo) {
                $recentCount++;
            }
        }

        // Latest agreement info
        $latestItem = null;
        $latestTime = 0;
        if (!empty($spAgreements[0])) {
            $t = strtotime($spAgreements[0]['accepted_at'] ?? '2000-01-01');
            if ($t > $latestTime) {
                $latestTime = $t;
                $latestItem = [
                    'name' => $spAgreements[0]['provider_name'] ?? 'Provider',
                    'type' => 'Service Provider',
                    'time' => $spAgreements[0]['accepted_at'] ?? ''
                ];
            }
        }
        if (!empty($clientAgreements[0])) {
            $t = strtotime($clientAgreements[0]['accepted_at'] ?? '2000-01-01');
            if ($t > $latestTime) {
                $latestTime = $t;
                $latestItem = [
                    'name' => $clientAgreements[0]['client_name'] ?? 'Client',
                    'type' => 'Client',
                    'time' => $clientAgreements[0]['accepted_at'] ?? ''
                ];
            }
        }

        $data = [
            'spAgreements'          => $spAgreements,
            'clientAgreements'      => $clientAgreements,
            'totalSpAgreements'     => $totalSpAgreements,
            'totalClientAgreements' => $totalClientAgreements,
            'totalAgreements'       => $totalAgreements,
            'recentCount'           => $recentCount,
            'latestItem'            => $latestItem,
        ];

        return $this->loadView('admin/agreements', $data);
    }

    public function getDetails($type, $id)
    {
        $id = (int)$id;
        if (!$id || !in_array($type, ['sp', 'client'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Invalid parameters provided.'
            ]);
        }

        $table = ($type === 'sp') ? 'provider_agreements' : 'client_agreements';
        if (!$this->db->tableExists($table)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Agreements table not found.'
            ]);
        }

        $record = $this->db->table($table)->where('id', $id)->get()->getRowArray();
        if (!$record) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Agreement record not found.'
            ]);
        }

        // Parse JSON fields if valid JSON
        $record['acceptance_record_parsed'] = null;
        if (!empty($record['acceptance_record'])) {
            $parsed = json_decode($record['acceptance_record'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $record['acceptance_record_parsed'] = $parsed;
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'type'   => $type,
            'data'   => $record
        ]);
    }
}
