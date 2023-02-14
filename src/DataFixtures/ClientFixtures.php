<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use App\Factory\ThreadFactory;
use App\Factory\ThreadMessageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ClientFactory::createMany(15, function () {
            return [
                // create Thread
                'threads' => ThreadFactory::createMany(ClientFactory::faker()->numberBetween(0, 5), function () {
                    return [
                        // create ThreadMessage
                        'replies' => ThreadMessageFactory::createMany(ClientFactory::faker()->numberBetween(0, 5), function () {
                            return [
                                // set to a random author
                                'user' => ClientFactory::random(),
                            ];
                        }),
                    ];
                }),
                'tel' => ClientFactory::faker()->boolean() ? ClientFactory::faker()->phoneNumber() : null,
            ];
        });
    }
}
