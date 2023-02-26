<?php

namespace App\Tests\Api\Vacation;

use App\Entity\Vacation;
use App\Factory\AgendaFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostVacationCest
{
    private static function expectedPropertiesResultPost(): array
    {
        return [
            'id' => 'integer',
            'lib' => 'string',
            'startDate' => 'string',
            'endDate' => 'string',
            'agenda' => 'string',
        ];
    }

    public function createVacationOnAgenda(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createOne([
            'veto' => $veto,
        ]); // Create an agenda for the Veto
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/vacations', [
            'lib' => 'Vacances d\'été',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-02',
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Vacation::class, '/api/vacations/1', self::expectedPropertiesResultPost());
    }

    public function cantCreateIfNotLoggedIn(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createOne([
            'veto' => $veto,
        ]); // Create an agenda for the Veto

        $I->sendPost('/api/vacations', [
            'lib' => 'Vacances d\'été',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-02',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantCreateIfVetoHaveNoAgenda(APITester $I): void
    {
        $veto = VetoFactory::createOne();
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/vacations', [
            'lib' => 'Vacances d\'été',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-02',
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN); // TODO: UNPROCESSABLE_ENTITY
        $I->seeResponseIsJson();
    }

    public function cantCreateIfSameDate(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createOne([
            'veto' => $veto,
        ]); // Create an agenda for the Veto
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/vacations', [
            'lib' => 'Vacances d\'été',
            'startDate' => '2021-01-01',
            'endDate' => '2021-01-01',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    public function cantCreateIfInvalidDate(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createOne([
            'veto' => $veto,
        ]); // Create an agenda for the Veto
        $I->amLoggedInAs($veto->object());

        $I->sendPost('/api/vacations', [
            'lib' => 'Vacances d\'été',
            'startDate' => '2023-01-01',
            'endDate' => '2021-01-01',
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }
}
