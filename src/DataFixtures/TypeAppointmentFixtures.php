<?php

namespace App\DataFixtures;

use App\Factory\TypeAppointmentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeAppointmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TypeAppointmentFactory::createOne([
            'name' => 'Normal',
            'duration' => 10,
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Blessure',
            'duration' => 30,
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (3h)',
            'duration' => 180,
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (2h)',
            'duration' => 120,
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (1h)',
            'duration' => 60,
        ]);

        TypeAppointmentFactory::createMany(5);

        $manager->flush();
    }
}
