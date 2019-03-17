<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Model class for RECORD table in database. Handles setters, getters and low-level operations.
 *
 * @property $id integer
 * @property $fName string
 * @property $lName string
 * @property $phone string
 * @property $countryCode string
 * @property $timeZone string
 * @property $insertedOn Datetime
 * @property $updatedOn Datetime
 */
class Record extends \Phalcon\Mvc\Model
{
    /** @var int $id ID of the record in database. Auto-incremented */
    public $id;

    /** @var string $fName First name of the person */
    public $fName;

    /** @var string $lName Last name of the person */
    public $lName;

    /** @var string $phone Phone number of the person in format +XX XXX XXXXXXXXX */
    public $phone;

    /** @var string $countryCode Two chars code of the person's country based on ext API list */
    public $countryCode;

    /** @var string $timeZone Time zone of the person based on ext API list */
    public $timeZone;

    /** @var \DateTime $insertedOn Time when record has been created in phonebook */
    public $insertedOn;

    /** @var \DateTime $updatedOn Time when record has been updated in phonebook */
    public $updatedOn;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        // Set schema and source for table Record

        $this->setSchema('phonebook_db');
        $this->setSource('Record');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Record';
    }

    /**
     * Method to set the value of field first_name.
     *
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->fName = $firstName;

        return $this;
    }

    /**
     * Returns the value of field firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->fName;
    }

    /**
     * Method to set the value of field last_name.
     *
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lName = $lastName;

        return $this;
    }

    /**
     * Returns the value of field lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lName;
    }

    /**
     * Method to set the value of field phone.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Returns the value of field phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Method to set the value of field countryCode.
     *
     * @param string $countryCode
     *
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Returns the value of field countryCode.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Method to set the value of field timeZone.
     *
     * @param string $timeZone
     *
     * @return $this
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Returns the value of field timeZone.
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Method to set the value of field insertedOn.
     *
     * @return $this
     */
    public function setInsertedOn()
    {
        $date = date('Y-m-d H:i:s');
        $this->insertedOn = $date;

        return $this;
    }

    /**
     * Returns the value of field insertedOn.
     *
     * @return string
     */
    public function getInsertedOn()
    {
        return $this->insertedOn;
    }

    /**
     * Method to set the value of field updatedOn.
     *
     * @return $this
     */
    public function setUpdatedOn()
    {
        $date = date('Y-m-d H:i:s');
        $this->updatedOn = $date;

        return $this;
    }

    /**
     * Returns the value of field updatedOn.
     *
     * @return string
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Allows to query a set of records that match the specified conditions.
     *
     * @param mixed $parameters
     *
     * @return \Phalcon\Mvc\Model\ResultSetInterface|Record|Record[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions.
     *
     * @param mixed $parameters
     *
     * @return \Phalcon\Mvc\Model\ResultInterface|Record
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
