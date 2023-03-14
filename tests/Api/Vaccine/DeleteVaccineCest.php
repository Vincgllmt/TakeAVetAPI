<?php

namespace Api\Vaccine;

use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\VaccineFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteVaccineCest
{
    public function anonymousVetoForbiddenToDelete(ApiTester $I): void
    {
        $animal = AnimalFactory::createOne();
        $vaccine = VaccineFactory::createOne(['animal' => $animal]);
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(401);
    }

    public function VetoCanDeleteVaccine(ApiTester $I): void
    {
        $user = VetoFactory::createOne();
        $animal = AnimalFactory::createOne();
        $vaccine = VaccineFactory::createOne(['animal' => $animal]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(204);
    }

    public function ClientForbiddenToDeleteVaccine(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $animal = AnimalFactory::createOne();
        $vaccine = VaccineFactory::createOne(['animal' => $animal]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/vaccines/{$vaccine->getId()}");
        $I->seeResponseCodeIs(httpCode::FORBIDDEN);
    }
}
