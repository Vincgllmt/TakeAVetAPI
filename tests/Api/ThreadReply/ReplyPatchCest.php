<?php

declare(strict_types=1);

namespace App\Tests\Api\ThreadReply;

use App\Entity\Client;
use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadReplyFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class ReplyPatchCest
{
    public function anonymousUserForbiddenToPatchReply(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $reply = ThreadReplyFactory::createOne([
            'thread' => $thread,
            'user' => $user,
        ]);
        $I->sendPatch("/api/thread_replies/{$reply->getId()}", [
            'description' => 'Oui',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedUserForbiddenToPatchOtherUserRating(ApiTester $I): void
    {
        // 1. 'Arrange'
        /** @var $user Client */
        $user = ClientFactory::createOne();
        $test = ClientFactory::createOne();
        $thread = ThreadFactory::createOne();
        $I->amLoggedInAs($user->object());
        $reply = ThreadReplyFactory::createOne([
            'user' => $user,
            'thread' => $thread,
        ]);
        $I->sendPatch("/api/thread_replies/{$reply->getId()}");
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
