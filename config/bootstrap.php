<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/'.'.env');
}

// PostgreSQL configuration
$dsn = getenv('DATABASE_URL');
if ($dsn !== false) {
    $parsedUrl = parse_url($dsn);
    putenv('DATABASE_DSN=pgsql:host=' . $parsedUrl['host'] . ';port=' . $parsedUrl['port']);
    putenv('DATABASE_USER=' . $parsedUrl['user']);
    putenv('DATABASE_PASSWORD=' . $parsedUrl['pass']);
}

// MongoDB configuration
$mongoDsn = getenv('MONGODB_URI');
if ($mongoDsn !== false) {
    putenv('MONGO_DSN=' . $mongoDsn);
}