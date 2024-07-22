<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Charger les variables d'environnement à partir de Heroku si .env n'existe pas
if (!file_exists(dirname(__DIR__).'/.env')) {
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'prod';
    $_SERVER['DATABASE_URL'] = $_ENV['DATABASE_URL'] = getenv('DATABASE_URL');
    $_SERVER['APP_SECRET'] = $_ENV['APP_SECRET'] = getenv('APP_SECRET');
} else {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}