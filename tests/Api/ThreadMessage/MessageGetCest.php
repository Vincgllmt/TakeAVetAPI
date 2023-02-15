<?php

declare(strict_types=1);

namespace App\Tests\Api\ThreadMessage;

use App\Entity\ThreadMessage;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadMessageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class MessageGetCest
{
    public function getMessage(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();
        $user = ClientFactory::createOne();
        ThreadMessageFactory::createOne([
            'user' => $user,
            'thread' => $thread
        ]);
        $I->sendGet('/api/thread_messages/1');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(ThreadMessage::class, '/api/thread_messages/1');
    }
    public function getAllMessage(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();
        $user = ClientFactory::createOne();
        ThreadMessageFactory::createOne([
            'user' => $user,
            'thread' => $thread
        ]);
        ThreadMessageFactory::createOne([
            'user' => $user,
            'thread' => $thread
        ]);
        $I->sendGet('/api/thread_messages');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsACollection(ThreadMessage::class, '/api/thread_messages', [
            'hydra:member' => 'array',
            'hydra:totalItems' => 'integer',
        ]);
        $jsonResponse = $I->grabJsonResponse();
        $I->assertSame(2, $jsonResponse['hydra:totalItems']);
        $I->assertCount(2, $jsonResponse['hydra:member']);

    }
}