<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketMessageModel extends Model
{
  protected $table = 'ticket_messages';
  protected $primaryKey = 'id';
  protected $allowedFields = ['ticket_id', 'sender_id', 'sender_type', 'message', 'attachments', 'created_at', 'is_read', 'is_deleted', 'deleted_by', 'deleted_at'];
  public $timestamps = false;

  public function getMessagesToUpdate($ticketId, $senderType = null)
  {
    $builder = $this->db->table('ticket_messages')
      ->where('ticket_id', $ticketId)
      ->where('is_read', 0);

    if (!empty($senderType) && $senderType !== 'all') {
      $builder->where('sender_type', $senderType);
    } else {
      $builder->where('sender_type !=', 'support');
    }

    return $builder->countAllResults(false);
  }

  public function markAsRead($ticketId, $senderType = null)
  {
    $builder = $this->db->table('ticket_messages')
      ->where('ticket_id', $ticketId);

    if (!empty($senderType) && $senderType !== 'all') {
      $builder->where('sender_type', $senderType);
    } else {
      $builder->where('sender_type !=', 'support');
    }

    $builder->set(['is_read' => 1])->update();
  }
}
