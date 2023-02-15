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
}