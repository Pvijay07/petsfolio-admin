<?php

namespace App\Controllers\admin;

use App\Models\EventModel;
use CodeIgniter\API\ResponseTrait;

class EventController extends AdminController
{
    protected $EventModel;
    use ResponseTrait;

    public function __construct()
    {
        $this->EventModel = new EventModel();
    }

    public function index()
    {
        // Grouping by event_id to show a list of events that have tickets
        // In a real scenario, you'd likely have a separate 'events' table
        $data['events'] = $this->EventModel
            ->select('event_id as id, event_id as name') // Fallback if no events table
            ->groupBy('event_id')
            ->findAll();

        return $this->loadView('admin/events/index', $data);
    }

    public function bookings($eventId = null)
    {
        $query = $this->EventModel;
        if ($eventId) {
            $query = $query->where('event_id', $eventId);
        }

        $data['bookings'] = $query->select('event_tickets.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = event_tickets.user_id', 'left')
            ->orderBy('event_tickets.created_at', 'DESC')
            ->findAll();

        $data['eventId'] = $eventId;
        $data['events'] = $this->EventModel->select('event_id')->groupBy('event_id')->findAll();

        return $this->loadView('admin/events/bookings', $data);
    }

    public function scannerStats()
    {
        $data['stats'] = $this->EventModel->getScanStats();
        return $this->loadView('admin/events/scanner_stats', $data);
    }
    public function updateWhatsappStatus()
    {
        $json = $this->request->getJSON();
        $id = $json->id ?? null;
        $status = $json->status ?? 'pending';

        if (!$id) {
            return $this->respond(['status' => false, 'message' => 'ID required']);
        }

        if ($this->EventModel->update($id, ['whatsapp_status' => $status])) {
            return $this->respond(['status' => true, 'message' => 'Status updated']);
        }

        return $this->respond(['status' => false, 'message' => 'Update failed']);
    }
}
