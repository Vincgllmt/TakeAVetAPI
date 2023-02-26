<?php

namespace App\Tests\Api\Vacation;

use App\Entity\Vacation;
use App\Factory\AgendaFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetVacationCest
{
    private static function expectedPropertiesResultGet(): array
    {
        return [
            'id' => 'integer',
            'lib' => 'string',
            'startDate' => 'string',
            'endDate' => 'string',
            'agenda' => 'string',
        ];
    }

    public function getVacationUnauthenticated(ApiTester $I): void
    {
        $vacation = VacationFactory::createOne([
            'agenda' => AgendaFactory::createOne([
                'veto' => VetoFactory::createOne(),
            ]),
        ]);

        $I->sendGet("/api/vacations/{$vacation->getId()}");

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Vacation::class, "/api/vacations/{$vacation->getId()}", self::expectedPropertiesResultGet());
    }
}
