<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use App\Factory\ClientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (ClientFactory::repository()->findAll() as $client) {
            AddressFactory::createOne([
                'client' => $client,
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
