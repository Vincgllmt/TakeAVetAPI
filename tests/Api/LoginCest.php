<?php

use App\Factory\ClientFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class LoginCest
{
    public function loginWithExistingUser(ApiTester $I): void
    {
        $user = ClientFactory::createOne([
            'email' => 'test@test.test',
            'password' => '12345678910',
        ]);

        $I->sendPost('/login', [
            'email' => 'test@test.test',
            'password' => '12345678910',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'user' => $user->getId(),
        ]);
    }

    public function cantLoginWithWrongPassword(ApiTester $I): void
    {
        $user = ClientFactory::createOne([
            'email' => 'test@test.test',
            'password' => 'motdepasse',
        ]);

        $I->sendPost('/login', [
            'email' => 'test@test.test',
            'password' => '12345678910',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson([
            'error' => 'Invalid credentials.',
        ]);
    }

    public function cantLoginWithWrongParams(ApiTester $I): void
    {
        $user = ClientFactory::createOne([
            'email' => 'test@test.test',
            'password' => '12345678910',
        ]);

        $I->sendPost('/login', [
            'email' => 'test@test.test',
            'abcd' => '12345678910',
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson([
            'detail' => 'The key "password" must be provided.',
        ]);
    }
}
