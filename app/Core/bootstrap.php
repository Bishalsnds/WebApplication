<?php
// app/Core/bootstrap.php
// Application initialization and autoloading

session_start();

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load configuration
$config = require __DIR__ . '/../../config/config.php';

// Define app constants
if (!defined('APP_URL')) {
    define('APP_URL', $config['app']['base_url']);
}

if (!defined('APP_ENV')) {
    define('APP_ENV', $config['app']['env']);
}

// Error handling for development
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
