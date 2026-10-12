<?php

namespace App\Models;

use CodeIgniter\Model;

class InsurancePolicy extends Model
{

  protected $table = 'insurance_policies';
  protected $primaryKey = 'id';
  protected $allowedFields = ['policy_number', 'insurance_user_id', 'pet_id', 'coverage_type', 'duration_years', 'start_date', 'status', 'end_date', 'documents', 'remarks', 'created_at', 'updated_at'];
  protected $returnType = 'object';
  protected $useTimestamps = false;
  protected $db;

  public function getBreeds()
  {
    return $this->db->table('dog_breeds')->orderBy('value', 'ASC')->get()->getResult();
  }

  public function __construct()
  {
    parent::__construct();
    $this->db = \Config\Database::connect();
  }

  public function createUser($data)
  {
    $this->db->table('users')->insert($data);
    return $this->db->insertId();
  }
  public function createInsuranceUser($data)
  {
    $this->db->table('insurance_users')->insert($data);
    return $this->db->insertId();
  }
  public function createInsurance($data)
  {
    $this->db->table('insurance_policies')->insert($data);
    return $this->db->insertId();
  }
  public function createPet($data)
  {
    $this->db->table('user_pets')->insert($data);
    return $this->db->insertId();
  }
  public function quotationExists($policyId)
  {
    return $this->db->table('insurance_policies_quotations')
      ->where('policy_id', $policyId)
      ->countAllResults() > 0;
  }

  public function createQuotation($data)
  {
    $this->db->table('insurance_policies_quotations')->insert($data);
    return $this->db->insertId();
  }
  public function getEnquiries()
  {
    return $this->db->table('insurance_policies as ip')
      ->select('ip.*, iu.full_name, iu.email, iu.phone, ipq.amount')
      ->select('up.name, up.breed, dob, gender, last_vaccination')

      ->join('insurance_users as iu', 'iu.id = ip.insurance_user_id', 'left')
      ->join('insurance_policies_quotations as ipq', 'TRIM(ip.policy_number) = TRIM(ipq.policy_id)', 'left', false)
      ->join('user_pets as up', 'up.id = ip.pet_id', 'left')

      ->where('ip.status', 'Pending')
      ->where('ipq.id', null)    // ❗ Only enquiries with NO quotation
      ->orderBy('ip.created_at', 'DESC')
      ->get()
      ->getResult();
  }

  public function getQuotations()
  {

    return $this->db->table('insurance_policies_quotations as ipq')
      ->select('ipq.amount, insurance_users.full_name, insurance_users.email, insurance_users.phone, ipq.policy_id, insurance_policies.remarks')
      ->join('insurance_policies', 'TRIM(insurance_policies.policy_number)=TRIM(ipq.policy_id)', 'left', false)
      ->join('insurance_users', 'insurance_users.id=insurance_policies.insurance_user_id', 'left')
      ->where('insurance_policies.status', 'Pending')
      ->get()
      ->getResult();
  }
  public function getActiveInsurances()
  {
    return $this->db->table('insurance_policies')
      ->select('insurance_policies.*')
      ->select('insurance_users.full_name,email,phone')
      ->select('ipq.amount')
      ->select('up.name, up.breed, dob, gender, last_vaccination')

      ->join('insurance_users', 'insurance_users.id=insurance_policies.insurance_user_id', 'left')
      ->join('insurance_policies_quotations as ipq', 'TRIM(ipq.policy_id)=TRIM(insurance_policies.policy_number)', 'left', false)
      ->join('user_pets as up', 'up.id = insurance_policies.pet_id', 'left')

      ->where('insurance_policies.status', 'Active')
      ->orderBy('insurance_policies.created_at', 'DESC')
      ->get()->getResult();
  }
  public function getExpiringInsurances()
  {
    $tenDaysFromNow = date('Y-m-d', strtotime('+10 days'));

    $today = date('Y-m-d');

    return $this->db->table('insurance_policies')
      ->select('insurance_policies.*')
      ->select('insurance_users.full_name, email, phone')
      ->select('insurance_policies_quotations.amount')

      ->join('insurance_users', 'insurance_users.id = insurance_policies.insurance_user_id', 'left')
      ->join('insurance_policies_quotations', 'insurance_policies_quotations.policy_id = insurance_policies.policy_number', 'left')

      ->where('insurance_policies.status', 'Active')

      ->where('insurance_policies.end_date <=', $tenDaysFromNow)
      ->where('insurance_policies.end_date >=', $today)

      ->get()
      ->getResult();
  }
  public function getClaims()
  {
    return $this->db->table('insurance_claims as ic')
      ->select('ic.*, ip.policy_number')
      ->select('iu.full_name as client_name,iu.phone as contact')

      ->join('insurance_policies as ip', 'ip.id = ic.policy_id', 'left')
      ->join('insurance_users as iu', 'iu.id = ip.insurance_user_id', 'left')
      ->orderBy('ic.created_at', 'DESC')
      ->get()->getResultArray();
  }
  public function updateClaim($claimId, $data)
  {
    return
      $this->db->table('insurance_claims')
      ->where('id', $claimId)
      ->update($data);
  }

  public function createClaim($data)
  {
    $this->db->table('insurance_claims')->insert($data);
    return $this->db->insertId();
  }

  public function getInsuranceStats()
  {
    $requests = $this->db->table('insurance_policies')->where('status', 'Pending')->countAllResults();
    $claims = $this->db->table('insurance_claims')->countAllResults();
    return [
      'requests' => $requests,
      'claims' => $claims,
      'total' => $requests + $claims
    ];
  }

  public function getTabCounts()
  {
    $tenDaysFromNow = date('Y-m-d', strtotime('+10 days'));
    $today = date('Y-m-d');

    $enquiries = $this->db->table('insurance_policies as ip')
      ->join('insurance_policies_quotations as ipq', 'TRIM(ip.policy_number) = TRIM(ipq.policy_id)', 'left', false)
      ->where('ip.status', 'Pending')
      ->where('ipq.id', null)
      ->countAllResults();

    $quotations = $this->db->table('insurance_policies_quotations as ipq')
      ->join('insurance_policies', 'TRIM(insurance_policies.policy_number)=TRIM(ipq.policy_id)', 'left', false)
      ->where('insurance_policies.status', 'Pending')
      ->countAllResults();

    $active = $this->db->table('insurance_policies')
      ->where('status', 'Active')
      ->countAllResults();

    $expiring = $this->db->table('insurance_policies')
      ->where('status', 'Active')
      ->where('end_date <=', $tenDaysFromNow)
      ->where('end_date >=', $today)
      ->countAllResults();

    $claims = $this->db->table('insurance_claims')->countAllResults();

    return [
      'enquiries'  => $enquiries,
      'quotations' => $quotations,
      'active'     => $active,
      'expiring'   => $expiring,
      'claims'     => $claims,
    ];
  }

  public function getEnquiryById($id)
  {
    return $this->db->table('insurance_policies as ip')
      ->select('ip.*, iu.full_name as client_name, iu.phone as client_phone, iu.email as client_email, up.name as pet_name, up.breed as pet_breed, up.gender, up.dob')
      ->join('insurance_users as iu', 'iu.id = ip.insurance_user_id', 'left')
      ->join('user_pets as up', 'up.id = ip.pet_id', 'left')
      ->where('ip.id', $id)
      ->get()
      ->getRow();
  }

  public function deleteEnquiry($id)
  {
    return $this->db->table('insurance_policies')->where('id', $id)->delete();
  }
}
