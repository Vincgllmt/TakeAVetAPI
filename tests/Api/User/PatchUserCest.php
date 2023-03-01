<?php

use App\Factory\ClientFactory;
use App\Tests\Support\ApiTester;

class PatchUserCest
{
    public function testPatchUserNames(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());

        $I->sendPatch("/api/users/{$user->getId()}", [
            'lastName' => 'abcde',
            'firstName' => 'abcdef',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->assertSame('abcde', $user->getLastName());
        $I->assertSame('abcdef', $user->getFirstName());
    }

    public function testPatchUserPassword(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());

        $I->sendPatch("/api/users/{$user->getId()}", [
            'password' => 'password',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->assertTrue($I->grabService('security.password_encoder')->isPasswordValid($user->object(), 'password'));
    }
}