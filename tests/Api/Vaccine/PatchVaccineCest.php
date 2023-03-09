<?php

namespace App\Tests\Api\Vaccine;

use App\Factory\ClientFactory;
use App\Factory\VaccineFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchVaccineCest
{
    public function cantPatchVaccineUnauthenticated(ApiTester $I): void
    {
        $vaccine = VaccineFactory::createOne();
        $I->sendPatch("/api/vaccines/{$vaccine->getId()}", [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }
    public function cantPatchIfNotVet(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $vaccine = VaccineFactory::createOne();
        $I->amLoggedInAs($client->object());
        $I->sendPatch("/api/vaccines/{$vaccine->getId()}", [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }
}