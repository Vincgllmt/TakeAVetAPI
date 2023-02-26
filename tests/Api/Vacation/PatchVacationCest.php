<?php

namespace App\Tests\Api\Vacation;

use App\Entity\Vacation;
use App\Factory\AgendaFactory;
use App\Factory\ClientFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchVacationCest
{
    public function cantPatchVacationUnauthenticated(ApiTester $I): void
    {
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);

        $I->sendPatch("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantPatchIfNotVet(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($client->object());

        $I->sendPatch("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function cantPatchIfNotOwner(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPatch("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function patchVacation(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => $veto,
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPatch("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Vacation::class, "/api/vacations/{$vacation->getId()}");
        $I->assertEquals('Vacances d\'hiver', $vacation->getLib());
    }
}
