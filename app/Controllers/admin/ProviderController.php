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
<<<<<<< HEAD
    helper('common');
=======
    helper('Common_helper');
>>>>>>> origin/main
  }
  public function index()
  {
    $status = $this->request->getGet('status');
    $type   = $this->request->getGet('type');

<<<<<<< HEAD
    $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'non_verified'];
=======
    if (empty($status) && empty($type)) {
      if (hasPermission('providers.view') || hasPermission('verified.view')) {
        $status = 'active';
      } elseif (hasPermission('fee_pending.view')) {
        $status = 'pending_registration';
      } elseif (hasPermission('wait_for_call.view') || hasPermission('wait_for_call_24.view')) {
        $status = 'pending_verification';
      } elseif (hasPermission('manager_approval.view')) {
        $status = 'approval';
      } elseif (hasPermission('kyc_pending.view')) {
        $status = 'pending_kyc';
      } elseif (hasPermission('old_non_verified.view')) {
        $status = 'non_verified';
      } elseif (hasPermission('suspended.view')) {
        $status = 'inactive';
      } else {
        $status = 'active';
      }
    }

    if (empty($status) && !empty($type) && !in_array($type, ['walkers', 'groomers', 'trainers', 'boarders', 'sitters'])) {
      $status = $type;
    }

    // Verify permission for the requested status
    if ($status === 'pending_registration' && !hasPermission('providers.view') && !hasPermission('fee_pending.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Fee Pending providers.');
    } elseif ($status === 'pending_verification' && !hasPermission('providers.view') && !hasPermission('wait_for_call.view') && !hasPermission('wait_for_call_24.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Waiting for Call providers.');
    } elseif ($status === 'approval' && !hasPermission('providers.view') && !hasPermission('manager_approval.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Manager Approval providers.');
    } elseif ($status === 'pending_kyc' && !hasPermission('providers.view') && !hasPermission('kyc_pending.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view KYC Pending providers.');
    } elseif ($status === 'active' && !hasPermission('providers.view') && !hasPermission('verified.view') && !hasPermission('old_verified.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Verified providers.');
    } elseif ($status === 'non_verified' && !hasPermission('providers.view') && !hasPermission('old_non_verified.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Old SP-Non Verified providers.');
    } elseif ($status === 'inactive' && !hasPermission('providers.view') && !hasPermission('suspended.view')) {
      return redirect()->to(getDefaultPermittedRoute())->with('error', 'You do not have permission to view Suspended providers.');
    }

    $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'non_verified', 'pending_kyc'];
>>>>>>> origin/main

    $experience        = $this->request->getGet('experience');
    $filter_service_id = $this->request->getGet('service_id');
    $city              = $this->request->getGet('city');
    $area              = $this->request->getGet('area');
<<<<<<< HEAD

    $perPage = (int) ($this->request->getGet('limit') ?? 50);
    if (!in_array($perPage, [10, 25, 50, 100])) {
=======
    $search            = $this->request->getGet('search');
    $reg_type          = $this->request->getGet('reg_type');

    $perPage = (int) ($this->request->getGet('limit') ?? 50);
    if (!in_array($perPage, [10, 25, 50, 100, 250, 500, 10000])) {
>>>>>>> origin/main
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
<<<<<<< HEAD
        $total             = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, null, 0, true);
        $data['providers'] = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, $perPage, $offset);
      } else {
        $total             = $this->model->getAll($status, $filter_service_id, $city, $area, null, 0, true);
        $data['providers'] = $this->model->getAll($status, $filter_service_id, $city, $area, $perPage, $offset);
=======
        $total             = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, null, 0, true, $search, $reg_type);
        $data['providers'] = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, $perPage, $offset, false, $search, $reg_type);
      } else {
        $total             = $this->model->getAll($status, $filter_service_id, $city, $area, null, 0, true, $search, $reg_type);
        $data['providers'] = $this->model->getAll($status, $filter_service_id, $city, $area, $perPage, $offset, false, $search, $reg_type);
>>>>>>> origin/main
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

<<<<<<< HEAD
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
=======
      $total             = $this->model->getByService($service_id, null, 0, true, $search);
      $data['providers'] = $this->model->getByService($service_id, $perPage, $offset, false, $search);
    }

    $pager = service('pager');
    $allowedParams = ['status', 'type', 'experience', 'service_id', 'city', 'area', 'search', 'limit', 'overdue', 'flow', 'reg_type'];
    $allGetKeys    = array_keys($this->request->getGet());
    $pagerParams   = array_diff(array_unique(array_merge($allowedParams, $allGetKeys)), ['page']);
    $pager->only($pagerParams);

    $data['pager']        = $pager;
    $data['pager_links']  = $pager->makeLinks($page, $perPage, $total, 'default_full');
    $data['total_items']  = $total;
    $data['per_page']     = $perPage;
    $data['current_page'] = $page;
    $data['reg_type']     = $reg_type;
>>>>>>> origin/main

    $data['title']             = '';
    $data['type']              = $status ?? $type ?? 'active';
    $data['experience']        = $experience;
    $data['filter_service_id'] = $filter_service_id;
    $data['city']              = $city;
    $data['area']              = $area;
<<<<<<< HEAD
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
=======
    $data['search']            = $search;
    $data['all_services']      = $this->model->getAllServices();
    $currentStatus = $status ?? $type ?? 'active';
    $isServiceWise = in_array($currentStatus, $perServiceStatuses);
    $data['all_cities']        = $this->model->getCitiesByStatus($currentStatus, $isServiceWise, $filter_service_id);
    $data['all_areas']         = !empty($city)
      ? $this->model->getAreasByCityAndStatus($city, $currentStatus, $isServiceWise, $filter_service_id)
      : $this->model->getAreasByStatus($currentStatus, $isServiceWise, $filter_service_id);
    $data['page']              = 'provider';

    $providerIds = [];
    if (!empty($data['providers'])) {
      foreach ($data['providers'] as $p) {
        $providerIds[] = is_object($p) ? $p->id : $p['id'];
      }
    }

    if (!empty($providerIds)) {
      $allVerifications  = $this->model->batchGetVerifications($providerIds);
      $allServiceDetails = $this->model->batchGetServiceDetails($providerIds);
      $allDevices        = $this->model->batchGetDevices($providerIds);
      $allCallAttempts   = $this->model->batchGetCallAttempts($providerIds);

      foreach ($data['providers'] as $provider) {
        $pid = is_object($provider) ? $provider->id : $provider['id'];
        $callAttemptData = $allCallAttempts[$pid] ?? null;
        $serviceId = is_object($provider) ? ($provider->service_id ?? null) : ($provider['service_id'] ?? null);

        $attemptCount = 0;
        if ($callAttemptData) {
          if ($serviceId && isset($callAttemptData['by_service'][$serviceId])) {
            $attemptCount = $callAttemptData['by_service'][$serviceId];
          } else {
            $attemptCount = $callAttemptData['total_attempts'] ?? 0;
          }
        }
        $provider->call_attempts_count  = $attemptCount;
        $provider->total_call_attempts  = $callAttemptData['total_attempts'] ?? 0;
        $provider->last_call_attempt_at = $callAttemptData['last_call_at'] ?? null;

        $provider->age     = ageCalculator($provider->dob);
        $verifications     = $allVerifications[$pid] ?? null;
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

        $totalVerifications     = count($verificationFields);
        $completedVerifications = 0;

        foreach ($verificationFields as $field) {
          if (!empty($verificationArray[$field])) {
            $completedVerifications++;
          }
        }

        $provider->completionPercentage = ($totalVerifications > 0)
          ? round(($completedVerifications / $totalVerifications) * 100, 1)
          : 0;

        $provider->verifications = $verifications;
        if (!isset($provider->serviceDetails) || empty($provider->serviceDetails)) {
          $provider->serviceDetails = $allServiceDetails[$pid] ?? [];
        }
        if (!isset($provider->latest_device) || empty($provider->latest_device)) {
          $provider->latest_device = $allDevices[$pid] ?? null;
        }

        if (isset($provider->service_id)) {
          $details = null;
          if ($provider->service_id == '4') $details = $provider->serviceDetails['walking'] ?? null;
          elseif ($provider->service_id == '3') $details = $provider->serviceDetails['training'] ?? null;
          elseif ($provider->service_id == '2') $details = $provider->serviceDetails['grooming'] ?? null;
          elseif ($provider->service_id == '1') $details = $provider->serviceDetails['boarding'] ?? null;
          elseif ($provider->service_id == '6') $details = $provider->serviceDetails['sitting'] ?? null;

          if ($details) {
            $provider->experience     = $details['experience'] ?? ($details->experience ?? '');
            $provider->available_time = $details['available_time'] ?? ($details->available_time ?? '');
            $provider->confidence     = $details['confidence'] ?? ($details->confidence ?? '');
          }
        }
      }
    }


    $user    = session()->get('user');
    $roleId  = 0;
    $adminId = session()->get('admin_id');

    if ($user) {
      $roleId = is_object($user) ? ($user->role_id ?? 0) : (is_array($user) ? ($user['role_id'] ?? 0) : 0);
      if (!$adminId) {
        $adminId = is_object($user) ? ($user->id ?? 1) : (is_array($user) ? ($user['id'] ?? 1) : 1);
      }
    } else {
      $roleId = session()->get('role_id') ?? 0;
      if (!$adminId) {
        $adminId = 1;
      }
    }

    if (hasRole(['verification_executive', 'super_admin', 'manager'], $user) || in_array($roleId, [1, 7, 10])) {
      $walks = $this->db->table('sp_walking_details')->where('verifier_id', $adminId)->countAllResults();
      $trains = $this->db->table('sp_training_details')->where('verifier_id', $adminId)->countAllResults();
      $grooms = $this->db->table('sp_grooming_details')->where('verifier_id', $adminId)->countAllResults();
      $boards = $this->db->table('sp_boarding_details')->where('verifier_id', $adminId)->countAllResults();
      $sits = $this->db->table('sp_sitting_details')->where('verifier_id', $adminId)->countAllResults();

      $callVerificationCount = $walks + $trains + $grooms + $boards + $sits;

      $walks_app = $this->db->table('sp_walking_details')
        ->join('sp_services', 'sp_services.provider_id = sp_walking_details.provider_id AND sp_services.service_id = 4')
        ->where('sp_walking_details.verifier_id', $adminId)
        ->whereIn('sp_services.status', ['approved', 'active'])
        ->where('sp_services.is_verified', 1)->countAllResults();
      $trains_app = $this->db->table('sp_training_details')
        ->join('sp_services', 'sp_services.provider_id = sp_training_details.provider_id AND sp_services.service_id = 3')
        ->where('sp_training_details.verifier_id', $adminId)
        ->whereIn('sp_services.status', ['approved', 'active'])
        ->where('sp_services.is_verified', 1)->countAllResults();
      $grooms_app = $this->db->table('sp_grooming_details')
        ->join('sp_services', 'sp_services.provider_id = sp_grooming_details.provider_id AND sp_services.service_id = 2')
        ->where('sp_grooming_details.verifier_id', $adminId)
        ->whereIn('sp_services.status', ['approved', 'active'])
        ->where('sp_services.is_verified', 1)->countAllResults();
      $boards_app = $this->db->table('sp_boarding_details')
        ->join('sp_services', 'sp_services.provider_id = sp_boarding_details.provider_id AND sp_services.service_id = 1')
        ->where('sp_boarding_details.verifier_id', $adminId)
        ->whereIn('sp_services.status', ['approved', 'active'])
        ->where('sp_services.is_verified', 1)->countAllResults();
      $sits_app = $this->db->table('sp_sitting_details')
        ->join('sp_services', 'sp_services.provider_id = sp_sitting_details.provider_id AND sp_services.service_id = 6')
        ->where('sp_sitting_details.verifier_id', $adminId)
        ->whereIn('sp_services.status', ['approved', 'active'])
        ->where('sp_services.is_verified', 1)->countAllResults();

      $managerApprovalCount = $walks_app + $trains_app + $grooms_app + $boards_app + $sits_app;

      $providers_query = $this->db->query("
          SELECT COUNT(DISTINCT provider_id) as total FROM (
              SELECT provider_id FROM sp_walking_details WHERE verifier_id = ?
              UNION
              SELECT provider_id FROM sp_training_details WHERE verifier_id = ?
              UNION
              SELECT provider_id FROM sp_grooming_details WHERE verifier_id = ?
              UNION
              SELECT provider_id FROM sp_boarding_details WHERE verifier_id = ?
              UNION
              SELECT provider_id FROM sp_sitting_details WHERE verifier_id = ?
          ) as temp
      ", [$adminId, $adminId, $adminId, $adminId, $adminId])->getRow();

      $data['call_verification_count'] = $callVerificationCount;
      $data['manager_approval_count']  = $managerApprovalCount;
      $data['total_sp_count']          = $providers_query->total ?? 0;
      $data['show_dashboard_counts']   = true;
    } else {
      $data['show_dashboard_counts'] = false;
    }

>>>>>>> origin/main
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

<<<<<<< HEAD
      // Filter organized questions
=======
      // Filter organized questions with deduplication across types
      $seenGroups = [];
      $seenNormalized = [];
>>>>>>> origin/main
      $filtered_organized = [];
      foreach ($organized as $cat) {
        $filtered_questions = [];
        foreach ($cat['questions'] as $q) {
          $q_type = $q['training_type'] ?? '';
<<<<<<< HEAD
          if (empty($q_type) || in_array($q_type, $selected_types)) {
            $filtered_questions[] = $q;
          }
=======
          if (!empty($q_type) && !in_array($q_type, $selected_types)) {
            continue;
          }

          $groupKey = !empty($q['group_key']) ? trim($q['group_key']) : '';
          $normText = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $q['question_text'] ?? '')));

          if ($groupKey !== '') {
            if (isset($seenGroups[$groupKey])) {
              continue;
            }
            $seenGroups[$groupKey] = true;
          } elseif ($normText !== '') {
            if (isset($seenNormalized[$normText])) {
              continue;
            }
            $seenNormalized[$normText] = true;
          }

          $filtered_questions[] = $q;
>>>>>>> origin/main
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

<<<<<<< HEAD
      // Filter flat questions
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (empty($q_type) || in_array($q_type, $selected_types)) {
          $filtered_flat[] = $q;
        }
=======
      // Filter flat questions with deduplication across types
      $seenGroupsFlat = [];
      $seenNormalizedFlat = [];
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (!empty($q_type) && !in_array($q_type, $selected_types)) {
          continue;
        }

        $groupKey = !empty($q['group_key']) ? trim($q['group_key']) : '';
        $normText = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $q['question_text'] ?? '')));

        if ($groupKey !== '') {
          if (isset($seenGroupsFlat[$groupKey])) {
            continue;
          }
          $seenGroupsFlat[$groupKey] = true;
        } elseif ($normText !== '') {
          if (isset($seenNormalizedFlat[$normText])) {
            continue;
          }
          $seenNormalizedFlat[$normText] = true;
        }

        $filtered_flat[] = $q;
>>>>>>> origin/main
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
<<<<<<< HEAD
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (empty($q_type) || in_array($q_type, $selected_types)) {
          $filtered_flat[] = $q;
        }
=======
      $seenGroupsFlat = [];
      $seenNormalizedFlat = [];
      $filtered_flat = [];
      foreach ($questions as $q) {
        $q_type = $q['training_type'] ?? '';
        if (!empty($q_type) && !in_array($q_type, $selected_types)) {
          continue;
        }

        $groupKey = !empty($q['group_key']) ? trim($q['group_key']) : '';
        $normText = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $q['question_text'] ?? '')));

        if ($groupKey !== '') {
          if (isset($seenGroupsFlat[$groupKey])) {
            continue;
          }
          $seenGroupsFlat[$groupKey] = true;
        } elseif ($normText !== '') {
          if (isset($seenNormalizedFlat[$normText])) {
            continue;
          }
          $seenNormalizedFlat[$normText] = true;
        }

        $filtered_flat[] = $q;
>>>>>>> origin/main
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
<<<<<<< HEAD
=======

      // Automatically queue audio recording into AI Call Intelligence pipeline
      // try {
      //   $adminUserId = session()->get('user')->id ?? 1;
      //   $empRole = $db->table('employees')->select('role_id')->where('id', $adminUserId)->get()->getRowArray();
      //   $isRegRole = (!empty($empRole['role_id']) && (int)$empRole['role_id'] === 13);
      //   $callType = $isRegRole ? 'registration' : 'verification';

      //   $recUrl = 'public/uploads/providers/call_records/' . $callRecordPath;
      //   $recModel = new \App\Models\CallRecordingModel();
      //   $jobModel = new \App\Models\CallJobModel();

      //   $recId = $recModel->insert([
      //     'provider_id'   => (int)$provider_id,
      //     'customer_id'   => null,
      //     'verifier_id'   => (int)$adminUserId,
      //     'call_type'     => $callType,
      //     'recording_url' => $recUrl,
      //     'duration'      => 0,
      //     'status'        => 'queued',
      //     'created_at'    => date('Y-m-d H:i:s'),
      //     'updated_at'    => date('Y-m-d H:i:s')
      //   ], true);

      //   if ($recId) {
      //     $jobModel->insert([
      //       'recording_id' => $recId,
      //       'status'       => 'queued',
      //       'retry_count'  => 0,
      //     ]);
      //   }
      // } catch (\Throwable $e) {
      //   log_message('error', 'Failed to queue call recording from submitServiceVerification: ' . $e->getMessage());
      // }
>>>>>>> origin/main
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
<<<<<<< HEAD

=======
    $currentUser = session()->get('user')->id ?? 0;

    if (($currentService && $currentService->status === 'approved')) {
      // Manager approval stage
      $serviceDetails['approver_id'] = $currentUser;
      $serviceDetails['approved_at'] = date('Y-m-d H:i:s');
      $serviceDetails['verified_at'] = date('Y-m-d H:i:s');
    } else {
      // Caller verification stage
      $serviceDetails['verifier_id'] = $currentUser;
      $serviceDetails['call_verified_at'] = date('Y-m-d H:i:s');
    }
>>>>>>> origin/main
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
<<<<<<< HEAD
=======

      // Log completed call verification record for executive payout calculation
      if ($currentUser > 0) {
        $empExists = $db->table('employees')->where('id', $currentUser)->countAllResults();
        if ($empExists > 0) {
          $detailRow = $db->table($detailTable)->where('provider_id', $provider_id)->get()->getRow();
          if ($detailRow) {
            $sourceId = $detailRow->id;
            $callDate = date('Y-m-d');
            $existingCallRecord = $db->table('executive_call_records')
              ->where([
                'source_table' => $detailTable,
                'source_id'    => $sourceId,
                'provider_id'  => $provider_id,
                'service_id'   => $service_id,
              ])->get()->getRow();

            if (!$existingCallRecord) {
              try {
                $db->table('executive_call_records')->insert([
                  'executive_id'  => $currentUser,
                  'provider_id'   => $provider_id,
                  'service_id'    => $service_id,
                  'source_table'  => $detailTable,
                  'source_id'     => $sourceId,
                  'call_date'      => $callDate,
                  'payout_status' => 'pending',
                  'rate'          => 0.00,
                  'created_at'    => date('Y-m-d H:i:s'),
                  'updated_at'    => date('Y-m-d H:i:s'),
                ]);
              } catch (\Throwable $e) {
                log_message('error', 'ProviderController call record logging error: ' . $e->getMessage());
              }
            }
          }
        }
      }
>>>>>>> origin/main
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
<<<<<<< HEAD
      $this->sendPartnerAgreementEmail($provider_id, $service_id);
=======
      // $this->sendPartnerAgreementEmail($provider_id, $service_id);
      $this->sendProviderNotification($provider_id, '🎉 Manager Approval Granted!', 'Congratulations! Your provider profile has received manager approval and is now live on Petsfolio.', 'manager_approved');
    } elseif ($db->transStatus() !== false && (!empty($updateData['call_verified']) || isset($serviceDetails['call_verified_at']))) {
      $this->sendProviderNotification($provider_id, '📞 Call Verification Approved!', 'Your call verification has been completed and approved by our team.', 'call_verified');
>>>>>>> origin/main
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
<<<<<<< HEAD
=======

      if ($result) {
        if ($status === 'suspended') {
          $this->sendProviderNotification($id, '⚠️ Account Suspended', 'Your provider account has been suspended by management. Please contact support if you have questions.', 'account_suspended');
        } elseif ($status === 'approved' || $status === 'active') {
          $this->sendProviderNotification($id, '🎉 Manager Approval Granted!', 'Congratulations! Your provider account is now fully approved and active on Petsfolio.', 'manager_approved');
        } elseif ($status === 'pending_verification' || $status === 'call_verified') {
          $this->sendProviderNotification($id, '📞 Call Verification Approved!', 'Your provider account call verification has been updated and approved.', 'call_verified');
        }
      }

>>>>>>> origin/main
      return $this->response->setJSON(['status' => $result ? true : false]);
    }

    if ($status == 'completed') {
      $this->model->logTransaction($provider_id, 'debit', $netAmount, 'Withdrawal amount', 'sp_wallet_histories');
      $this->model->debitProviderWallet($provider_id, $netAmount);
    }
    $this->model->updateStatus($id, $provider_id, $status);
<<<<<<< HEAD
    return $this->response->setJSON(['status' => true]);
  }
=======

    if ($status === 'suspended') {
      $this->sendProviderNotification($provider_id, '⚠️ Account Suspended', 'Your provider account has been suspended by management.', 'account_suspended');
    }

    return $this->response->setJSON(['status' => true]);
  }

  /**
   * Helper method to send FCM push notification and in-app DB notification to provider
   */
  private function sendProviderNotification($providerId, $title, $message, $type = 'account_update')
  {
    try {
      $db = \Config\Database::connect();
      $hasFcm = $db->fieldExists('fcm_token', 'service_providers');
      $tokenSelect = $hasFcm ? 'COALESCE(NULLIF(device_token, ""), fcm_token) as device_token' : 'device_token';

      $provider = $db->table('service_providers')
        ->select("id, name, phone, email, {$tokenSelect}")
        ->where('id', $providerId)
        ->get()->getRowArray();

      if (!$provider) return;

      // 1. Insert DB notification if table exists
      if ($db->tableExists('notifications')) {
        $db->table('notifications')->insert([
          'user_id'    => $providerId,
          'user_type'  => 'provider',
          // 'title'      => $title,
          'message'    => $message,
          'type'       => $type,
          'created_at' => date('Y-m-d H:i:s')
        ]);
      }

      // 2. Dispatch FCM push notification
      $deviceToken = $provider['device_token'] ?? null;
      if ($deviceToken && $deviceToken !== 'default_token') {
        $pushNotify = new \App\Libraries\PushNotify();
        $pushRes    = $pushNotify->notify($deviceToken, $title, $message);

        $isSent = ($pushRes['status_code'] ?? 0) === 200;
        $db->table('notification_logs')->insert([
          'provider_id'   => $providerId,
          'user_type'     => 'provider',
          'channel'       => 'push',
          'title'         => $title,
          'body'          => $message,
          'status'        => $isSent ? 'sent' : 'failed',
          'error_message' => $isSent ? null : ($pushRes['message'] ?? 'Push failed'),
          'device_token'  => $deviceToken,
          'sent_at'       => $isSent ? date('Y-m-d H:i:s') : null,
          'created_at'    => date('Y-m-d H:i:s'),
        ]);
      }
    } catch (\Throwable $e) {
      log_message('error', 'Provider Notification Error: ' . $e->getMessage());
    }
  }
>>>>>>> origin/main
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


<<<<<<< HEAD

  // Add this method to your controller
=======
>>>>>>> origin/main
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

<<<<<<< HEAD
    $builder = $db->table('notification_queue n')
      ->select('n.*, sp.name as provider_name, sp.phone as provider_phone, COALESCE(n.provider_id, sp.id) as provider_id')
=======
    $hasProviderId = $db->fieldExists('provider_id', 'notification_queue');
    $providerCol   = $hasProviderId ? 'COALESCE(n.provider_id, sp.id) as provider_id' : 'sp.id as provider_id';

    $builder = $db->table('notification_queue n')
      ->select("n.*, sp.name as provider_name, sp.phone as provider_phone, {$providerCol}")
>>>>>>> origin/main
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
<<<<<<< HEAD
    $newId = $db->table('notification_queue')->insert([
=======
    $queueData = [
>>>>>>> origin/main
      'tokens'       => $notification->tokens,
      'title'        => $notification->title,
      'message'      => $notification->message,
      'priority'     => $notification->priority,
      'status'       => 'pending',
      'scheduled_at' => date('Y-m-d H:i:s'),
      'attempts'     => 0,
<<<<<<< HEAD
      'route_name'   => $notification->route_name,
      'provider_id'  => $notification->provider_id,
      'booking_id'   => $notification->booking_id,
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s')
    ]);
=======
      'route_name'   => $notification->route_name ?? null,
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s')
    ];

    if ($db->fieldExists('provider_id', 'notification_queue') && isset($notification->provider_id)) {
      $queueData['provider_id'] = $notification->provider_id;
    }
    if ($db->fieldExists('booking_id', 'notification_queue') && isset($notification->booking_id)) {
      $queueData['booking_id'] = $notification->booking_id;
    }

    $newId = $db->table('notification_queue')->insert($queueData);
>>>>>>> origin/main

    if ($newId) {
      return $this->response->setJSON(['status' => true, 'message' => 'Notification queued for resending']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to queue notification']);
    }
  }

  public function downloadProviders()
  {
<<<<<<< HEAD
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
=======
    $status            = $this->request->getVar('status');
    $type              = $this->request->getVar('type');
    $experience        = $this->request->getVar('experience');
    $filter_service_id = $this->request->getVar('service_id');
    $city              = $this->request->getVar('city');
    $area              = $this->request->getVar('area');
    $search            = $this->request->getVar('search');
    $reg_type          = $this->request->getVar('reg_type');
    $overdue           = $this->request->getVar('overdue') === '1' || $this->request->getVar('overdue') === 1;
    $idsRaw            = $this->request->getVar('ids');

    $selectedIds = [];
    if (!empty($idsRaw)) {
      if (is_array($idsRaw)) {
        $selectedIds = array_values(array_filter(array_map('intval', $idsRaw)));
      } elseif (is_string($idsRaw)) {
        $decoded = json_decode($idsRaw, true);
        if (is_array($decoded)) {
          $selectedIds = array_values(array_filter(array_map('intval', $decoded)));
        } else {
          $selectedIds = array_values(array_filter(array_map('intval', explode(',', $idsRaw))));
        }
      }
    }

    if (empty($status) && empty($type)) {
      if (hasPermission('providers.view') || hasPermission('verified.view')) {
        $status = 'active';
      } elseif (hasPermission('fee_pending.view')) {
        $status = 'pending_registration';
      } elseif (hasPermission('wait_for_call.view') || hasPermission('wait_for_call_24.view')) {
        $status = 'pending_verification';
      } elseif (hasPermission('manager_approval.view')) {
        $status = 'approval';
      } elseif (hasPermission('kyc_pending.view')) {
        $status = 'pending_kyc';
      } elseif (hasPermission('old_non_verified.view')) {
        $status = 'non_verified';
      } elseif (hasPermission('suspended.view')) {
        $status = 'inactive';
      } else {
        $status = 'active';
      }
    }

    if (empty($status) && !empty($type) && !in_array($type, ['walkers', 'groomers', 'trainers', 'boarders', 'sitters'])) {
      $status = $type;
    }

    $perServiceStatuses = ['pending_registration', 'pending_verification', 'approval', 'active', 'non_verified', 'pending_kyc', 'rejected', 'inactive'];

    if ($status && in_array($status, $perServiceStatuses)) {
      $providers = $this->model->getServiceWiseProviders($status, $experience, $filter_service_id, $city, $area, $overdue, null, 0, false, $search, $reg_type, $selectedIds);
    } elseif ($status) {
      $providers = $this->model->getAll($status, $filter_service_id, $city, $area, null, 0, false, $search, $reg_type, $selectedIds);
    } else {
      switch ($type) {
        case 'walkers':  $service_id = 4; break;
        case 'groomers': $service_id = 2; break;
        case 'trainers': $service_id = 3; break;
        case 'sitters':  $service_id = 6; break;
        case 'boarders': $service_id = 1; break;
        default:         $service_id = 1;
      }
      $providers = $this->model->getByService($service_id, null, 0, false, $search, $selectedIds);
    }

    $filename = "providers_" . ($status ?? $type ?? 'all') . "_" . date('Ymd_His') . ".csv";

    if (ob_get_level() > 0) {
      ob_end_clean();
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

    if ($status === 'pending_verification' && !empty($providers)) {
      $pids = [];
      foreach ($providers as $p) {
        $pids[] = is_object($p) ? $p->id : $p['id'];
      }
      $callAttempts = $this->model->batchGetCallAttempts($pids);
      foreach ($providers as $p) {
        $pid = is_object($p) ? $p->id : $p['id'];
        $srv = is_object($p) ? ($p->service_id ?? null) : ($p['service_id'] ?? null);
        $att = 0;
        if (isset($callAttempts[$pid])) {
          if ($srv && isset($callAttempts[$pid]['by_service'][$srv])) {
            $att = $callAttempts[$pid]['by_service'][$srv];
          } else {
            $att = $callAttempts[$pid]['total_attempts'] ?? 0;
          }
        }
        if (is_object($p)) {
          $p->call_attempts_count = $att;
        } else {
          $p['call_attempts_count'] = $att;
        }
      }
    }

    $csvHeaders = [
      'ID', 'Name', 'Phone', 'Email', 'Gender', 'Services',
      'Experience', 'City', 'Area', 'State', 'Service Address', 'Joined At'
    ];
    if ($status === 'pending_verification') {
      $csvHeaders[] = 'Call Attempts';
    }

    fputcsv($output, $csvHeaders);

    foreach ($providers as $provider) {
      $p = is_array($provider) ? (object) $provider : $provider;

      $services = $p->service_name ?? '';
      if (empty($services) && !empty($p->serviceDetails) && is_array($p->serviceDetails)) {
        $services = implode(', ', array_keys($p->serviceDetails));
      }

      $exp = '';
      if (isset($p->experience) && $p->experience !== '' && $p->experience !== null) {
        $exp = strpos((string)$p->experience, 'Y') !== false ? (string)$p->experience : $p->experience . ' Yrs';
      }

      $row = [
        $p->id ?? '',
        $p->name ?? '',
        $p->phone ?? '',
        $p->email ?? '',
        $p->gender ?? '',
        $services,
        $exp,
        $p->city ?? '',
        $p->area ?? '',
        $p->state ?? '',
        $p->service_address ?? '',
        $p->created_at ?? ''
      ];
      if ($status === 'pending_verification') {
        $row[] = (int) ($p->call_attempts_count ?? 0);
      }

      fputcsv($output, $row);
>>>>>>> origin/main
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
<<<<<<< HEAD

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

=======
>>>>>>> origin/main
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
<<<<<<< HEAD
=======

  public function serviceMedia()
  {
    $status      = $this->request->getGet('status');
    $media_type  = $this->request->getGet('media_type');
    $search      = $this->request->getGet('search');
    $provider_id = $this->request->getGet('provider_id');

    $perPage = (int) ($this->request->getGet('limit') ?? 24);
    if (!in_array($perPage, [12, 24, 48, 96, 500])) {
      $perPage = 24;
    }
    $page = (int) ($this->request->getGet('page') ?? 1);
    if ($page < 1) {
      $page = 1;
    }
    $offset = ($page - 1) * $perPage;

    $total  = $this->model->getServiceMedia($status === 'all' ? null : $status, $media_type === 'all' ? null : $media_type, $provider_id, $search, null, 0, true);
    $media  = $this->model->getServiceMedia($status === 'all' ? null : $status, $media_type === 'all' ? null : $media_type, $provider_id, $search, $perPage, $offset, false);
    $counts = $this->model->getServiceMediaCounts();

    foreach ($media as &$item) {
      $item->file_url = ('https://api.petsfolio.in/public/uploads/providers_service/' . $item->file_name);
      $ext = strtolower(pathinfo($item->file_name, PATHINFO_EXTENSION));
      if (in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm', '3gp'])) {
        $item->is_video = true;
      } else {
        $item->is_video = ($item->file_type === 'video' || $item->file_type === 'recording_video');
      }
    }

    $pager = service('pager');
    $pager->only(['status', 'media_type', 'search', 'limit', 'provider_id']);
    $data['pager']        = $pager;
    $data['pager_links']  = $pager->makeLinks($page, $perPage, $total, 'default_full');
    $data['total_items']  = $total;
    $data['per_page']     = $perPage;
    $data['current_page'] = $page;
    $data['media']        = $media;
    $data['counts']       = $counts;
    $data['status']       = $status ?? 'all';
    $data['media_type']   = $media_type ?? 'all';
    $data['search']       = $search;
    $data['provider_id']  = $provider_id;
    $data['title']        = 'Service Media Approval';
    $data['page']         = 'provider/service_media';

    return $this->loadView('admin/provider/service_media', $data);
  }

  public function updateServiceMediaStatus()
  {
    $media_ids = $this->request->getPost('media_ids');
    $status    = $this->request->getPost('status');

    if (empty($media_ids) || empty($status)) {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON(['status' => false, 'message' => 'Invalid parameters']);
      }
      return redirect()->back()->with('error', 'Invalid parameters');
    }

    $result = $this->model->updateServiceMediaStatus($media_ids, $status);

    if ($result) {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON(['status' => true, 'message' => 'Status updated successfully']);
      }
      return redirect()->back()->with('success', 'Service media status updated successfully');
    } else {
      if ($this->request->isAJAX()) {
        return $this->response->setJSON(['status' => false, 'message' => 'Failed to update status']);
      }
      return redirect()->back()->with('error', 'Failed to update service media status');
    }
  }
>>>>>>> origin/main
}
