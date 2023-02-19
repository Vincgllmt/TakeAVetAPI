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
            'description' => 'Consultation de routine pour votre animal',
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Blessure',
            'duration' => 30,
            'description' => 'Consultation pour une blessure',
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (3h)',
            'duration' => 180,
            'description' => 'Consultation pour une operation de 3 heures',
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (2h)',
            'duration' => 120,
            'description' => 'Consultation pour une operation de 2 heures',
        ]);

        TypeAppointmentFactory::createOne([
            'name' => 'Operation (1h)',
            'duration' => 60,
            'description' => 'Consultation pour une operation de 1 heure',
        ]);

        TypeAppointmentFactory::createMany(5);
    }
}
