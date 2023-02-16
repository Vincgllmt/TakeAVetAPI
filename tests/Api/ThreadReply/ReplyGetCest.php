<?php

declare(strict_types=1);

namespace App\Tests\Api\ThreadReply;

use App\Entity\ThreadReply;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadReplyFactory;
use App\Tests\Support\ApiTester;

class ReplyGetCest
{
    public function getMessage(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();
        $user = ClientFactory::createOne();
        ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->sendGet('/api/thread_replies/1');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(ThreadReply::class, '/api/thread_replies/1');
    }

    public function getAllMessage(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();
        $user = ClientFactory::createOne();
        ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->sendGet('/api/thread_replies');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsACollection(ThreadReply::class, '/api/thread_replies', [
            'hydra:member' => 'array',
            'hydra:totalItems' => 'integer',
        ]);
        $jsonResponse = $I->grabJsonResponse();
        $I->assertSame(2, $jsonResponse['hydra:totalItems']);
        $I->assertCount(2, $jsonResponse['hydra:member']);
    }
}
