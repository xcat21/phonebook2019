<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 22:30
 */

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\RecordService;

/**
 * Operations with Records: CRUD
 */
class RecordController extends AbstractController
{

    /**
     * Returns record by ID
     *
     * @param string $id
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
     * Returns records list
     *
     * @return array
     */
    public function getItemListAction()
    {
       // Getting GET params for pagination
       $limit =  $this->request->getQuery('limit', 'int', 100);
       $offset = $this->request->getQuery('offset', 'int', 0);

       // Align human-understandable offset (offset =0 & offset =1 returns same)
       // Offset = 12 means we will get 12th element from collection first, not 13th
       $offset = $offset >0 ? $offset - 1 : 0;


        try {
            $records = $this->recordService->getItemList($limit, $offset);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $records;
    }

    /**
     * Returns records list
     *
     * @return array
     */
    public function getItemListSearchAction()
    {
        // Getting GET params for search
        $name =  $this->request->getQuery('name', 'string', '');

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
     * Returns records list
     *
     * @return array
     */
    public function addItemAction()
    {
        // Init
        $errors = [];

        $data = (array) $this->request->getJsonRawBody();

        // Add validation here

        try {
            $this->recordService->createRecord($data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case RecordService::ERROR_UNABLE_CREATE_RECORD:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }

        return ["result" => "ok"];

    }

    /**
     * Returns records list
     *
     * @param string $id
     * @return array
     */
    public function updateRecordAction($id)
    {
        // Init
        $errors = [];

        $data = (array) $this->request->getJsonRawBody();
        $data["id"] = (int) $id;

        // Add validation here

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
     * Returns records list
     *
     * @param string $id
     * @return array
     */
    public function deleteRecordAction($id)
    {
        if (!ctype_digit($id) || ($id < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        try {
            $result = $this->recordService->deleteRecord((int)$id);
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


}
