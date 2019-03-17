<?php

declare(strict_types=1);

class MixedCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function mixedTest(ApiTester $I, \Codeception\Module\Db $Db)
    {
        $I->wantTo('check the chain ADD-GET-UPDATE-GET-DELETE on record');
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
        $I->sendGET('v1/phonebook/6');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"6","fName":"Padme","lName":"Amidala","phone":"+21 333 999888777",'.
            '"countryCode":"GB","timeZone":"Europe\/Moscow"'
        );
        $I->sendPUT('v1/phonebook/6', [
            'firstName' => 'Jar Jar',
            'lastName' => 'Binks',
            'countryCode' => 'US',
        ]);
        $I->canSeeResponseCodeIs(204);
        $I->seeResponseEquals(null);
        $Db->seeInDatabase('Record', [
            'id' => '6',
            'fName' => 'Jar Jar',
            'lName' => 'Binks',
            'countryCode' => 'US',
        ]);
        $I->sendGET('v1/phonebook/search?name=jar%20jar');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"id":"6"');
        $I->sendDELETE('v1/phonebook/6');
        $I->seeResponseCodeIs(200);
        $I->seeResponseEquals(null);
        $Db->dontSeeInDatabase('Record', [
            'id' => '6',
            'fName' => 'Jar Jar',
        ]);
    }
}
