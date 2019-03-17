<?php

declare(strict_types=1);

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

/**
 * Class Http500Exception.
 *
 * Exception class for Internal Server Error (500)
 */
class Http500Exception extends AbstractHttpException
{
    protected $httpCode = 500;
    protected $httpMessage = 'Internal Server Error';
}
