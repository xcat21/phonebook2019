<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ServiceException;

/**
 * Class AbstractHttpException.
 *
 * Runtime Exceptions abstract constructors, properties and methods for all child exception classes.
 */
abstract class AbstractHttpException extends \RuntimeException
{
    // Possible fields in exception body
    public const KEY_CODE = 'error';
    public const KEY_DETAILS = 'details';
    public const KEY_MESSAGE = 'error_description';

    // HTTP result code
    protected $httpCode = null;

    // HTTP error message
    protected $httpMessage = null;

    // Error information
    protected $appError = [];

    /**
     * Exception object constructor.
     *
     * @param string     $appErrorMessage Exception message
     * @param int        $appErrorCode    Exception code
     * @param \Exception $previous        Chain of exceptions
     *
     * @throws \RuntimeException
     */
    public function __construct($appErrorMessage = null, $appErrorCode = null, \Exception $previous = null)
    {
        // Check httpCode and httpMessage are not null
        if (null === $this->httpCode || null === $this->httpMessage) {
            throw new \RuntimeException('HttpException without httpCode or httpMessage');
        }

        // Sending ServiceExceptions by the chain
        if ($previous instanceof ServiceException) {
            if (null === $appErrorCode) {
                $appErrorCode = $previous->getCode();
            }

            if (null === $appErrorMessage) {
                $appErrorMessage = $previous->getMessage();
            }
        }

        // Error information set
        $this->appError = [
            self::KEY_CODE => $appErrorCode,
            self::KEY_MESSAGE => $appErrorMessage,
        ];

        parent::__construct($this->httpMessage, $this->httpCode, $previous);
    }

    /**
     * Returns client error description.
     *
     * @return array|null
     */
    public function getAppError()
    {
        return $this->appError;
    }

    /**
     * Adding error array to the general chain.
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

        // Let throw upstairs
        return $this;
    }
}
