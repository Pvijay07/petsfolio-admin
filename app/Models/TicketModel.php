<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
  protected $table = 'tickets';
  protected $primaryKey = 'id';
  protected $allowedFields = ['ticket_id', 'user_id', 'user_type', 'category', 'subject', 'description', 'status', 'priority', 'created_at', 'updated_at'];
  public $timestamps = false;

  public function getTicket($ticketId)
  {
    return $this->db->table('tickets')
      ->select('tickets.id,tickets.user_id,tickets.ticket_number, tickets.description as message, tickets.user_type,tickets.created_at,tickets.status')
      ->select('COALESCE(users.name, service_providers.name) as name')
      ->select('COALESCE(users.email, service_providers.email) as email')
      ->select('COALESCE(users.phone, service_providers.phone) as phone')
      ->select('COALESCE(users.profile, service_providers.profile) as profile')

      ->join('users', 'users.id = tickets.user_id AND (tickets.user_type = "client" OR tickets.user_type = "user")', 'left')
      ->join('service_providers', 'service_providers.id = tickets.user_id AND tickets.user_type = "provider"', 'left')
      ->where('tickets.id', $ticketId)
      ->get()
      ->getRow();
  }
  public function getTicketMessage($ticketNumber)
  {
    return $this->db->table('ticket_messages')
      ->select('ticket_messages.*')
      ->select('employees.name as support_name')
      ->join('employees', 'employees.id = ticket_messages.sender_id AND ticket_messages.sender_type = "support"', 'left')

      ->where('ticket_messages.ticket_id', $ticketNumber)
      ->orderBy('ticket_messages.created_at', 'asc')
      ->get()
      ->getResult();
  }
}
