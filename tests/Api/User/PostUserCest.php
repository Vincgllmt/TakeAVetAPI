<?php

use App\Tests\Support\ApiTester;

class PostUserCest
{
    protected static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'isHusbandry' => 'boolean',
            'email' => 'string',
            'lastName' => 'string',
            'firstName' => 'string',
            'phone' => 'string|null',
            'avatarPath' => 'string|null',
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
