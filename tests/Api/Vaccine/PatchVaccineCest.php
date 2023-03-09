<?php

namespace App\Tests\Api\Vaccine;

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
}