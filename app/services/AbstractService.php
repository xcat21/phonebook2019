<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:06.
 */

namespace App\Services;

/**
 * Class AbstractService.
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
