<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 12:56
 */

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

/**
 * Class Http422Exception
 *
 * Execption class for Unprocessable entity Error (422)
 *
 * @package App\Lib\Exceptions
 */
class Http422Exception extends AbstractHttpException
{
    protected $httpCode = 422;
    protected $httpMessage = 'Unprocessable entity';
}
