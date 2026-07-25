<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketMessageModel extends Model
{
  protected $table = 'ticket_messages';
  protected $primaryKey = 'id';
  protected $allowedFields = ['ticket_id', 'sender_id', 'sender_type', 'message', 'attachments', 'created_at'];
  public $timestamps = false;

  public function getMessagesToUpdate($ticketId, $senderType = 'client')
  {
    return  $this->db->table('ticket_messages')
      ->where('ticket_id', $ticketId)
      ->where('sender_type', $senderType)
      ->where('is_read', 0)
      ->countAllResults(false);
  }
  public function markAsRead($ticketId, $senderType = 'client')
  {
    $this->db->table('ticket_messages')
      ->where('ticket_id', $ticketId)
      ->where('sender_type', $senderType)
      ->set(['is_read' => 1])
      ->update();
  }
}
