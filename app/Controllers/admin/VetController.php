<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class UserController extends BaseController
{
  protected $userModel;
  public function __construct()
  {
    $this->userModel = model('User');
    helper('Common_helper');
  }
  public function index()
  {
    $status          = $this->request->getGet('status');
    $data['clients'] = $this->userModel->getAll($status);
    $data['title']   = '';
    $data['page']    = 'client';
    if (!empty($data['clients'])) {
      foreach ($data['clients'] as $clients) {
        if (!empty($clients->pets)) {
          array_walk($clients->pets, function ($pet) {
            $pet->age = ageCalculator($pet->dob);
          });
        }
      }
    }

    return $this->loadView('admin/user/index', $data);
  }
}
