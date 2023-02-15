<?php

use App\Tests\Support\ApiTester;

class UserCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'isAnHusbandry' => 'boolean',
            'email' => 'string',
            'lastName' => 'string',
            'firstName' => 'string',
            'tel' => 'string|null',
            'profilePicPath' => 'string|null',
        ];
    }

    public function testCreateUser(ApiTester $I): void
    {
        $I->sendPost('/api/register', [
            'email' => 'test@test.test',
            'password' => 'password',
            'lastName' => 'Doe',
            'firstName' => 'John',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultPost());
    }
}
