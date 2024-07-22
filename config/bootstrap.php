<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

$postgresDsn = getenv('DATABASE_URL');
if ($postgresDsn !== false) {
    $parsedUrl = parse_url($postgresDsn);
    putenv('DATABASE_DSN=pgsql:host=' . $parsedUrl['host'] . ';port=' . $parsedUrl['port']);
    putenv('DATABASE_USER=' . $parsedUrl['user']);
    putenv('DATABASE_PASSWORD=' . $parsedUrl['pass']);
}

$mongoDsn = getenv('MONGODB_URI');
if ($mongoDsn !== false) {
    putenv('MONGO_DSN=' . $mongoDsn);
}