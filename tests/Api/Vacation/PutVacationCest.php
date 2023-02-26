<?php

namespace App\Tests\Api\Vacation;

use App\Entity\Vacation;
use App\Factory\AgendaFactory;
use App\Factory\ClientFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PutVacationCest
{
    public function cantPutVacationUnauthenticated(ApiTester $I): void
    {
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);

        $I->sendPut("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-31',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantPutIfNotVet(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($client->object());

        $I->sendPut("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-31',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function cantPutIfNotOwner(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPut("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-31',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function putVacation(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => $veto,
            ]),
            'lib' => 'Vacances d\'été',
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPut("/api/vacations/{$vacation->getId()}", [
            'lib' => 'Vacances d\'hiver',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-31',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Vacation::class, "/api/vacations/{$vacation->getId()}");
        $I->assertEquals('Vacances d\'hiver', $vacation->getLib());
    }
}
