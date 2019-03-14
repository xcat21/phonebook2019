<?php 

class GetItemsCest
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
        $I->seeResponseContains('{"id":"2","fName":"Chewbacca","lName":"","phone":"+20 333 459935766",'.
                                    '"countryCode":"GF","timeZone":"Europe\/Athens","insertedOn":"2019-03-12 '.
                                    '12:43:00","updatedOn":"2019-03-12 13:40:00"}'
                               );
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'fName' => 'string',
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
        $I->seeResponseContains('[{"id":"1","fName":"Luke","lName":"Skywalker","phone":"+11 123 '.
                                    '445674312","countryCode":"AR","timeZone":"Pacific\/Saipan",'.
                                    '"insertedOn":"2019-03-12 09:22:00","updatedOn":"2019-03-12 11:43:00"},'.
                                    '{"id":"2","fName":"Chewbacca","lName":"","phone":"+20 333 459935766","'.
                                    'countryCode":"GF","timeZone":"Europe\/Athens","insertedOn":"2019-03-12 12'.
                                    ':43:00","updatedOn":"2019-03-12 13:40:00"},{"id":"3","fName":"Han",'.
                                    '"lName":"Solo","phone":"+02 144 265555890","countryCode":"JM","timeZone":'.
                                    '"Europe\/Bucharest","insertedOn":"2019-03-15 12:43:00","updatedOn":"2'.
                                    '019-03-15 18:40:00"},{"id":"4","fName":"Moff Kohl","lName":"Seerdon","phone'.
                                    '":"+44 333 265786344","countryCode":"SC","timeZone":"America\/Denver",'.
                                    '"insertedOn":"2019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"},'.
                                    '{"id":"5","fName":"Darth","lName":"Vader","phone":"+99 876 265111657",'.
                                    '"countryCode":"VU","timeZone":"Antarctica\/DumontDUrville","insertedOn":"2'.
                                    '019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

    public function getRecordsListAllLimit (ApiTester $I)
    {
        $I->wantTo('get list of all records from my Phonebook via API with limit');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"1","fName":"Luke","lName":"Skywalker","phone":"+11 123 445674312",'.
                                    '"countryCode":"AR","timeZone":"Pacific\/Saipan","insertedOn":"2019-03-12 0'.
                                    '9:22:00","updatedOn":"2019-03-12 11:43:00"},{"id":"2","fName":"Chewbacca",'.
                                    '"lName":"","phone":"+20 333 459935766","countryCode":"GF","timeZone":"Eu'.
                                    'rope\/Athens","insertedOn":"2019-03-12 12:43:00","updatedOn":"2019-03-12 13'.
                                    ':40:00"}]');
    }

    public function getRecordsListAllLimitOffset (ApiTester $I)
    {
        $I->wantTo('get list of all records from my Phonebook via API with limit and offset');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook?limit=2&offset=2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[{"id":"2","fName":"Chewbacca","lName":"","phone":"+20 333 4599357'.
                                    '66","countryCode":"GF","timeZone":"Europe\/Athens","insertedOn":"201'.
                                    '9-03-12 12:43:00","updatedOn":"2019-03-12 13:40:00"},{"id":"3","fName"'.
                                    ':"Han","lName":"Solo","phone":"+02 144 265555890","countryCode":"JM","ti'.
                                    'meZone":"Europe\/Bucharest","insertedOn":"2019-03-15 12:43:00","update'.
                                    'dOn":"2019-03-15 18:40:00"}]');
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
        $I->seeResponseContains('[{"id":"1","fName":"Luke","lName":"Skywalker","phone":"+11 123 '.
                                    '445674312","countryCode":"AR","timeZone":"Pacific\/Saipan",'.
                                    '"insertedOn":"2019-03-12 09:22:00","updatedOn":"2019-03-12 11:43:00"},'.
                                    '{"id":"2","fName":"Chewbacca","lName":"","phone":"+20 333 459935766","'.
                                    'countryCode":"GF","timeZone":"Europe\/Athens","insertedOn":"2019-03-12 12'.
                                    ':43:00","updatedOn":"2019-03-12 13:40:00"},{"id":"3","fName":"Han",'.
                                    '"lName":"Solo","phone":"+02 144 265555890","countryCode":"JM","timeZone":'.
                                    '"Europe\/Bucharest","insertedOn":"2019-03-15 12:43:00","updatedOn":"2'.
                                    '019-03-15 18:40:00"},{"id":"4","fName":"Moff Kohl","lName":"Seerdon","phone'.
                                    '":"+44 333 265786344","countryCode":"SC","timeZone":"America\/Denver",'.
                                    '"insertedOn":"2019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"},'.
                                    '{"id":"5","fName":"Darth","lName":"Vader","phone":"+99 876 265111657",'.
                                    '"countryCode":"VU","timeZone":"Antarctica\/DumontDUrville","insertedOn":"2'.
                                    '019-03-11 10:43:00","updatedOn":"2019-03-15 15:20:00"}]');
    }

}
