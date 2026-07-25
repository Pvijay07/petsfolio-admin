<?php

namespace App\Models;

use CodeIgniter\Model;

class Provider extends Model
{
  protected $db;
  public function __construct()
  {
    $this->db = \Config\Database::connect();
  }

  public function getAll($status, $service_id = null, $city = null, $area = null, $limit = null, $offset = 0, $countOnly = false)
  {
    $builder = $this->db->table('service_providers')
      ->select('service_providers.id, service_providers.name, service_providers.email, service_providers.phone,
      service_providers.gender,service_providers.dob,service_providers.profile,service_providers.status,service_providers.area,
      service_providers.city,service_providers.created_at,service_providers.is_verified as verified,service_providers.service_address,
      service_providers.profile,service_providers.followup_status,service_providers.followup_comment,service_providers.followup_notes,
      service_providers.followup_date, service_providers.registration_paid')
      ->select('sp_withdrawal_wallet.amount as wallet_amount')
      ->select('sp_reviews.rating,
            sp_reviews.comment,
            sp_reviews.reviewed_by,
            sp_reviews.admin_id,
           
            sp_reviews.created_at as verifiedAt
            ')
      ->join('sp_withdrawal_wallet', 'sp_withdrawal_wallet.provider_id = service_providers.id', 'left')
      ->join('sp_tiers', 'sp_tiers.provider_id = service_providers.id', 'left')
      ->join('sp_stats', 'sp_stats.provider_id = service_providers.id', 'left')
      ->join('sp_reviews', 'sp_reviews.provider_id = service_providers.id AND sp_reviews.reviewed_by = "admin"', 'left');

    if ($status === 'pending_registration') {
      $builder->where('service_providers.is_verified', 0);
    } elseif ($status === 'active') {
      $builder->where('service_providers.status', 'active');
      $builder->where('service_providers.is_verified', 1);
      
    } elseif ($status === 'approval') {
      $builder->where('service_providers.status', 'active');
      $builder->groupStart()
        ->where('service_providers.is_verified', 0)
        ->orWhere('service_providers.is_verified', null)
        ->groupEnd();
    } elseif ($status === 'pending_verification') {
      $builder->where('service_providers.status', 'pending_verification');
    } elseif ($status === 'pending_kyc') {
      $builder->where('service_providers.status', 'pending_kyc');
    } elseif ($status === 'non_verified') {
      $builder->where('service_providers.is_verified !=', 1);
      $builder->where('service_providers.status !=', 'inactive');
      $builder->where('service_providers.status !=', 'pending_verification');
      $builder->where('service_providers.status !=', 'PENDING_VERIFICATION');
    } else {
      $builder->where('service_providers.status', $status);
    }
    
    if ($service_id) {
        $builder->join('sp_services', 'sp_services.provider_id = service_providers.id');
        $builder->where('sp_services.service_id', $service_id);
    }
    if ($city) {
        $builder->where('service_providers.city', $city);
    }
    if ($area) {
        $builder->where('service_providers.area', $area);
    }

    if ($status !== 'followup') {
      $builder->groupStart()
        ->where('service_providers.followup_status !=', 'followup')
        ->orWhere('service_providers.followup_status', null)
        ->groupEnd();
    }

    if ($status === 'non_verified' || $service_id) {
        $builder->groupBy('service_providers.id');
    }

    if ($countOnly) {
        return $builder->get()->getNumRows();
    }

    $builder->orderBy('sp_tiers.tier', 'ASC');
    $builder->orderBy('sp_stats.ranking_score', 'DESC');
    $builder->orderBy('service_providers.id', 'DESC');

    if ($limit !== null) {
        $builder->limit($limit, $offset);
    }

    $providers = $builder->get()->getResult();

    foreach ($providers as $provider) {
      $details = $this->db->table('sp_services')
        ->select('services.name as service_name, sp_reviews.rating as admin_rating, sp_reviews.comment as admin_review')
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.experience 
            WHEN sp_services.service_id = 3 THEN sp_training_details.experience 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.experience 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.experience 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.experience 
            ELSE NULL 
        END as experience', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.available_time 
            WHEN sp_services.service_id = 3 THEN sp_training_details.available_time 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.available_time 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.available_time 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.available_time 
            ELSE NULL 
        END as available_time', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.confidence 
            WHEN sp_services.service_id = 3 THEN sp_training_details.confidence 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.confidence 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.confidence 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.confidence 
            ELSE NULL 
        END as confidence', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.interview_answers 
            WHEN sp_services.service_id = 3 THEN sp_training_details.interview_answers 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.interview_answers 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.interview_answers 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.interview_answers 
            ELSE NULL 
        END as interview_answers', false)
        ->join('services', 'services.id = sp_services.service_id')
        ->join('sp_reviews', 'sp_reviews.provider_id = sp_services.provider_id AND sp_reviews.service_id = sp_services.service_id AND sp_reviews.reviewed_by = "admin"', 'left')
        ->join('sp_walking_details', 'sp_walking_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_training_details', 'sp_training_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_grooming_details', 'sp_grooming_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_boarding_details', 'sp_boarding_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_sitting_details', 'sp_sitting_details.provider_id = sp_services.provider_id', 'left')
        ->where('sp_services.provider_id', $provider->id)
        ->get()->getResult();

      $serviceDetails = [];
      foreach ($details as $detail) {
        $serviceDetails[$detail->service_name] = [
          'rating' => $detail->admin_rating ?? 'No Rating',
          'review' => $detail->admin_review ?? 'No Review',
          'experience' => ($detail->experience ?? '0') . ' Years',
          'available_time' => $detail->available_time ?? '--',
          'confidence' => $detail->confidence ?? '--',
          'interview_answers' => $detail->interview_answers ? json_decode($detail->interview_answers, true) : null
        ];
      }
      $provider->serviceDetails = $serviceDetails;
    }

    return $providers;
  }

  public function getServiceWiseProviders($status, $experience = null, $service_id = null, $city = null, $area = null, $overdue = false, $limit = null, $offset = 0, $countOnly = false)
  {
    $builder = $this->db->table('service_providers')
      ->select('service_providers.id as id, service_providers.name, service_providers.email, service_providers.phone,
              service_providers.gender, service_providers.dob, service_providers.profile, 
              service_providers.area, service_providers.city, service_providers.created_at, 
              sp_services.is_verified as verified, service_providers.service_address,
              service_providers.registration_paid, service_providers.status,
              service_providers.followup_status, service_providers.followup_comment,
              service_providers.followup_notes, service_providers.followup_date,
              service_providers.state,
              (SELECT COUNT(*) FROM sp_services WHERE provider_id = service_providers.id) as total_services')
      ->select('sp_services.service_id, GROUP_CONCAT(DISTINCT services.name SEPARATOR ", ") as service_name, sp_services.status as service_status, sp_services.payment_paid, sp_services.call_verified')
      ->select('sp_withdrawal_wallet.amount as wallet_amount')
      ->select('sp_reviews.rating, sp_reviews.comment,  sp_reviews.call_record, sp_reviews.created_at as verifiedAt')
      ->select('CASE 
          WHEN sp_services.service_id = 4 THEN sp_walking_details.experience 
          WHEN sp_services.service_id = 3 THEN sp_training_details.experience 
          WHEN sp_services.service_id = 2 THEN sp_grooming_details.experience 
          WHEN sp_services.service_id = 1 THEN sp_boarding_details.experience 
          WHEN sp_services.service_id = 6 THEN sp_sitting_details.experience 
          ELSE NULL 
      END as experience', false)
      ->select('CASE 
          WHEN sp_services.service_id = 4 THEN sp_walking_details.available_time 
          WHEN sp_services.service_id = 3 THEN sp_training_details.available_time 
          WHEN sp_services.service_id = 2 THEN sp_grooming_details.available_time 
          WHEN sp_services.service_id = 1 THEN sp_boarding_details.available_time 
          WHEN sp_services.service_id = 6 THEN sp_sitting_details.available_time 
          ELSE NULL 
      END as available_time', false)
      ->select('CASE 
          WHEN sp_services.service_id = 4 THEN sp_walking_details.confidence 
          WHEN sp_services.service_id = 3 THEN sp_training_details.confidence 
          WHEN sp_services.service_id = 2 THEN sp_grooming_details.confidence 
          WHEN sp_services.service_id = 1 THEN sp_boarding_details.confidence 
          WHEN sp_services.service_id = 6 THEN sp_sitting_details.confidence 
          ELSE NULL 
      END as confidence', false)
      ->select('CASE 
          WHEN sp_services.service_id = 4 THEN sp_walking_details.interview_answers 
          WHEN sp_services.service_id = 3 THEN sp_training_details.interview_answers 
          WHEN sp_services.service_id = 2 THEN sp_grooming_details.interview_answers 
          WHEN sp_services.service_id = 1 THEN sp_boarding_details.interview_answers 
          WHEN sp_services.service_id = 6 THEN sp_sitting_details.interview_answers 
          ELSE NULL 
      END as interview_answers', false)

      ->join('sp_services', 'sp_services.provider_id = service_providers.id', 'left')
      ->join('services', 'services.id = sp_services.service_id', 'left')
      ->join('sp_verifications', 'sp_verifications.provider_id = service_providers.id', 'left')
      ->join('sp_withdrawal_wallet', 'sp_withdrawal_wallet.provider_id = service_providers.id', 'left')
      ->join('sp_reviews', 'sp_reviews.provider_id = service_providers.id AND sp_reviews.service_id = sp_services.service_id AND sp_reviews.reviewed_by = "admin"', 'left')
      ->join('sp_walking_details', 'sp_walking_details.provider_id = service_providers.id', 'left')
      ->join('sp_training_details', 'sp_training_details.provider_id = service_providers.id', 'left')
      ->join('sp_grooming_details', 'sp_grooming_details.provider_id = service_providers.id', 'left')
      ->join('sp_boarding_details', 'sp_boarding_details.provider_id = service_providers.id', 'left')
      ->join('sp_sitting_details', 'sp_sitting_details.provider_id = service_providers.id', 'left')
      ->join('sp_tiers', 'sp_tiers.provider_id = service_providers.id', 'left')
      ->join('sp_stats', 'sp_stats.provider_id = service_providers.id', 'left');

    if ($status === 'pending_registration') {
      $builder->groupStart();
      
      $builder->where('sp_services.id IS NULL');
      
      $builder->orGroupStart();
          $builder->groupStart();
          $builder->where('sp_services.payment_paid', 0);
          $builder->orWhere('sp_services.payment_paid', null);
          $builder->groupEnd();
          
          $builder->groupStart();
          $builder->where('sp_services.call_verified', 0);
          $builder->orWhere('sp_services.call_verified', null);
          $builder->groupEnd();
          
          $builder->groupStart();
          $builder->where('sp_verifications.identity_proof_verified', 0);
          $builder->orWhere('sp_verifications.identity_proof_verified', null);
          $builder->groupEnd();
      $builder->groupEnd();
      
      $builder->groupEnd();
    } elseif ($status === 'pending_verification') {
      $builder->where('sp_services.payment_paid', 1);
      $builder->groupStart();
      $builder->where('sp_services.call_verified', 0);
      $builder->orWhere('sp_services.call_verified', null);
      $builder->groupEnd();

      if ($overdue) {
        $builder->where('service_providers.created_at <', date('Y-m-d H:i:s', strtotime('-24 hours')));
      } else {
        $builder->where('service_providers.created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')));
      }
    } elseif ($status === 'approval') {
      $builder->where('service_providers.status', 'active');
      $builder->where('sp_services.payment_paid', 1);
      $builder->where('sp_services.call_verified', 1);
      $builder->where('sp_verifications.identity_proof_verified', 1);

      $builder->groupStart();
      $builder->where('sp_services.is_verified', 0);
      $builder->orWhere('sp_services.is_verified', null);
      $builder->groupEnd();

      $builder->groupStart();
      $builder->where('sp_services.status', 'approved');
      $builder->orWhere('sp_services.status', 'rejected');
      $builder->groupEnd();
    } elseif ($status === 'inactive') {
      $builder->where('service_providers.status', 'inactive');
    } elseif ($status === 'pending_kyc') {
      $builder->where('service_providers.status', 'pending_kyc');
    } elseif ($status === 'active') {
      $builder->where('service_providers.status', 'active');
      $builder->where('sp_services.is_verified', 1);
    } elseif ($status === 'non_verified') {
      $builder->where('sp_services.is_verified !=', 1);
      $builder->where('service_providers.status !=', 'inactive');
      $builder->where('service_providers.status !=', 'pending_verification');
      $builder->where('service_providers.status !=', 'PENDING_VERIFICATION');
    } else {
      $builder->where('sp_services.status', $status);
    }

    if ($service_id) {
        $builder->where('sp_services.service_id', $service_id);
    }
    if ($city) {
        $builder->where('service_providers.city', $city);
    }
    if ($area) {
        $builder->where('service_providers.area', $area);
    }

    if ($experience) {
      $expField = 'CASE 
          WHEN sp_services.service_id = 4 THEN sp_walking_details.experience 
          WHEN sp_services.service_id = 3 THEN sp_training_details.experience 
          WHEN sp_services.service_id = 2 THEN sp_grooming_details.experience 
          WHEN sp_services.service_id = 1 THEN sp_boarding_details.experience 
          WHEN sp_services.service_id = 6 THEN sp_sitting_details.experience 
          ELSE NULL 
      END';
      if ($experience === '5+') {
        $builder->where("CAST($expField AS SIGNED) >=", 5, false);
      } else {
        $expParts = explode('-', $experience);
        if (count($expParts) == 2) {
          $builder->where("CAST($expField AS SIGNED) >=", $expParts[0], false);
          $builder->where("CAST($expField AS SIGNED) <=", $expParts[1], false);
        }
      }
    }


    if ($status === 'active') {
      $builder->groupBy('service_providers.id');
    } elseif ($status === 'non_verified') {
      $builder->groupBy('service_providers.id');
    } else {
      $builder->groupBy(['service_providers.id', 'sp_services.service_id']);
    }



    if ($countOnly) {
        return $builder->get()->getNumRows();
    }

    $builder->orderBy('service_providers.id', 'DESC');

    if ($limit !== null) {
        $builder->limit($limit, $offset);
    }

    $providers = $builder->get()->getResult();

    foreach ($providers as $provider) {
      $details = $this->db->table('sp_services')
        ->select('services.name as service_name, sp_reviews.rating as admin_rating, sp_reviews.comment as admin_review')
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.experience 
            WHEN sp_services.service_id = 3 THEN sp_training_details.experience 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.experience 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.experience 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.experience 
            ELSE NULL 
        END as experience', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.available_time 
            WHEN sp_services.service_id = 3 THEN sp_training_details.available_time 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.available_time 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.available_time 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.available_time 
            ELSE NULL 
        END as available_time', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.confidence 
            WHEN sp_services.service_id = 3 THEN sp_training_details.confidence 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.confidence 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.confidence 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.confidence 
            ELSE NULL 
        END as confidence', false)
        ->select('CASE 
            WHEN sp_services.service_id = 4 THEN sp_walking_details.interview_answers 
            WHEN sp_services.service_id = 3 THEN sp_training_details.interview_answers 
            WHEN sp_services.service_id = 2 THEN sp_grooming_details.interview_answers 
            WHEN sp_services.service_id = 1 THEN sp_boarding_details.interview_answers 
            WHEN sp_services.service_id = 6 THEN sp_sitting_details.interview_answers 
            ELSE NULL 
        END as interview_answers', false)
        ->join('services', 'services.id = sp_services.service_id')
        ->join('sp_reviews', 'sp_reviews.provider_id = sp_services.provider_id AND sp_reviews.service_id = sp_services.service_id AND sp_reviews.reviewed_by = "admin"', 'left')
        ->join('sp_walking_details', 'sp_walking_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_training_details', 'sp_training_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_grooming_details', 'sp_grooming_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_boarding_details', 'sp_boarding_details.provider_id = sp_services.provider_id', 'left')
        ->join('sp_sitting_details', 'sp_sitting_details.provider_id = sp_services.provider_id', 'left')
        ->where('sp_services.provider_id', $provider->id)
        ->get()->getResult();

      $serviceDetails = [];
      foreach ($details as $detail) {
        $serviceDetails[$detail->service_name] = [
          'rating' => $detail->admin_rating ?? 'No Rating',
          'review' => $detail->admin_review ?? 'No Review',
          'experience' => ($detail->experience ?? '0') . ' Years',
          'available_time' => $detail->available_time ?? '--',
          'confidence' => $detail->confidence ?? '--',
          'interview_answers' => $detail->interview_answers ? json_decode($detail->interview_answers, true) : null
        ];
      }
      $provider->serviceDetails = $serviceDetails;
    }

    return $providers;
  }



  public function getByService($service_id, $limit = null, $offset = 0, $countOnly = false)
  {
    $builder = $this->db->table('service_providers')
      ->select('
            service_providers.id,
            service_providers.name,
            service_providers.email,
            service_providers.phone,
            service_providers.gender,
            service_providers.dob,
            service_providers.profile,
            service_providers.status,
            service_providers.area,
            service_providers.city,
            service_providers.state,
            service_providers.created_at,
            service_providers.service_address,
            sp_withdrawal_wallet.amount as wallet_amount,
            services.id as service_id,
            services.name as service_name,
            sp_reviews.rating,
            sp_reviews.comment,
            sp_reviews.reviewed_by,
            sp_reviews.admin_id,
            service_providers.is_verified as verified,
            service_providers.followup_status,
            service_providers.followup_comment,
            service_providers.followup_notes,
            service_providers.followup_date,
            sp_reviews.created_at as verifiedAt,
            sp_services.payment_paid,
            sp_services.call_verified
        ')
      ->join('sp_services', 'sp_services.provider_id = service_providers.id')
      ->join('services', 'services.id = sp_services.service_id')
      ->join('sp_withdrawal_wallet', 'sp_withdrawal_wallet.provider_id = service_providers.id', 'left')
      ->join('sp_reviews', 'sp_reviews.provider_id = service_providers.id AND sp_reviews.reviewed_by = "admin"', 'left')
      ->where('sp_services.service_id', $service_id)
      ->groupBy('service_providers.id')
      ->orderBy('service_providers.id', 'DESC');

    if ($countOnly) {
        return $builder->get()->getNumRows();
    }

    if ($limit !== null) {
        $builder->limit($limit, $offset);
    }

    return $builder->get()->getResult();
  }


  public function getProviderDetails($id)
  {
    return $this->db->table('service_providers')->select('*')->where('id', $id)->get()->getRow();
  }

  public function getServiceProviders($service_id)
  {
    $providers = $this->db->table('service_providers')
      ->select('service_providers.id, service_providers.name, service_providers.email, service_providers.phone,service_providers.profile,
      service_providers.status,service_providers.area,service_providers.city,service_providers.created_at, service_providers.device_token')

      ->join('sp_services', 'sp_services.provider_id = service_providers.id', 'left')
      ->where('sp_services.service_id', $service_id)


      ->orderBy('id', 'DESC')
      ->get()
      ->getResult();

    return $providers;
  }

  public function getAllVerfications($provider_id)
  {
    $data = $this->db->table('service_providers')
      ->select(
        'service_providers.name, service_providers.gender, service_providers.dob, 
            sp_verifications.email_verified, sp_verifications.mobile_verified, sp_verifications.identity_proof_verified,
            sp_verifications.address_proof_verified, sp_verifications.address_proof, sp_verifications.police_verification_verified, sp_verifications.police_verification,
            sp_verifications.post_card_verified, sp_verifications.post_card, sp_verifications.certificate_verified, sp_verifications.walking_verified, sp_verifications.profile_picture_verified, sp_verifications.call_verification,
            sp_verifications.training_verified, sp_verifications.grooming_verified, sp_verifications.boarding_verified,

            sp_kyc.aadhaar_photo as identity_proof' 
      )
      ->select('sp_walking_details.certificate as walking')
      ->select('sp_grooming_details.certificate as grooming')
      ->select('sp_boarding_details.certificate as boarding')
      ->select('sp_training_details.certificate as training')

      ->join('sp_verifications', 'sp_verifications.provider_id = service_providers.id', 'left')
      ->join('sp_kyc', 'sp_kyc.provider_id = service_providers.id', 'left')
      ->join('sp_walking_details', 'sp_walking_details.provider_id = service_providers.id', 'left')
      ->join('sp_grooming_details', 'sp_grooming_details.provider_id = service_providers.id', 'left')
      ->join('sp_boarding_details', 'sp_boarding_details.provider_id = service_providers.id', 'left')
      ->join('sp_training_details', 'sp_training_details.provider_id = service_providers.id', 'left')

      ->where('service_providers.id', $provider_id)
      ->get()
      ->getRow();

    return $data;
  }


  public function getAverageRating($provider_id)
  {
    // Calculate the average rating and total number of reviews
    $ratingData = $this->db->table('sp_reviews')
      ->select('AVG(rating) as avg_rating, COUNT(id) as total_reviews')
      ->where('provider_id', $provider_id)
      ->get()
      ->getRow();

    if ($ratingData) {
      $avgRating    = number_format((float) $ratingData->avg_rating, 1);
      $totalReviews = $ratingData->total_reviews;
      return "$avgRating ($totalReviews)";
    } else {
      return "0.0 (0)";
    }
  }
  public function getPaymentsRequests()
  {
    $data = $this->db->table('wallet_withdrawals')
      ->select('wallet_withdrawals.*')
      ->select('service_providers.id as provider_id,service_providers.name,service_providers.phone,service_providers.aadhar_name')
      ->select('sp_verified_bank_details.account_number,sp_verified_bank_details.bank_name,sp_verified_bank_details.ifsc_code,sp_verified_bank_details.account_holder_name')
      ->select('wallet_withdrawals.receipt,processed_at,requested_at')

      ->join('service_providers', 'service_providers.id = wallet_withdrawals.user_id', 'left')
      ->join('sp_verified_bank_details', 'sp_verified_bank_details.id = wallet_withdrawals.bank_id', 'left')

      // ->where('withdrawal_receipts.withdrawl_request', true)
      ->where('wallet_withdrawals.amount>', 0)
      ->whereIn('wallet_withdrawals.status', ['pending', 'in_progress', 'completed'])
      ->where('wallet_withdrawals.isBankConfirmed', 1)

      ->orderBy('wallet_withdrawals.requested_at', 'desc')

      ->get()
      ->getResult();

    return $data;
  }

  public function updateStatus($id, $provider_id, $status)
  {

    $builder = $this->db->table('wallet_withdrawals');
    return $builder
      ->set('status', $status)
      ->where('id', $id)
      ->where('user_id', $provider_id)
      ->update();
  }
  public function logTransaction($user_id, $type, $amount, $desc, $table)
  {
    if ($table == 'user_wallet_histories') {
      $id = 'user_id';
    } else {
      $id = 'provider_id';
    }
    return $this->db->table($table)
      ->set($id, $user_id)
      ->set('transaction_type', $type)
      ->set('amount', $amount)
      ->set('description', $desc)
      ->insert();
  }
  public function debitProviderWallet($provider_id, $DebitAmount)
  {
    return $this->db->table('sp_withdrawal_wallet')
      ->set('amount', 'amount-' . $DebitAmount, false)
      ->where('provider_id', $provider_id)
      ->update();
  }
  public function uploadReceipt($id, $provider_id, $receipt)
  {
    $builder = $this->db->table('wallet_withdrawals');

    // Check if the record exists
    $exists = $builder
      // ->where('withdrawl_id', $id)
      ->where('user_id', $provider_id)
      ->countAllResults();

    if ($exists > 0) {
      // Update existing record
      return $builder
        ->set('receipt', $receipt)
        // ->where('withdrawl_id', $id)
        ->where('user_id', $provider_id)
        ->update();
    } else {
      // Insert new record
      return $builder->insert([
        // 'withdrawl_id' => $id,
        'user_id' => $provider_id,
        'receipt' => $receipt
      ]);
    }
  }
  public function updateProviderCall($provider_id)
  {
    return $builder = $this->db->table('sp_verifications')
      ->set('call_verification', true)
      ->where('provider_id', $provider_id)
      ->update();
  }
  public function updateVerification($id, $type, $field)
  {
    $builder = $this->db->table('sp_verifications');
    return $builder->set($field, true)
      ->where($field, $field)
      ->where('provider_id', $id)
      ->update();
  }
  public function getProvidersEarnings()
  {
    $walletQuery = $this->db->table('sp_withdrawal_wallet')
      ->select('provider_id, SUM(amount) as wallet_total')
      ->where('amount >', 0)
      ->groupBy('provider_id')
      ->getCompiledSelect();

    $receiptQuery = $this->db->table('wallet_withdrawals')
      ->select('user_id, SUM(amount) as receipts_total,')
      ->where('amount >', 0)
      ->groupBy('user_id')
      ->getCompiledSelect();

    return $this->db->table('service_providers sp')
      ->select('sp.id as provider_id, sp.name, sp.phone, sp.area')
      ->select('COALESCE(w.wallet_total, 0) as wallet_total')
      ->select('COALESCE(r.receipts_total, 0) as receipts_total')
      ->join("($walletQuery) w", 'w.provider_id = sp.id', 'left')
      ->join("($receiptQuery) r", 'r.user_id = sp.id', 'left')
      ->where('w.wallet_total >', 0)
      ->orWhere('r.receipts_total >', 0)
      ->get()
      ->getResult();
  }


  public function getTotalBookings($provider_id)
  {

    $walking = $this->db->table('walking_service_bookings')
      ->select('walking_service_bookings.id')

      ->join('quotations', 'quotations.booking_id = walking_service_bookings.id', 'left')
      ->where('walking_service_bookings.status', 'Completed')
      ->where('quotations.status', 'Completed')
      ->where('quotations.provider_id', $provider_id)

      ->countAllResults();


    $grooming = $this->db->table('grooming_service_bookings')
      ->select('grooming_service_bookings.id')

      ->join('quotations', 'quotations.booking_id = grooming_service_bookings.id', 'left')
      ->where('grooming_service_bookings.status', 'Completed')
      ->where('quotations.status', 'Completed')
      ->where('quotations.provider_id', $provider_id)

      ->countAllResults();

    $trainig = $this->db->table('training_service_bookings')
      ->select('training_service_bookings.id')

      ->join('quotations', 'quotations.booking_id = training_service_bookings.id', 'left')
      ->where('training_service_bookings.status', 'Completed')
      ->where('quotations.status', 'Completed')
      ->where('quotations.provider_id', $provider_id)

      ->countAllResults();

    return $walking + $grooming + $trainig;
  }
  public function getWalkingDetails($id)
  {
    return $this->db->table('sp_walking_details')
      ->select('*')
      ->where('provider_id', $id)
      ->get()
      ->getRow();
  }
  public function getTrainingDetails($id)
  {
    return $this->db->table('sp_training_details')
      ->select('*')
      ->where('provider_id', $id)
      ->get()
      ->getRow();
  }
  public function getGroomingDetails($id)
  {
    return $this->db->table('sp_grooming_details')
      ->select('*')
      ->where('provider_id', $id)
      ->get()
      ->getRow();
  }
  public function getBoardingDetails($id)
  {
    return $this->db->table('sp_boarding_details')
      ->select('*')
      ->where('provider_id', $id)
      ->get()
      ->getRow();
  }
  public function getSittingDetails($id)
  {
    return $this->db->table('sp_sitting_details')
      ->select('*')
      ->where('provider_id', $id)
      ->get()
      ->getRow();
  }
  public function getReviews($id)
  {
    return $this->db->table('sp_reviews')
      ->select('sp_reviews.id,sp_reviews.created_at,sp_reviews.rating,sp_reviews.comment,sp_reviews.reviewed_by')
      ->select('service_providers.name as provider_name')
      ->select('users.name as client_name,users.phone')
      ->select('services.name as service_name')

      ->join('users', 'users.id=sp_reviews.user_id', 'left')
      ->join('service_providers', 'service_providers.id=sp_reviews.provider_id', 'left')
      ->join('services', 'services.id=sp_reviews.service_id', 'left')

      ->where('provider_id', $id)
      ->get()
      ->getResult();
  }
  public function getAllServices()
  {
    return $this->db->table('services')->get()->getResult();
  }

  public function getAllCities()
  {
    return $this->db->table('service_providers')->select('city')->distinct()->where('city !=', '')->get()->getResult();
  }

  public function getAllAreas()
  {
    return $this->db->table('service_providers')->select('area')->distinct()->where('area !=', '')->get()->getResult();
  }
    public function getAreasByCity($city)
  {
    return $this->db->table('service_providers')
      ->select('area')
      ->distinct()
      ->where('city', $city)
      ->where('area !=', '')
      ->get()
      ->getResult();
  }
}
