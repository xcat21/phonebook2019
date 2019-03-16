<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:01.
 */

namespace App\Controllers;

use App\Services\ServiceException;

/**
 * Class AbstractHttpException.
 *
 * Runtime Exceptions
 */
abstract class AbstractHttpException extends \RuntimeException
{
    /**
     * Possible fields in the answer body.
     */
    public const KEY_CODE = 'error';
    public const KEY_DETAILS = 'details';
    public const KEY_MESSAGE = 'error_description';

    /**
     * http result code.
     *
     * @var null
     */
    protected $httpCode = null;

    /**
     * http error message.
     *
     * @var null
     */
    protected $httpMessage = null;

    /**
     * Error info.
     *
     * @var array
     */
    protected $appError = [];

    /**
     * @param string     $appErrorMessage Exception message
     * @param int        $appErrorCode    Exception code
     * @param \Exception $previous        Chain of exceptions
     *
     * @throws \RuntimeException
     */
    public function __construct($appErrorMessage = null, $appErrorCode = null, \Exception $previous = null)
    {
        if (null === $this->httpCode || null === $this->httpMessage) {
            throw new \RuntimeException('HttpException without httpCode or httpMessage');
        }

        // Sending ServiceExceptions along the chain
        if ($previous instanceof ServiceException) {
            if (null === $appErrorCode) {
                $appErrorCode = $previous->getCode();
            }

            if (null === $appErrorMessage) {
                $appErrorMessage = $previous->getMessage();
            }
        }

        $this->appError = [
            self::KEY_CODE => $appErrorCode,
            self::KEY_MESSAGE => $appErrorMessage,
        ];

        parent::__construct($this->httpMessage, $this->httpCode, $previous);
    }

    /**
     * Returns client error.
     *
     * @return array|null
     */
    public function getAppError()
    {
        return $this->appError;
    }

    /**
     * Adding error array.
     *
     * @param array $fields Array with errors
     *
     * @return $this
     */
    public function addErrorDetails(array $fields)
    {
        if (\array_key_exists(self::KEY_DETAILS, $this->appError)) {
            $fields = array_merge($this->appError[self::KEY_DETAILS], $fields);
        }
        $this->appError[self::KEY_DETAILS] = $fields;

        // For throw
        return $this;
    }
}
