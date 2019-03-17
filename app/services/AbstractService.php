<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Class AbstractService for Phonebook application.
 *
 * @property \Phalcon\Db\Adapter\Pdo\Mysql $db
 * @property \Phalcon\Cache\Backend\Redis  $cache
 * @property \Phalcon\Config               $config
 * @property \Phalcon\Logger               $logger
 */
abstract class AbstractService extends \Phalcon\DI\Injectable
{
    /**
     * Invalid parameters anywhere.
     */
    public const ERROR_INVALID_PARAMETERS = 10001;

    /**
     * Record already exists.
     */
    public const ERROR_ALREADY_EXISTS = 10002;
}
