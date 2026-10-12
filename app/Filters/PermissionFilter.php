<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Session\Session;
use Config\Services;

class PermissionFilter implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = null)
  {
    $session = Services::session();
    $user    = $session->get('user');

    if (!$user) {
      return redirect()->to('/')->with('error', 'Please log in first.');
    }

    // Super Admin bypasses all permission checks
    if (isSuperAdmin($user)) {
      return;
    }

    $requiredPermission = $arguments[0] ?? null;

    if ($requiredPermission) {
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
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do nothing after request
  }
}
