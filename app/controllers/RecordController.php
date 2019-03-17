<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\RecordService;
use App\Services\ServiceException;
use GuzzleHttp\Client as guzzleClient;
use GuzzleHttp\Exception as guzzleException;
use GuzzleHttp\Promise as guzzlePromise;

/**
 * Main controller for phonebook API - operations with Records: CRUD.
 */
class RecordController extends AbstractController
{
    /**
     * Returns record by ID in array format.
     *
     * @param string $id
     *
     * @return array
     */
    public function getItemByIdAction(string $id)
    {
        try {
            // Get an item from service

            /** @var array $record Item to be retreived by ID from Service level */
            $record = $this->recordService->getItemById($id);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        // Return result back to API
        return $record;
    }

    /**
     * Returns records list with limit and offset parameters.
     *
     * @return array
     */
    public function getItemListAction()
    {
        // Getting GET params for pagination

        /** @var int $limit Number of records to select */
        $limit = $this->request->getQuery('limit', 'int', 100);

        /** @var int $offset Start point of selection of the records */
        $offset = $this->request->getQuery('offset', 'int', 0);

        // Align human-understandable offset (offset =0 & offset =1 returns same)
        // Offset = 12 means we will get 12th element from collection first, not 13th
        $offset = $offset > 0 ? $offset - 1 : 0;

        try {
            // Get an items from service

            /** @var mixed $records List of items from service */
            $records = $this->recordService->getItemList((int) $limit, (int) $offset);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        // Return result back to API
        return $records;
    }

    /**
     * Returns records list by search in first or last names.
     *
     * @return array
     */
    public function getItemListSearchAction()
    {
        // Getting GET params for search

        /** @var string $name Key (Needle) for records search */
        $name = $this->request->getQuery('name', 'string', '');

        // Process name sanitization for security
        $name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        try {
            // Get an items from service

            /** @var mixed $records List of items from service */
            $records = $this->recordService->getItemListSearch($name);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        // Return result back to API
        return $records;
    }

    /**
     * Creates item in phonebook.
     *
     * @return array
     */
    public function addItemAction()
    {
        // Get request body - values for insert

        /** @var mixed $data Request body in JSON format - fields data to be inserted */
        $data = (array) $this->request->getJsonRawBody();

        // Perform validation of values
        $errors = $this->validateRecord($data);

        // Check for any validation errors stacked in errors array
        if ($errors) {
            $this->logger->error('['.__METHOD__.'] Validation error acquired:'.print_r($errors, true));
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            // Validation is OK, try to create record via service

            /** @var array $result Result of record creation in database */
            $result = $this->recordService->createRecord($data);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());

            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case RecordService::ERROR_UNABLE_CREATE_RECORD:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        // Return result back to API
        return $result;
    }

    /**
     * Updates item in phonebook by ID.
     *
     * @param string $id
     *
     * @return array
     */
    public function updateRecordAction(string $id)
    {
        // Get request body - values for update

        /** @var array $data Fields from API to be updated */
        $data = (array) $this->request->getJsonRawBody();

        // Set id from parameter to the item
        $data['id'] = (int) $id;

        // Perform validation - required fields could be null as not all the fields possibly are sent via API
        $errors = $this->validateRecord($data, false);

        // Check for any validation errors stacked in errors array
        if ($errors) {
            $this->logger->error('['.__METHOD__.'] Validation error acquired:'.print_r($errors, true));
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            // Try to update Record as validation is OK and we have all the data

            /** @var array $result Result of record update in database */
            $result = $this->recordService->updateRecord($data);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.']['.$e->getCode().'] Unable to update: '.$e->getMessage());
            switch ($e->getCode()) {
                case RecordService::ERROR_UNABLE_CREATE_RECORD:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                case RecordService::ERROR_RECORD_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        // Return result back to API
        return $result;
    }

    /**
     * Deletes record from phonebook by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function deleteRecordAction(int $id)
    {
        // Check if $id is positive integer
        if (!ctype_digit($id) || ($id < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        try {
            // Try to delete item via service

            $this->recordService->deleteRecord((int) $id);
        } catch (ServiceException $e) {
            $this->logger->error('['.__METHOD__.']['.$e->getCode().'] Unable to delete: '.$e->getMessage());
            switch ($e->getCode()) {
                case recordService::ERROR_UNABLE_DELETE_RECORD:
                case recordService::ERROR_RECORD_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        // Return result back to API with "deleted" flag
        return ['deleted' => true];
    }

    /**
     * Validates record fields for insert and update on controller level to fail faster.
     *
     * @param $data array
     * @param $isCreate bool
     *
     * @return array
     */
    protected function validateRecord(array $data, bool $isCreate = true)
    {
        // Init errors array for validation problems list
        $errors = [];

        // Validate data by fields

        // First name validation
        if ($isCreate || !empty($data['firstName'])) { // Don't validate empty value on Update
            if (!\is_string($data['firstName'])
                || mb_strlen($data['firstName'], 'UTF-8') > 60
                || mb_strlen($data['firstName'], 'UTF-8') < 1) {
                $errors['firstName'] = 'First name must consist of 1-60 symbols';
            }
        }

        // Last name validation
        if (!empty($data['lastName'])) {
            if (!\is_string($data['lastName'])
                || mb_strlen($data['lastName'], 'UTF-8') > 60
                || mb_strlen($data['lastName'], 'UTF-8') < 1) {
                $errors['lastName'] = 'Last name must consist of 1-60 symbols';
            }
        }

        // Phone number validation
        if ($isCreate || !empty($data['phoneNumber'])) { // Don't validate empty value on Update
            if (!\is_string($data['phoneNumber'])
                || !preg_match('/^\+([0-9]{2})(\s|-)([0-9]{3})(\s|-)([0-9]{9})$/', $data['phoneNumber'])) {
                $errors['phoneNumber'] = 'Phone number must be in format: +XX XXX XXXXXXXXX';
            }
        }

        // Country code pre-validation
        if (!empty($data['countryCode'])) {
            if (!\is_string($data['countryCode']) || 2 != mb_strlen($data['countryCode'], 'UTF-8')) {
                $errors['countryCode'] = 'Country code must consist of 2 symbols';
            }
        }
        // TimeZone pre-validation
        if (!empty($data['timeZone'])) {
            if (!\is_string($data['timeZone'])
                || mb_strlen($data['timeZone'], 'UTF-8') > 40
                || mb_strlen($data['timeZone'], 'UTF-8') < 3) {
                $errors['timeZone'] = 'Time zone must consist of 3-40 symbols';
            }
        }

        // Perfom external validation of countryCode and timeZone to collect all the validation problems
        /** @var array $externalValidation Result of validation based on external API services */
        $externalValidation = $this->validateExternal($data['countryCode'], $data['timeZone']);

        // Concat basic validation errors with external ones
        $errors = array_merge($errors, $externalValidation);

        // Return full list of problems founded. No validation problems if array is empty
        return $errors;
    }

    /**
     * Validates data for external APIs for countryCode and timeZone fields.
     *
     * @param $countryCode string
     * @param $timeZone string
     *
     * @throws /guzzleException in case of problem to get data from external APIs
     *
     * @return array
     */
    protected function validateExternal($countryCode = '', $timeZone = '')
    {
        // Init error container

        /** @var array $extErrors Array to collect external validation problems */
        $extErrors = [];

        try {
            // Create new guzzle HTTP client to get external APIs

            /** @var /guzzleClient $client Handler of HTTP client requests */
            $client = new guzzleClient([
                'base_uri' => 'https://api.hostaway.com/',
                'http_errors' => false,
            ]);

            // Initiate each async request external API but do not block

            /** @var /guzzlePromise|array $promises Promises of objects to be retrieved from ext API */
            $promises = [
                'countryCodes' => $client->getAsync('/countries'),
                'timeZones' => $client->getAsync('/timezones'),
            ];

            // Unwrap promises
            /** @var object $results Object contained external API dictionaries */
            $results = guzzlePromise\unwrap($promises);

            // Extract countryCodes and timeZones from dictionaries
            /** @var array $cCodes List of proper countryCodes */
            $cCodes = array_keys(json_decode((string) $results['countryCodes']->getBody(), true)['result']);

            /** @var array $tZones List of proper timeZOnes */
            $tZones = array_keys(json_decode((string) $results['timeZones']->getBody(), true)['result']);

            // Perform validation id values provided are in dictionaries or not
            /** @var bool $validCountry Flag if countryCode is valid */
            $validCountry = (empty($countryCode) || \in_array($countryCode, $cCodes)) ? true : false;

            /** @var bool $validZone Flag if timeZone is valid */
            $validZone = (empty($timeZone) || \in_array($timeZone, $tZones)) ? true : false;

            // Check and fill errors container with validation results

            if (!$validCountry) {
                $extErrors['countryCodeAPI'] = 'Country code must be presented on external API';
            }

            if (!$validZone) {
                $extErrors['timeZoneAPI'] = 'Time zone must be presented on external API';
            }

            // Return errors container with validation results
            return $extErrors;
        } catch (guzzleException\ClientException $e) {
            $this->logger->error('['.__METHOD__.']['.$e->getCode().'] Unable to validate: '.$e->getMessage());
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
    }
}
