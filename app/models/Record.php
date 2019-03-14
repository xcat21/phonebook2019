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
     * @param string $first_name
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Returns the value of field first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
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
