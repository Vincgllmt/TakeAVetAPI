<?php

namespace App\Tests\Api\Agenda;

use App\Factory\AgendaFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteAgendaCest
{
    public function deleteAgenda(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto,
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendDelete("/api/agendas/{$agenda->getId()}");

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $I->assertEquals(null, $veto->getAgenda());
    }

    public function cantDeleteAgendaWhenNotOwned(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $veto2 = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto2,
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendDelete("/api/agendas/{$agenda->getId()}");

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function cantDeleteAgendaWhenNotLoggedIn(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto,
        ]);

        $I->sendDelete("/api/agendas/{$agenda->getId()}");

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
