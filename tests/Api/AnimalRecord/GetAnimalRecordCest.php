<?php

namespace Api\AnimalRecord;

use App\Factory\AnimalFactory;
use App\Factory\AnimalRecordFactory;
use App\Tests\Support\ApiTester;

class GetAnimalRecordCest
{
    public function getAllAnimalRecord(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);

        $I->sendGet('/api/animal_records');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }

    public function getOneAnimalRecord(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $animalRecord = AnimalRecordFactory::createOne(['Animal' => $animal]);

        $I->sendGet("/api/animal_records/{$animalRecord->getId()}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
