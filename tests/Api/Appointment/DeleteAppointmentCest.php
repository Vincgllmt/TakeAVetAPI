<?php

namespace Api\Appointment;

use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;

class DeleteAppointmentCest
{
    /*
    public function anonymousUserForbiddenToDelete(ApiTester $I): void
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
        $I->sendDelete("/api/appointments/{$rdv->getId()}");
        $I->seeResponseCodeIs(401);
    }

    public function VetoCanDeleteAppointment(ApiTester $I): void
    {
        $user = VetoFactory::createOne();
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => ClientFactory::createOne(),
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/appointments/{$rdv->getId()}");
        $I->seeResponseCodeIs(204);
    }

    public function ClientCanDeleteOwnAppointment(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => $user,
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->amLoggedInAs($user->object());
        $I->sendDelete("/api/appointments/{$rdv->getId()}");
        $I->seeResponseCodeIs(204);
    }
    */
}
