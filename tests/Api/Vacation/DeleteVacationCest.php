<?php

namespace App\Tests\Api\Vacation;

use App\Factory\AgendaFactory;
use App\Factory\ClientFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteVacationCest
{
    public function cantDeleteVacationUnauthenticated(ApiTester $I): void
    {
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
        ]);

        $I->sendDelete("/api/vacations/{$vacation->getId()}");

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantDeleteVacationIfNotVet(ApiTester $I): void
    {
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
        ]);
        $I->amLoggedInAs(ClientFactory::createOne()->object());

        $I->sendDelete("/api/vacations/{$vacation->getId()}");

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function cantDeleteVacationIfNotAgendaOwner(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendDelete("/api/vacations/{$vacation->getId()}");

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function deleteVacation(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => $veto,
            ]),
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendDelete("/api/vacations/{$vacation->getId()}");

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
