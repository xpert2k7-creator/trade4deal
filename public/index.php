<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// XAMPP Apache (daemon) cannot write to macOS user temp dirs — point TMPDIR at XAMPP temp.
$xamppTemp = '/Applications/XAMPP/xamppfiles/temp';
if (is_dir($xamppTemp) && is_writable($xamppTemp)) {
    putenv('TMPDIR='.$xamppTemp);
    $_ENV['TMPDIR'] = $xamppTemp;
    $_SERVER['TMPDIR'] = $xamppTemp;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
