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
    $session  = Services::session();
    $userRole = $session->get('user')->role_id;

    $requiredPermission = $arguments[0] ?? null;

    if ($requiredPermission) {
      $db               = \Config\Database::connect();
      $permissionExists = $db->table('role_permissions')
        ->join('permissions', 'permissions.id = role_permissions.permission_id')
        ->where('role_permissions.role_id', $userRole)
        ->where('permissions.id', $requiredPermission)
        ->countAllResults();

      if ($permissionExists == 0) {
        return redirect()->to('/unauthorized')->with('error', 'You do not have permission to access this page.');
      }
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // Do nothing after request
  }
}
