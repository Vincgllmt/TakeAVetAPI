<?php

use App\Factory\AnimalFactory;
use App\Tests\Support\ApiTester;

class GetAnimalCest
{
    public function getAllAnimals(ApiTester $I): void
    {
        AnimalFactory::createMany(20);
    }

    public function getOneAnimal(ApiTester $I): void
    {
    }
}
