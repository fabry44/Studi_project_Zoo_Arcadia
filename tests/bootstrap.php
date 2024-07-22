<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

$dsn = getenv('DATABASE_URL');
if ($dsn !== false) {
    $parsedUrl = parse_url($dsn);
    putenv('DATABASE_USER=' . $parsedUrl['user']);
    putenv('DATABASE_PASSWORD=' . $parsedUrl['pass']);
    putenv('DATABASE_HOST=' . $parsedUrl['host']);
    putenv('DATABASE_PORT=' . $parsedUrl['port']);
}