<?php

namespace App\Tests\Api\Thread;

use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteThreadCest
{
    public function adminCanDeleteThread(ApiTester $I): void
    {
        $I->amLoggedInAs(ClientFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
        ])->object());

        $thread = ThreadFactory::createOne();

        $I->sendDelete("/api/threads/{$thread->getId()}");

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function cantDeleteThreadWithoutBeingLoggedIn(ApiTester $I): void
    {
        $thread = ThreadFactory::createOne();

        $I->sendDelete("/api/threads/{$thread->getId()}");

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function cantDeleteThreadWithoutBeingAdmin(ApiTester $I): void
    {
        $I->amLoggedInAs(ClientFactory::createOne()->object());
        $thread = ThreadFactory::createOne();
        $I->sendDelete("/api/threads/{$thread->getId()}");
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->amLoggedInAs(VetoFactory::createOne()->object());
        $I->sendDelete("/api/threads/{$thread->getId()}");
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
