<?php 

class CreateItemCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function getErrorForGET404 (ApiTester $I)
    {
        $I->wantTo('get an 404 error when sending GET with not valid request to my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/kj54');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('{"error":1,"error_description":"URI not found or error in request."}');
    }

    public function getErrorForPOST404 (ApiTester $I)
    {
        $I->wantTo('get an 404 error when sending POST with not valid request to my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook/hjsdhgbe');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('{"error":1,"error_description":"URI not found or error in request."}');
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
    }

    public function getRecordById204 (ApiTester $I)
    {
        $I->wantTo('get an 204 error when getting nonexistent item from my Phonebook via API');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/34');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }



}
