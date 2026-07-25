<?php

namespace App\Models;

use CodeIgniter\Model;

class UpgradeRequestModel extends Model
{
  protected $table = 'upgrade_requests';
  protected $primaryKey = 'id';

  protected $allowedFields = [
    'booking_id',
    'provider_id',
    'user_id',
    // 'pet_id',
    'command',
    'amount',
    'status',
    // 'provider_message'
  ];

  protected $useTimestamps = true;
  protected $useSoftDeletes = true;

  // protected $casts = [
  //   'commands' => 'json'
  // ];

  public function getCommands($user_id)
  {
    return $this->db->table('upgrade_requests')
      ->select('id, command, amount, user_id, booking_id')
      ->where('user_id', $user_id)
      ->groupBy('id, command, amount')
      ->get()
      ->getResult();
  }
  public function getCommandsByBookingId($booking_id)
  {
    return $this->db->table('upgrade_requests')
      ->select('id, command, amount, user_id, booking_id')
      ->where('booking_id', $booking_id)
      ->groupBy('id, command, amount')
      ->get()
      ->getResult();
  }
  public function hasApproved($bookingId)
  {
    // 1. If ANY approved record exists → false
    $approvedCount = $this->db->table('upgrade_requests')
      ->where('booking_id', $bookingId)
      ->where('status', 'approved')
      ->countAllResults();

    if ($approvedCount > 0) {
      return false;
    }

    // 2. Get latest record
    $latest = $this->db->table('upgrade_requests')
      ->where('booking_id', $bookingId)
      ->orderBy('id', 'DESC')
      ->get()
      ->getRow();

    // 3. No records → false
    if (!$latest) {
      return false;
    }

    // 4. Latest is pending → true
    if ($latest->status === 'pending') {
      return true;
    }

    // 5. Otherwise rejected/paid/completed → false
    return false;
  }

public function hasApprovedProvider($bookingId)
  {
    // 1. If ANY approved record exists → false
    $approvedCount = $this->db->table('upgrade_requests')
      ->where('booking_id', $bookingId)
      ->where('status', 'approved')
      ->countAllResults();

    if ($approvedCount > 0) {
      return true;
    }

    // 2. Get latest record
    $latest = $this->db->table('upgrade_requests')
      ->where('booking_id', $bookingId)
      ->orderBy('id', 'DESC')
      ->get()
      ->getRow();

    // 3. No records → false
    if (!$latest) {
      return false;
    }

    // 4. Latest is pending → true
    if ($latest->status === 'pending') {
      return true;
    }

    // 5. Otherwise rejected/paid/completed → false
    return false;
  }

  public function approveCommand($booking_id, $command)
  {
    return $this->db->table('upgrade_requests')
      ->set('status', 'approved')
      ->where('booking_id', $booking_id)
      ->where('command', $command)
      ->update();
  }
  public function rejectMissingCommands($booking_id, $commandList)
  {
    return $this->db->table('upgrade_requests')
      ->set('status', 'rejected')
      ->where('booking_id', $booking_id)
      ->whereNotIn('command', $commandList)
      ->update();
  }
}
