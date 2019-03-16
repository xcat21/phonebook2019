<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RecordMigration_100
 */
class RecordMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('Record', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'fName',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'default' => null,
                            'size' => 60,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'lName',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 60,
                            'after' => 'fName'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'lName'
                        ]
                    ),
                    new Column(
                        'countryCode',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 2,
                            'after' => 'phone'
                        ]
                    ),
                    new Column(
                        'timeZone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 40,
                            'after' => 'countryCode'
                        ]
                    ),
                    new Column(
                        'insertedOn',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'timeZone'
                        ]
                    ),
                    new Column(
                        'updatedOn',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'insertedOn'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('i_fName_fulltext', ['fName'], 'FULLTEXT'),
                    new Index('i_lName_fulltext', ['lName'], 'FULLTEXT'),
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '5',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8mb4_general_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        self::$connection->dropTable('Record');
        $this->morph();
    }


    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        self::$connection->dropTable('Record');
    }

}
