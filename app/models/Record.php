<?php

namespace App\Models;

class Record extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $fName;

    /**
     *
     * @var string
     */
    public $lName;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $countryCode;

    /**
     *
     * @var string
     */
    public $timeZone;

    /**
     *
     * @var string
     */
    public $insertedOn;

    /**
     *
     * @var string
     */
    public $updatedOn;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phonebook_db");
        $this->setSource("phoneBookItem");
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
// ----
    /**
     * Method to set the value of field first_name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->fName = $firstName;
        return $this;
    }

    /**
     * Returns the value of field firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->fName;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lName = $lastName;
        return $this;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lName;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $timeZone
     * @return $this
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Method to set the value of field last_name
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
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getInsertedOn()
    {
        return $this->insertedOn;
    }

    /**
     * Method to set the value of field last_name
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
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }


// ----



    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Record[]|Record|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Record|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
