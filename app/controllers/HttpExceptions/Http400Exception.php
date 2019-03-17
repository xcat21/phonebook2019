<?php

declare(strict_types=1);

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

/**
 * Class Http400Exception.
 *
 * Exception class for Bad Request Error (400)
 */
class Http400Exception extends AbstractHttpException
{
    protected $httpCode = 400;
    protected $httpMessage = 'Bad request';
}
