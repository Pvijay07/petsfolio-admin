<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!defined('FCPATH')) {
    define('FCPATH', '/var/www/petsfolio/live/admin/public/');
}

require_once __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/Boot.php';

$bootClass = new ReflectionClass(CodeIgniter\Boot::class);
foreach ([
    'definePathConstants'      => [$paths],
    'loadConstants'            => [],
    'loadDotEnv'               => [$paths],
    'defineEnvironment'        => [],
    'loadEnvironmentBootstrap' => [$paths],
    'loadCommonFunctions'      => []
] as $method => $args) {
    $r = $bootClass->getMethod($method);
    $r->setAccessible(true);
    $r->invokeArgs(null, $args);
}

$bootClass->getMethod('loadAutoloader')->invoke(null, new Config\Autoload(), new Config\Modules());

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'Tests\\')) {
        $rel = str_replace('\\', '/', substr($class, 6));
        $file = __DIR__ . '/' . $rel . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
        $parts = explode('/', $rel);
        if (count($parts) > 1) {
            $parts[0] = strtolower($parts[0]);
            $fileLower = __DIR__ . '/' . implode('/', $parts) . '.php';
            if (file_exists($fileLower)) {
                require_once $fileLower;
                return;
            }
        }
    }
});

// Establish mysqli direct connection for independent ground truth verification
$dbConfig = config('Database');
$defaultDb = $dbConfig->default;

$mysqli = new mysqli(
    $defaultDb['hostname'],
    $defaultDb['username'],
    $defaultDb['password'],
    $defaultDb['database'],
    $defaultDb['port'] ?? 3306
);

if ($mysqli->connect_error) {
    throw new Exception("Direct MySQL ground truth connection failed: " . $mysqli->connect_error);
}

$GLOBALS['gt_mysqli'] = $mysqli;
