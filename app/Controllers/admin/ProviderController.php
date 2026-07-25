<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\Provider;
use App\Models\VerificationQuestionModel;

class ProviderController extends BaseController
{
  protected $model;
  protected $questionModel;
  protected $db;
  public function __construct()
  {
    $this->model         = new Provider();
    $this->questionModel = new VerificationQuestionModel();
    $this->db            = \Config\Database::connect();
    helper('Common_helper');
  }
  public function index()
  {
    $status = $this->request->getGet('status');
    $type   = $this->request->getGet('type');

    $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'non_verified'];

    $experience        = $this->request->getGet('experience');
    $filter_service_id = $this->request->getGet('service_id');
    $city              = $this->request->getGet('city');
    $area              = $this->request->getGet('area');

    $perPage = (int) ($this->request->getGet('limit') ?? 50);
    if (!in_array($perPage, [10, 25, 50, 100])) {
      $perPage = 50;
    }
    $page = (int) ($this->request->getGet('page') ?? 1);
    if ($page < 1) {
      $page = 1;
    }
    $offset = ($page - 1) * $perPage;

    if ($status) {
      if (in_array($status, $perServiceStatuses)) {
        $overdue           = $this->request->getGet('overdue') === '1';
        $total             = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, null, 0, true);
        $data['providers'] = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, $perPage, $offset);
      } else {
        $total             = $this->model->getAll($status, $filter_service_id, $city, $area, null, 0, true);
        $data['providers'] = $this->model->getAll($status, $filter_service_id, $city, $area, $perPage, $offset);
      }
    } else {
      // Determine service_id based on type
      switch ($type) {
        case 'walkers':
          $service_id = 4;
          break;
        case 'groomers':
          $service_id = 2;
          break;
        case 'trainers':
          $service_id = 3;
          break;
        default:
          $service_id = 1; // Default to vets or general
      }

      // getByService doesn't have pagination implemented yet, but we can stick with standard for now or update it too if needed
      // For now, let's just use getAll if no status but type is provided, or implement getByService pagination
      $total             = $this->model->getByService($service_id, null, 0, true);
      $data['providers'] = $this->model->getByService($service_id, $perPage, $offset);
    }

    $data['pager']        = service('pager');
    $data['pager_links']  = $data['pager']->makeLinks($page, $perPage, $total, 'default_full');
    $data['total_items']  = $total;
    $data['per_page']     = $perPage;
    $data['current_page'] = $page;

    $data['title']             = '';
    $data['type']              = $status ?? $type ?? 'active';
    $data['experience']        = $experience;
    $data['filter_service_id'] = $filter_service_id;
    $data['city']              = $city;
    $data['area']              = $area;
    $data['all_services']      = $this->model->getAllServices();
    $data['all_cities']        = $this->model->getAllCities();
    $data['all_areas']         = $this->model->getAllAreas();
    $data['page']              = 'provider';
    foreach ($data['providers'] as $provider) {
      $provider->age     = ageCalculator($provider->dob);
      $verifications     = $this->model->getAllVerfications($provider->id);
      $verificationArray = (array) $verifications;

      $verificationFields = [
        'email_verified',
        'mobile_verified',
        'identity_proof_verified',
        'police_verification_verified',
        'walking_verified',
        'profile_picture_verified',
        'call_verification',
        'training_verified',
        'grooming_verified'
      ];

      // Total verification fields
      $totalVerifications     = count($verificationFields);
      $completedVerifications = 0;

      foreach ($verificationFields as $field) {
        if (!empty($verificationArray[$field])) {
          $completedVerifications++;
        }
      }

      // Completion percentage
      $provider->completionPercentage = ($totalVerifications > 0)
        ? round(($completedVerifications / $totalVerifications) * 100, 1)
        : 0;

      $provider->verifications = $verifications;

      if (!isset($provider->serviceDetails) || empty($provider->serviceDetails)) {
        $provider->serviceDetails = [];
        if (!empty($provider->services)) {
          foreach ($provider->services as $service) {
            if ($service->service_id == '4') {
              $provider->serviceDetails['walking'] = $this->model->getWalkingDetails($provider->id);
            } elseif ($service->service_id == '3') {
              $provider->serviceDetails['training'] = $this->model->getTrainingDetails($provider->id);
            } elseif ($service->service_id == '2') {
              $provider->serviceDetails['grooming'] = $this->model->getGroomingDetails($provider->id);
            } elseif ($service->service_id == '1') {
              $provider->serviceDetails['boarding'] = $this->model->getBoardingDetails($provider->id);
            } elseif ($service->service_id == '6') {
              $provider->serviceDetails['sitting'] = $this->model->getSittingDetails($provider->id);
            }
          }
        }
      }

      // If this is a service-wise view, pull current service details to main object
      if (isset($provider->service_id)) {
        $details = null;
        if ($provider->service_id == '4') $details = $provider->serviceDetails['walking'] ?? $this->model->getWalkingDetails($provider->id);
        elseif ($provider->service_id == '3') $details = $provider->serviceDetails['training'] ?? $this->model->getTrainingDetails($provider->id);
        elseif ($provider->service_id == '2') $details = $provider->serviceDetails['grooming'] ?? $this->model->getGroomingDetails($provider->id);
        elseif ($provider->service_id == '1') $details = $provider->serviceDetails['boarding'] ?? $this->model->getBoardingDetails($provider->id);
        elseif ($provider->service_id == '6') $details = $provider->serviceDetails['sitting'] ?? $this->model->getSittingDetails($provider->id);

        if ($details) {
          $provider->experience     = $details->experience ?? '';
          $provider->available_time = $details->available_time ?? '';
          $provider->confidence     = $details->confidence ?? '';
        }
      }
    }
    return $this->loadView('admin/provider/index', $data);
  }
  public function add()
  {
    $data['title'] = 'Add Provider';
    $data['page']  = 'provider/add';
    return $this->loadView('admin/provider/add', $data);
  }
  public function edit()
  {
    $data['title'] = 'Edit Provider';
    $data['page']  = 'provider/edit';
    return $this->loadView('admin/provider/edit', data: $data);
  }
  public function delete()
  {
    $data['title'] = 'Delete Provider';
    $data['page']  = 'provider/delete';
    return $this->loadView('admin/provider/delete', $data);
  }
  public function getProviderDetails()
  {
    $id       = $this->request->getPost('id');
    $provider = $this->model->getProviderDetails($id);
    return $this->response->setJSON($provider);
  }
  public function walkers()
  {
    $type = $this->request->getGet('type');

    if ($type == 'groomers') {
      $data['providers'] = $this->model->getServiceProviders('2');
    } else if ($type == 'walkers') {
      $data['providers'] = $this->model->getServiceProviders('4');
    } else if ($type == 'boarders') {
      $data['providers'] = $this->model->getServiceProviders('1');
    } else {
      $data['providers'] = $this->model->getServiceProviders('3');
    }
    return $this->loadView('admin/bookings/walkers', $data);
  }
  public function spProfile()
  {
    $data['title']     = 'Service Provider Profile';
    $data['page']      = 'sp-profile';
    $data['providers'] = $this->model->getAll();
    foreach ($data['providers'] as $provider) {
      $verifications          = $this->model->getAllVerfications($provider->id);
      $totalVerifications     = count((array) $verifications);
      $completedVerifications = count(array_filter((array) $verifications));
      if ($totalVerifications > 0) {
        $provider->completionPercentage = round(($completedVerifications / $totalVerifications) * 100, 1);
      } else {
        $provider->completionPercentage = 0;
      }
    }
    return $this->loadView('admin/provider/sp-profile', $data);
  }
  public function payments()
  {
    $data['title']    = '';
    $data['page']     = 'payments';
    $data['payments'] = $this->model->getPaymentsRequests();

    return $this->loadView('admin/provider/payments', $data);
  }

  public function verify($provider_id, $service_id)
  {
    $provider = $this->model->getProviderDetails($provider_id);
    if (!$provider) {
      return redirect()->back()->with('error', 'Provider not found');
    }

    $data['provider']   = $provider;
    $data['service_id'] = $service_id;

    // Determine service name
    $serviceMap           = [
      '1' => 'Boarding',
      '2' => 'Grooming',
      '3' => 'Training',
      '4' => 'Walking',
      '6' => 'Sitting',
    ];
    $data['service_name'] = $serviceMap[$service_id] ?? 'Service';

    // Get specific service details
    $details = null;
    if ($service_id == 4) $details = $this->model->getWalkingDetails($provider_id);
    elseif ($service_id == 3) $details = $this->model->getTrainingDetails($provider_id);
    elseif ($service_id == 2) $details = $this->model->getGroomingDetails($provider_id);
    elseif ($service_id == 1) $details = $this->model->getBoardingDetails($provider_id);
    elseif ($service_id == 6) $details = $this->model->getSittingDetails($provider_id);

    $data['service_details'] = $details;

    if ($service_id == 3 && $details) {
      try {
        @file_put_contents('d:/Temp/fz3temp-2/fields.txt', json_encode($details));
        @file_put_contents(ROOTPATH . 'public/fields.txt', json_encode($details));
      } catch (\Exception $e) {
        // Ignore write errors
      }
    }

    // Get service-specific status and flags
    $service_row         = $this->db->table('sp_services')
      ->select('sp_services.*, sp_reviews.rating, sp_reviews.comment as review, sp_reviews.call_record')
      ->join('sp_reviews', 'sp_reviews.provider_id = sp_services.provider_id AND sp_reviews.service_id = sp_services.service_id AND sp_reviews.reviewed_by = "admin"', 'left')
      ->where(['sp_services.provider_id' => $provider_id, 'sp_services.service_id' => $service_id])
      ->get()->getRow();
    $data['service_row'] = $service_row;

    // Get verifications
    $data['verifications'] = $this->model->getAllVerfications($provider_id);
    $data['title']         = 'Service Verification - ' . $provider->name;
    $data['page']          = 'provider/verify';

    // Fetch dynamic questions from database
    $organized = $this->questionModel->getOrganizedQuestions($service_id);

    // Calculate total possible score dynamically from questions
    $questions = $this->questionModel->getQuestionsByService($service_id);

    // Filter for training service if service_id = 3 or grooming service if service_id = 2
    if ($service_id == 3 || $service_id == 2) {
      if ($service_id == 3) {
        $selected_types = $this->getSelectedTrainingTypes($provider_id, $details);
      } else {
        $selected_types = $this->getSelectedGroomingTypes($provider_id, $details);
      }

      // Filter organized questions
      $filtered_organized = [];
      foreach ($organized as $cat) {
        $filtered_questions = [];
        foreach ($cat['questions'] as $q) {
          $q_type = $q['training_type'] ?? '';
          if (empty($q_type) || in_array($q_type, $selected_types)) {
            $filtered_questions[] = $q;
          }
        }
        if (!empty($filtered_questions)) {
          $cat['questions'] = $filtered_questions;
          // Dynamically compute category weight from questions inside it
          $cat['weight'] = array_reduce($filtered_questions, function ($carry, $item) {
            return $carry + (float)$item['weight'];
          }, 0);
          $filtered_organized[] = $cat;
        }
      }
      $organized = $filtered_organized;

      // Filter flat questions
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (empty($q_type) || in_array($q_type, $selected_types)) {
          $filtered_flat[] = $q;
        }
      }
      $questions = $filtered_flat;
      $data['selected_types'] = $selected_types;
    }

    $data['organized_questions'] = $organized;
    $data['total_possible_score'] = array_reduce($questions, function ($carry, $item) {
      return $carry + (float) $item['weight'];
    }, 0);

    return $this->loadView('admin/provider/verify', $data);
  }

  public function submitServiceVerification()
  {
    $provider_id    = $this->request->getPost('provider_id');
    $service_id     = $this->request->getPost('service_id');
    $status         = $this->request->getPost('status');
    $rating         = $this->request->getPost('rating');
    $comment        = $this->request->getPost('comment');
    $experience     = $this->request->getPost('experience');
    $available_time = $this->request->getPost('available_time');
    $confidence     = $this->request->getPost('confidence');
    $file           = $this->request->getFile('call_record');

    // Validation
    $validation = \Config\Services::validation();
    $validation->setRules([
      'provider_id'    => 'required|numeric',
      'service_id'     => 'required|numeric',
      'status'         => 'required',
      'experience'     => 'required|numeric',
      'available_time' => 'required',
      'confidence'     => 'required',
      'comment'        => 'required|min_length[5]',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'Please fill all mandatory fields: Experience, Availability, Confidence, and Admin Notes.',
          'errors'  => $validation->getErrors()
        ]);
      }
      return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Calculate weightage-based rating dynamically from database
    $questions = $this->questionModel->getQuestionsByService($service_id);

    // Filter questions if service is training (ID 3) or grooming (ID 2)
    if ($service_id == 3 || $service_id == 2) {
      if ($service_id == 3) {
        $selected_types = $this->getSelectedTrainingTypes($provider_id);
      } else {
        $selected_types = $this->getSelectedGroomingTypes($provider_id);
      }
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (empty($q_type) || in_array($q_type, $selected_types)) {
          $filtered_flat[] = $q;
        }
      }
      $questions = $filtered_flat;
    }

    // Find trigger value if exists to handle visibility matching
    $triggerValue = null;
    foreach ($questions as $q) {
      if (!empty($q['is_trigger'])) {
        $fieldName    = !empty($q['field_name']) ? $q['field_name'] : 'q_' . $q['id'];
        $triggerValue = $this->request->getPost($fieldName);
        break;
      }
    }

    $totalPossibleScore = 0;
    $totalScore         = 0;
    $hasInterviewData   = false;
    $answers            = [];

    foreach ($questions as $q) {
      $fieldName = !empty($q['field_name']) ? $q['field_name'] : 'q_' . $q['id'];

      // Exclude dependent questions if the trigger question is set to 'No' (score <= 0)
      if (!empty($q['is_dependent']) && ($triggerValue === null || (float) $triggerValue <= 0)) {
        continue;
      }

      $val = $this->request->getPost($fieldName);
      if ($val !== null) {
        $score               = (float) $val;
        $totalScore         += $score;
        $totalPossibleScore += (float) $q['weight'];
        $hasInterviewData    = true;

        $weight = (float) $q['weight'];
        $ratio  = $weight > 0 ? ($score / $weight) : 0;

        if ($ratio < 0.125) {
          $key = 'Bad';
          $pct = '0%';
        } elseif ($ratio < 0.375) {
          $key = 'Poor';
          $pct = '25%';
        } elseif ($ratio < 0.625) {
          $key = 'Average';
          $pct = '50%';
        } elseif ($ratio < 0.875) {
          $key = 'Good';
          $pct = '75%';
        } else {
          $key = 'Excellent';
          $pct = '100%';
        }

        $parsedOptions = [];
        $optionsStr    = $q['options'] ?? '';
        if (!empty($optionsStr)) {
          $parts = explode(',', $optionsStr);
          foreach ($parts as $part) {
            $subparts = explode(':', $part, 2);
            if (count($subparts) === 2) {
              $k                 = trim($subparts[0]);
              $v                 = trim($subparts[1]);
              $k                 = str_replace(["\r", "\n", "\\r", "\\n"], "", $k);
              $v                 = str_replace(["\r", "\n", "\\r", "\\n"], "", $v);
              $parsedOptions[$k] = $v;
            }
          }
        }

        $description = $parsedOptions[$key] ?? $key;
        $answerLabel = $description . ' (' . $key . ' ' . $pct . ')';

        $answers[$fieldName] = [
          'question' => $q['question_text'],
          'answer'   => $answerLabel,
          'score'    => $score
        ];
      }
    }

    if ($hasInterviewData) {
      $percentage = ($totalPossibleScore > 0) ? ($totalScore / $totalPossibleScore) * 100 : 0;
      if ($percentage >= 90) $rating = 5;
      elseif ($percentage >= 75) $rating = 4;
      elseif ($percentage >= 55) $rating = 3;
      elseif ($percentage >= 35) $rating = 2;
      elseif ($totalScore > 0) $rating = 1;
      else $rating = 0;
    }

    $callRecordPath = null;
    if ($file && $file->isValid()) {
      $newName = $file->getRandomName();
      $file->move(ROOTPATH . 'public/uploads/providers/call_records/', $newName);
      $callRecordPath = $newName;
    }

    $db = \Config\Database::connect();
    $db->transStart();

    // Force status to approved if it's coming from Manager Approval stage
    $currentService = $db->table('sp_services')
      ->where(['provider_id' => $provider_id, 'service_id' => $service_id])
      ->get()->getRow();

    if ($currentService && $currentService->status === 'approval' && $status !== 'approved' && $status !== 'rejected') {
      $status = 'approved';
    }

    if ($status === 'pending_kyc' || $status === 'approval') {
      $service_status = 'approved';
    } else {
      $service_status = $status;
    }

    // Auto-reject if rating is less than 3
    // if (!empty($rating) && $rating < 3) {
    //   $service_status = 'rejected';
    // }

    $updateData = ['status' => $service_status];

    if ($status === 'pending_kyc' || $status === 'approval' || $status === 'approved' || $service_status === 'rejected') {
      $updateData['call_verified'] = 1;
      $updateData['payment_paid']  = 1;
    }



    // 1. Update sp_services status and flags
    $db->table('sp_services')
      ->where('provider_id', $provider_id)
      ->where('service_id', $service_id)
      ->update($updateData);

    // 2. Add/Update review in sp_reviews
    $reviewData = [
      'provider_id' => $provider_id,
      'user_id'     => 0, // Admin review
      'service_id'  => $service_id,
      'rating'      => $rating,
      'comment'     => $comment,
      'reviewed_by' => 'admin',
      'admin_id'    => session()->get('user')->id ?? 1,
      'created_at'  => date('Y-m-d H:i:s'),
      'updated_at'  => date('Y-m-d H:i:s')
    ];

    if ($callRecordPath) {
      $reviewData['call_record'] = $callRecordPath;
    }

    // Check if review already exists for this admin/service
    $existingReview = $db->table('sp_reviews')
      ->where('provider_id', $provider_id)
      ->where('service_id', $service_id)
      ->where('reviewed_by', 'admin')
      ->get()->getRow();

    if ($existingReview) {
      $db->table('sp_reviews')->where('id', $existingReview->id)->update($reviewData);
    } else {
      $db->table('sp_reviews')->insert($reviewData);
    }

    // 3. Update specific service detail tables with experience, available_time, confidence
    $serviceDetails = [
      'experience'        => $experience,
      'available_time'    => $available_time,
      'confidence'        => $confidence,
      'interview_answers' => !empty($answers) ? json_encode($answers) : null,
      'updated_at'        => date('Y-m-d H:i:s'),
      'verifier_id'       => session()->get('user')->id ?? 1,
    ];

    $detailTable = '';
    if ($service_id == 4) $detailTable = 'sp_walking_details';
    elseif ($service_id == 3) $detailTable = 'sp_training_details';
    elseif ($service_id == 2) $detailTable = 'sp_grooming_details';
    elseif ($service_id == 1) $detailTable = 'sp_boarding_details';
    elseif ($service_id == 6) $detailTable = 'sp_sitting_details';

    if ($detailTable) {
      // Check if detail record exists
      $exists = $db->table($detailTable)->where('provider_id', $provider_id)->countAllResults();
      if ($exists > 0) {
        $db->table($detailTable)->where('provider_id', $provider_id)->update($serviceDetails);
      } else {
        $serviceDetails['provider_id'] = $provider_id;
        $serviceDetails['created_at']  = date('Y-m-d H:i:s');
        $db->table($detailTable)->insert($serviceDetails);
      }
    }

    // 4. Special handling for Global Account Status if needed
    if ($status === 'approved') {
      // Check KYC status before allowing approval
      $verifications = $db->table('sp_verifications')
        ->where('provider_id', $provider_id)
        ->get()->getRow();

      if (!$verifications || !$verifications->identity_proof_verified) {
        $db->transRollback();
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'Cannot approve service. KYC verification (Aadhaar/Address Proof) is pending for this provider.'
        ]);
      }
      $db->table('sp_services')->where(['provider_id' => $provider_id, 'service_id' => $service_id])->update(['is_verified' => 1]);

      $totalServices    = $db->table('sp_services')->where('provider_id', $provider_id)->countAllResults();
      $verifiedServices = $db->table('sp_services')->where(['provider_id' => $provider_id, 'is_verified' => 1])->countAllResults();

      if ($totalServices > 0 && $totalServices === $verifiedServices) {
        $db->table('service_providers')->where('id', $provider_id)->update(['is_verified' => 1]);
      }
    }

    if ($status === 'pending_kyc') {
      $db->table('service_providers')->where('id', $provider_id)->update(['status' => 'pending_kyc']);
    }

    $db->transComplete();

    if ($status === 'approved' && $db->transStatus() !== false) {
      $this->sendPartnerAgreementEmail($provider_id, $service_id);
    }

    if ($db->transStatus() === false) {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON(['status' => false, 'message' => 'Failed to update verification']);
      }
      return redirect()->back()->with('error', 'Failed to update verification');
    }

    if ($this->request->isAJAX()) {
      return $this->response->setJSON(['status' => true, 'message' => 'Verification updated successfully']);
    }
    $redirect_status = 'active';
    if ($status === 'approved') {
      $redirect_status = 'active';
    } elseif (in_array($status, ['pending_registration', 'pending_verification', 'approval', 'pending_kyc', 'inactive'])) {
      $redirect_status = $status;
    }

    if ($redirect_status === 'pending_verification') {
      $prov = $db->table('service_providers')->select('created_at')->where('id', $provider_id)->get()->getRow();
      if ($prov && strtotime($prov->created_at) < strtotime('-24 hours')) {
        $redirect_status .= '&overdue=1';
      }
    }

    return redirect()->to(base_url('providers?status=' . $redirect_status))->with('success', 'Verification updated successfully');
  }

  public function updateStatus()
  {
    $module      = $this->request->getPost('module');
    $id          = $this->request->getPost('id');
    $provider_id = $this->request->getPost('provider_id');
    $status      = $this->request->getPost('status');
    $netAmount   = $this->request->getPost('amount');

    if ($module === 'service_providers') {
      $updateData = ['status' => $status];
      // If manually moving to pending_verification, assume registration is paid
      if ($status === 'pending_verification') {
        $updateData['registration_paid'] = 1;
        // Also update all services for this provider
        $this->model->db->table('sp_services')
          ->where('provider_id', $id)
          ->update(['payment_paid' => 1]);
      }
      $result = $this->model->db->table('service_providers')->where('id', $id)->update($updateData);
      return $this->response->setJSON(['status' => $result ? true : false]);
    }

    if ($status == 'completed') {
      $this->model->logTransaction($provider_id, 'debit', $netAmount, 'Withdrawal amount', 'sp_wallet_histories');
      $this->model->debitProviderWallet($provider_id, $netAmount);
    }
    $this->model->updateStatus($id, $provider_id, $status);
    return $this->response->setJSON(['status' => true]);
  }
  public function uploadReceipt()
  {
    $id          = $this->request->getPost('id');
    $provider_id = $this->request->getPost('provider_id');
    $file        = $this->request->getFile('file');

    if (!$file->isValid()) {
      return $this->response->setJSON(['status' => 'error', 'message' => $file->getErrorString()]);
    }

    $newName = $file->getRandomName();
    if ($file->move(ROOTPATH . 'public/uploads/receipts/', $newName)) {
      $this->model->uploadReceipt($id, $provider_id, $newName);
      return $this->response->setJSON(['status' => 'success', 'message' => 'File uploaded']);
    }

    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload file']);
  }
  public function updateProviderCall()
  {
    $provider_id = $this->request->getPost('id');
    // $call_status = $this->request->getPost('call_status');
    $result = $this->model->updateProviderCall($provider_id);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Call status updated']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Call status not updated']);
    }
  }
  public function spEarnings()
  {
    $data['title']     = '';
    $data['page']      = 'payments';
    $data['providers'] = $this->model->getProvidersEarnings();
    foreach ($data['providers'] as $provider) {
      $provider->total         = $provider->wallet_total + $provider->receipts_total;
      $provider->totalBookings = $this->model->getTotalBookings($provider->provider_id);
    }
    return $this->loadView('admin/provider/earnings', $data);
  }
  public function updateVerification()
  {
    $id     = $this->request->getPost('id');
    $type   = $this->request->getPost('type');
    $field  = $this->request->getPost('field');
    $result = $this->model->updateVerification($id, $type, $field);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'status updated']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'status not updated']);
    }
  }



  // Add this method to your controller
  public function addFollowupComment()
  {
    $ids     = $this->request->getPost('ids');
    $comment = $this->request->getPost('comment');
    $notes   = $this->request->getPost('notes');

    // Handle IDs robustly (could be array, JSON string, or comma-separated string)
    if (is_string($ids)) {
      $decoded = json_decode($ids, true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $ids = $decoded;
      } else {
        $ids = explode(',', $ids);
      }
    }

    if (!is_array($ids)) {
      $ids = !empty($ids) ? [$ids] : [];
    }

    $ids = array_filter(array_map('trim', $ids));

    if (empty($ids)) {
      return $this->response->setJSON(['status' => false, 'message' => 'No providers selected']);
    }

    try {
      $data = [
        'followup_status'  => 'followup',
        'followup_comment' => $comment,
        'followup_notes'   => $notes,
        'followup_date'    => date('Y-m-d H:i:s'),
        'updated_at'       => date('Y-m-d H:i:s')
      ];

      $builder = $this->model->db->table('service_providers');
      $builder->whereIn('id', $ids);
      $result = $builder->update($data);

      if ($result) {
        return $this->response->setJSON([
          'status'  => true,
          'message' => 'Follow-up comments added successfully'
        ]);
      } else {
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'Failed to add follow-up comments'
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  // Method to remove follow-up status
  public function removeFollowupStatus()
  {
    $id = $this->request->getPost('id');

    if (empty($id)) {
      return $this->response->setJSON(['status' => false, 'message' => 'Provider ID required']);
    }

    try {
      $data = [
        'followup_status'  => null,
        'followup_comment' => null,
        'followup_notes'   => null,
        'followup_date'    => null,
        'updated_at'       => date('Y-m-d H:i:s')
      ];

      $builder = $this->model->db->table('service_providers');
      $builder->where('id', $id);
      $result = $builder->update($data);

      if ($result) {
        return $this->response->setJSON([
          'status'  => true,
          'message' => 'Provider moved back to Non-Verified'
        ]);
      } else {
        return $this->response->setJSON([
          'status'  => false,
          'message' => 'Failed to move provider'
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }
  public function reviews($id)
  {
    $data['title']   = '';
    $data['page']    = 'reviews';
    $data['reviews'] = $this->model->getReviews($id);
    foreach ($data['reviews'] as $review) {
      $data['provider_name'] = $review->provider_name;
    }
    return $this->loadView('admin/provider/sp_reviews', $data);
  }

  public function notificationStatus()
  {
    $provider_id   = $this->request->getGet('provider_id');
    $data['title'] = 'Provider Notification Status';
    $data['page']  = 'notification-status';

    $db = \Config\Database::connect();

    // Fetch all active providers for the filter dropdown
    $data['providers_list'] = $db->table('service_providers')
      ->select('id, name, phone')
      ->where('status', 'active')
      ->orderBy('name', 'ASC')
      ->get()->getResult();

    $builder = $db->table('notification_queue n')
      ->select('n.*, sp.name as provider_name, sp.phone as provider_phone, COALESCE(n.provider_id, sp.id) as provider_id')
      ->join('service_providers sp', 'n.tokens LIKE CONCAT("%", sp.device_token, "%")', 'left');

    if ($provider_id) {
      $builder->where('sp.id', $provider_id);
    }

    $data['notifications'] = $builder->orderBy('n.created_at', 'DESC')
      ->limit(500)
      ->get()
      ->getResult();

    $data['selected_provider'] = $provider_id;

    return $this->loadView('admin/provider/notification_status', $data);
  }

  public function resendNotification()
  {
    $id = $this->request->getPost('id');
    $db = \Config\Database::connect();

    $notification = $db->table('notification_queue')->where('id', $id)->get()->getRow();

    if (!$notification) {
      return $this->response->setJSON(['status' => false, 'message' => 'Notification not found']);
    }

    // Create a new entry in the queue with the same details
    $newId = $db->table('notification_queue')->insert([
      'tokens'       => $notification->tokens,
      'title'        => $notification->title,
      'message'      => $notification->message,
      'priority'     => $notification->priority,
      'status'       => 'pending',
      'scheduled_at' => date('Y-m-d H:i:s'),
      'attempts'     => 0,
      'route_name'   => $notification->route_name,
      'provider_id'  => $notification->provider_id,
      'booking_id'   => $notification->booking_id,
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s')
    ]);

    if ($newId) {
      return $this->response->setJSON(['status' => true, 'message' => 'Notification queued for resending']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to queue notification']);
    }
  }

  public function downloadProviders()
  {
    $status            = $this->request->getGet('status');
    $experience        = $this->request->getGet('experience');
    $filter_service_id = $this->request->getGet('service_id');
    $city              = $this->request->getGet('city');
    $area              = $this->request->getGet('area');

    $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'non_verified'];

    if ($status && in_array($status, $perServiceStatuses)) {
      $overdue   = $this->request->getGet('overdue') === '1';
      $providers = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue);
    } elseif ($status) {
      $providers = $this->model->getAll($status, $filter_service_id, $city, $area);
    } else {
      $providers = $this->model->getByService(1); // Default
    }

    $filename = "providers_" . ($status ?? 'all') . "_" . date('Ymd') . ".csv";

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Phone', 'Email', 'Gender', 'City', 'Area', 'Status', 'Joined At']);

    foreach ($providers as $provider) {
      fputcsv($output, [
        $provider->id,
        $provider->name,
        $provider->phone,
        $provider->email,
        $provider->gender,
        $provider->city,
        $provider->area,
        $provider->status,
        $provider->created_at
      ]);
    }

    fclose($output);
    exit;
  }

  public function verificationRules()
  {

    $ruleModel = new \App\Models\VerificationRuleModel();
    $rules     = $ruleModel->getAllRules();

    $data['title'] = 'Verification Rules';
    $data['page']  = 'verification-rules';
    $data['rules'] = $rules;
    return $this->loadView('admin/provider/verification_rules', $data);
  }

  public function checkVerificationRequirement()
  {
    $service_id = $this->request->getPost('service_id');
    $city       = $this->request->getPost('city');
    $state      = $this->request->getPost('state');

    // Map service_id to service_type string used in VerificationRuleModel
    $serviceMap = [
      '2' => 'grooming',
      '3' => 'training',
      '4' => 'walking',
      '1' => 'boarding', // Assuming 1 is boarding/sitter
    ];

    $serviceType = $serviceMap[$service_id] ?? 'walking';

    $ruleModel = new \App\Models\VerificationRuleModel();
    $rule      = $ruleModel->getRule($serviceType, $city, $state);

    if (!$rule) {
      return $this->response->setJSON([
        'status'                => true,
        'verification_required' => false,
        'message'               => 'No verification required for this service/location.'
      ]);
    }

    $isDeferred = (bool) ($rule['deferred_verification'] ?? 0);
    $fee        = (float) ($rule['registration_fee'] ?? 0);

    return $this->response->setJSON([
      'status'                => true,
      'verification_required' => (bool) $rule['verification_required'],
      'is_deferred'           => $isDeferred,
      'registration_fee'      => $fee,
      'steps'                 => [
        'call'    => (bool) $rule['call_verification_required'],
        'aadhaar' => (bool) $rule['aadhaar_verification_required'],
      ],
      'rule_id'               => $rule['id']
    ]);
  }
  public function getRuleData(int $id)
  {
    $ruleModel = new \App\Models\VerificationRuleModel();
    $rule      = $ruleModel->getRuleById($id);
    return $this->response->setJSON(['status' => !!$rule, 'rule' => $rule]);
  }

  public function saveRule()
  {
    $ruleModel = new \App\Models\VerificationRuleModel();
    $id        = $this->request->getPost('id');
    $data      = [
      'service_type'                  => $this->request->getPost('service_type'),
      'city'                          => $this->request->getPost('city'),
      'state'                         => $this->request->getPost('state'),
      'country'                       => $this->request->getPost('country') ?: 'India',
      'registration_fee'              => $this->request->getPost('registration_fee'),
      'call_verification_required'    => $this->request->getPost('call_verification_required') ? 1 : 0,
      'aadhaar_verification_required' => $this->request->getPost('aadhaar_verification_required') ? 1 : 0,
      'deferred_verification'         => $this->request->getPost('deferred_verification') ? 1 : 0,
      'status'                        => $this->request->getPost('status') ?: 'active',
    ];

    if ($id) {
      $res = $ruleModel->updateRule((int) $id, $data);
    } else {
      $res = $ruleModel->createRule($data);
    }

    return $this->response->setJSON(['status' => $res, 'message' => $res ? 'Rule saved successfully' : 'Failed to save rule']);
  }

  public function deleteRule(int $id)
  {
    $ruleModel = new \App\Models\VerificationRuleModel();
    $res       = $ruleModel->deleteRule($id);
    return $this->response->setJSON(['status' => $res, 'message' => $res ? 'Rule deleted successfully' : 'Failed to delete rule']);
  }
  public function getAreas()
  {
    $city  = $this->request->getPost('city');
    $areas = $this->model->getAreasByCity($city);
    return $this->response->setJSON($areas);
  }

  private function sendPartnerAgreementEmail($provider_id, $service_id)
  {
    $db       = \Config\Database::connect();
    $provider = $db->table('service_providers')->where('id', $provider_id)->get()->getRow();
    $service  = $db->table('services')->where('id', $service_id)->get()->getRow();

    // Fetch aadhaar number if available, else leave blank
    $kyc        = $db->table('sp_kyc')->where('provider_id', $provider_id)->get()->getRow();
    $aadhaar_no = $kyc && isset($kyc->aadhaar_number) ? $kyc->aadhaar_number : '________________________';

    if ($kyc && isset($kyc->aadhaar_no) && $aadhaar_no === '________________________') {
      $aadhaar_no = $kyc->aadhaar_no;
    }

    if (!$provider || empty($provider->email)) {
      return false;
    }

    $serviceName = $service ? $service->name : 'Pet Care';
    $currentDate = date('d-M-Y');
    $data        = [
      'provider' => $provider,
      'service'  => $service,
      'aadhaar_no' => $aadhaar_no,
      'currentDate' => $currentDate,
      'serviceName' => $serviceName,
    ];
    try {
      $htmlContent = view('admin/provider/agreement.php', $data);
    } catch (\Exception $e) {
      log_message('error', 'Could not find agreement.php view: ' . $e->getMessage());
      return false;
    }

    if (!class_exists('\Dompdf\Dompdf')) {
      log_message('error', 'Dompdf library is not installed. Please run: composer require dompdf/dompdf');
      return false;
    }

    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    if (empty($pdfOutput)) {
      log_message('error', 'Dompdf failed to generate PDF output.');
      return false;
    }

    $email = \Config\Services::email();
    $email->setTo($provider->email);

    $fromEmail = getenv('emailFrom') ?: 'info.petsfolio@gmail.com';
    $fromName  = getenv('emailDomain') ?: 'Petsfolio';

    $email->setFrom($fromEmail, $fromName);
    $email->setSubject('Petsfolio Partner Agreement - Approved');

    // Attach the raw PDF buffer directly
    $email->attach($pdfOutput, 'attachment', 'Petsfolio_Partner_Agreement.pdf', 'application/pdf');

    // Simple email body
    $messageBody = 'Dear ' . $provider->name . ',<br><br>';
    $messageBody .= 'Congratulations! Your service verification has been approved.<br><br>';
    $messageBody .= 'Please find your Petsfolio Partner Agreement attached to this email as a PDF document.<br><br>';
    $messageBody .= 'Best regards,<br>The Petsfolio Team';

    $email->setMessage($messageBody);
    $email->setMailType('html');

    $result = $email->send();

    return $result;
  }

  private function isFieldActive($value)
  {
    if ($value === null) {
      return false;
    }
    $val = trim(strtolower((string)$value));
    return !($val === '' || $val === '0' || $val === 'no' || $val === 'false' || $val === 'none');
  }

  private function getSelectedTrainingTypes($provider_id, $details = null)
  {
    if (!$details) {
      $details = $this->model->getTrainingDetails($provider_id);
    }

    $selected_types = [];
    if ($details) {
      $details_array = (array)$details;

      $puppy_keys = ['puppy_obedience', 'puppy_obedience_training', 'puppy_training', 'puppy', 'intermediate_training', 'obedience_training'];
      foreach ($puppy_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'obedience_training';
          break;
        }
      }

      $behaviour_keys = ['behaviour', 'behaviour_training', 'behavior', 'behavior_training', 'behavioral_training'];
      foreach ($behaviour_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'behavioral_training';
          break;
        }
      }

      $advanced_keys = ['advanced', 'advanced_training'];
      foreach ($advanced_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'advanced_training';
          break;
        }
      }

      if (empty($selected_types)) {
        $text_fields = ['training_type', 'training_types', 'specialization', 'specializations', 'services'];
        foreach ($text_fields as $f) {
          if (!empty($details_array[$f])) {
            $val = strtolower($details_array[$f]);
            $decoded = json_decode($details_array[$f], true);
            if (is_array($decoded)) {
              foreach ($decoded as $item) {
                $item_lower = strtolower($item);
                if (strpos($item_lower, 'puppy') !== false || strpos($item_lower, 'obedience') !== false || strpos($item_lower, 'intermediate') !== false) {
                  $selected_types[] = 'obedience_training';
                }
                if (strpos($item_lower, 'behav') !== false) {
                  $selected_types[] = 'behavioral_training';
                }
                if (strpos($item_lower, 'adv') !== false) {
                  $selected_types[] = 'advanced_training';
                }
              }
            } else {
              if (strpos($val, 'puppy') !== false || strpos($val, 'obedience') !== false || strpos($val, 'intermediate') !== false) {
                $selected_types[] = 'obedience_training';
              }
              if (strpos($val, 'behav') !== false) {
                $selected_types[] = 'behavioral_training';
              }
              if (strpos($val, 'adv') !== false) {
                $selected_types[] = 'advanced_training';
              }
            }
          }
        }
      }
    }

    if (empty($selected_types)) {
      $selected_types = ['obedience_training', 'behavioral_training', 'advanced_training'];
    }

    return array_unique($selected_types);
  }

  private function getSelectedGroomingTypes($provider_id, $details = null)
  {
    if (!$details) {
      $details = $this->model->getGroomingDetails($provider_id);
    }

    $selected_types = [];
    if ($details) {
      $details_array = (array)$details;

      // Check single grooming_type first
      if (!empty($details_array['grooming_type'])) {
        $g_type = trim(strtolower((string)$details_array['grooming_type']));
        if ($g_type === 'grooming_trimming' || $g_type === 'grooming_and_trimming') {
          return ['grooming_and_trimming'];
        } elseif ($g_type === 'only_grooming' || $g_type === 'grooming') {
          return ['grooming'];
        } elseif ($g_type === 'grooming_haircut' || $g_type === 'grooming_and_cut') {
          return ['grooming_and_cut'];
        }
      }

      // 1. Check direct tinyint/boolean columns
      $trimming_keys = ['grooming_trimming', 'trimming', 'grooming_and_trimming'];
      foreach ($trimming_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'grooming_and_trimming';
          break;
        }
      }

      $only_keys = ['only_grooming', 'basic_grooming', 'grooming'];
      foreach ($only_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'grooming';
          break;
        }
      }

      $haircut_keys = ['grooming_haircut', 'grooming_styling', 'haircut', 'stylish_hair_cut', 'styling', 'grooming_and_cut'];
      foreach ($haircut_keys as $k) {
        if (isset($details_array[$k]) && $this->isFieldActive($details_array[$k])) {
          $selected_types[] = 'grooming_and_cut';
          break;
        }
      }

      // 2. Check JSON or comma-separated columns
      if (empty($selected_types)) {
        $text_fields = ['grooming_type', 'grooming_types', 'specialization', 'specializations', 'services'];
        foreach ($text_fields as $f) {
          if (!empty($details_array[$f])) {
            $val = strtolower($details_array[$f]);
            $decoded = json_decode($details_array[$f], true);
            if (is_array($decoded)) {
              foreach ($decoded as $item) {
                $item_lower = strtolower($item);
                if (strpos($item_lower, 'trimming') !== false || strpos($item_lower, 'trim') !== false) {
                  $selected_types[] = 'grooming_and_trimming';
                }
                if (strpos($item_lower, 'only') !== false || strpos($item_lower, 'basic') !== false || strpos($item_lower, 'grooming') !== false) {
                  if (strpos($item_lower, 'trim') === false && strpos($item_lower, 'cut') === false) {
                    $selected_types[] = 'grooming';
                  }
                }
                if (strpos($item_lower, 'haircut') !== false || strpos($item_lower, 'style') !== false || strpos($item_lower, 'styling') !== false || strpos($item_lower, 'cut') !== false) {
                  $selected_types[] = 'grooming_and_cut';
                }
              }
            } else {
              if (strpos($val, 'trimming') !== false || strpos($val, 'trim') !== false) {
                $selected_types[] = 'grooming_and_trimming';
              }
              if (strpos($val, 'only') !== false || strpos($val, 'basic') !== false || strpos($val, 'grooming') !== false) {
                if (strpos($val, 'trim') === false && strpos($val, 'cut') === false) {
                  $selected_types[] = 'grooming';
                }
              }
              if (strpos($val, 'haircut') !== false || strpos($val, 'style') !== false || strpos($val, 'styling') !== false || strpos($val, 'cut') !== false) {
                $selected_types[] = 'grooming_and_cut';
              }
            }
          }
        }
      }
    }

    if (empty($selected_types)) {
      $selected_types = ['grooming_and_trimming', 'grooming', 'grooming_and_cut'];
    }

    return array_unique($selected_types);
  }
}
