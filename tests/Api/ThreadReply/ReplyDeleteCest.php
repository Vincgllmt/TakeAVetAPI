<?php

declare(strict_types=1);

namespace App\Tests\Api\ThreadReply;

use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadReplyFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class ReplyDeleteCest
{
    public function anonymousClientForbiddenToDelete(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(401);
    }

    public function UserForbiddenToDeleteOtherReply(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $test = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $I->amLoggedInAs($user->object());
        $reply = ThreadReplyFactory::createOne([
            'user' => $test,
            'thread' => $thread,
        ]);
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(httpCode::FORBIDDEN);
    }
    public function UserCanDeleteOwnReply(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(204);
    }
}
