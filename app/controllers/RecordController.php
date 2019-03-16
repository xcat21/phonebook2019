<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 22:30.
 */

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
 * Operations with Records: CRUD.
 */
class RecordController extends AbstractController
{
    /**
     * Returns record by ID.
     *
     * @param string $id
     *
     * @return array
     */
    public function getItemByIdAction($id)
    {
        try {
            $record = $this->recordService->getItemById($id);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $record;
    }

    /**
     * Returns records list.
     *
     * @return array
     */
    public function getItemListAction()
    {
        // Getting GET params for pagination
        $limit = $this->request->getQuery('limit', 'int', 100);
        $offset = $this->request->getQuery('offset', 'int', 0);

        // Align human-understandable offset (offset =0 & offset =1 returns same)
        // Offset = 12 means we will get 12th element from collection first, not 13th
        $offset = $offset > 0 ? $offset - 1 : 0;

        try {
            $records = $this->recordService->getItemList($limit, $offset);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $records;
    }

    /**
     * Returns records list.
     *
     * @return array
     */
    public function getItemListSearchAction()
    {
        // Getting GET params for search
        $name = $this->request->getQuery('name', 'string', '');

        // Add name validation
        /*    if (empty($name)) { // Add regexp
                $errors['search_string'] = 'Search string should be passed to API';
            }

            if ($errors) {
                $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
                throw $exception->addErrorDetails($errors);
            }
        */

        try {
            $records = $this->recordService->getItemListSearch($name);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $records;
    }

    /**
     * Returns records list.
     *
     * @return array
     */
    public function addItemAction()
    {
        $data = (array) $this->request->getJsonRawBody();

        // Add validation here
        $errors = $this->validateRecord($data);

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $result = $this->recordService->createRecord($data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case RecordService::ERROR_UNABLE_CREATE_RECORD:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        return $result;
    }

    /**
     * Returns records list.
     *
     * @param string $id
     *
     * @return array
     */
    public function updateRecordAction($id)
    {
        $data = (array) $this->request->getJsonRawBody();
        $data['id'] = (int) $id;

        // Add validation here

        // Add validation here
        $errors = $this->validateRecord($data);

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $result = $this->recordService->updateRecord($data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case RecordService::ERROR_UNABLE_CREATE_RECORD:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        return $result;
    }

    /**
     * Returns records list.
     *
     * @param string $id
     *
     * @return array
     */
    public function deleteRecordAction($id)
    {
        if (!ctype_digit($id) || ($id < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        try {
            $result = $this->recordService->deleteRecord((int) $id);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case recordService::ERROR_UNABLE_DELETE_RECORD:
                case recordService::ERROR_RECORD_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        return $result;
    }

    /**
     * Returns records list.
     *
     * @param $countryCode string
     * @param $timeZone string
     *
     * @throws
     *
     * @return array
     */
    protected function validateExternal($countryCode = '', $timeZone = '')
    {
        // Init
        $extErrors = [];

        try {
            $client = new guzzleClient([
                                        'base_uri' => 'https://api.hostaway.com/',
                                        'http_errors' => false,
                                   ]);

            // Initiate each request but do not block
            $promises = [
                'countryCodes' => $client->getAsync('/countries'),
                'timeZones' => $client->getAsync('/timezones'),
            ];

            $results = guzzlePromise\unwrap($promises);

            $cCodes = array_keys(json_decode($results['countryCodes']->getBody(), true)['result']);
            $tZones = array_keys(json_decode($results['timeZones']->getBody(), true)['result']);

            $validCountry = (empty($countryCode) || \in_array($countryCode, $cCodes)) ? true : false;
            $validZone = (empty($timeZone) || \in_array($timeZone, $tZones)) ? true : false;

            if (!$validCountry) {
                $extErrors['countryCodeAPI'] = 'Country code must be presented on external API';
            }

            if (!$validZone) {
                $extErrors['timeZoneAPI'] = 'Time zone must be presented on external API';
            }

            return $extErrors;
        } catch (guzzleException\ClientException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list.
     *
     * @param $data array
     *
     * @throws
     *
     * @return array
     */
    protected function validateRecord($data)
    {
        // init
        $errors = [];

        // First name validation
        if (!\is_string($data['firstName'])
            || mb_strlen($data['firstName'], 'UTF-8') > 60
            || mb_strlen($data['firstName'], 'UTF-8') < 1) {
            $errors['firstName'] = 'First name must consist of 1-60 symbols';
        }

        // Last name validation
        if (!empty($data['lastName'])) {
            if (!\is_string($data['lastName'])
                || mb_strlen($data['lastName'], 'UTF-8') > 60
                || mb_strlen($data['lastName'], 'UTF-8') < 1) {
                $errors['lastName'] = 'First name must consist of 1-60 symbols';
            }
        }

        // Phone number validation
        if (!\is_string($data['phoneNumber'])
            || !preg_match('/^\+([0-9]{2})(\s|-)([0-9]{3})(\s|-)([0-9]{9})$/', $data['phoneNumber'])) {
            $errors['phoneNumber'] = 'Phone number must be in format: +XX XXX XXXXXXXXX';
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

        $externalValidation = $this->validateExternal($data['countryCode'], $data['timeZone']);
        $errors = array_merge($errors, $externalValidation);

        return $errors;
    }
}
