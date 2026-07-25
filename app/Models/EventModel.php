<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'event_tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [

        'event_id',
        'price',
        'booking_id',
        'package_details',
        'user_id',
        'qr_token',
        'status',
        'checked_in_by',
        'checked_in_at',
        'qr_image',
        'ticket_status',
        'max_entries',
        'used_entries',
        'persons_allowed',
        'customer_name',
        'customer_phone',
        'customer_email',
        'payment_status',
        'order_id',
        'quantity',
        'location',
        'email_status',
        'whatsapp_status'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    public function getScanStats()
    {
        return $this->db->table('event_scan_logs l')
            ->select('u.name as scanner_name, COUNT(l.id) as total_scanned')
            ->join('users u', 'u.id = l.scanner_id', 'left')
            ->groupBy('l.scanner_id')
            ->get()
            ->getResult();
    }

    public function logScan($bookingId, $scannerId)
    {
        return $this->db->table('event_scan_logs')->insert([
            'booking_id' => $bookingId,
            'scanner_id' => $scannerId,
            'scanned_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getScanCount($bookingId)
    {
        // We can either use the count from logs or just return the used_entries field
        return $this->find($bookingId)['used_entries'] ?? 0;
    }
}
