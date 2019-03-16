<?php

declare(strict_types=1);

class GetItemsSearchCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function getRecordsNoName204(ApiTester $I)
    {
        $I->wantTo('Get (204) when search without NAME in URL');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordEmptyName204(ApiTester $I)
    {
        $I->wantTo('Get (204) when search with EMPTY NAME is provided in URL');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordbyFirstName(ApiTester $I)
    {
        $I->wantTo('Get (200) and searched by NAME w/o spaces from fName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=moff');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"4","fName":"Moff Kohl","lName":"Seerdon","phone":"+44 333 265786344'.
                                    '","countryCode":"SC","timeZone":"America\/Denver","insertedOn":"2019-03-11 10:'.
                                    '43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

    public function getRecordbyLastName(ApiTester $I)
    {
        $I->wantTo('Get (200) and searched by NAME w/o spaces from lName');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=vader');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"5","fName":"Darth","lName":"Vader","phone":"+99 876 26511165'.
                                    '7","countryCode":"VU","timeZone":"Antarctica\/DumontDUrville","inserted'.
                                    'On":"2019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

    public function getRecordEmptyNoName204(ApiTester $I)
    {
        $I->wantTo('Get (204) when nothing is found by NAME');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=bash');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordbyNames(ApiTester $I)
    {
        $I->wantTo('Get (200) and where fName or lName contain NAME');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=w');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"1","fName":"Luke","lName":"Skywalker","phone":"+11 123 44567431'.
                                    '2","countryCode":"AR","timeZone":"Pacific\/Saipan","insertedOn":"2019-03-1'.
                                    '2 09:22:00","updatedOn":"2019-03-12 11:43:00"},{"id":"2","fName":"Chewbacc'.
                                    'a","lName":"","phone":"+20 333 459935766","countryCode":"GF","timeZone":"Eur'.
                                    'ope\/Athens","insertedOn":"2019-03-12 12:43:00","updatedOn":"2019-03-1'.
                                    '2 13:40:00"}]');
    }

    public function getRecordbyFirstNamewithSpaces(ApiTester $I)
    {
        $I->wantTo('Get (200) and searched record by NAME WITH SPACES');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=moff%20kohl');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"4","fName":"Moff Kohl","lName":"Seerdon","phone":"+44 333 265786344'.
            '","countryCode":"SC","timeZone":"America\/Denver","insertedOn":"2019-03-11 10:'.
            '43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }
}
