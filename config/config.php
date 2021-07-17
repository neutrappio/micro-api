<?php

use josegonzalez\Dotenv;
use Phalcon\Config;

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

require_once(dirname(__DIR__) . '/vendor/autoload.php');

(new Dotenv\Loader(BASE_PATH . '/.env'))
            ->parse()
            ->toEnv()
            ->putenv(true);

$config =  new Config([
    'mode' => getenv('APP_ENV') ?? 'production',

    'application' => [
        'modelsDir'      => APP_PATH . '/Models/',
        'controllersDir' => APP_PATH . '/Controllers/',
        'migrationsDir'  => BASE_PATH . '/resources/migrations/',
        'baseUri'        => '/',
        'url'=> getenv("APP_FRONT_URL") ?? "http://localhost/",
    ],

    'database' => [
        'adapter'    => getenv('DB_ADAPTER') ?? 'Postgresql',
        'host'       => getenv('DB_HOST') ?? 'localhost',
        'username'   => getenv('DB_USER') ?? 'root',
        'password'   => getenv('DB_PASS') ?? '',
        'dbname'     => getenv('DB_NAME') ?? 'test',
        'schema'     => getenv('DB_SCHEMA') ?? 'test',
        'port'       => intval(getenv('DB_PORT') ?? 3308),
    ],

    'cache'=>[
        'adapter'=> getenv('CACHE_ADAPTER') ?? 'Stream',
        'options'=>[
            'Redis' =>[
                'defaultSerializer' => 'Php',
                'lifetime'          => getenv('CACHE_REDIS_LIFETIME') ??7200,
                'host'              => getenv('CACHE_REDIS_HOST') ?? '0.0.0.0',
                'port'              => getenv('CACHE_REDIS_PORT') ?? 6379,
                'index'             => 1,
            ],
            'apcu' =>[
                'defaultSerializer' => 'Php',
                'lifetime'          => getenv('CACHE_APCU_LIFETIME') ?? 7200,
            ],
            'Stream' =>[
                'defaultSerializer' => 'Php',
                'storageDir' => getenv('CACHE_APCU_STORAGEDIR') ?? BASE_PATH . '/storage/cache/shared/',
            ]
        ]
    ],

    'jwt' => [
        'url'  =>
        (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || ($_SERVER['SERVER_PORT'] ?? null) == 443) ? "https://" : "http://")
        . ($_SERVER['HTTP_HOST'] ?? 'localhost') ,
        'timezone' => 'Europe/Paris',
        'secretkey'=> getenv('JWT_SIGNER_KEY_BASE64BASE') ?? "U0VDUkVU"
    ],

    'storage'=>  [
        'version' => 'latest',
        'region'  => 'eu-east-1', // does not mind
        'endpoint' => getenv('MINIO_ENDPOINT') ?? null,
        'use_path_style_endpoint' => true, // true and true
        'credentials' => [
            'key'    => getenv('MINIO_ACCESS_KEY') ?? null,
            'secret'    => getenv('MINIO_SECRET_KEY') ?? null
        ],
    ],

    'mailer'=> [
        'driver'=> getenv('MAILER_DRIVER') ?? 'php',
        'host'=> getenv('MAILER_HOST') ?? null,
        'port'=> getenv('MAILER_PORT') ?? null,
        'encryption'=> getenv('MAILER_ENCRYPTION') ?? 'tls',
        'sendmail'=> getenv('MAILER_SENDMAIL') ?? '/usr/sbin/sendmail -bs',
        'username'=> getenv('MAILER_USERNAME') ?? null,
        'password'=> getenv('MAILER_PASSWORD') ?? null,
        'views' => BASE_PATH . '/resources/mails/',
        'from' => [
            'name'=> getenv('MAILER_FROM_NAME') ?? 'Mapi',
            'email'=> getenv('MAILER_FROM_EMAIL') ?? 'no-reply@neutrapp.io',
        ],
    ]
]);

return $config;
