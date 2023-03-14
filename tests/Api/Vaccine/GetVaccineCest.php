<?php

namespace Api\Vaccine;

use App\Factory\AnimalFactory;
use App\Factory\VaccineFactory;
use App\Tests\Support\ApiTester;

class GetVaccineCest
{
    public function getAllVaccine(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $vaccine = VaccineFactory::createOne(['animal' => $animal]);
        $animal2 = AnimalFactory::createOne();
        $vaccine2 = VaccineFactory::createOne(['animal' => $animal2]);

        $I->sendGet('/api/vaccines');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }

    public function getOneVaccine(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $vaccine = VaccineFactory::createOne(['animal' => $animal]);

        $I->sendGet("/api/vaccines/{$vaccine->getId()}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
