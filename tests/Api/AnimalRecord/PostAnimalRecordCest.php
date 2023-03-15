<?php

namespace Api\AnimalRecord;

use App\Factory\AnimalFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAnimalRecordCest
{
    public function testAnimalRecordPost(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $animal = AnimalFactory::createOne();
        $I->amLoggedInAs($veto->object());
        $I->sendPost('/api/animal_records', [
            'weight' => 0,
            'height' => 0,
            'updatedAt' => '2023-03-14T17:54:25.788Z',
            'otherInfos' => 'string',
            'healthInfos' => 'string',
            'Animal' => "/api/animals/{$animal->getId()}",
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
    }
}
