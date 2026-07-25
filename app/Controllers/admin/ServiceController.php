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
    if ($module == 'service_providers') {
      $check = $this->serviceModel->checkProviderJobStatus($id);
      if ($check && $status == 'inactive') {
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

    return $this->loadView('admin/banners', $data);
  }

  public function addBanner()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setJSON([
        'status' => false,
        'message' => 'Invalid request'
      ]);
    }

    $id = $this->request->getPost('id');
    $uploadPath = ROOTPATH . 'public/uploads/banners/';

    // Create folder if not exists
    if (!is_dir($uploadPath)) {
      mkdir($uploadPath, 0777, true);
    }

    $data = [
      'show_popup' => $this->request->getPost('show_popup'),
      'popup_link' => $this->request->getPost('popup_link'),
    ];

    $bannerImage = $this->request->getFile('banner_image');
    if ($bannerImage && $bannerImage->isValid() && !$bannerImage->hasMoved()) {
      $bannerImageName = $bannerImage->getRandomName();
      $bannerImage->move($uploadPath, $bannerImageName);
      $data['banner_image'] = $bannerImageName;
    }

    // Upload Popup Image
    $popupImage = $this->request->getFile('popup_image');
    if ($popupImage && $popupImage->isValid() && !$popupImage->hasMoved()) {
      $popupImageName = $popupImage->getRandomName();
      $popupImage->move($uploadPath, $popupImageName);
      $data['popup_image'] = $popupImageName;
    }

    $db = \Config\Database::connect();

    if ($id) {
      $db->table('banners')->where('id', $id)->update($data);
      $status = true; // update usually returns true if no error, even if 0 rows affected
      $message = 'Banner updated successfully';
    } else {
      $data['is_active'] = 1;
      $data['created_at'] = date('Y-m-d H:i:s');
      $db->table('banners')->insert($data);
      $status = $db->affectedRows() > 0;
      $message = $status ? 'Banner created successfully' : 'Failed to create banner';
    }

    return $this->response->setJSON([
      'status' => $status,
      'message' => $message
    ]);
  }

  public function deleteBanner()
  {
    $id = $this->request->getPost('id');

    $id = $this->request->getVar('id');
    $result = $this->serviceModel->deleteBanner($id);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Banner deleted successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to delete banner']);
    }
  }

  public function updateBannerStatus()
  {
    $id = $this->request->getPost('id');
    $status = $this->request->getPost('status');
    $result = $this->serviceModel->updateBannerStatus($id, $status);
    if ($result) {
      return $this->response->setJSON(['status' => true, 'message' => 'Status updated successfully']);
    } else {
      return $this->response->setJSON(['status' => false, 'message' => 'Failed to update status']);
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
