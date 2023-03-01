<?php

namespace App\Tests\Api\Animal;

use App\Entity\Animal;
use App\Factory\AnimalFactory;
use App\Tests\Support\ApiTester;

class GetAnimalCest
{
    public static function exceptPropertiesResultGet(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'note' => 'string',
            'specificRace' => 'string|null',
            'gender' => 'string',
            'birthday' => 'string',
            'imagePath' =>'string',
            'inFarm' => 'boolean',
            'isGroup' => 'boolean',
            'records' => 'Collection',
            'appointments' => 'Collection',
            'type' => 'TypeAnimal',
            'owner' => 'Client',
            'vaccines' => 'Collection'
        ];
    }
    public function getAllAnimals(ApiTester $I): void
    {
        AnimalFactory::createMany(20);
    }

    public function getOneAnimal(ApiTester $I): void
    {
    }
}
