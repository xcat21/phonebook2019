<?php

declare(strict_types=1);

class AddItemsCest
{
    public function _before(ApiTester $I, \Codeception\Module\Db $Db)
    {
    }

    // tests

    public function addRecord(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (201) and create new Record from correct dataset');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
                                                'firstName' => 'Padme',
                                                'lastName' => 'Amidala',
                                                'phoneNumber' => '+21 333 999888777',
                                                'countryCode' => 'GB',
                                                'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(201);
        $I->seeResponseEquals(null);
        $I->seeHttpHeader('Location');
        $Db->seeInDatabase('Record', [
                                            'fName' => 'Padme',
                                            'lName' => 'Amidala',
                                            'phone' => '+21 333 999888777',
                                            'countryCode' => 'GB',
                                            'timeZone' => 'Europe/Moscow',
                                         ]);
    }

    public function addRecordfNameRequired(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record without first name required');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
                                    'or","details":{"firstName":"First name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordfNameIncorrect(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record with first name = ""');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => '',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
            'or","details":{"firstName":"First name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => '',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordfNameLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record with first name > 60 chars');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
            'or","details":{"firstName":"First name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => '',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordlNameLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record with last name > 60 chars');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation err'.
            'or","details":{"lastName":"Last name must consist of 1-60 symbols"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordNoLName(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (201) and create new Record, dataset w/o lastName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(201);
        $I->seeResponseEquals(null);
        $I->seeHttpHeader('Location');
        $Db->seeInDatabase('Record', [
            'fName' => 'Padme',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordPhoneWrong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record with wrong phone format');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 3338999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Europe/Moscow',
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

    public function addRecordCOuntryCodeLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding record with long countryCode');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GBA',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validatio'.
                                    'n error","details":{"countryCode":"Country code must consist of 2 symbol'.
                                    's","countryCodeAPI":"Country code must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GBA',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordCOuntryCodeWrong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding item with countryCode not in ext. API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'ZZ',
            'timeZone' => 'Europe/Moscow',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation error","d'.
                                    'etails":{"countryCodeAPI":"Country code must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'ZZ',
            'timeZone' => 'Europe/Moscow',
        ]);
    }

    public function addRecordTimezoneWrong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding item with timeZone not in ext. API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Mars/Cedonia',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation er'.
                                    'ror","details":{"timeZoneAPI":"Time zone must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Mars/Cedonia',
        ]);
    }

    public function addRecordTimezoneLong(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('get (400) error adding item with long timeZone>40 chars');
        //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/add', [
            'firstName' => 'Padme',
            'lastName' => 'Amidala',
            'phoneNumber' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Mars/CedoniaMars/CedoniaMars/CedoniaMars/CedoniaMars/CedoniaMars/Cedonia',
        ]);
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContains('{"error":2,"error_description":"Input parameters validation erro'.
                                    'r","details":{"timeZone":"Time zone must consist of 3-40 symbols","tim'.
                                    'eZoneAPI":"Time zone must be presented on external API"}}');
        $Db->dontSeeInDatabase('Record', [
            'fName' => 'Padme',
            'lName' => 'Amidala',
            'phone' => '+21 333 999888777',
            'countryCode' => 'GB',
            'timeZone' => 'Mars/CedoniaMars/CedoniaMars/CedoniaMars/CedoniaMars/CedoniaMars/Cedonia',
        ]);
    }
}
