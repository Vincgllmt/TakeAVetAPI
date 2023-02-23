<?php

namespace App\Tests\Api\Agenda;

use App\Entity\Agenda;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAgendaCest
{
    private static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'startHour' => 'string',
            'endHour' => 'string',
            'veto' => 'string',
        ];
    }

    public function createAgenda(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/agendas', [
            'startHour' => '08:00',
            'endHour' => '18:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Agenda::class, '/api/agendas/1', self::expectedPropertiesResultPost());
    }

    public function cantCreateAgendaInvalidHours(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/agendas', [
            'startHour' => '18:00',
            'endHour' => '08:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    public function cantCreateAgendaUnauthenticated(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();

        $I->sendPost('/api/agendas', [
            'startHour' => '08:00',
            'endHour' => '18:00',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }
}
