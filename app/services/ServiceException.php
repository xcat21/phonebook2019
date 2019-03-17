<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Class ServiceException.
 * Runtime exception which is generated on the service level - errors in business logic.
 */
class ServiceException extends \RuntimeException
{
}
