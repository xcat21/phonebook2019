<?php

declare(strict_types=1);

class DeleteItemsCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function deleteRecord(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (200) after successfully deleted record');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $Db->seeInDatabase('Record', [
        'id' => 3,
        'fName' => 'Han',
        'lName' => 'Solo',
        'phone' => '+02 144 265555890',
        'countryCode' => 'JM',
        'timeZone' => 'Europe/Bucharest',
        'insertedOn' => '2019-03-15 12:43:00',
        'updatedOn' => '2019-03-15 18:40:00',
    ]);
        $I->sendDELETE('v1/phonebook/3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseEquals(null);
        $Db->dontSeeInDatabase('Record', [
            'id' => '3',
            'fName' => 'Han',
            'lName' => 'Solo',
            'phone' => '+02 144 265555890',
            'countryCode' => 'JM',
            'timeZone' => 'Europe/Bucharest',
            'insertedOn' => '2019-03-15 12:43:00',
            'updatedOn' => '2019-03-15 18:40:00',
        ]);
    }

    public function deleteRecordNotExist(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (422) after trying to delete non-existing record');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $Db->seeInDatabase('Record', [
            'id' => 3,
            'fName' => 'Han',
            'lName' => 'Solo',
            'phone' => '+02 144 265555890',
            'countryCode' => 'JM',
            'timeZone' => 'Europe/Bucharest',
            'insertedOn' => '2019-03-15 12:43:00',
            'updatedOn' => '2019-03-15 18:40:00',
        ]);
        $I->sendDELETE('v1/phonebook/45');
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('{"error":11002,"error_description":"Record not found"}');
    }
}
