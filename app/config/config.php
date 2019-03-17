<?php

declare(strict_types=1);

/*
 * Main config file for phonebook application
 * Contains all the settings of entire application
 *
 * @author Roman Smirnov
 *
 */

// Define base and app paths to be set in one place
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(__DIR__.'/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH.'/app');

return new \Phalcon\Config(
    [
        'database' => [
            'adapter' => 'Mysql',
            'host' => 'localhost',
            'username' => 'phonebook_dbu',
            'password' => 'superpass',
            'dbname' => 'phonebook_db',
            'charset' => 'utf8',
        ],

        'application' => [
            'controllersDir' => APP_PATH.'/controllers/',
            'migrationsDir' => APP_PATH.'/migrations/',
            'modelsDir' => APP_PATH.'app/models/',
            'baseUri' => '/',
        ],

        'logger' => [
            'format' => '[%date%][%type%] %message%', // record format
            'fileName' => APP_PATH.'/logs/[%date%].log', // file name
            'level' => \Phalcon\Logger::INFO, // minimum level to log
        ],

        'cache' => [
            'lifetime' => 180, // 3 min, should be set depends on the load and cache success
            'redis' => [
                'prefix' => 'phonebook-%path%_', // record prefix
                'host' => '127.0.0.1',
                'port' => 6379,
                'index' => 0, // Database number in Redis. Must be integer
            ],
        ],
    ]
);
