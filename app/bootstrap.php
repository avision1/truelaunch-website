<?php

declare(strict_types=1);

session_start();

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    throw new RuntimeException('Missing config.php. Copy app/config.php and update credentials.');
}
$config = require $configPath;

if (!empty($config['debug'])) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require __DIR__ . '/content.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/csrf.php';

$defaults = require __DIR__ . '/defaults.php';

$pdo = null;
$content = $defaults;

try {
    $pdo = db($config['db']);
    $content = get_site_content($pdo, $defaults);
} catch (Throwable $e) {
    if (!empty($config['debug'])) {
        $content['_db_error'] = $e->getMessage();
    }
}
