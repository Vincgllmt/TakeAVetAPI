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
            'firstName' => 'Jane',
            'email' => 'admin@takea.vet',
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ]);

        ClientFactory::createOne([
            'lastName' => 'Doe',
            'firstName' => 'John',
            'email' => 'client@takea.vet',
            'password' => 'test',
        ]);

        ClientFactory::createMany(5);
    }
}
