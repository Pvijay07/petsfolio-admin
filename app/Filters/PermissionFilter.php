<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
<<<<<<< HEAD
=======
use CodeIgniter\Session\Session;
>>>>>>> origin/main
use Config\Services;

class PermissionFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $session = Services::session();
    $user    = $session->get('user');

    if (!$user) {
<<<<<<< HEAD
      return redirect()->to('/login')->with('error', 'Please log in to continue.');
=======
      return redirect()->to('/')->with('error', 'Please log in first.');
    }

    // Super Admin bypasses all permission checks
    if (isSuperAdmin($user)) {
      return;
>>>>>>> origin/main
    }

    $requiredPermission = $arguments[0] ?? null;

    if ($requiredPermission) {
<<<<<<< HEAD
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
=======
      $hasPerm = hasPermission($requiredPermission);
      if (!$hasPerm && ($requiredPermission === 'providers.view' || $requiredPermission === 'service_providers.view')) {
        $hasPerm = function_exists('hasAnyProviderPermission') ? hasAnyProviderPermission() : false;
      }

      if (!$hasPerm) {
        if ($requiredPermission === 'dashboard.view') {
          return redirect()->to(getDefaultPermittedRoute($user));
        }
        return redirect()->to('/unauthorized')->with('error', 'You do not have permission to access this page.');
      }
    }
>>>>>>> origin/main
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do nothing after request
  }
}
