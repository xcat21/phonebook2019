<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class PhonebookitemMigration_100
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
                            'size' => 200,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'lName',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 200,
                            'after' => 'fname'
                        ]
                    ),
                    new Column(
                        'phone',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'lname'
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
                            'size' => 60,
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
                    new Index('PRIMARY', ['id'], 'PRIMARY')
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

        self::$connection->insert(
            'Record',
            [1, 'Luke','Skywalker','+11 123 445674312', 'AR', 'Pacific/Saipan', '2019-03-12 09:22','2019-03-12 11:43'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);

        self::$connection->insert(
            'Record',
            [2, 'Chewbacca','','+20 333 459935766', 'GF', 'Europe/Athens', '2019-03-12 12:43:00','2019-03-12 13:40:00'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);

        self::$connection->insert(
            'Record',
            [3, 'Han','Solo','+02 144 265555890', 'JM', 'Europe/Bucharest', '2019-03-15 12:43:00',' 2019-03-15 18:40:00'],
            ['id','fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);

        self::$connection->insert(
            'Record',
            [4, 'Moff Kohl','Seerdon','+44 333 265786344', 'SC', 'America/Denver', ' 2019-03-11 10:43:00','2019-03-15 15:20:00'],
            ['id','fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);

        self::$connection->insert(
            'Record',
            [5, 'Darth','Vader','+99 876 265111657', 'VU', 'Antarctica/DumontDUrville', ' 2019-03-11 10:43:00','2019-03-15 15:20:00'],
            ['id','fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
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
