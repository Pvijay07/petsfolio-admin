<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\ActivityLogger;

class ActivityLogFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        try {
            $session = session();
            if ($session->has('user')) {
                $user = $session->get('user');
                if (!empty($user)) {
                    ActivityLogger::logRequest($user, $request, $response);
                }
            }
        } catch (\Throwable $e) {
            // Activity logging should never disrupt user response
            log_message('error', 'ActivityLogFilter after() error: ' . $e->getMessage());
        }

        return $response;
    }
}
