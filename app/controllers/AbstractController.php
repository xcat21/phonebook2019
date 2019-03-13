<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:02
 */

namespace App\Controllers;

/**
 * Class AbstractController
 *
 * @property \Phalcon\Http\Request              $request
 * @property \Phalcon\Http\Response             $htmlResponse
 * @property \Phalcon\Db\Adapter\Pdo\Mysql   $db
 * @property \Phalcon\Config                    $config
 * @property \App\Services\RecordService        $recordService
 * @property \App\Models\Record                 $record
 */
abstract class AbstractController extends \Phalcon\DI\Injectable
{
    /**
     * Route not found. HTTP 404 Error
     */
    const ERROR_NOT_FOUND = 1;

    /**
     * Invalid Request. HTTP 400 Error.
     */
    const ERROR_INVALID_REQUEST = 2;
}
