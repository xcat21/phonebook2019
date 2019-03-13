<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:06
 */

namespace App\Services;

/**
 * Class AbstractService
 *
 * @property \Phalcon\Db\Adapter\Pdo\Postgresql $db
 * @property \Phalcon\Cache\Backend\Redis       $cache
 * @property \Phalcon\Config                    $config
 */
abstract class AbstractService extends \Phalcon\DI\Injectable
{
    /**
     * Invalid parameters anywhere
     */
    const ERROR_INVALID_PARAMETERS = 10001;

    /**
     * Record already exists
     */
    const ERROR_ALREADY_EXISTS = 10002;
}
