<?php 

class CreateItemCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function getErrorForGET404 (ApiTester $I)
    {
        $I->wantTo('get an 404 code when sending GET with not valid request to my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/kj54');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('{"error":1,"error_description":"URI not found or error in request."}');
        $I->seeResponseMatchesJsonType([
            'error' => 'integer',
            'error_description' => 'string'
        ]);
    }

    public function getErrorForPOST404 (ApiTester $I)
    {
        $I->wantTo('get an 404 code when sending POST with not valid request to my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/hjsdhgbe');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('{"error":1,"error_description":"URI not found or error in request."}');
        $I->seeResponseMatchesJsonType([
            'error' => 'integer',
            'error_description' => 'string'
        ]);
    }

    public function getRecordByIdOk(ApiTester $I)
    {
        $I->wantTo('get an existing item from my Phonebook via API');
      //  $I->amHttpAuthenticated('service_user', '123456');
      //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"2",'.
                                    '"fname":"Tom",'.
                                    '"lname":"Cruize",'.
                                    '"phone":"+70 333 45 99",'.
                                    '"countryCode":"EU",'.
                                    '"timeZone":"Mars\/Cedonia",'.
                                    '"insertedOn":"2019-03-12 12:43:00",'.
                                    '"updatedOn":"2019-03-12 13:40:00"}'
                               );
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'fname' => 'string',
            'phone' => 'string',
            'countryCode' => 'string',
            'timeZone' => 'string',
            'insertedOn' => 'string',
            'updatedOn' => 'string'
        ]);
    }

    public function getRecordById204 (ApiTester $I)
    {
        $I->wantTo('get an 204 code when asking for non-existed item from my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/34');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordsListAll (ApiTester $I)
    {
        $I->wantTo('get list of all records from my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"1","fname":"Roman","lname":"Smirnov","phone":"+7 123 45 67",'.
            '"countryCode":"RU","timeZone":"Moscow\/Europe","insertedOn":"2019-03-12 09:22:00","updatedOn":"'.
            '2019-03-12 11:43:00"},{"id":"2","fname":"Tom","lname":"Cruize","phone":"+70 333 45 99",'.
            '"countryCode":"EU","timeZone":"Mars\/Cedonia","insertedOn":"2019-03-12 12:43:00","updatedOn"'.
            ':"2019-03-12 13:40:00"},{"id":"3","fname":"Anna","lname":"Brown","phone":"+2 144 265","countryCode"'.
            ':"AF","timeZone":"Venera\/Base","insertedOn":"2019-03-15 12:43:00","updatedOn":"2019-03-15 18:40:00"}'.
            ',{"id":"4","fname":"Boris","lname":"Johnson","phone":"+44 333 265","countryCode":"GB","timeZone":"'.
            'Longway\/Passing","insertedOn":"2019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

    public function getRecordsListAllLimit (ApiTester $I)
    {
        $I->wantTo('get list of all records from my Phonebook via API with limit');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"1","fname":"Roman","lname":"Smirnov","phone":"+7 123 45 67",'.
            '"countryCode":"RU","timeZone":"Moscow\/Europe","insertedOn":"2019-03-12 09:22:00","updatedOn":'.
            '"2019-03-12 11:43:00"},{"id":"2","fname":"Tom","lname":"Cruize","phone":"+70 333 45 99",'.
            '"countryCode":"EU","timeZone":"Mars\/Cedonia","insertedOn":"2019-03-12 12:43:00","updatedOn":"'.
            '2019-03-12 13:40:00"}]');
    }

    public function getRecordsListAllLimitOffset (ApiTester $I)
    {
        $I->wantTo('get list of all records from my Phonebook via API with limit and offset');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=2&offset=2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"2","fname":"Tom","lname":"Cruize","phone":"+70 333 45 99","'.
            'countryCode":"EU","timeZone":"Mars\/Cedonia","insertedOn":"2019-03-12 12:43:00","updatedOn":"'.
            '2019-03-12 13:40:00"},{"id":"3","fname":"Anna","lname":"Brown","phone":"+2 144 265","countryCode'.
            '":"AF","timeZone":"Venera\/Base","insertedOn":"2019-03-15 12:43:00","updatedOn":"2019-03-15 18:40'.
            ':00"}]');
    }

    public function getRecordsListAllLimitOffset204 (ApiTester $I)
    {
        $I->wantTo('get an 204 code when asking for non-existed offset from my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=2&offset=200');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordsListAllSafeLimitOffset (ApiTester $I)
    {
        $I->wantTo('get list of all records with default values of limit and offset when them are not valid');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=kjdhfjk&offset=kejrh');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"1","fname":"Roman","lname":"Smirnov","phone":"+7 123 45 67",'.
            '"countryCode":"RU","timeZone":"Moscow\/Europe","insertedOn":"2019-03-12 09:22:00","updatedOn":"'.
            '2019-03-12 11:43:00"},{"id":"2","fname":"Tom","lname":"Cruize","phone":"+70 333 45 99",'.
            '"countryCode":"EU","timeZone":"Mars\/Cedonia","insertedOn":"2019-03-12 12:43:00","updatedOn"'.
            ':"2019-03-12 13:40:00"},{"id":"3","fname":"Anna","lname":"Brown","phone":"+2 144 265","countryCode"'.
            ':"AF","timeZone":"Venera\/Base","insertedOn":"2019-03-15 12:43:00","updatedOn":"2019-03-15 18:40:00"}'.
            ',{"id":"4","fname":"Boris","lname":"Johnson","phone":"+44 333 265","countryCode":"GB","timeZone":"'.
            'Longway\/Passing","insertedOn":"2019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

}
