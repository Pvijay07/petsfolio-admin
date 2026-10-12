<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\User;
use App\Libraries\ActivityLogger;

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
      return redirect()->to(getDefaultPermittedRoute($admin));
    }
    $data = [
      'recaptchaSiteKey' => getenv('RECAPTCHA_SITE_KEY') ?: ''
    ];
    return view('admin/auth/login', $data);
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
        ActivityLogger::logAuth('login_failed', $username, "Failed login attempt (invalid credentials) for {$username}", ['method' => 'password']);
        return $this->response->setJSON(['success' => false, 'error' => 'Invalid username or password']);
      }

      $this->session->set('user', $user);
      ActivityLogger::logAuth('login', $user, "User {$user->email} logged in via password", ['method' => 'password']);
      $redirect = getDefaultPermittedRoute($user);
      return $this->response->setJSON(['success' => true, 'message' => 'Login success', 'redirect' => $redirect]);
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
    $baseUrl = 'https://admin.petsfolio.in/';
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


  public function sendOtp()
  {
    $clientIp = $this->request->getIPAddress();

    // 1. IP-based Rate Limiting (max 10 requests per hour per IP)
    $throttler = \Config\Services::throttler();
    if ($throttler->check(md5('otp_ip_' . $clientIp), 20, 3600) === false) {
      log_message('warning', "OTP request blocked: IP rate limit exceeded for IP {$clientIp}");
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Too many OTP requests from your IP. Please try again in an hour.'
      ]);
    }

    // Parse email & captcha token
    $email = $this->request->getJsonVar('email') ?? $this->request->getJSON('email') ?? $this->request->getPost('email');
    if (is_object($email) && isset($email->email)) {
      $email = $email->email;
    }
    $email = strtolower(trim((string)$email));

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid email address.']);
    }

    // 2. Google reCAPTCHA Verification (if secret key configured in .env)
    $secretKey = getenv('RECAPTCHA_SECRET_KEY');
    if (!empty($secretKey)) {
      $recaptchaToken = $this->request->getJsonVar('g-recaptcha-response') 
        ?? $this->request->getJsonVar('recaptcha_token') 
        ?? $this->request->getPost('g-recaptcha-response');

      if (empty($recaptchaToken)) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Please complete the reCAPTCHA verification.'
        ]);
      }

      $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
      $verifyData = [
        'secret'   => $secretKey,
        'response' => $recaptchaToken,
        'remoteip' => $clientIp
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $verifyUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifyData));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      $verifyResponse = curl_exec($ch);
      curl_close($ch);

      $responseData = json_decode($verifyResponse, true);
      $score = $responseData['score'] ?? 1.0;
      if (!isset($responseData['success']) || !$responseData['success'] || $score < 0.3) {
        log_message('warning', "OTP request blocked: reCAPTCHA verification failed for email {$email} from IP {$clientIp} (Score: {$score})");
        return $this->response->setJSON([
          'success' => false,
          'message' => 'reCAPTCHA verification failed. Please try again.'
        ]);
      }
    }

    // 3. Email-based Cooldown (60 seconds) & Limit (3 requests per 15 minutes)
    $cache = \Config\Services::cache();
    $lastSentKey     = 'otp_last_sent_' . md5($email);
    $requestCountKey = 'otp_req_count_' . md5($email);

    $lastSentTime = $cache->get($lastSentKey);
    if ($lastSentTime) {
      $elapsed  = time() - (int)$lastSentTime;
      $cooldown = 60;
      if ($elapsed < $cooldown) {
        $waitTime = $cooldown - $elapsed;
        return $this->response->setJSON([
          'success' => false,
          'message' => "Please wait {$waitTime} seconds before requesting another OTP."
        ]);
      }
    }

    $reqCount = (int)($cache->get($requestCountKey) ?? 0);
    if ($reqCount >= 3) {
      log_message('warning', "OTP request blocked: Max 3 requests/15m exceeded for email {$email} from IP {$clientIp}");
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Maximum OTP requests reached (3 per 15 minutes). Please try again later.'
      ]);
    }

    // 4. Verify user exists and send OTP
    $user = $this->model->getUserByEmail($email);
    if ($user) {
      $otp = rand(100000, 999999);
      $this->model->updateUserOtp($email, $otp);
      $result = $this->sendEmail($email, $otp);

      // Save cooldown & increment 15-minute count
      $cache->save($lastSentKey, time(), 60);
      $cache->save($requestCountKey, $reqCount + 1, 900);

      log_message('info', "OTP sent successfully to {$email} from IP {$clientIp}");
      return $this->response->setJSON(['success' => true, 'message' => 'Email sent successfully', 'result' => $result]);
    } else {
      log_message('info', "OTP request for non-existent email {$email} from IP {$clientIp}");
      return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }
  }

  public function logout()
  {
    $user = $this->session->get('user');
    if ($user) {
      ActivityLogger::logAuth('logout', $user, "User {$user->email} logged out");
    }
    $this->session->destroy();
    return redirect()->to('/');
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
        ActivityLogger::logAuth('login', $user, "User {$user->email} logged in successfully via OTP", ['method' => 'otp']);
        $redirect = getDefaultPermittedRoute($user);
        return $this->response->setJSON(['success' => true, 'message' => 'successfully', 'user' => $user, 'redirect' => $redirect]);
      } else {
        ActivityLogger::logAuth('login_failed', $email, "Failed OTP verification for {$email}", ['method' => 'otp']);
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid OTP ']);
      }
    } else {
      ActivityLogger::logAuth('login_failed', $email, "Failed login - user not found for {$email}", ['method' => 'otp']);
      return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }
  }
  public function unauthorized()
  {
    return view('admin/unauthorize');
  }
}
