<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Libraries\PushNotify;
use App\Models\ServiceModel;

class ServiceController extends BaseController
{
  protected $serviceModel;
  public function __construct()
  {
    $this->serviceModel = new ServiceModel();
  }
  public function index()
  {
    $data['title']    = 'Services';
    $data['services'] = $this->serviceModel->getServices();
    return $this->loadView('admin/services/services', $data);
  }
  public function packages()
  {
    $data['title']    = 'Packages';
    $data['packages'] = $this->serviceModel->getPackages();
    return $this->loadView('admin/services/packages', $data);
  }
  public function walkingAddons()
  {
    $type = $this->request->getGet('type');

    if ($type == 'grooming') {
      $data['service_id'] = '2';
    } else if ($type == 'walking') {
      $data['service_id'] = '4';
    } else if ($type == 'boarding') {
      $data['service_id'] = '1';
    } else {
      $data['service_id'] = '3';
    }

    $data['type']   = $type;
    $data['title']  = '';
    $data['addons'] = $this->serviceModel->getServiceAddons($data['service_id']);
    return $this->loadView('admin/services/walking-addons', $data);
  }
  public function walkingPackages()
  {
    $type = $this->request->getGet('type');

    if ($type == 'grooming') {
      $data['service_id'] = '2';
    } else if ($type == 'walking') {
      $data['service_id'] = '4';
    } else if ($type == 'boarding') {
      $data['service_id'] = '1';
    } else {
      $data['service_id'] = '3';
    }

    $data['type'] = $type;

    $data['title']    = '';
    $data['packages'] = $this->serviceModel->getServicePackages($data['service_id']);
    return $this->loadView('admin/services/walking-packages', $data);
  }
  public function updateAddon()
  {

    $id     = $this->request->getPost('addonId');
    $name   = $this->request->getPost('addonName');
    $price  = $this->request->getPost('addonPrice');
    $status = $this->request->getPost('status');
    $result = $this->serviceModel->updateAddon($id, $name, $price, $status);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Addon updated successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to update addon']);
    }
  }
  public function updatePackage()
  {

    $id       = $this->request->getPost('packageId');
    $name     = $this->request->getPost('packageName');
    $price    = $this->request->getPost('price');
    $duration = $this->request->getPost('duration');
    $status   = $this->request->getPost('status');
    $addons   = ($this->request->getPost('addons[]'));
    $addons   = !empty($addons) ? implode(',', $addons) : "[]";
    $result   = $this->serviceModel->updatePackage($id, $name, $price, $status, $addons);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Addon updated successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to update addon']);
    }
  }
  public function addAddon()
  {

    $data = [
      'service_id'              => $this->request->getPost('service_id'),
      'name'                    => $this->request->getPost('addonName'),
      'price'                   => $this->request->getPost('addonPrice'),
      'status'                  => $this->request->getPost('status'),
      'is_included_in_packages' => $this->request->getPost('is_included') ?? 0,
    ];

    $addonId = $this->serviceModel->addAddon($data);

    if ($addonId) {
      $addon = [
        'addon_id'    => $addonId,
        'package_id'  => $this->request->getPost('package_id'),
        'is_included' => $this->request->getPost('is_included') ?? 0,
      ];
      $this->serviceModel->addPackageAddons($addon);
      return $this->response->setJSON(['status' => true, 'message' => 'Addon created successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to create addon']);
    }
  }
  public function addPackage()
  {
    $service_id = $this->request->getPost('service_id');
    $name       = $this->request->getPost('packageName');
    $price      = $this->request->getPost('price');
    $duration   = $this->request->getPost('duration');
    $addons     = ($this->request->getPost('addons[]'));
    $addons     = !empty($addons) ? implode(',', $addons) : "[]";
    $status     = $this->request->getPost('status');
    $result     = $this->serviceModel->addPackage($service_id, $name, $price, $duration, $status, $addons);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Package created successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to create Package']);
    }
  }
  public function updateStatus()
  {
    $id      = $this->request->getPost('id');
    $status  = $this->request->getPost('status');
    $module  = $this->request->getPost('module');
    $queryId = $this->request->getPost('queryId');
   if($module=='service_providers'){
      $check = $this->serviceModel->checkProviderJobStatus($id);
      if($check && $status=='inactive'){
        return $this->response->setJSON(['status' => false, 'message' => 'This service provider is currently assigned to an active job']);
      }
    }
    $result = $this->serviceModel->updateStatus($id, $status, $module);
    if ($queryId) {
      $this->serviceModel->updateQueryStatus($queryId);
    }
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => ' Status updated successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => ' Failed to update status']);
    }
  }
  public function updateQueryStatus($queryId)
  {
    if ($queryId) {
      $this->serviceModel->updateQueryStatus($queryId);
      return $this->response->setJSON(['status' => true, 'message' => ' Status updated successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => ' Failed to update status']);
    }
  }
  public function deleteAddon()
  {
    $id     = $this->request->getPost('id');
    $result = $this->serviceModel->deleteAddon($id);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Addon deleted successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to delete addon']);
    }
  }
  public function deletePackage()
  {
    $id     = $this->request->getPost('id');
    $result = $this->serviceModel->deletePackage($id);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Package deleted successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to delete package']);
    }
  }
  public function sendNotification()
  {
    $ids     = $this->request->getPost('id');
    $title   = $this->request->getPost('title');
    $message = $this->request->getPost('message');
    $module  = $this->request->getPost('module');

    if (empty($ids) || !$title || !$message) {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Invalid data provided'
      ]);
    }

    $notify       = new PushNotify();
    $errors       = [];
    $successCount = 0;
    $tokens       = [];

    if (is_string($ids)) {
      $decodedIds = json_decode($ids, true);

      if (json_last_error() === JSON_ERROR_NONE) {
        $ids = $decodedIds;
      } else {
        $ids = is_numeric($ids) ? [$ids] : explode(',', $ids);
      }
    }

    if (!is_array($ids)) {

      $ids = [$ids];
    }

    foreach ($ids as $id) {
      $result = $this->serviceModel->getUser($id, $module);

      if ($result && !empty($result->device_token)) {
        $tokens[] = $result->device_token;
      } else {
        $errors[] = "User ID $id not found";
      }
    }

    if (!empty($tokens)) {
      if (count($tokens) === 1) {

        $response     = $notify->notify($tokens[0], $title, $message);
        $successCount = 1;
      } else {

        $response     = $notify->notifyMultipleParallel($tokens, $title, $message);
        $successCount = count($tokens);
      }
    }

    return $this->response->setJSON([
      'status'   => $successCount > 0,
      'message'  => "$successCount notification(s) sent successfully",
      'errors'   => $errors,
      'response' => $response
    ]);
  }



  public function getUser()
  {
    $id     = $this->request->getPost('id');
    $module = $this->request->getPost('module');
    $result = $this->serviceModel->getUser($id, $module);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'data' => $result]);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'User not found']);
    }
  }

  public function updateUser()
  {
    $id     = $this->request->getPost('id');
    $module = $this->request->getPost('module');

    $data = [
      'name'   => $this->request->getPost('name'),
      'email'  => $this->request->getPost('email'),
      'phone'  => $this->request->getPost('phone'),
      'gender' => $this->request->getPost('gender'),
      'dob'    => $this->request->getPost('dob'),
      'module' => $this->request->getPost('module')
    ];

    // $profileImage = $this->request->getFile('profile_image');
    // if ($profileImage && $profileImage->isValid()) {
    //   if (!$profileImage->hasMoved() && $profileImage->isValid() && $profileImage->getSize() <= 2048000) {
    //     $newName = $profileImage->getRandomName();
    //     $uploadPath = getenv('apiUrl') . 'public/uploads/providers';

    //     if ($profileImage->move($uploadPath, $newName)) {
    //       $data['profile'] = $newName;
    //     } else {
    //       return $this->response->setJSON([
    //         'status' => false,
    //         'message' => 'Failed to upload profile image'
    //       ]);
    //     }
    //   } else {
    //     return $this->response->setJSON([
    //       'status' => false,
    //       'message' => 'Invalid profile image file'
    //     ]);
    //   }
    // }

    if ($module === 'service_providers') {
      $status = $this->request->getPost('status') === 'on' ? 'active' : 'inactive';
    } elseif ($module === 'users') {
      $status = $this->request->getPost('status') === 'on' ? 'active' : 'inactive';
    } else {
      return $this->response->setJSON([
        'status'  => false,
        'message' => 'Invalid module type.'
      ]);
    }
    $data['status'] = $status;
    $result         = $this->serviceModel->updateUser($id, $data);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'data' => $data]);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'User not found']);
    }
  }
  public function deleteUser()
  {
    $id     = $this->request->getPost('id');
    $module = $this->request->getPost('module');
    $result = $this->serviceModel->getUser($id, $module);
    if ($result) {
      $this->serviceModel->deleteUser($id, $module);
      return $this->response->setJSON(['status' => true, 'data' => $result]);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'User not found']);
    }
  }
  public function download()
  {
    $selectedIds = json_decode($this->request->getVar('ids')) ?? [];
    $status = $this->request->getVar('status');

    if (!empty($selectedIds)) {
      $data = $this->serviceModel->getAllUsers($selectedIds, $status);
    } else {
      $data = $this->serviceModel->getUsers();
    }

    $header = ['id', 'name', 'number', 'email', 'location', 'pets'];

    $this->response->setHeader('Content-Type', 'text/csv');
    $this->response->setHeader('Content-Disposition', 'attachment; filename="clients.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, $header);

    foreach ($data as $row) {
      $row['location'] = $row['addresses'];

      $row['pets'] = $row['pets'] ?? '';

      fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['phone'],
        $row['email'],
        $row['location'],
        $row['pets']
      ]);
    }
    fclose($output);
  }

  public function downloadQuotes()
  {
    $booking_id = $this->request->getGet('id');
    $data       = $this->serviceModel->getWalkingQuotes($booking_id);

    // Define CSV file header
    $header = ['id', 'name', 'number', 'area', 'service'];
    // Set headers for the CSV download
    $this->response->setHeader('Content-Type', 'text/csv');
    $this->response->setHeader('Content-Disposition', 'attachment; filename="bookingQuotations.csv"');

    // Open output stream for writing the CSV data
    $output = fopen('php://output', 'w');

    // Write the CSV header
    fputcsv($output, $header);

    // Write the data to the CSV
    foreach ($data as $row) {
      // Modify the location field to include addresses with new lines
      $row['service'] = 'walking';


      // Write the row to the CSV
      fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
  }

  public function downloadProviders()
  {
    $selectedIds = json_decode($this->request->getVar('ids')) ?? [];
    $status = $this->request->getVar('status');

    if (!empty($selectedIds)) {
      $data = $this->serviceModel->getAllProviders($selectedIds, $status);
    } else {
      $data = $this->serviceModel->getProviders();
    }

    // Define CSV file header
    $header = ['name', 'number', 'area', 'city', 'service'];

    // Set headers for the CSV download
    $this->response->setHeader('Content-Type', 'text/csv');
    $this->response->setHeader('Content-Disposition', 'attachment; filename="sp.csv"');
    $this->response->setHeader('Pragma', 'no-cache');
    $this->response->setHeader('Expires', '0');

    // Disable CI output buffering
    ob_end_clean();

    // Open output stream for writing the CSV data
    $output = fopen('php://output', 'w');

    // Write the CSV header
    fputcsv($output, $header);

    // Write the data to the CSV
    foreach ($data as $row) {
      // Add/modify fields as needed
      $row['service'] = 'walking';

      // Ensure order matches header
      // $csvRow = [
      //     $row['name'] ?? '',
      //     $row['number'] ?? '',
      //     $row['area'] ?? '',
      //     $row['city'] ?? '',
      //     $row['service'] ?? ''
      // ];

      fputcsv($output, $row);
    }

    fclose($output);

    // Tell CI that this is the complete response
    return $this->response;
  }

  public function banners()
  {
    $data['title'] = 'Banners';
    $data['banners'] = $this->serviceModel->getBanners();
    $data['services'] = $this->serviceModel->getServices();
    $data['available_screens'] = [
        'all'         => ['label' => 'All Screens', 'icon' => '📱', 'color' => 'primary'],
        'home'        => ['label' => 'Home Screen', 'icon' => '🏠', 'color' => 'info'],
        'boarding'    => ['label' => 'Boarding',    'icon' => '🏨', 'color' => 'secondary'],
        'grooming'    => ['label' => 'Grooming',    'icon' => '✂️', 'color' => 'success'],
        'training'    => ['label' => 'Training',    'icon' => '🎓', 'color' => 'warning'],
        'walking'     => ['label' => 'Walking',     'icon' => '🦮', 'color' => 'danger'],
        'vet_at_home' => ['label' => 'Vet @ Home',  'icon' => '🩺', 'color' => 'teal'],
        'sitting'     => ['label' => 'Sitting',     'icon' => '🛋️', 'color' => 'dark'],
        'community'   => ['label' => 'Community',   'icon' => '👥', 'color' => 'purple'],
        'bookings'    => ['label' => 'Bookings',    'icon' => '📅', 'color' => 'orange'],
        'profile'     => ['label' => 'Profile',     'icon' => '👤', 'color' => 'indigo'],
    ];

    return $this->loadView('admin/banners', $data);
  }

  public function getBannerDetails($id = null)
  {
      if (!$id) {
          $id = $this->request->getVar('id');
      }
      $banner = $this->serviceModel->getBanner($id);
      if ($banner) {
          return $this->response->setJSON([
              'status'     => true,
              'data'       => $banner,
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash()
          ]);
      }
      return $this->response->setJSON([
          'status'     => false,
          'message'    => 'Banner not found',
          'csrf_token' => csrf_token(),
          'csrf_hash'  => csrf_hash()
      ]);
  }

  public function addBanner()
  {
      $id = $this->request->getPost('id');
      $isEdit = !empty($id) && is_numeric($id);
      $existing = $isEdit ? $this->serviceModel->getBanner($id) : null;

      if ($isEdit && !$existing) {
          return $this->response->setJSON([
              'status'     => false,
              'message'    => 'Banner record to update was not found',
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash()
          ]);
      }

      $uploadDir = FCPATH . 'public/uploads/banners/';
      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
      }

      // 1. Process Media Type
      $mediaType = $this->request->getPost('media_type');
      if (!in_array($mediaType, ['image', 'video'])) {
          $mediaType = 'image';
      }

      // 2. Process Screen Selection
      $screensInput = $this->request->getPost('screens');
      if (empty($screensInput)) {
          $screensInput = $this->request->getPost('screen');
      }
      $screenList = [];
      if (is_array($screensInput)) {
          $screenList = array_values(array_unique(array_filter(array_map('trim', $screensInput))));
      } elseif (is_string($screensInput) && trim($screensInput) !== '') {
          $screenList = array_values(array_unique(array_filter(array_map('trim', explode(',', $screensInput)))));
      }
      if (empty($screenList) || in_array('all', $screenList)) {
          $screenStr = 'all';
      } else {
          $screenStr = implode(',', $screenList);
      }

      // 3. Process Image Upload (Feature Image / Poster)
      $imageFile = $this->request->getFile('feature_image');
      if (!$imageFile || !$imageFile->isValid()) {
          $imageFile = $this->request->getFile('banner_image');
      }

      $imageName = $existing ? ($existing->banner_image ?? $existing->image ?? null) : null;
      if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
          $newImgName = $imageFile->getRandomName();
          $imageFile->move($uploadDir, $newImgName);
          // Delete old image file if updating
          if ($existing) {
              $oldFiles = array_filter([$existing->image ?? null, $existing->banner_image ?? null]);
              foreach (array_unique($oldFiles) as $oldF) {
                  if ($oldF && file_exists($uploadDir . $oldF) && !is_dir($uploadDir . $oldF)) {
                      @unlink($uploadDir . $oldF);
                  }
              }
          }
          $imageName = $newImgName;
      }

      // 4. Process Video Upload
      $videoFile = $this->request->getFile('banner_video');
      $videoName = $existing ? ($existing->banner_video ?? null) : null;
      if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
          $newVideoName = $videoFile->getRandomName();
          $videoFile->move($uploadDir, $newVideoName);
          // Delete old video file if updating
          if ($existing && !empty($existing->banner_video)) {
              if (file_exists($uploadDir . $existing->banner_video) && !is_dir($uploadDir . $existing->banner_video)) {
                  @unlink($uploadDir . $existing->banner_video);
              }
          }
          $videoName = $newVideoName;
      }

      // 5. Process Popup Image Upload
      $popupFile = $this->request->getFile('popup_image');
      $popupImgName = $existing ? ($existing->popup_image ?? null) : null;
      if ($popupFile && $popupFile->isValid() && !$popupFile->hasMoved()) {
          $newPopupName = $popupFile->getRandomName();
          $popupFile->move($uploadDir, $newPopupName);
          if ($existing && !empty($existing->popup_image)) {
              if (file_exists($uploadDir . $existing->popup_image) && !is_dir($uploadDir . $existing->popup_image)) {
                  @unlink($uploadDir . $existing->popup_image);
              }
          }
          $popupImgName = $newPopupName;
      }

      // 6. Assemble Data
      $showPopup = $this->request->getPost('show_popup');
      $showPopupVal = ($showPopup === '1' || $showPopup === 1 || $showPopup === 'on' || $showPopup === 'true') ? 1 : 0;

      $data = [
          'name'         => $this->request->getPost('name') ?? '',
          'url'          => $this->request->getPost('url') ?? '',
          'type'         => $this->request->getPost('type') ?? '',
          'link_id'      => $this->request->getPost('link_id') ?? '',
          'media_type'   => $mediaType,
          'video_url'    => $this->request->getPost('video_url') ?? null,
          'screen'       => $screenStr,
          'banner_video' => $videoName,
          'image'        => $imageName,
          'banner_image' => $imageName,
          'show_popup'   => $showPopupVal,
          'popup_image'  => $popupImgName,
          'popup_link'   => $this->request->getPost('popup_link') ?? null,
          'banner_type'  => $this->request->getPost('banner_type') ?? ($existing->banner_type ?? 'promo'),
      ];

      if ($isEdit) {
          $result = $this->serviceModel->updateBanner($id, $data);
          $message = 'Banner updated successfully';
      } else {
          $data['is_active'] = 1;
          $result = $this->serviceModel->addBanner($data);
          $message = 'Banner added successfully';
      }

      if ($result) {
          return $this->response->setJSON([
              'status'     => true,
              'message'    => $message,
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash(),
          ]);
      } else {
          return $this->response->setJSON([
              'status'     => false,
              'message'    => $isEdit ? 'Failed to update banner' : 'Failed to add banner',
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash(),
          ]);
      }
  }

  public function deleteBanner()
  {
      $id = $this->request->getVar('id');
      if (!$id) {
          return $this->response->setJSON([
              'status'     => false,
              'message'    => 'Banner ID is required',
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash()
          ]);
      }

      $banner = $this->serviceModel->getBanner($id);
      if ($banner) {
          $uploadDir = FCPATH . 'public/uploads/banners/';
          $filesToDelete = array_filter([
              $banner->image ?? null,
              $banner->banner_image ?? null,
              $banner->banner_video ?? null,
              $banner->popup_image ?? null,
          ]);

          foreach (array_unique($filesToDelete) as $f) {
              if ($f && file_exists($uploadDir . $f) && !is_dir($uploadDir . $f)) {
                  @unlink($uploadDir . $f);
              }
          }

          $result = $this->serviceModel->deleteBanner($id);
          if ($result) {
              return $this->response->setJSON([
                  'status'     => true,
                  'message'    => 'Banner deleted successfully',
                  'csrf_token' => csrf_token(),
                  'csrf_hash'  => csrf_hash()
              ]);
          }
      }

      return $this->response->setJSON([
          'status'     => false,
          'message'    => 'Failed to delete banner or record not found',
          'csrf_token' => csrf_token(),
          'csrf_hash'  => csrf_hash()
      ]);
  }

  public function updateBannerStatus()
  {
      $id = $this->request->getPost('id');
      $status = $this->request->getPost('status');

      $result = $this->serviceModel->updateBannerStatus($id, $status);
      if ($result) {
          return $this->response->setJSON([
              'status'     => true,
              'message'    => 'Status updated successfully',
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash()
          ]);
      } else {
          return $this->response->setJSON([
              'status'     => false,
              'message'    => 'Failed to update status',
              'csrf_token' => csrf_token(),
              'csrf_hash'  => csrf_hash()
          ]);
      }
  }
  public function events()
  {
    try {
      $url = 'https://www.petsfolio.com/in/test.php';
      $ch  = curl_init($url);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

      $response = curl_exec($ch);

      if (curl_errno($ch)) {
        throw new Exception('cURL error: ' . curl_error($ch));
      }

      curl_close($ch);

      $data = json_decode($response);
      if ($data === null) {
        throw new Exception("Failed to decode JSON response.");
      }
      foreach ($data as $post) {

        $post->post_content = strip_tags($post->post_content);

        $post->post_url = 'https://www.petsfolio.com/in/events/' . $post->post_name;
        $content        = preg_replace(['/(\xC2\xA0)/', '/&amp;/'], [' ', ' & '], $post->post_content);

        preg_match('/Address\s*:-\s*(.*?)\r\n/', $content, $addressMatch);
        $post->address = isset($addressMatch[1]) ? trim($addressMatch[1]) : '';

        if (preg_match('/Date(?:\s*&\s*time)?\s*:-\s*(.*?)(?:\r\n|\n|$)/i', $content, $dateMatch)) {
          $post->dateTime = isset($dateMatch[1]) ? trim($dateMatch[1]) : '';
        } else {
          $post->dateTime = '';
        }

        preg_match('/website\s*:-\s*(https?:\/\/[^\s\r\n]+)/i', $content, $websiteMatch);
        $post->website = isset($websiteMatch[1]) ? trim($websiteMatch[1]) : '';
      }
      $data['events'] = array_values($data);
      // $data['users'] = [];

      return $this->loadView('admin/events', $data);
    } catch (Exception $e) {
      return $this->response->setStatusCode(500)->setJSON('Error fetching events: ' . $e->getMessage());
    }
  }
}
