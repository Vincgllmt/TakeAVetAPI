<?php

namespace App\Tests\Api\Vaccine;

use App\Factory\VaccineFactory;
use App\Tests\Support\ApiTester;

class GetVaccineCest
{
    public function getAllVaccine(ApiTester $I): void
    {
        VaccineFactory::createMany(2);

        $I->sendGet('/api/threads');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }
    public function getOneVaccine(ApiTester $I): void
    {
        $vaccine = VaccineFactory::createOne();

        $I->sendGet("/api/threads/{$vaccine->getId()}");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}