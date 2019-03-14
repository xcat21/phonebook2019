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
 * Operations with Users: CRUD
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

    public function getDoc() {
        return ["t" => 200];

    }

}
