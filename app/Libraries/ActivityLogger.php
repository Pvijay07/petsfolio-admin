<?php

namespace App\Libraries;

use Config\Database;
use Config\Services;

class ActivityLogger
{
    /**
     * List of path prefixes or full paths to ignore from logging.
     * Prevents polling, heartbeats, and asset endpoints from polluting logs.
     */
    protected static array $excludedPaths = [
        'audit-logs',
        // 'supports',
        'getSidebarCounts',
        'debugbar',
        'favicon.ico',
        'robots.txt',
        'assets/',
        'uploads/',
        'notifications/load',
        'notifications/unread-count',
    ];

    /**
     * Fields containing sensitive information that must never be recorded in logs.
     */
    protected static array $sensitiveFields = [
        'password',
        'password_confirm',
        'confirm_password',
        'current_password',
        'new_password',
        'otp',
        'token',
        'fcm_token',
        'device_token',
        'secret',
        'api_key',
        'g-recaptcha-response',
        'recaptcha_token',
        'card_number',
        'cvv',
    ];

    /**
     * Log authentication events (login, logout, failed login)
     */
    public static function logAuth(string $action, $user = null, ?string $description = null, array $metadata = []): bool
    {
        try {
            $request = Services::request();
            $ip = self::getClientIp($request);
            $userAgent = self::getClientUserAgent($request);
            $url = self::getRequestPath($request);

            $actorId = null;
            $userName = null;
            $userEmail = null;
            $roleName = null;

            if (is_object($user)) {
                $actorId = (string) ($user->id ?? '');
                $userName = $user->name ?? null;
                $userEmail = $user->email ?? null;
                $roleName = $user->role_name ?? ($user->role_slug ?? null);
            } elseif (is_array($user)) {
                $actorId = (string) ($user['id'] ?? '');
                $userName = $user['name'] ?? null;
                $userEmail = $user['email'] ?? null;
                $roleName = $user['role_name'] ?? ($user['role_slug'] ?? null);
            } elseif (is_string($user) && filter_var($user, FILTER_VALIDATE_EMAIL)) {
                $userEmail = $user;
                $userName = explode('@', $user)[0];
            }

            if (!$description) {
                switch ($action) {
                    case 'login':
                        $description = "User {$userEmail} logged in successfully";
                        break;
                    case 'logout':
                        $description = "User {$userEmail} logged out";
                        break;
                    case 'login_failed':
                        $description = "Failed login attempt for {$userEmail}";
                        break;
                    default:
                        $description = "Auth event: {$action}";
                }
            }

            $db = Database::connect();
            return $db->table('audit_logs')->insert([
                'entity_type' => 'auth',
                'entity_id'   => $actorId ?: ($userEmail ?: '0'),
                'actor_type'  => 'admin',
                'actor_id'    => $actorId,
                'user_name'   => $userName,
                'user_email'  => $userEmail,
                'role_name'   => $roleName,
                'action'      => $action,
                'method'      => 'POST',
                'url'         => $url,
                'description' => $description,
                'metadata'    => !empty($metadata) ? json_encode($metadata) : null,
                'ip_address'  => $ip,
                'user_agent'  => $userAgent,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'ActivityLogger::logAuth error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Programmatic logger for specific updates, deletes, and actions
     */
    public static function log(
        string $action,
        string $description,
        ?string $entityType = null,
        $entityId = null,
        $oldState = null,
        $newState = null,
        array $metadata = []
    ): bool {
        try {
            $session = Services::session();
            $user = $session->get('user');

            $actorId = null;
            $userName = 'System';
            $userEmail = null;
            $roleName = null;

            if (is_object($user)) {
                $actorId = (string) ($user->id ?? '');
                $userName = $user->name ?? null;
                $userEmail = $user->email ?? null;
                $roleName = $user->role_name ?? ($user->role_slug ?? null);
            } elseif (is_array($user)) {
                $actorId = (string) ($user['id'] ?? '');
                $userName = $user['name'] ?? null;
                $userEmail = $user['email'] ?? null;
                $roleName = $user['role_name'] ?? ($user['role_slug'] ?? null);
            }

            $request = Services::request();
            $ip = self::getClientIp($request);
            $userAgent = self::getClientUserAgent($request);
            $url = self::getRequestPath($request);
            $method = self::getRequestMethod($request);

            $db = Database::connect();
            return $db->table('audit_logs')->insert([
                'entity_type' => $entityType ?: 'system',
                'entity_id'   => $entityId !== null ? (string) $entityId : '0',
                'actor_type'  => $actorId ? 'admin' : 'system',
                'actor_id'    => $actorId,
                'user_name'   => $userName,
                'user_email'  => $userEmail,
                'role_name'   => $roleName,
                'action'      => $action,
                'method'      => $method,
                'url'         => $url,
                'description' => $description,
                'old_state'   => $oldState !== null ? (is_string($oldState) ? $oldState : json_encode($oldState)) : null,
                'new_state'   => $newState !== null ? (is_string($newState) ? $newState : json_encode($newState)) : null,
                'metadata'    => !empty($metadata) ? json_encode($metadata) : null,
                'ip_address'  => $ip,
                'user_agent'  => $userAgent,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'ActivityLogger::log error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Automatic HTTP request logger invoked by ActivityLogFilter.
     */
    public static function logRequest($user, $request, $response = null): bool
    {
        if (!$user) {
            return false;
        }

        try {
            $path = trim($request->getUri()->getPath(), '/');

            // Skip excluded paths
            foreach (self::$excludedPaths as $excluded) {
                if ($path === $excluded || str_starts_with($path, $excluded)) {
                    return false;
                }
            }

            $method = strtoupper($request->getMethod());

            // Skip AJAX GETs that are just partial UI components unless meaningful
            $isAjax = $request->isAJAX();
            if ($method === 'GET' && $isAjax) {
                // If it's just fetching small fragments or counts, skip
                if (str_contains($path, 'badge') || str_contains($path, 'count') || str_contains($path, 'poll')) {
                    return false;
                }
            }

            $actorId = (string) (is_object($user) ? ($user->id ?? '') : ($user['id'] ?? ''));
            $userName = is_object($user) ? ($user->name ?? '') : ($user['name'] ?? '');
            $userEmail = is_object($user) ? ($user->email ?? '') : ($user['email'] ?? '');
            $roleName = is_object($user) ? ($user->role_name ?? ($user->role_slug ?? '')) : ($user['role_name'] ?? ($user['role_slug'] ?? ''));

            $ip = $request->getIPAddress();
            $userAgent = substr((string) $request->getUserAgent(), 0, 255);

            // Determine Entity Type, Entity ID & Action
            $bodyData = [];
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $bodyData = self::extractRequestBody($request);
            }

            [$entityType, $entityId, $action, $description] = self::resolveActionAndEntity($method, $path, $request, $bodyData);

            // Sanitize payload for state/metadata
            $payload = [];
            if (!empty($bodyData)) {
                $payload = self::sanitizeData($bodyData);
            }

            $getParams = array_filter((array) $request->getGet());
            $metadata = !empty($getParams) ? json_encode(['params' => $getParams]) : null;

            $db = Database::connect();
            return $db->table('audit_logs')->insert([
                'entity_type' => substr((string) $entityType, 0, 50),
                'entity_id'   => substr((string) $entityId, 0, 100),
                'actor_type'  => 'admin',
                'actor_id'    => substr((string) $actorId, 0, 100),
                'user_name'   => substr((string) $userName, 0, 150),
                'user_email'  => substr((string) $userEmail, 0, 150),
                'role_name'   => substr((string) $roleName, 0, 100),
                'action'      => substr((string) $action, 0, 50),
                'method'      => substr((string) $method, 0, 10),
                'url'         => substr('/' . $path, 0, 500),
                'description' => substr((string) $description, 0, 500),
                'new_state'   => !empty($payload) ? json_encode($payload) : null,
                'metadata'    => $metadata,
                'ip_address'  => substr((string) $ip, 0, 45),
                'user_agent'  => $userAgent,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'ActivityLogger::logRequest error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Resolves humanized action, entity type, and entity ID from the request
     */
    protected static function resolveActionAndEntity(string $method, string $path, $request, array $bodyData = []): array
    {
        $segments = explode('/', $path);
        $firstSegment = strtolower($segments[0] ?? '');
        $secondSegment = strtolower($segments[1] ?? '');
        $pathLower = strtolower($path);

        $post = !empty($bodyData) ? $bodyData : self::extractRequestBody($request);

        $entityType = 'system';
        $entityId = '0';
        $action = 'view';
        $description = '';

        // 1. Entity resolution (URL path + POST body keys)
        if (str_contains($pathLower, 'booking') || str_contains($pathLower, 'quotation') || str_contains($pathLower, 'cancelreason') || !empty($post['booking_id'])) {
            $entityType = 'booking';
        } elseif (str_contains($pathLower, 'provider') || str_contains($pathLower, 'walker') || !empty($post['provider_id'])) {
            $entityType = 'provider';
        } elseif (str_contains($pathLower, 'user') || str_contains($pathLower, 'client') || str_contains($pathLower, 'customer') || !empty($post['user_id'])) {
            $entityType = 'client';
        } elseif (str_contains($pathLower, 'employee') || str_contains($pathLower, 'staff') || !empty($post['employee_id']) || !empty($post['emp_id'])) {
            $entityType = 'employee';
        } elseif (str_contains($pathLower, 'service') || str_contains($pathLower, 'package') || str_contains($pathLower, 'addon') || !empty($post['addon_id']) || !empty($post['package_id']) || !empty($post['service_id'])) {
            $entityType = 'service';
        } elseif (str_contains($pathLower, 'permission') || str_contains($pathLower, 'role') || !empty($post['role_id']) || !empty($post['permission_id'])) {
            $entityType = 'permission';
        } elseif (str_contains($pathLower, 'coupon')) {
            $entityType = 'coupon';
        } elseif (str_contains($pathLower, 'ticket') || str_contains($pathLower, 'support') || !empty($post['ticket_id']) || !empty($post['chat_id'])) {
            $entityType = 'support';
        } elseif (str_contains($pathLower, 'call')) {
            $entityType = 'call_analysis';
        } elseif (str_contains($pathLower, 'refund') || str_contains($pathLower, 'payout') || str_contains($pathLower, 'payment')) {
            $entityType = 'financial';
        } elseif (str_contains($pathLower, 'community') || str_contains($pathLower, 'channel') || str_contains($pathLower, 'post') || str_contains($pathLower, 'comment')) {
            $entityType = 'community';
        } elseif (str_contains($pathLower, 'breed') || str_contains($pathLower, 'pet') || !empty($post['breed_id'])) {
            $entityType = 'pet';
        } elseif (str_contains($pathLower, 'banner') || !empty($post['banner_id'])) {
            $entityType = 'banner';
        } elseif (str_contains($pathLower, 'notification') || str_contains($pathLower, 'template') || !empty($post['notification_id'])) {
            $entityType = 'notification';
        } elseif (str_contains($pathLower, 'insurance') || str_contains($pathLower, 'claim') || !empty($post['claim_id'])) {
            $entityType = 'insurance';
        } elseif (str_contains($pathLower, 'loyalty') || str_contains($pathLower, 'milestone') || str_contains($pathLower, 'reward')) {
            $entityType = 'loyalty';
        } elseif (str_contains($pathLower, 'task') || !empty($post['task_id'])) {
            $entityType = 'task';
        } elseif (str_contains($pathLower, 'tutorial')) {
            $entityType = 'tutorial';
        } elseif (str_contains($pathLower, 'auth') || str_contains($pathLower, 'login') || str_contains($pathLower, 'logout')) {
            $entityType = 'auth';
        } else {
            $entityType = $firstSegment ?: 'dashboard';
        }

        // 2. Extract potential entity ID from URI or POST
        foreach ($segments as $seg) {
            if (is_numeric($seg) && (int) $seg > 0) {
                $entityId = $seg;
                break;
            } elseif (preg_match('/^(PSF[a-zA-Z0-9]+)$/', $seg)) {
                $entityId = $seg;
                break;
            }
        }
        if ($entityId === '0') {
            $idKeys = [
                'booking_id', 'chat_id', 'ticket_id', 'user_id', 'provider_id',
                'employee_id', 'emp_id', 'service_id', 'addon_id', 'package_id',
                'breed_id', 'role_id', 'permission_id', 'banner_id', 'notification_id',
                'task_id', 'claim_id', 'rule_id', 'id'
            ];
            foreach ($idKeys as $key) {
                if (!empty($post[$key]) && (is_string($post[$key]) || is_numeric($post[$key]))) {
                    $entityId = (string) $post[$key];
                    break;
                }
            }
        }

        // Support module custom action & description resolution
        if ($entityType === 'support') {
            $statusVal = strtolower(trim((string) ($post['status'] ?? '')));

            if ($statusVal === 'resolved' || str_contains($pathLower, 'resolve')) {
                $action = 'update';
                $description = "Marked Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "") . " as Resolved";
            } elseif ($statusVal === 'closed' || str_contains($pathLower, 'close')) {
                $action = 'update';
                $description = "Closed Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "");
            } elseif (!empty($statusVal)) {
                $action = 'update';
                $description = "Updated Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "") . " to " . ucfirst($statusVal);
            } elseif ($method === 'POST' && (str_contains($pathLower, 'send') || str_contains($pathLower, 'reply') || str_contains($pathLower, 'message'))) {
                $action = 'create';
                $description = "Sent Message on Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "");
            } elseif ($method === 'POST') {
                $action = 'update';
                $description = "Updated Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "");
            } else {
                $action = 'view';
                if ($pathLower === 'supports' || $pathLower === 'support') {
                    $description = "Viewed Client Support Tickets";
                } elseif ($pathLower === 'sp-support' || $pathLower === 'sp_support') {
                    $description = "Viewed SP Support Tickets";
                } elseif (str_contains($pathLower, 'sp-supportticket')) {
                    $description = "Viewed SP Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "");
                } elseif (str_contains($pathLower, 'supportticket')) {
                    $description = "Viewed Support Ticket" . ($entityId !== '0' ? " #{$entityId}" : "");
                } else {
                    $cleanTitle = ucwords(str_replace(['-', '_'], ' ', $firstSegment ?: 'Support'));
                    $description = "Viewed {$cleanTitle}" . ($secondSegment ? " - " . ucwords(str_replace(['-', '_'], ' ', $secondSegment)) : "");
                }
            }
            return [$entityType, $entityId, $action, $description];
        }

        // 3. Action resolution for other modules
        if ($method === 'DELETE' || str_contains($pathLower, 'delete') || str_contains($pathLower, 'remove') || str_contains($pathLower, 'destroy')) {
            $action = 'delete';
            $description = "Deleted " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } elseif (str_contains($pathLower, 'cancel')) {
            $action = 'cancel';
            $reasonNote = !empty($post['cancel_reason']) ? ' (' . substr($post['cancel_reason'], 0, 80) . ')' : '';
            $description = "Cancelled " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "") . $reasonNote;
        } elseif (str_contains($pathLower, 'comment')) {
            $action = 'update';
            $description = "Updated Note / Comment on " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } elseif (str_contains($pathLower, 'approve') || str_contains($pathLower, 'accept')) {
            $action = 'approve';
            $description = "Approved " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } elseif (str_contains($pathLower, 'reject') || str_contains($pathLower, 'decline')) {
            $action = 'reject';
            $description = "Rejected " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } elseif (str_contains($pathLower, 'create') || str_contains($pathLower, 'add') || str_contains($pathLower, 'store') || (str_contains($pathLower, 'save') && $method === 'POST')) {
            $action = 'create';
            $description = "Created " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } elseif ($method === 'POST' || $method === 'PUT' || $method === 'PATCH' || str_contains($pathLower, 'update') || str_contains($pathLower, 'edit') || str_contains($pathLower, 'change') || str_contains($pathLower, 'status') || str_contains($pathLower, 'toggle')) {
            $action = 'update';
            $description = "Updated " . ucfirst($entityType) . ($entityId !== '0' ? " #{$entityId}" : "");
        } else {
            $action = 'view';
            $cleanTitle = ucwords(str_replace(['-', '_'], ' ', $firstSegment ?: 'Dashboard'));
            $description = "Viewed {$cleanTitle}" . ($secondSegment ? " - " . ucwords(str_replace(['-', '_'], ' ', $secondSegment)) : "");
        }

        return [$entityType, $entityId, $action, $description];
    }

    /**
     * Safely extract request payload from POST, PUT, DELETE without throwing JSON parse exceptions
     */
    protected static function extractRequestBody($request): array
    {
        try {
            // 1. Check standard POST data
            if (method_exists($request, 'getPost')) {
                $post = (array) ($request->getPost() ?? []);
                if (!empty($post)) {
                    return $post;
                }
            }

            // 2. Safely check JSON body only if raw body looks like JSON
            $rawBody = '';
            if (method_exists($request, 'getBody')) {
                $rawBody = trim((string) $request->getBody());
            }

            if ($rawBody !== '' && (str_starts_with($rawBody, '{') || str_starts_with($rawBody, '['))) {
                $decoded = json_decode($rawBody, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }

            // 3. Fallback to raw input for PUT/PATCH/DELETE
            if (method_exists($request, 'getRawInput')) {
                $rawInput = (array) ($request->getRawInput() ?? []);
                if (!empty($rawInput)) {
                    return $rawInput;
                }
            }
        } catch (\Throwable $e) {
            // Never disrupt logging due to payload parsing
        }

        return [];
    }

    /**
     * Recursively sanitizes array data, masking sensitive fields
     */
    protected static function sanitizeData($data)
    {
        if (is_object($data)) {
            $data = (array) $data;
        }

        if (!is_array($data)) {
            return $data;
        }

        $sanitized = [];
        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string) $key);
            if (in_array($lowerKey, self::$sensitiveFields)) {
                $sanitized[$key] = '********';
            } elseif (is_array($value) || is_object($value)) {
                $sanitized[$key] = self::sanitizeData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    protected static function getClientIp($request): string
    {
        if (method_exists($request, 'getIPAddress')) {
            return (string) $request->getIPAddress();
        }
        return '127.0.0.1';
    }

    protected static function getClientUserAgent($request): string
    {
        if (method_exists($request, 'getUserAgent')) {
            return substr((string) $request->getUserAgent(), 0, 255);
        }
        return PHP_SAPI === 'cli' ? 'CLI Console' : 'Unknown';
    }

    protected static function getRequestPath($request): string
    {
        if (method_exists($request, 'getUri')) {
            return (string) $request->getUri()->getPath();
        }
        return '/';
    }

    protected static function getRequestMethod($request): string
    {
        if (method_exists($request, 'getMethod')) {
            return strtoupper((string) $request->getMethod());
        }
        return 'CLI';
    }
}
