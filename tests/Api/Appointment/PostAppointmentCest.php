<?php

namespace Api\Appointment;

use App\Factory\AddressFactory;
use App\Factory\AgendaFactory;
use App\Factory\AnimalFactory;
use App\Factory\AppointmentFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\UnavailabilityFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PostAppointmentCest
{
    public function testCantCreateAppointmentWhenOverlapping_Start(ApiTester $apiTester): void
    {
        $type = TypeAppointmentFactory::createOne([
            'duration' => 30,
        ]);
        $veto = VetoFactory::createOne([
            'agenda' => AgendaFactory::createOne(),
        ]);
        $client = ClientFactory::createOne();
        $location = AddressFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        AppointmentFactory::createOne([
            'note' => 'This is a note',
            'isUrgent' => true,
            'type' => $type,
            'veto' => $veto,
            'animal' => $animal,
            'client' => $client,
            'location' => $location,
            'date' => new \DateTimeImmutable('2021-01-01'),
            'startHour' => new \DateTimeImmutable('10:00'),
            'endHour' => new \DateTimeImmutable('10:30'),
        ]);

        $loggedUser = ClientFactory::createOne();
        $apiTester->amLoggedInAs($loggedUser->object());

        $apiTester->sendPost('/api/appointments', [
            'note' => 'This will overlap',
            'isUrgent' => true,
            'type' => "/api/type_appointments/{$type->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'location' => "/api/addresses/{$location->getId()}",
            'date' => '2021-01-01',
            'startHour' => '10:29',
        ]);

        $apiTester->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseContainsJson([
            'hydra:description' => 'date: There is already an appointment at this time.',
        ]);
    }

    public function testCantCreateAppointmentWhenOverlapping_End(ApiTester $apiTester): void
    {
        $type = TypeAppointmentFactory::createOne([
            'duration' => 30,
        ]);
        $veto = VetoFactory::createOne([
            'agenda' => AgendaFactory::createOne(),
        ]);
        $client = ClientFactory::createOne();
        $location = AddressFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        $appointment = AppointmentFactory::createOne([
            'note' => 'This is a note',
            'isUrgent' => true,
            'type' => $type,
            'veto' => $veto,
            'animal' => $animal,
            'client' => $client,
            'location' => $location,
            'date' => new \DateTimeImmutable('2021-01-01'),
            'startHour' => new \DateTimeImmutable('10:00'),
            'endHour' => new \DateTimeImmutable('10:30'),
        ]);

        $loggedUser = ClientFactory::createOne();
        $apiTester->amLoggedInAs($loggedUser->object());

        $apiTester->sendPost('/api/appointments', [
            'note' => 'This will overlap',
            'isUrgent' => true,
            'type' => "/api/type_appointments/{$type->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'location' => "/api/addresses/{$location->getId()}",
            'date' => '2021-01-01',
            'startHour' => '09:31',
        ]);

        $apiTester->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseContainsJson([
            'hydra:description' => 'date: There is already an appointment at this time.',
        ]);
    }

    public function canCreateNextToAppointment(ApiTester $apiTester): void
    {
        $type = TypeAppointmentFactory::createOne([
            'duration' => 30,
        ]);
        $veto = VetoFactory::createOne([
            'agenda' => AgendaFactory::createOne(),
        ]);
        $client = ClientFactory::createOne();
        $location = AddressFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        $appointment = AppointmentFactory::createOne([
            'note' => 'This is a note',
            'isUrgent' => true,
            'type' => $type,
            'veto' => $veto,
            'animal' => $animal,
            'client' => $client,
            'location' => $location,
            'date' => new \DateTimeImmutable('2021-01-01'),
            'startHour' => new \DateTimeImmutable('10:00'),
            'endHour' => new \DateTimeImmutable('10:30'),
        ]);

        $loggedUser = ClientFactory::createOne();
        $apiTester->amLoggedInAs($loggedUser->object());

        $apiTester->sendPost('/api/appointments', [
            'note' => 'This will pass',
            'isUrgent' => true,
            'type' => "/api/type_appointments/{$type->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'location' => "/api/addresses/{$location->getId()}",
            'date' => '2021-01-01',
            'startHour' => '10:30',
        ]);

        $apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $apiTester->seeResponseIsJson();
    }

    public function cantCreateAppointmentWhenVetIsInVacation(ApiTester $apiTester): void
    {
        $type = TypeAppointmentFactory::createOne([
            'duration' => 30,
        ]);
        $veto = VetoFactory::createOne([
            'agenda' => AgendaFactory::createOne(),
        ]);
        $vacation = VacationFactory::createOne([
            'agenda' => $veto->getAgenda(),
            'lib' => 'Name here',
            'startDate' => new \DateTimeImmutable('2021-01-01'),
            'endDate' => new \DateTimeImmutable('2021-01-02'),
        ]);
        $client = ClientFactory::createOne();
        $location = AddressFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        $loggedUser = ClientFactory::createOne();
        $apiTester->amLoggedInAs($loggedUser->object());

        $apiTester->sendPost('/api/appointments', [
            'note' => 'This will overlap',
            'isUrgent' => true,
            'type' => "/api/type_appointments/{$type->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'location' => "/api/addresses/{$location->getId()}",
            'date' => '2021-01-01',
            'startHour' => '10:00',
        ]);

        $apiTester->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseContainsJson([
            'hydra:description' => 'date: The vet is on vacation on this date (Name here).',
        ]);
    }

    public function cantCreateWhenTheVetIsUnavailable(ApiTester $apiTester): void
    {
        $type = TypeAppointmentFactory::createOne([
            'duration' => 30,
        ]);
        $veto = VetoFactory::createOne([
            'agenda' => AgendaFactory::createOne(),
        ]);
        $unavailable = UnavailabilityFactory::createOne([
            'agenda' => $veto->getAgenda(),
            'lib' => 'Name here',
            'startDate' => new \DateTimeImmutable('2021-01-01 10:00'),
            'endDate' => new \DateTimeImmutable('2021-01-01 10:30'),
        ]);
        $client = ClientFactory::createOne();
        $location = AddressFactory::createOne();
        $animal = AnimalFactory::createOne([
            'owner' => $client,
        ]);

        $loggedUser = ClientFactory::createOne();
        $apiTester->amLoggedInAs($loggedUser->object());

        $apiTester->sendPost('/api/appointments', [
            'note' => 'This will pass',
            'isUrgent' => true,
            'type' => "/api/type_appointments/{$type->getId()}",
            'veto' => "/api/users/{$veto->getId()}",
            'animal' => "/api/animals/{$animal->getId()}",
            'location' => "/api/addresses/{$location->getId()}",
            'date' => '2021-01-01',
            'startHour' => '10:29',
        ]);

        $apiTester->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseContainsJson([
            'hydra:description' => 'date: The vet is not available at this time (Name here)',
        ]);
    }
}
