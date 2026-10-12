<?php

namespace App\Controllers;

use App\Models\AnalyticsEventModel;
use App\Models\AnalyticsFunnelModel;
use CodeIgniter\RESTful\ResourceController;

class AnalyticsApiController extends ResourceController
{
    protected AnalyticsEventModel $eventModel;

    public function __construct()
    {
        $this->eventModel = new AnalyticsEventModel();
        // Ensure tables exist
        (new AnalyticsFunnelModel())->ensureTables();
    }

    /**
     * Ingest events from Mobile App (Android/iOS) or Web Frontend
     * Supports single JSON event object or array of events (batch)
     */
    public function recordEvents()
    {
        // Handle CORS preflight
        if ($this->request->getMethod() === 'options') {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS')
                ->setStatusCode(200);
        }

        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $raw = $this->request->getPost();
            if (!empty($raw)) {
                $json = $raw;
            }
        }

        if (empty($json)) {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setJSON(['status' => 'error', 'message' => 'Empty payload received.'])
                ->setStatusCode(400);
        }

        $ip = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent() ? (string)$this->request->getUserAgent() : null;

        // Check if single or batch
        $isBatch = isset($json[0]) && is_array($json[0]);
        $eventsList = $isBatch ? $json : [$json];

        $validEvents = [];
        foreach ($eventsList as $ev) {
            if (empty($ev['event_name']) || empty($ev['session_id'])) {
                continue;
            }

            $ev['ip_address'] = $ip;
            if (empty($ev['user_agent'])) {
                $ev['user_agent'] = substr($userAgent ?? '', 0, 250);
            }
            if (empty($ev['created_at'])) {
                $ev['created_at'] = date('Y-m-d H:i:s');
            }
            $validEvents[] = $ev;
        }

        if (empty($validEvents)) {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'No valid events found in payload. "event_name" and "session_id" are required.'
                ])
                ->setStatusCode(422);
        }

        if (count($validEvents) === 1) {
            $insertedId = $this->eventModel->logEvent($validEvents[0]);
            $count = $insertedId ? 1 : 0;
        } else {
            $count = $this->eventModel->logEventsBatch($validEvents);
        }

        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setJSON([
                'status'   => 'success',
                'recorded' => $count,
                'message'  => "{$count} analytics event(s) recorded successfully."
            ])
            ->setStatusCode(201);
    }
}
