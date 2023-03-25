<?php

namespace Api\Appointment;

use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetAppointmentCest
{
//    public function getAllAppointment(ApiTester $I): void
//    {
//        $type = TypeAppointmentFactory::createOne();
//        $rdv = AppointmentFactory::createOne([
//            'type' => $type,
//            'client' => ClientFactory::createOne(),
//            'veto' => VetoFactory::createOne(),
//            'animal' => AnimalFactory::createOne(),
//            'startHour' => new \DateTime('now'),
//            'endHour' => new \DateTime('now'),
//        ]);
//        $I->sendGet('/api/appointments');
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//        $I->seeResponseMatchesJsonType([
//            'hydra:totalItems' => 'integer',
//        ]);
//    }

    public function getOneAppointment(ApiTester $I): void
    {
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => ClientFactory::createOne(),
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->sendGet("/api/appointments/{$rdv->getId()}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function cantSeeOthersAppointmentAsClient(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => TypeAppointmentFactory::createOne(),
            'client' => ClientFactory::createOne(),
            'veto' => $veto,
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);

        $client = ClientFactory::createOne();
        $rdvClient = AppointmentFactory::createOne([
            'type' => TypeAppointmentFactory::createOne(),
            'client' => $client,
            'veto' => $client,
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->amLoggedAs($client);

        $I->sendGet('/me/appointments/');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'hydra:totalItems' => '1',
        ]);
    }
}
