<?php

namespace App\DataFixtures;

use App\Factory\AgendaFactory;
use App\Factory\UnavailabilityFactory;
use App\Factory\VacationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgendaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AgendaFactory::createOne([
//            'days' => AgendaDayFactory::createWeek(8, 18), // 8h to 18h, all days,
            'vacations' => VacationFactory::createMany(2), // 2 vacations of 2 months for next year
            'unavailabilities' => UnavailabilityFactory::createMany(5),
        ]);
    }
}
