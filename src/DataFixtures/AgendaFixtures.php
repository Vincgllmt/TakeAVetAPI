<?php

namespace App\DataFixtures;

use App\Factory\AgendaFactory;
use App\Factory\UnavailabilityFactory;
use App\Factory\VacationFactory;
use App\Factory\VetoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AgendaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (VetoFactory::repository()->findAll() as $veto) {
            AgendaFactory::createOne([
                'veto' => $veto,
                'vacations' => VacationFactory::createMany(2),
                'unavailabilities' => UnavailabilityFactory::createMany(5),
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            VetoFixtures::class,
        ];
    }
}
