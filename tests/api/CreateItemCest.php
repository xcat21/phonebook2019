<?php 

class CreateItemCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->wantTo('create an item in my Phonebook via API');
      //  $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('v1/phonebook', [
            'firstName' => 'John',
            'lastName' => 'Sunders',
            'phoneNumber' => '+20 1234 56',
            'countryCode' => 'BL',
            'timeZone' => 'Moscow/Europe'
        ]);
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
       // $I->seeResponseContains('{"result":"ok"}');
    }

}
