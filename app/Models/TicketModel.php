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

      ->join('users', 'users.id = tickets.user_id AND tickets.user_type = "client"', 'left')
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
  public function getCategories()
  {

    return $this->db->table('ticket_categories')
      ->select('id,name,description')
      ->where('is_active', true)
      // ->orderBy('created_at', 'asc')
      ->get()
      ->getResult();
  }
  public function getCategoryTickets($userId, $categoryId, $userType = 'user')
  {
    return $this->db->table('tickets')
      ->select('*')
      ->where('status', 'open')
      ->where('user_id', $userId)
      ->where('user_type', $userType)
      ->where('category', $categoryId)
      ->orderBy('created_at', 'desc')
      ->get()
      ->getResult();
  }
  public function findUser($senderId, $senderType = 'user')
  {
    $table = ($senderType === 'provider') ? 'service_providers' : 'users';
    return $this->db->table($table)
      ->select('name')
      ->where('id', $senderId)
      ->get()
      ->getRow();
  }
  public function findChatReceiver($chatId, $excludeUserId)
  {
    $ticket = $this->db->table('tickets')->select('user_type')->where('id', $chatId)->get()->getRow();
    if (!$ticket) return null;

    $table = ($ticket->user_type === 'provider') ? 'service_providers' : 'users';

    return $this->db->table('tickets')
      ->select("$table.id, $table.device_token as fcm_token, $table.name")
      ->join($table, "tickets.user_id = $table.id")
      ->where('tickets.id', $chatId)
      ->where("$table.id !=", $excludeUserId)
      ->get()
      ->getRow();
  }
  public function addFeedback($data)
  {
    return $this->db->table('ticket_feedback')
      ->insert($data);
  }
  public function checkFeedback($data)
  {
    return $this->db->table('ticket_feedback')
      ->where('ticket_id', $data['ticket_id'])
      ->where('user_id', $data['user_id'])
      ->where('user_type', $data['user_type'] ?? 'user')
      ->countAllResults();
  }
  public function updateRatingTrack($ticketId, $rateRequest)
  {
    return $this->db->table('tickets')
      ->set('is_rating_requested', $rateRequest)
      ->where('tickets.id', $ticketId)
      ->update();
  }
  public function updateBankTrack($ticketId, $bankRequest)
  {
    return $this->db->table('tickets')
      ->set('is_bank_requested', $bankRequest)
      ->where('tickets.id', $ticketId)
      ->update();
  }
  public function findSupportUser($senderId)
  {
    return $this->db->table('employees')
      ->select('name')
      ->where('id', $senderId)
      ->get()
      ->getRow();
  }
}
