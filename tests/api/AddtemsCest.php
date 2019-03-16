<?php

declare(strict_types=1);

class AddtemsCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests

    public function addRecord(ApiTester $I)
    {
        $I->wantTo('get 204 code when search without NAME');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }

    public function getRecordEmptyName204(ApiTester $I)
    {
        $I->wantTo('get an 204 code when search with EMPTY NAME');
        //  $I->amHttpAuthenticated('service_user', '123456');
        //  $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('v1/phonebook/search?name=');
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals(null);
    }
}
