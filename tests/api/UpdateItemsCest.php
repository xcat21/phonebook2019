<?php

declare(strict_types=1);

class UpdateItemsCest
{
    public function _before(ApiTester $I, \Codeception\Module\Db $Db)
    {
    }

    // tests

    public function updateRecord(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (200) and update Record(3) with correct dataset');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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

        $I->sendPUT('v1/phonebook/3', [
                                                'firstName' => 'Padme',
                                                'lastName' => 'Amidala',
                                                'phoneNumber' => '+11 999 444555676',
                                                'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(204);
        $I->seeResponseEquals(null);
        $Db->seeInDatabase('Record', [
                                            'id' => '3',
                                            'fName' => 'Padme',
                                            'lName' => 'Amidala',
                                            'phone' => '+11 999 444555676',
                                            'countryCode' => 'SA',
                                            'timeZone' => 'Europe/Bucharest',
                                            'insertedOn' => '2019-03-15 12:43:00',
                                         ]);
    }

    public function updateRecordNoFirstName(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (200) and update with correct data without fName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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

        $I->sendPUT('v1/phonebook/3', [
            'lastName' => 'Amidala',
            'phoneNumber' => '+11 999 444555676',
            'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(204);
        $I->seeResponseEquals(null);
        $Db->seeInDatabase('Record', [
            'id' => '3',
            'fName' => 'Han',
            'lName' => 'Amidala',
            'phone' => '+11 999 444555676',
            'countryCode' => 'SA',
            'timeZone' => 'Europe/Bucharest',
            'insertedOn' => '2019-03-15 12:43:00',
        ]);
    }

    public function updateRecordNoPhone(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (200) and update with correct data without phone');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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

        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(204);
        $I->seeResponseEquals(null);
        $Db->seeInDatabase('Record', [
            'id' => '3',
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+02 144 265555890',
            'countryCode' => 'SA',
            'timeZone' => 'Europe/Bucharest',
            'insertedOn' => '2019-03-15 12:43:00',
        ]);
    }

    public function updateRecordfNameIncorrect(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with incorrect firstName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'PadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadme',
            'lastName' => 'Amidala',
            'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
            'or","details":{"firstName":"First name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'PadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadmePadme',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function updateRecordlNameLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with long lastName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'AmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidala',
            'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
            'or","details":{"lastName":"Last name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'AmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidalaAmidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function updateRecordPhoneWrong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with wrong phoneNumber');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 3338999888777',
            'countryCode' => 'SA',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation erro'.
            'r","details":{"phoneNumber":"Phone number must be in form'.
            'at: +XX XXX XXXXXXXXX"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 3338999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function updateRecordCountryCodeLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with long countryCode');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'SAAB',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validatio'.
            'n error","details":{"countryCode":"Country code must consist of 2 symbol'.
            's","countryCodeAPI":"Country code must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 3338999888777',
            'countryCode' => 'SAAB',
        ]);
    }

    public function updateRecordCountryCodeNotAPI(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with countryCode not in ext.API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'ZZ',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation error","d'.
            'etails":{"countryCodeAPI":"Country code must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 3338999888777',
            'countryCode' => 'ZZ',
        ]);
    }

    public function updateRecordTimeZoneNotAPI(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with timeZone not in ext.API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'US',
            'timeZone' => 'Mars/Cedonia',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation er'.
            'ror","details":{"timeZoneAPI":"Time zone must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 3338999888777',
            'countryCode' => 'US',
            'timeZone' => 'Mars/Cedonia',
        ]);
    }

    public function updateRecordTimeZoneLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error updates with timeZone in very long');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
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
        $I->sendPUT('v1/phonebook/3', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'US',
            'timeZone' => 'Mars/Cedonia/Europe/Bucharest/Mars/Cedonia/Europe/Bucharest',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation erro'.
            'r","details":{"timeZone":"Time zone must consist of 3-40 symbols","tim'.
            'eZoneAPI":"Time zone must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 3338999888777',
            'countryCode' => 'US',
            'timeZone' => 'Mars/Cedonia/Europe/Bucharest/Mars/Cedonia/Europe/Bucharest',
        ]);
    }

    public function updateRecordNotExist(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (422) error updates non-existing record');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $Db->dontSeeInDatabase('Record', [
            'id' => 45,
        ]);
        $I->sendPUT('v1/phonebook/45', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'US',
            'timeZone' => 'Europe/Bucharest',
        ]);
        $I->canSeeResponseCodeIs(422);
        $I->seeResponseContains('{"error":11002,"error_description":"Record not found"}');
    }
}
