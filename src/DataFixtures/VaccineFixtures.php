<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\VaccineFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VaccineFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (AnimalFactory::repository()->findAll() as $animal) {
            VaccineFactory::createOne([
                'animal' => $animal,
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
        ];
    }
}
