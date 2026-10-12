<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class PermissionFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $session = Services::session();
    $user    = $session->get('user');

    if (!$user) {
      return redirect()->to('/login')->with('error', 'Please log in to continue.');
    }

    $requiredPermission = $arguments[0] ?? null;

    if ($requiredPermission) {
      // Super Admin (Role 1) has full unrestricted access
      if (isset($user->role_id) && $user->role_id == 1) {
        return null;
      }

      // Check using central hasPermission helper (supports user-wise & role-wise permissions)
      helper('common');
      if (!hasPermission($requiredPermission)) {
        return redirect()->to('/unauthorized')->with('error', 'You do not have permission to access this resource.');
      }
    }

    return null;
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do nothing after request
  }
}
