<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 13:08.
 */

namespace App\Services;

use App\Models\Record;

/**
 * Business-logic for users.
 *
 * Class UsersService
 */
class RecordService extends AbstractService
{
    /** Unable to create record */
    public const ERROR_UNABLE_CREATE_RECORD = 11001;

    /** Record not found */
    public const ERROR_RECORD_NOT_FOUND = 11002;

    /** No such record */
    public const ERROR_INCORRECT_RECORD = 11003;

    /** Unable to update record */
    public const ERROR_UNABLE_UPDATE_RECORD = 11004;

    /** Unable to delete record */
    public const ERROR_UNABLE_DELETE_RECORD = 1105;

    /** Key to store records in cache */
    public const CACHE_KEY = 'all_records';

    /**
     * Returns record details by ID.
     *
     * @param string $id
     *
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

    /**
     * Returns records list.
     *
     * @param string $id
     * @param mixed  $limit
     * @param mixed  $offset
     *
     * @return array
     */
    public function getItemList($limit, $offset)
    {
        try {
            //    $cachedRecords = $this->cache->get(self::CACHE_KEY);

            //    if (is_null($cachedRecords)) {

            $records = Record::find(
                    [
                        'conditions' => '',
                        'bind' => [],
                        'columns' => '*',
                        'limit' => $limit,
                        'offset' => $offset,
                    ]
                );

            if (!$records) {
                //    $this->cache->save(self::CACHE_KEY, []);
                return [];
            }

            $recordsResult = $records->toArray();

            // $cachedUsers = array_combine(array_column( $usersResult, 'id'), $usersResult );
            // $this->cache->save(self::CACHE_KEY, $cachedUsers);
            //    }

            return $recordsResult;
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list by search.
     *
     * @param string $name
     *
     * @return array
     */
    public function getItemListSearch($name)
    {
        if (empty($name)) {
            return [];
        }

        try {
            //    $cachedRecords = $this->cache->get(self::CACHE_KEY);

            //    if (is_null($cachedRecords)) {

            $query = Record::query();

            $query->where('fName LIKE :name: OR lName LIKE :name:');
            $query->bind([
                               'name' => '%'.$name.'%',
                           ]);

            $records = $query->execute();

            if (!$records) {
                //    $this->cache->save(self::CACHE_KEY, []);
                return [];
            }

            $recordsResult = $records->toArray();

            // $cachedUsers = array_combine(array_column( $usersResult, 'id'), $usersResult );
            // $this->cache->save(self::CACHE_KEY, $cachedUsers);
            //    }

            return $recordsResult;
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list by search.
     *
     * @param string $name
     * @param mixed  $data
     *
     * @return array
     */
    public function createRecord($data)
    {
        try {
            $record = new Record();

            $result = $record
              ->setFirstName($data['firstName'])
              ->setLastName($data['lastName'])
              ->setPhone($data['phoneNumber'])
              ->setCountryCode($data['countryCode'])
              ->setTimeZone($data['timeZone'])
              ->setInsertedOn()
              ->setUpdatedOn()
              ->create();

            if (!$result) {
                throw new ServiceException('Unable to create record', self::ERROR_UNABLE_CREATE_RECORD);
            }

            return ['location' => 'http://api.phonebook.loc:8000/v1/phonebook/'.$record->id];
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list by search.
     *
     * @param string $data
     *
     * @return array
     */
    public function updateRecord($data)
    {
        try {
            $record = Record::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $data['id'],
                    ],
                ]
            );

            $data['firstName'] = (null === $data['firstName']) ? $record->getFirstName() : $data['firstName'];
            $data['lastName'] = (null === $data['lastName']) ? $record->getLastName() : $data['lastName'];
            $data['phoneNumber'] = (null === $data['phoneNumber']) ? $record->getPhone() : $data['phoneNumber'];
            $data['countryCode'] = (null === $data['countryCode']) ? $record->getCountryCode() : $data['countryCode'];
            $data['timeZone'] = (null === $data['timeZone']) ? $record->getTimeZone() : $data['timeZone'];

            $result = $record
                ->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setPhone($data['phoneNumber'])
                ->setCountryCode($data['countryCode'])
                ->setTimeZone($data['timeZone'])
                ->setUpdatedOn()
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update record', self::ERROR_UNABLE_UPDATE_RECORD);
            }

            $this->logger->info('Record with ID = '.$record->id.' successfully updated.');

            return [];
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list by search.
     *
     * @param string $id
     *
     * @return array
     */
    public function deleteRecord($id)
    {
        try {
            $record = Record::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $id,
                    ],
                ]
            );

            if (!$record) {
                throw new ServiceException('Record not found', self::ERROR_RECORD_NOT_FOUND);
            }

            $result = (array) $record->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete record', self::ERROR_UNABLE_DELETE_RECORD);
            }

            // Deleting from cache if exists
            // $cachedUsers = $this->cache->get(self::CACHE_KEY);
            // if (!is_null($cachedUsers) && isset($cachedUsers[$userId])) {
            //    unset($cachedUsers[$userId]);
            //    $this->cache->save(self::CACHE_KEY, $cachedUsers);
            // }

            return $result;
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
