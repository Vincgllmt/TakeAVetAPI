<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ClientFactory::createOne([
            'lastName' => 'Doe',
            'firstName' => 'John',
            'email' => 'client@takea.vet',
        ]);

        ClientFactory::createMany(5);
    }
}
