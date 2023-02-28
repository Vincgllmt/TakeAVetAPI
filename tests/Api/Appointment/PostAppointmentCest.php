<?php

namespace App\Tests\Api\Appointment;

use App\DataFixtures\AgendaFixtures;
use App\DataFixtures\AnimalFixtures;
use App\Entity\Agenda;
use App\Entity\TypeAppointment;
use App\Factory\AgendaFactory;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAppointmentCest
{
    public static function expectedPropertiesResultPost(): array
    {
        return [

        ];
    }

    public function testCreateAppointment(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createOne([
            'veto' => $veto,
        ]);
        $typeAppointment = TypeAppointmentFactory::createOne([
            'name' => 'Consultation',
            'description' => 'Consultation de routine',
            'duration' => 30,
        ]);

        $client = ClientFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        $client2 = ClientFactory::createOne();
        $I->amLoggedInAs($client2->object());

        // create an appointment for a client with his animal and a type of appointment in +5 days at 10:00
        $I->sendPost('/api/appointments/make', [
            'note' => 'This is a note',
            'isUrgent' => false,
            'isCompleted' => false,
            'type' => "/api/type_appointments/{$typeAppointment->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'date' => (new \DateTime())->modify('+5 day')->format('Y-m-d'),
            'startHour' => (new \DateTime())->setTime(10, 0)->format('H:i:s'),
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->canSeeResponseIsJson();
    }
}
