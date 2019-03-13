<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:08
 */

namespace App\Services;

/**
 * Class ServiceException
 *
 * Runtime exception which is generated on the service level. It signals about an error in business logic.
 *
 * @package App\Exceptions
 */
class ServiceException extends \RuntimeException
{
}
