<?php

namespace App\Tests\Api\User;

use App\Tests\Support\ApiTester;

class PostUserCest
{
    protected static function expectedPropertiesResultPost(bool $useClientProperties): array
    {
        $properties = [
            'id' => 'integer',
            'email' => 'string',
            'lastName' => 'string',
            'firstName' => 'string',
            'phone' => 'string|null',
            'isAdmin' => 'boolean',
            'avatar' => 'string|null',
        ];

        if ($useClientProperties) {
            $properties['isHusbandry'] = 'boolean';
        }

        return $properties;
    }

    public function testCreateClient(ApiTester $I): void
    {
        $I->sendPost('/api/register', [
            'email' => 'test@test.test',
            'password' => 'password',
            'lastName' => 'Doe',
            'firstName' => 'John',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultPost(true));
    }

    public function testCreateVeto(ApiTester $I): void
    {
        $I->sendPost('/api/vet/register', [
            'email' => 'test@test.test',
            'password' => 'password',
            'lastName' => 'Doe',
            'firstName' => 'John',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultPost(false));
    }
}
