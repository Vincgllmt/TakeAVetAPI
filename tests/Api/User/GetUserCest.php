<?php

use App\Entity\Client;
use App\Factory\ClientFactory;
use App\Tests\Support\ApiTester;

class GetUserCest
{
    protected static function expectedPropertiesResultGet(): array
    {
        return [
            'id' => 'integer',
            'isHusbandry' => 'boolean', // This property is in the Client entity.
            'lastName' => 'string',
            'firstName' => 'string',
            'avatarPath' => 'string|null',
        ];
    }

    protected static function expectedPropertiesResultGetMe(): array
    {
        return array_merge(self::expectedPropertiesResultGet(), [
            'email' => 'string',
            'phone' => 'string|null',
            'avatarPath' => 'string|null',
        ]);
    }

    public function testGetAClientWithThisProperties(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $I->sendGet("/api/users/{$client->getId()}");

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultGet());
    }

    public function testGetMe(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $I->amLoggedInAs($client->object());
        $I->sendGet('/api/me');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnItem(self::expectedPropertiesResultGetMe());
    }
}
