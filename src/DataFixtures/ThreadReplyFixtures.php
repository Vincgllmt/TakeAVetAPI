<?php

namespace App\DataFixtures;

use App\Factory\ThreadFactory;
use App\Factory\ThreadReplyFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ThreadReplyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $threadRepo = ThreadFactory::repository();
        foreach ($threadRepo->findAll() as $thread) {
            // create a random number of messages for each thread (0 to 3) with a random author
            ThreadReplyFactory::createMany(ThreadReplyFactory::faker()->numberBetween(0, 3), [
                'thread' => $thread,
                'user' => UserFactory::random(),
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            ThreadFixtures::class,
            ClientFixtures::class,
            VetoFixtures::class,
        ];
    }
}
