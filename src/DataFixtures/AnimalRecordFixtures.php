<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\AnimalRecordFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnimalRecordFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (AnimalFactory::repository()->findAll() as $animal) {
            // 50% chance to create a record for this animal
            if (AnimalFactory::faker()->boolean()) {
                AnimalRecordFactory::createOne([
                    'animal' => $animal,
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
        ];
    }
}
