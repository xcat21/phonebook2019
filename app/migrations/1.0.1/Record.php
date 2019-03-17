<?php

declare(strict_types=1);
use Phalcon\Mvc\Model\Migration;

/**
 * Migration Class RecordMigration_101. Fills Record database with some dummy values
 * used by phalcon migration tool.
 */
class RecordMigration_101 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Insert some dummy data

        self::$connection->insert(
            'Record',
            [1, 'Luke', 'Skywalker', '+11 123 445674312', 'AR', 'Pacific/Saipan', '2019-03-12 09:22', '2019-03-12 11:43'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
        self::$connection->insert(
            'Record',
            [2, 'Chewbacca', '', '+20 333 459935766', 'GF', 'Europe/Athens', '2019-03-12 12:43:00', '2019-03-12 13:40:00'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
        self::$connection->insert(
            'Record',
            [3, 'Han', 'Solo', '+02 144 265555890', 'JM', 'Europe/Bucharest', '2019-03-15 12:43:00', ' 2019-03-15 18:40:00'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
        self::$connection->insert(
            'Record',
            [4, 'Moff Kohl', 'Seerdon', '+44 333 265786344', 'SC', 'America/Denver', ' 2019-03-11 10:43:00', '2019-03-15 15:20:00'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
        self::$connection->insert(
            'Record',
            [5, 'Darth', 'Vader', '+99 876 265111657', 'VU', 'Antarctica/DumontDUrville', ' 2019-03-11 10:43:00', '2019-03-15 15:20:00'],
            ['id', 'fName', 'lName', 'phone', 'countryCode', 'timeZone', 'insertedOn', 'updatedOn']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        self::$connection->execute('TRUNCATE TABLE `Record`;');
    }
}
