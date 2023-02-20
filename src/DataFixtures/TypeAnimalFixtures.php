<?php

namespace App\DataFixtures;

use App\Factory\TypeAnimalFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeAnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Load the data from the JSON file
        $file = json_decode(file_get_contents(__DIR__.'/data/animals.json'), flags: JSON_OBJECT_AS_ARRAY);
        foreach ($file as $category) {
            TypeAnimalFactory::createOne(['name' => $category]);
        }
    }
}
