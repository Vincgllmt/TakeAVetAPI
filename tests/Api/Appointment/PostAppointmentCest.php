<?php

namespace Api\Appointment;

use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAppointmentCest
{
    /*
    public function testAppointmentPost(ApiTester $I): void
    {
        $type = TypeAppointmentFactory::createOne();
        $animal = AnimalFactory::createOne();
        $veto = VetoFactory::createOne();
        $client = ClientFactory::createOne();
        $I->amLoggedInAs($client->object());
        $I->sendPost('/api/appointments', [
            'type' => "/api/type_appointments/{$type->getId()}",
            'client' => "/api/clients/{$client->getId()}",
            'veto' => "/api/vetos/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'startHour' => '2023-03-15T08:29:28.840Z',
            'endHour' => '2023-03-15T08:29:28.840Z',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
    }
    */
}
