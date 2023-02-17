<?php

namespace App\Tests\Api\Thread;

use App\Factory\ClientFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostThreadCest
{
    public function testThreadPost(ApiTester $I): void
    {
        $client = ClientFactory::createOne();

        $I->sendPost('/api/threads', [
            'subject' => 'test',
            'description' => 'test',
            'author' => "/api/users/{$client->getId()}",
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
    }
}
