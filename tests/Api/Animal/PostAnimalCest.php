<?php

namespace Api\Animal;

use App\Factory\AnimalFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAnimalCest
{
    public function AnonymousCantPost(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $I->sendPost('/api/animals', [
            'name' => 'string',
            'note' => 'string',
            'specificRace' => 'string',
            'gender' => 'string',
            'birthday' => '2023-03-13T17:22:51.766Z',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /*
    public function ClientCanPost(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $I->amLoggedInAs($client->object());
        $I->sendPost('/api/animals', [
            'name' => 'string',
            'note' => 'string',
            'specificRace' => 'string',
            'gender' => 'string',
            'birthday' => '2023-03-13T17:22:51.766Z',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
    }
     **/
}
