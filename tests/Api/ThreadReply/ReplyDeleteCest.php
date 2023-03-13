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
    public function anonymousClientAreForbiddenToDelete(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function cantUserDeleteOtherUserReply(ApiTester $I): void
    {
        $creator = ClientFactory::createOne();
        $thread = ThreadFactory::createOne([
            'author' => $creator,
        ]);
        $reply = ThreadReplyFactory::createOne([
            'user' => $creator,
            'thread' => $thread,
        ]);
        $user = ClientFactory::createOne();

        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(httpCode::FORBIDDEN);
    }

    public function userCanDeleteHisOwnReplyToAThread(ApiTester $I): void
    {
        $threadCreator = ClientFactory::createOne();
        $user = ClientFactory::createOne();
        $thread = ThreadFactory::createOne([
            'author' => $threadCreator,
        ]);
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function adminCanDeleteNonOwnedReply(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $admin = ClientFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ]);

        $thread = ThreadFactory::createOne();
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);

        $I->amLoggedInAs($admin->object());
        $I->sendDelete("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
