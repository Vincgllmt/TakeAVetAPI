<?php

namespace App\Factory;

use App\Entity\Appointment;
use App\Entity\Client;
use App\Entity\Veto;
use App\Repository\AppointmentRepository;
use Symfony\Component\Console\Output\ConsoleOutput;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Appointment>
 *
 * @method        Appointment|Proxy                     create(array|callable $attributes = [])
 * @method static Appointment|Proxy                     createOne(array $attributes = [])
 * @method static Appointment|Proxy                     find(object|array|mixed $criteria)
 * @method static Appointment|Proxy                     findOrCreate(array $attributes)
 * @method static Appointment|Proxy                     first(string $sortedField = 'id')
 * @method static Appointment|Proxy                     last(string $sortedField = 'id')
 * @method static Appointment|Proxy                     random(array $attributes = [])
 * @method static Appointment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AppointmentRepository|RepositoryProxy repository()
 * @method static Appointment[]|Proxy[]                 all()
 * @method static Appointment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Appointment[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Appointment[]|Proxy[]                 findBy(array $attributes)
 * @method static Appointment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Appointment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AppointmentFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'date' => self::faker()->dateTime(),
            'isCompleted' => false,
            'isUrgent' => self::faker()->boolean(30),
            'isValidated' => self::faker()->boolean(80),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Appointment $appointment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Appointment::class;
    }

    /**
     *  Create appointments for a week.
     *
     * @param Veto|Proxy $veto           the veto to create the appointments for
     * @param int        $minPerDays     the minimum number of appointments per day
     * @param int        $maxPerDays     the maximum number of appointments per day
     * @param int        $daysFromMonday the number of days from monday to create appointments for
     *
     * @return Appointment[]|Proxy[] the created appointments
     *
     * @throws \Exception
     */
    public static function createOnWeek(Veto|Proxy $veto, int $minPerDays, int $maxPerDays, int $daysFromMonday, \DateTime $week): array
    {
        $output = new ConsoleOutput();
        $output->setDecorated(true);

        // get the first day of the week
        $currentDayOfWeek = clone $week;
        $currentDayOfWeek->modify('monday this week');

        $appointments = [];

        $output->writeln("<info>Creating appointments for <fg=red>{$veto->getEmail()}</> (between [{$minPerDays} and {$maxPerDays}] events per days for {$daysFromMonday} days from {$week->format('d/m/Y')})</info>");
        // for each day of the week
        for ($i = 0; $i < $daysFromMonday; ++$i) {
            $agenda = $veto->getAgenda();
            /* @var \DateTime $hourInDay */
            $hourInDay = $agenda->getStartHour();

            // clone the current day of the week and set the hour of the day to the start hour of the agenda.
            $currentHourAndDay = (clone $currentDayOfWeek)->setTime($hourInDay->format('H'), $hourInDay->format('i'), $hourInDay->format('s'));

            // for each hour of the day (random between min and max)
            for ($j = 0; $j < self::faker()->numberBetween($minPerDays, $maxPerDays); ++$j) {
                // get all the relations for the appointment on a random client.
                $client = ClientFactory::random();
                $addressOfClient = self::faker()->randomElement($client->getAdresses());
                $animalOfClient = self::faker()->randomElement($client->getAnimals());
                $typeOfAppointment = TypeAppointmentFactory::random();

                // create the appointment data
                $appointmentData = [
                    // relations
                    'veto' => $veto,
                    'type' => $typeOfAppointment,
                    'client' => $client,
                    'location' => $addressOfClient,
                    'animal' => $animalOfClient,
                    // dates and fields
                    'note' => self::faker()->realText(),
                    'date' => $currentHourAndDay, // it also copies the date.
                    'startHour' => $currentHourAndDay,
                    'endHour' => (clone $currentHourAndDay)->modify("+{$typeOfAppointment->getDuration()} minutes"),
                    'isValidated' => self::faker()->boolean(80),
                ];

                // increment the hour for the next appointment
                $currentHourAndDay = (clone $currentHourAndDay)->modify("+{$typeOfAppointment->getDuration()} minutes");

                // create the appointment
                $appointments[] = self::createOne($appointmentData);
                $output->writeln("> Appointment created for {$client->getLastName()} on {$appointmentData['date']->format('d/m/Y')} at {$appointmentData['startHour']->format('H:i')} / {$appointmentData['endHour']->format('H:i')} for {$typeOfAppointment->getName()} ({$typeOfAppointment->getDuration()} minutes)");
            }

            $output->writeln('<info>Day completed</info>');

            // increment the day of the week for the next day
            $currentDayOfWeek = $currentDayOfWeek->modify('+1 day');
        }

        $eventCount = count($appointments);
        $output->writeln("<info>Week completed with <fg=red>{$eventCount}</> events for <fg=red>{$veto->getEmail()}</></info>");

        return $appointments;
    }
}
