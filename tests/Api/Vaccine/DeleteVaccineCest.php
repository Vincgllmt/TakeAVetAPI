<?php

namespace App\Tests\Api\Vaccine;

use App\Factory\VaccineFactory;
use App\Tests\Support\ApiTester;

class DeleteVaccineCest
{
    public function anonymousVetoForbiddenToDelete(ApiTester $I): void
    {
        $vaccine = VaccineFactory::createOne();
        $I->sendDelete("/api/thread_replies/{$vaccine->getId()}");
        $I->seeResponseCodeIs(401);
    }
}
