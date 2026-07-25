<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationRuleModel extends Model
{
  protected $table            = 'verification_rules';
  protected $primaryKey       = 'id';

  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;

  protected $allowedFields = [
    'service_type',
    'country',
    'state',
    'city',
    'pincode',

    'verification_required',
    'registration_fee',

    'call_verification_required',
    'aadhaar_verification_required',

    'deferred_verification',
    'status',
  ];

  protected bool $allowEmptyInserts = false;

  protected bool $updateOnlyChanged = true;

  protected array $casts = [
    'verification_required'        => 'integer',
    'registration_fee'             => 'float',
    'call_verification_required'   => 'integer',
    'aadhaar_verification_required' => 'integer',
    'deferred_verification'         => 'integer',
  ];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  /**
   * Get verification rule based on service type + location
   *
   * Priority:
   * City > State > Global
   */
  public function getRule(
    string $serviceType,
    ?string $city = null,
    ?string $state = null,
    ?string $country = 'India'
  ): ?array {
    // 1. City Rule
    if (!empty($city)) {

      $cityRule = $this
        ->where('service_type', $serviceType)
        ->where('LOWER(city)', strtolower($city))
        ->where('status', 'active')
        ->first();

      if ($cityRule) {
        return $cityRule;
      }
    }

    // 2. State Rule
    if (!empty($state)) {

      $stateRule = $this
        ->where('service_type', $serviceType)
        ->where('LOWER(state)', strtolower($state))
        ->where('city IS NULL')
        ->where('status', 'active')
        ->first();

      if ($stateRule) {
        return $stateRule;
      }
    }

    // 3. Global Rule
    $globalRule = $this
      ->where('service_type', $serviceType)
      ->where('city IS NULL')
      ->where('state IS NULL')
      ->where('status', 'active')
      ->first();

    return $globalRule ?: null;
  }

  /**
   * Check whether verification is required
   */
  public function isVerificationRequired(
    string $serviceType,
    ?string $city = null,
    ?string $state = null
  ): bool {
    $rule = $this->getRule(
      $serviceType,
      $city,
      $state
    );

    if (!$rule) {
      return false;
    }

    return (bool) $rule['verification_required'];
  }

  /**
   * Check whether verification is deferred
   */
  public function isVerificationDeferred(
    string $serviceType,
    ?string $city = null,
    ?string $state = null
  ): bool {
    $rule = $this->getRule(
      $serviceType,
      $city,
      $state
    );

    if (!$rule) {
      return false;
    }

    return (bool) ($rule['deferred_verification'] ?? 0);
  }


  /**
   * Get registration fee
   */
  public function getRegistrationFee(
    string $serviceType,
    ?string $city = null,
    ?string $state = null
  ): float {
    $rule = $this->getRule(
      $serviceType,
      $city,
      $state
    );

    if (!$rule) {
      return 0;
    }

    return (float) $rule['registration_fee'];
  }

  public function getAllRules()
  {
    return $this->findAll();
  }

  public function getRuleById(int $id): ?array
  {
    return $this->find($id);
  }

  public function updateRule(int $id, array $data): bool
  {
    return $this->update($id, $data);
  }

  public function createRule(array $data): bool
  {
    return $this->insert($data);
  }

  public function deleteRule(int $id): bool
  {
    return $this->delete($id);
  }
}
