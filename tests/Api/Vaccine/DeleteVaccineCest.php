<?php

namespace App\Tests\Api\Vaccine;

use App\Factory\ClientFactory;
use App\Factory\VaccineFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteVaccineCest
{
    public function anonymousVetoForbiddenToDelete(ApiTester $I): void
    {
        $vaccine = VaccineFactory::createOne();
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(401);
    }

    public function VetoCanDeleteVaccine(ApiTester $I): void
    {
        $user = VetoFactory::createOne();
        $vaccine = VaccineFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(204);
    }
    public function ClientForbiddenToDeleteVaccine(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $vaccine = VaccineFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(httpCode::FORBIDDEN);
    }
}
