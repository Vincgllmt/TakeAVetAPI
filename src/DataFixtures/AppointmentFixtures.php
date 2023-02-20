<?php

namespace App\DataFixtures;

use App\Factory\AppointmentFactory;
use App\Factory\TypeAppointmentFactory;
use App\Factory\VetoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (VetoFactory::repository()->findAll() as $veto) {
            AppointmentFactory::createOnWeek($veto, 2, new \DateTime('now'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            VetoFixtures::class,
            TypeAppointmentFixtures::class,
            ClientFixtures::class,
            AnimalFixtures::class,
            AddressFixtures::class,
        ];
    }
}
