<?php

use App\Tests\Support\ApiTester;

class UserCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'email' => 'string',
        ];
    }

    public function testCreateUser(ApiTester $I): void
    {
        $I->sendPost('/api/users', [
            'email' => 'test@test.test',
            'password' => 'password',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultPost());
    }
}
