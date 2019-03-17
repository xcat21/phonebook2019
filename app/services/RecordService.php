<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Record;

/**
 * Class recordService implements business-logic for records CRUD.
 */
class RecordService extends AbstractService
{
    /** @constant ERROR_UNABLE_CREATE_RECORD Unable to create record */
    public const ERROR_UNABLE_CREATE_RECORD = 11001;

    /** @constant ERROR_RECORD_NOT_FOUND Record not found */
    public const ERROR_RECORD_NOT_FOUND = 11002;

    /** @constant ERROR_INCORRECT_RECORD No such record */
    public const ERROR_INCORRECT_RECORD = 11003;

    /** @constant ERROR_UNABLE_UPDATE_RECORD Unable to update record */
    public const ERROR_UNABLE_UPDATE_RECORD = 11004;

    /** @constant ERROR_UNABLE_DELETE_RECORD Unable to delete record */
    public const ERROR_UNABLE_DELETE_RECORD = 1105;

    /** @constant CACHE_KEY_EXT Key to store External API data in cache */
    public const CACHE_KEY_EXT = 'external_api';

    /**
     * Returns record details by ID.
     *
     * @param string $id
     *
     * @return array
     */
    public function getItemById(string $id)
    {
        try {
            /** @var /App\Models\Record $findRecord record container */
            $findRecord = Record::findFirst($id);

            // If no records with provided id returns empty set
            if (!$findRecord) {
                return [];
            }

            /** @var $recordResult array to be returned and for possible format transforms */
            $recordResult = $findRecord->toArray();

            // Put the event to the log
            $this->logger->info('['.__METHOD__.']Record with id '.$id.' successfully returned.');

            // Return record found
            return $recordResult;
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns records list w/o search with limit and offset.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getItemList(int $limit = 100, int $offset = 0)
    {
        try {
            // Find records in DB

            /** @var /App\Models\Record $records container for data-set */
            $records = Record::find(
                    [
                        'conditions' => '',
                        'bind' => [],
                        'columns' => '*',
                        'limit' => $limit,
                        'offset' => $offset,
                    ]
                );

            if (!$records) { // No records found
                $this->logger->info('['.__METHOD__.'] No records found with provided limit and offset');

                // Return empty set as no records found
                return [];
            }

            /** @var mixed $recordsResult Final result to return as Array */
            $recordsResult = $records->toArray();

            $this->logger->info('['.__METHOD__.']Records list successfully found.');

            //Return result if any records are found
            return $recordsResult;
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
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
    public function getItemListSearch(string $name)
    {
        // Check if name is empty to return empty set fast
        if (empty($name)) {
            return [];
        }

        try {
            // Try to find records like $name in firstName and lastName

            /** @var $query to be executed to search records from DB */
            $query = Record::query();

            // Set conditions and bind parameters

            $query->where('fName LIKE :name: OR lName LIKE :name:');
            $query->bind([
                               'name' => '%'.$name.'%',
                           ]);

            // Execute query to find records

            /** @var mixed $records Dataset of founded records from DB */
            $records = $query->execute();

            if (!$records) { // No records found
                $this->logger->info('['.__METHOD__.'] No records found with provided NAME search string');

                // Return empty set to controller
                return [];
            }
            /** @var mixed $recordsResult Final result to return as Array */
            $recordsResult = $records->toArray();

            $this->logger->info('['.__METHOD__.']Records list by search successfully found.');

            //Return result if any records are found
            return $recordsResult;
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Creates new record.
     *
     * @param array $data
     *
     * @return array
     */
    public function createRecord(array $data)
    {
        try {
            // Create new model

            /** @var /App\Models\Record $record Dataset container for new model */
            $record = new Record();

            // Fill model fields from $data parameter

            /** @var bool $result Result of model creation in DB */
            $result = $record
              ->setFirstName($data['firstName'])
              ->setLastName($data['lastName'])
              ->setPhone($data['phoneNumber'])
              ->setCountryCode($data['countryCode'])
              ->setTimeZone($data['timeZone'])
              ->setInsertedOn()
              ->setUpdatedOn()
              ->create();

            // Situation handling if record has not been created
            if (!$result) {
                $this->logger->info('['.__METHOD__.']Record with name '.$record->getFirstName().''.$record->getLastName().' has not been created');
                throw new ServiceException('Unable to create record', self::ERROR_UNABLE_CREATE_RECORD);
            }

            $this->logger->info('['.__METHOD__.']Record with ID = '.$record->id.' successfully created.');

            // Returns Location header string to be inserted to HTTP header with $record->id
            return ['location' => 'http://api.phonebook.loc:8000/v1/phonebook/'.$record->id];
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Update record.
     *
     * @param array $data
     *
     * @return array
     */
    public function updateRecord(array $data)
    {
        try {
            // Find model to be updated

            /** @var /App\Models\Record $record Dataset container for model to be updated */
            $record = Record::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $data['id'],
                    ],
                ]
            );

            // Check if model found
            if (!$record) {
                // Handle situation when model is not found
                $this->logger->error('['.__METHOD__.']Record is not found.');
                throw new ServiceException('Record not found', self::ERROR_RECORD_NOT_FOUND);
            }

            // Map fields from $data with model

            $data['firstName'] = (null === $data['firstName']) ? $record->getFirstName() : $data['firstName'];
            $data['lastName'] = (null === $data['lastName']) ? $record->getLastName() : $data['lastName'];
            $data['phoneNumber'] = (null === $data['phoneNumber']) ? $record->getPhone() : $data['phoneNumber'];
            $data['countryCode'] = (null === $data['countryCode']) ? $record->getCountryCode() : $data['countryCode'];
            $data['timeZone'] = (null === $data['timeZone']) ? $record->getTimeZone() : $data['timeZone'];

            // Try ti update record

            /** @var bool $result Result of model update in DB */
            $result = $record
                ->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setPhone($data['phoneNumber'])
                ->setCountryCode($data['countryCode'])
                ->setTimeZone($data['timeZone'])
                ->setUpdatedOn()
                ->update();

            // Situation handling if record has not been updated
            if (!$result) {
                $this->logger->info('['.__METHOD__.']Record with name '.$record->getFirstName().''.$record->getLastName().' has not been updated');
                throw new ServiceException('Unable to update record', self::ERROR_UNABLE_UPDATE_RECORD);
            }

            // Record is successfully updated
            $this->logger->info('['.__METHOD__.']Record with ID = '.$record->id.' successfully updated.');

            // Returns an empty set
            return [];
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete record by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function deleteRecord(int $id)
    {
        try {
            // Find model to delete

            /** @var /App\Models\Record $record Dataset container for model to be deleted */
            $record = Record::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind' => [
                        'id' => $id,
                    ],
                ]
            );

            // Check if model found
            if (!$record) {
                $this->logger->error('['.__METHOD__.']Record is not found.');
                throw new ServiceException('Record not found', self::ERROR_RECORD_NOT_FOUND);
            }

            // Try to delete model

            /** @var array $result Result of model deletion in DB */
            $result = (array) $record->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete record', self::ERROR_UNABLE_DELETE_RECORD);
            }

            // Record is successfully deleted
            $this->logger->info('['.__METHOD__.']Record with ID = '.$record->id.' successfully deleted.');

            // Return result of record deletion
            return $result;
        } catch (\PDOException $e) {
            $this->logger->error('['.__METHOD__.'] Exception raised.'.$e->getMessage());
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
