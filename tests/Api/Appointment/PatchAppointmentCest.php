<?php

namespace Api\Appointment;

use App\Entity\Appointment;
use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchAppointmentCest
{
    public function cantPatchAppointmentUnauthenticated(ApiTester $I): void
    {
        $rdv = AppointmentFactory::createOne([
            'type' => TypeAppointmentFactory::createOne(),
            'client' => ClientFactory::createOne(),
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->sendPatch("/api/appointments/{$rdv->getId()}", [
            'isValidated' => true,
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    public function cantPatchIfNotVet(ApiTester $I): void
    {
        $client = ClientFactory::createOne();
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => $client,
            'veto' => VetoFactory::createOne(),
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->amLoggedInAs($client->object());
        $I->sendPatch("/api/appointments/{$rdv->getId()}", [
            'isValidated' => true,
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    public function patchRecord(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        $type = TypeAppointmentFactory::createOne();
        $rdv = AppointmentFactory::createOne([
            'type' => $type,
            'client' => ClientFactory::createOne(),
            'veto' => $veto,
            'animal' => AnimalFactory::createOne(),
            'startHour' => new \DateTime('now'),
            'endHour' => new \DateTime('now'),
        ]);
        $I->amLoggedInAs($veto->object());
        $I->sendPatch("/api/appointments/{$rdv->getId()}", [
            'isValidated' => true,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Appointment::class, "/api/appointments/{$rdv->getId()}");
    }
}
