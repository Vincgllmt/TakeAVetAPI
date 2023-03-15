<?php

namespace Api\Appointment;

use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Faker\Core\DateTime;

class GetAppointmentCest
{
    public function getAllAppointment(ApiTester $I): void
    {
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => ClientFactory::createOne(),
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
        ]);
        $I->sendGet('/api/appointments');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'hydra:totalItems' => 'integer',
        ]);
    }

    public function getOneAppointment(ApiTester $I): void
    {
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => ClientFactory::createOne(),
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
        ]);
        $I->sendGet("/api/appointments/{$rdv->getId()}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
