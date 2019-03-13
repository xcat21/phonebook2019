<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 12:55
 */

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

/**
 * Class Http404Exception
 *
 * Execption class for Not Found Error (404)
 *
 * @package App\Lib\Exceptions
 */
class Http404Exception extends AbstractHttpException
{
    protected $httpCode = 404;
    protected $httpMessage = 'Not Found';
}
