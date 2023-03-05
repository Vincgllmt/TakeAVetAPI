<?php

namespace App\Tests\Api\User;

use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;

class GetUserCest
{
    protected static function expectedPropertiesResultGet(bool $useClientProperties): array
    {
        $properties = [
            'id' => 'integer',
            'lastName' => 'string',
            'firstName' => 'string',
// avatar can be get by the route /api/users/{id}/avatar
//            'avatarPath' => 'string|null',
            'isAdmin' => 'boolean',
            'avatar' => 'string|null',
        ];

        if ($useClientProperties) {
            $properties['isHusbandry'] = 'boolean';
        }

        return $properties;
    }

    protected static function expectedPropertiesResultGetMe(bool $useClientProperties): array
    {
        return array_merge(self::expectedPropertiesResultGet($useClientProperties), [
            'email' => 'string',
            'phone' => 'string|null',
        ]);
    }

    public function testGetClientWithHisProperties(ApiTester $I): void
    {
        $client = ClientFactory::createOne();

        $I->sendGet("/api/users/{$client->getId()}");

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultGet(true));
    }

    public function testGetVetoWithHisProperties(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();

        $I->sendGet("/api/users/{$veto->getId()}");

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultGet(false));
    }

    public function testGetMe(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $I->amLoggedInAs($client->object());
        $I->sendGet('/api/me');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultGetMe(true));
    }
}
