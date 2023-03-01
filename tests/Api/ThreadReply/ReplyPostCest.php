<?php

declare(strict_types=1);

namespace App\Tests\Api\ThreadReply;

use App\Entity\ThreadReply;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class ReplyPostCest
{
    public function anonymousUserForbiddenToCreateReply(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();
        $I->sendPost('/api/thread_replies', [
            'description' => 'Ouais ça dit quoi mon reuf',
            'thread' => "/api/threads/{$thread->getId()}",
            ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
