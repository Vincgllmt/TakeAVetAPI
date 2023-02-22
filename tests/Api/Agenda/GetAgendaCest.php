<?php

namespace App\Tests\Api\Agenda;

use App\Entity\Agenda;
use App\Factory\AgendaFactory;
use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetAgendaCest
{
    public static function exceptPropertiesResultGet(): array
    {
        return [
            'id' => 'integer',
            'startHour' => 'string',
            'endHour' => 'string',
            'veto' => 'string',
        ];
    }

    public function getAgendaById(ApiTester $I): void
    {
        $agenda = AgendaFactory::createOne();

        $I->sendGet("/api/agendas/{$agenda->getId()}");

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Agenda::class, "/api/agendas/{$agenda->getId()}", self::exceptPropertiesResultGet());
    }

    public function getAllAgenda(ApiTester $I): void
    {
        $veto = VetoFactory::createOne();
        AgendaFactory::createMany(2, [
            'veto' => $veto,
        ]);

        $I->sendGet('/api/agendas');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseIsACollection(Agenda::class, '/api/agendas', [
            'hydra:member' => [self::exceptPropertiesResultGet(), self::exceptPropertiesResultGet()],
        ]);
    }

    public function getUpcomingAppointmentEventsFromAnAgenda(ApiTester $I): void
    {
        // create an appointment in an agenda.
        $veto = VetoFactory::createOne();
        $agenda = AgendaFactory::createOne([
            'veto' => $veto,
        ]);
        $typeAppointment = TypeAppointmentFactory::createOne();
        $client = ClientFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        AppointmentFactory::createOne([
            'veto' => $veto,
            'type' => $typeAppointment,
            'client' => $client,
            'animal' => $animal,
            'startHour' => new \DateTimeImmutable(),
            'endHour' => new \DateTimeImmutable(),
        ]);

        $I->sendGet("/api/agendas/{$agenda->getId()}/upcoming");

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }
}
