<?php

namespace App\DataFixtures;

use App\Factory\TypeAnimalFactory;
use App\Factory\VetoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Populate the accepting animals type for each vet
        foreach (VetoFactory::repository()->findAll() as $veto) {
            foreach (TypeAnimalFactory::randomRange(0, 10) as $typeAnimal) {
                $veto->addAccepting($typeAnimal->object());
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
            VetoFixtures::class,
            TypeAnimalFixtures::class,
        ];
    }
}
