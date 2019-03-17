<?php

declare(strict_types=1);

use Phalcon\Db\Adapter\Pdo\Mysql;

/**
 * Dependency Injections container for phonebook application
 * Contains all the shares of services to be injected.
 */

// Initializing a DI Container
$di = new \Phalcon\DI\FactoryDefault();

// Overriding Response-object to set the Content-type header globally
$di->setShared(
    'response',
    function () {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8');

        return $response;
    }
);

// Overriding Request-object to set the Content-type header globally
$di->setShared(
    'request',
    function () {
        $request = new \Phalcon\Http\Request();

        return $request;
    }
);

// Common config connection
$di->setShared('config', $config);

// Database connection
$di->set(
    'db',
    function () use ($config) {
        return new Mysql(
            [
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
            ]
        );
    }
);

// Logger Service connection
$di->set(
    'logger',
    function () use ($di) {
        $oldUmask = umask(0);
        $logger = new \Phalcon\Logger\Multiple();
        $config = $di['config']->logger;
        $fileName = strtr($config->fileName, ['[%date%]' => date('Y-m-d')]);

        try {
            $fileAdapter = new \Phalcon\Logger\Adapter\File($fileName);
        } catch (\Exception $e) {
            // Writing error to a system log
            error_log($e);
        }

        if (isset($fileAdapter)) {
            if (isset($config->Level)) {
                $fileAdapter->setLogLevel($config->Level);
            }

            if (isset($config->Format)) {
                $formatter = new \Phalcon\Logger\Formatter\Line(strtr($config->Format, []));
                $fileAdapter->setFormatter($formatter);
            }

            $logger->push($fileAdapter);
        }
        umask($oldUmask);

        return $logger;
    },
  true
);

// Caching service (Redis) connection
$di->set(
    'cache',
    function () use ($di) {
        $config = $di['config'];

        // Frontend Cache
        $frontCache = new \Phalcon\Cache\Frontend\Data(['lifetime' => $config->lifetime]);

        // Connect to redis (Backend Cache)
        $redisClient = new \Phalcon\Cache\Backend\Redis($frontCache, $config->redis);

        return $redisClient;
    },
    true
);

// Service to perform operations with the Records
$di->setShared('recordService', '\App\Services\RecordService');

// Return container
return $di;
