<?php

// Define base and app paths to be set in one place
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config(
    [
        'database' => [
            'adapter' => 'Mysql',
            'host' => 'localhost',
            'username' => 'phonebook_dbu',
            'password' => 'superpass',
            'dbname' => 'phonebook_db',
            'charset'     => 'utf8',
        ],

        'application' => [
	        'controllersDir' => APP_PATH . '/controllers/',
            'migrationsDir'  => APP_PATH . '/migrations/',
	        'modelsDir'      => APP_PATH . 'app/models/',
	        'baseUri'        => "/",
        ],

        'logger' => [
            'format' => '[%date%][%type%] %message%', // record format
            'fileName' => __DIR__ . '/../logs/[%date%].log', // file name
            'level' => \Phalcon\Logger::CUSTOM, // minimum level to log
        ],
/*
        'cache' => [
            'lifetime' => 172800, // 2 days
            'redis' => [
                'prefix' => 'article-demo-%path%_', // record prefix
                'host' => '127.0.0.1',
                'port' => 6379,
                'index' => 0 // Database number in Redis. Must be integer
            ],
        ], */
    ]
);