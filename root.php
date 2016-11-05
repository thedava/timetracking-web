<?php

function check_cli_server()
{
    // Decline static file requests back to the PHP built-in webserver
    if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . '/public' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        return false;
    }

    return true;
}

define('_ROOT_', __DIR__);
define('TT_DEBUG', php_sapi_name() === 'cli-server');

// Force timezone
$date = require __DIR__ . '/config/date.php';
date_default_timezone_set($date['date']['timezone']);

require_once __DIR__ . '/vendor/autoload.php';
