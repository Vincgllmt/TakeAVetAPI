<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ThreadFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $clientRepo = ClientFactory::repository();
        foreach ($clientRepo->findAll() as $client) {
            // create a random number of threads for each client (0 to 5)
            ThreadFactory::createMany(ThreadFactory::faker()->numberBetween(0, 5), [
                'author' => $client,
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
        ];
    }
}
