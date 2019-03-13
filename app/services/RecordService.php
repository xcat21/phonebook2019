<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:08
 */

namespace App\Services;

use App\Models\Record;

/**
 * Business-logic for users
 *
 * Class UsersService
 */
class RecordService extends AbstractService
{
    /** Unable to create record */
    const ERROR_UNABLE_CREATE_USER = 11001;

    /** Record not found */
    const ERROR_USER_NOT_FOUND = 11002;

    /** No such record */
    const ERROR_INCORRECT_USER = 11003;

    /** Unable to update record */
    const ERROR_UNABLE_UPDATE_USER = 11004;

    /** Unable to delete record */
    const ERROR_UNABLE_DELETE_USER = 1105;

    /** Key to store records in cache */
    const CACHE_KEY = 'all_records';


    /**
     * Returns record details by ID
     *
     * @param string $id
     * @return array
     */
    public function getItemById($id)
    {
        try {
                $findRecord = Record::findFirst($id);

                if (!$findRecord) {
             //       $this->cache->save(self::CACHE_KEY, []);
                    return [];
                }

             //   $this->cache->save(self::CACHE_KEY, $resultRecord);

            $recordResult = $findRecord->toArray();
          //  $cachedUsers = array_combine(array_column( $usersResult, 'id'), $usersResult );



            return $recordResult;

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
