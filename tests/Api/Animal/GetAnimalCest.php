<?php

namespace Api\Animal;

use App\Factory\AnimalFactory;
use App\Tests\Support\ApiTester;

class GetAnimalCest
{
    public function getAllAnimal(ApiTester $I): void
    {
        $animal = AnimalFactory::createMany(5);
        $I->sendGet('/api/animals');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }

    public function getOneAnimal(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $I->sendGet("/api/animals/{$animal->getId()}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
