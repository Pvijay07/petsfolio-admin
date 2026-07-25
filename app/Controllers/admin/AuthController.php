<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
  protected $model;
  protected $session;
  public function __construct()
  {
    $this->model   = new User();
    $this->session = session();
  }
  public function index()
  {
    $admin = session()->get('user');

    if ($admin) {
      return redirect()->to('/dashboard');
    }
    return view('admin/auth/login');
  }
  public function login()
  {
    $allowedIps = getenv('allowedIps') ? explode(',', getenv('allowedIps')) : [];

    $clientIp = $this->request->getIPAddress();

    if (!in_array($clientIp, $allowedIps)) {
      return $this->response->setJSON(['success' => false, 'error' => 'Access denied from this IP']);
    }

    if ($this->request->getMethod() == 'POST') {
      $username = $this->request->getPost('email');
      $password = $this->request->getPost('password');

      $user = $this->model->getUserByUsername($username);
      if (!$user || !password_verify($password, $user->password)) {
        return $this->response->setJSON(['success' => false, 'error' => 'Invalid username or password']);
      }

      $this->session->set('user', $user);
      return $this->response->setJSON(['success' => true, 'message' => 'Login success']);
    }
  }

  public function forgetPassword()
  {
    $email = $this->request->getPost('email');
    $user  = $this->model->getUserByEmail($email);
    if ($user) {
      $token = $this->generateToken($user->id);
      $this->sendEmail($user->email, $token);
      return $this->response->setJSON(['message' => 'Email sent successfully']);
    } else {
      return $this->response->setJSON(['message' => "We can't find a user with that email address."]);
    }
  }

  public function sendEmail($userEmail, $otp)
  {
    $email = \Config\Services::email();
    $email->setTo($userEmail);

    $fromEmail = getenv('emailFrom');
    $fromName  = getenv('emailDomain');

    $email->setFrom($fromEmail, $fromName);

    $email->setSubject('🔐 Your Admin Panel Login OTP');
    $baseUrl = 'https://petsfolio.in/admin';
    $message = '
    <html>
      <head>
        <style>
          body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
          .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); padding: 30px; }
          .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; }
          .header h2 { margin: 0; color: #2c3e50; }
          .otp-box { text-align: center; background: #f9f9f9; padding: 20px; margin: 25px 0; border-radius: 10px; font-size: 28px; letter-spacing: 6px; font-weight: bold; color: #e74c3c; }
          .content { color: #333; font-size: 16px; line-height: 1.6; }
          .footer { margin-top: 25px; font-size: 12px; color: #888; text-align: center; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h2>Admin Panel Login</h2>
          </div>
          <div class="content">
            <p>Hello,</p>
            <p>We received a request to login to the <strong>Admin Panel</strong>. Please use the following OTP to proceed with your login:</p>
          </div>
          <div class="otp-box">
            ' . $otp . '
          </div>
          <div class="content">
            <p><strong>Note:</strong> This OTP is valid for the next <b>5 minutes</b>. Do not share this code with anyone.</p>
          </div>
        <div class="footer">
        <p>© ' . date('Y') . ' ' . $fromName . ' | Secure Login System</p>
        <p><a href="' . base_url() . '" style="color:#3498db; text-decoration:none; font-weight:bold;">Visit Portal</a></p>
      </div>
        </div>
      </body>
    </html>';

    $email->setMessage($message);
    $email->setMailType('html'); // Important for HTML emails

    if ($email->send()) {
      return true;
    } else {
      return $email->printDebugger(['headers']);
    }
  }


  public function generateToken($id)
  {
    $token = bin2hex(random_bytes(32));
    $this->model->updateUserToken($id, $token);
    return $token;
  }

  public function logout()
  {
    $this->session->destroy();
    return redirect()->to('/');
  }
  public function sendOtp()
  {
    $email = $this->request->getJSON('email');
    $user  = $this->model->getUserByEmail($email);
    if ($user) {
      $otp = rand(100000, 999999);
      $this->model->updateUserOtp($email, $otp);
      $result = $this->sendEmail($email, $otp);
      return $this->response->setJSON(['success' => true, 'message' => 'Email sent successfully', 'result' => $result]);
    } else {
      return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }
  }
  public function verifyOtp()
  {
    $email = $this->request->getJsonVar('email');
    $otp   = $this->request->getJsonVar('otp');
    $fcm_token   = $this->request->getJsonVar('fcm_token') ?? '';

    $user = $this->model->getUserByEmail($email);
    if ($user) {
      if ($user->otp == $otp) {
        $this->model->removeUserOtp($email, $fcm_token);
        $this->session->set('user', $user);

        return $this->response->setJSON(['success' => true, 'message' => 'successfully', 'user' => $user]);
      } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid OTP ']);
      }
    } else {
      return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }
  }
  public function unauthorized()
  {
    return view('admin/unauthorize');
  }
}
