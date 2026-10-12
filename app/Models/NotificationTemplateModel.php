<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationTemplateModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get all templates with optional filters
     */
    public function getTemplates($filters = [])
    {
        $builder = $this->db->table('notification_templates');

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        if (!empty($filters['channel'])) {
            $builder->where('channel', $filters['channel']);
        }
        if (!empty($filters['type'])) {
            $builder->where('type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('title', $filters['search'])
                ->orLike('name', $filters['search'])
                ->orLike('event_key', $filters['search'])
                ->groupEnd();
        }

        $builder->orderBy('created_at', 'DESC');
        return $builder->get()->getResult();
    }

    /**
     * Get active templates for dropdowns
     */
    public function getActiveTemplates()
    {
        return $this->db->table('notification_templates')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()->getResult();
    }

    /**
     * Get template by ID
     */
    public function getTemplate($id)
    {
        return $this->db->table('notification_templates')
            ->where('id', $id)
            ->get()->getRow();
    }

    /**
     * Get template by event key
     */
    public function getByEventKey($eventKey)
    {
        return $this->db->table('notification_templates')
            ->where('event_key', $eventKey)
            ->where('status', 1)
            ->get()->getRow();
    }

    /**
     * Create a new template
     */
    public function createTemplate($data)
    {
        $this->db->table('notification_templates')->insert($data);
        return $this->db->insertID();
    }

    /**
     * Update a template
     */
    public function updateTemplate($id, $data)
    {
        return $this->db->table('notification_templates')
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete a template
     */
    public function deleteTemplate($id)
    {
        return $this->db->table('notification_templates')
            ->delete(['id' => $id]);
    }

    /**
     * Get all predefined event keys for the system
     */
    public function getEventKeys()
    {
        return [
            // Booking Events
            'booking.created'          => 'Booking Request Created',
            'booking.confirmed'        => 'Booking Confirmed',
            'booking.cancelled'        => 'Booking Cancelled',
            'booking.completed'        => 'Booking Completed',
            'booking.started'          => 'Service Started',
            'booking.reminder'         => 'Booking Reminder',

            // Quote Events
            'quote.received'           => 'Quote Received',
            'quote.accepted'           => 'Quote Accepted',
            'quote.rejected'           => 'Quote Rejected',
            'quote.expired'            => 'Quote Expired',

            // Payment Events
            'payment.success'          => 'Payment Successful',
            'payment.failed'           => 'Payment Failed',
            'payment.refund'           => 'Refund Processed',

            // Provider Events
            'provider.arrived'         => 'Provider Arrived',
            'provider.bid'             => 'Provider Submitted Bid',
            'provider.kyc.approved'    => 'KYC Approved',
            'provider.kyc.rejected'    => 'KYC Rejected',

            // Walk Events
            'walk.started'             => 'Walk Started',
            'walk.completed'           => 'Walk Completed',
            'walk.approved'            => 'Walk Approved',

            // Wallet Events
            'wallet.credited'          => 'Wallet Credited',
            'wallet.debited'           => 'Wallet Debited',

            // Loyalty Events
            'loyalty.points.earned'    => 'Loyalty Points Earned',
            'loyalty.milestone'        => 'Loyalty Milestone Reached',

            // Engagement Events
            'engagement.inactive'      => 'Inactive User Reminder',
            'engagement.birthday'      => 'Birthday Wish',
            'engagement.grooming_due'  => 'Grooming Due Reminder',
            'engagement.vaccination'   => 'Vaccination Reminder',

            // Promotional
            'promo.general'            => 'General Promotion',
            'promo.offer'              => 'Special Offer',
            'promo.seasonal'           => 'Seasonal Campaign',
        ];
    }

    /**
     * Get available template variables for an event key
     */
    public function getVariablesForEvent($eventKey)
    {
        $commonVars = ['user_name', 'user_phone', 'app_name'];

        $eventVars = [
            'booking'  => ['booking_id', 'service_name', 'service_date', 'service_time', 'provider_name', 'pet_name', 'amount'],
            'quote'    => ['booking_id', 'provider_name', 'amount', 'service_name', 'pet_name'],
            'payment'  => ['booking_id', 'amount', 'transaction_id', 'service_name'],
            'provider' => ['provider_name', 'service_name'],
            'walk'     => ['booking_id', 'provider_name', 'pet_name', 'duration'],
            'wallet'   => ['amount', 'balance', 'transaction_type'],
            'loyalty'  => ['points', 'total_points', 'milestone_name', 'reward_name'],
            'engagement' => ['pet_name', 'days_inactive', 'last_service_date'],
            'promo'    => ['offer_name', 'discount_percent', 'coupon_code', 'valid_until'],
        ];

        $prefix = explode('.', $eventKey)[0] ?? '';
        $vars = array_merge($commonVars, $eventVars[$prefix] ?? []);

        return $vars;
    }

    /**
     * Get template count by status
     */
    public function getCountByStatus()
    {
        $result = $this->db->table('notification_templates')
            ->select("SUM(status = 1) as active, SUM(status = 0) as inactive, COUNT(*) as total")
            ->get()->getRow();

        return $result;
    }
}
