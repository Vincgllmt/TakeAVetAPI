<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeAnimalFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnimalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        AnimalFactory::createMany(25, function () {
            return [
                'type' => TypeAnimalFactory::random(),
                'owner' => ClientFactory::random(),
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            TypeAnimalFixtures::class,
        ];
    }
}
