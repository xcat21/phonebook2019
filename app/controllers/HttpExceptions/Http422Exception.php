<?php

declare(strict_types=1);

namespace App\Controllers\HttpExceptions;

use App\Controllers\AbstractHttpException;

/**
 * Class Http422Exception.
 *
 * Exception class for Unprocessable entity Error (422)
 */
class Http422Exception extends AbstractHttpException
{
    protected $httpCode = 422;
    protected $httpMessage = 'Unprocessable entity';
}
