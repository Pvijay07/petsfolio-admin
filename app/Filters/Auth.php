<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{

  public function before(RequestInterface $request, $arguments = null)
  {
    $sessionData = [];
    if (session()->has('user')) {
      $sessionData = session()->get('user');
    }

    // Check if session data is valid
    if (empty($sessionData)) {
      $currentURL = current_url();
      session()->set('redirect_url', $currentURL);
      return redirect()->to('/')->with('fail', 'You need to login');
    }

    return $request;
  }



  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
