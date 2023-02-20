<?php

namespace App\DataFixtures;

use App\Factory\AppointmentFactory;
use App\Factory\ReceiptFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReceiptFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (AppointmentFactory::repository()->findAll() as $appointment) {
            ReceiptFactory::createOne([
                'appointment' => $appointment,
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            AppointmentFixtures::class,
        ];
    }
}
