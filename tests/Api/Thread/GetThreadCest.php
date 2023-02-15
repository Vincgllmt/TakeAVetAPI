<?php

use App\Factory\ThreadFactory;
use App\Tests\Support\ApiTester;

class GetThreadCest
{
    public function getAllThread(ApiTester $I): void
    {
        ThreadFactory::createMany(2);

        $I->sendGet('/api/threads');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }

    public function getOneThread(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();

        $I->sendGet("/api/threads/{$thread->getId()}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
