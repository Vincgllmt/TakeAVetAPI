<?php

namespace App\Tests\Api\Agenda;

use App\Factory\AgendaFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PutAgendaCest
{
    /**
     * @throws \Exception
     */
    public function putAgenda(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto,
            'startHour' => new \DateTimeImmutable('08:00'),
            'endHour' => new \DateTimeImmutable('18:00'),
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPut("/api/agendas/{$agenda->getId()}", [
            'startHour' => '09:00',
            'endHour' => '18:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $json = $I->grabJsonResponse();

        $I->assertSame('09:00', (new \DateTime($json['startHour']))->format('H:i'));
        $I->assertSame('18:00', (new \DateTime($json['endHour']))->format('H:i'));
    }

    public function cantPutAgendaWhenNotOwned(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $veto2 = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto2,
        ]);
        $I->amLoggedInAs($veto->object());

        $I->sendPut("/api/agendas/{$agenda->getId()}", [
            'startHour' => '09:00',
            'endHour' => '18:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function cantPutAgendaWhenNotLoggedIn(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto,
        ]);

        $I->sendPut("/api/agendas/{$agenda->getId()}", [
            'startHour' => '09:00',
            'endHour' => '18:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
